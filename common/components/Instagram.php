<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\db\Expression;
use yii\authclient\InvalidResponseException;
use yii\helpers\ArrayHelper;
use common\models\InstagramUser;
use common\models\Record;
use common\models\Media;
use common\models\Comment;


class Instagram extends \kotchuprik\authclient\Instagram
{
    /**
     * All functions to Interact with Instagram will be listed here
     */
    public function testRandom()
    {

        $user = InstagramUser::findIdentity(3);
        print_r($this->apiWithUser($user ,
                'users/self/media/recent',
                'GET',
                [
                    'count' => Yii::$app->params['instagram.numberOfPastPostsToCrawl'],
                ]));

        //trigger newline event // delete this later
        print_r($record->user_bio);
        $this->trigger("newline");

    }

    /**
     * Posts OR Deletes all the comments queued by users, respecting API rate-limits
     */
    public function processQueuedComments()
    {
        $activeUsers = InstagramUser::find()->active()->with(['commentQueues.media', 'commentQueues.comment', 'commentQueues.agent']);
        //Loop through active users in batches of 50
        foreach($activeUsers->each(50) as $user)
        {
            //Users are limited to one post and one delete comment operation per process (~15 seconds)
            $userAlreadyPostedComment = false;
            $userAlreadyDeletedComment = false;

            //Get Queued Comments for Each User, then Post all Pending Comments
            $queuedComments = $user->commentQueues;
            foreach($queuedComments as $pendingComment)
            {
                $postOrDeleteAction = $pendingComment->comment_id ? "delete" : "post";

                //If User already posted one comment and one deletion, break out of loop
                if(($postOrDeleteAction == "post" && $userAlreadyPostedComment)
                    && ($postOrDeleteAction == "delete" && $userAlreadyDeletedComment)){
                    break;
                }
                //If User already posted one comment, continue the loop
                if($postOrDeleteAction == "post" && $userAlreadyPostedComment){
                    continue;
                }
                //User already deleted one comment, continue the loop
                if($postOrDeleteAction == "delete" && $userAlreadyDeletedComment){
                    continue;
                }

                /**
                 *  Now that the requests passed the above filters, need to check if user
                 *  is allowed to make a request of request type and process the action
                 *  (based on Instagram rate limits)
                 */

                if($postOrDeleteAction == "post" && $this->userAllowedToMakeApiCall($user, "post")){
                    //Post the comment
                    $this->postComment($user, $pendingComment);
                    $userAlreadyPostedComment = true;
                }elseif($postOrDeleteAction == "delete" && $this->userAllowedToMakeApiCall($user, "delete")){
                    //Delete the comment
                    $this->deleteComment($user, $pendingComment);
                    $userAlreadyDeletedComment = true;
                }

            }
        }
    }

    /**
     * Deletes a comment from Instagram
     * @param \common\models\InstagramUser $user
     * @param \common\models\CommentQueue $pendingComment
     */
    public function deleteComment($user, $pendingComment)
    {
        $media = $pendingComment->media;
        $mediaInstagramId = $media->media_instagram_id;

        $commentToDelete = $pendingComment->comment;
        $commentId = $commentToDelete->comment_instagram_id;

        $response = $this->apiWithUser($user,
            "media/$mediaInstagramId/comments/$commentId",
            'DELETE',
            []);


        //Increment Number of API Calls made by user this hour
        $user->incrementNumApiDeleteCallsThisHour();

        $responseCode = ArrayHelper::getValue($response, 'meta.code');
        if($responseCode == 200){ //Successfully deleted comment

            // Mark Comment Handled if not already
            if($commentToDelete->comment_handled == Comment::HANDLED_FALSE){
                $commentToDelete->comment_handled = Comment::HANDLED_TRUE;
                $commentToDelete->comment_handled_by = $pendingComment->agent->agent_id;
            }

            // Mark Comment as Deleted
            $commentToDelete->comment_deleted = Comment::DELETED_TRUE;
            $commentToDelete->comment_deleted_by = $pendingComment->agent->agent_id;
            $commentToDelete->save(false);

            //Delete the Queued Comment Action as it has been deleted successfully
            $pendingComment->delete();

            //Reduce the comment count on this media by 1
            $media->updateCounters(['media_num_comments' => -1]);

        }else Yii::error("[Fatal Error] Issue deleting a comment from Instagram", __METHOD__);

    }

    /**
     * Posts a comment to Instagram
     * @param \common\models\InstagramUser $user
     * @param \common\models\CommentQueue $pendingComment
     */
    public function postComment($user, $pendingComment)
    {
        $media = $pendingComment->media;
        $mediaInstagramId = $media->media_instagram_id;

        $response = $this->apiWithUser($user,
            "media/$mediaInstagramId/comments",
            'POST',
            [
                'text' => $pendingComment->queue_text,
            ]);

        //Increment Number of API Calls made by user this hour
        $user->incrementNumApiPostCallsThisHour();

        $responseCode = ArrayHelper::getValue($response, 'meta.code');
        if($responseCode == 200){ //Successful comment

            //Add the posted comment to our database
            $comment = new Comment();
            $comment->media_id = $media->media_id;
            $comment->user_id = $user->user_id;
            $comment->agent_id = $pendingComment->agent_id;
            $comment->comment_instagram_id = ArrayHelper::getValue($response, 'data.id');
            $comment->comment_text = ArrayHelper::getValue($response, 'data.text');
            $comment->comment_by_username = ArrayHelper::getValue($response, 'data.from.username');
            $comment->comment_by_photo = ArrayHelper::getValue($response, 'data.from.profile_picture');
            $comment->comment_by_id = ArrayHelper::getValue($response, 'data.from.id');
            $comment->comment_by_fullname = ArrayHelper::getValue($response, 'data.from.full_name');
            $comment->comment_handled = Comment::HANDLED_TRUE;
            $comment->comment_handled_by = $pendingComment->agent_id;
            $comment->comment_notification_email_sent = Comment::NOTIFICATION_EMAIL_SENT_TRUE;
            $comment->comment_pushnotif_sent = 1;

            $unixTime = ArrayHelper::getValue($response, 'data.created_time');
            $comment->comment_datetime = new yii\db\Expression("FROM_UNIXTIME($unixTime)");

            if(!$comment->save()){
                Yii::error("[Fatal Error] Unable to save successfully posted comment ".print_r($comment->errors, true), __METHOD__);
            }

            //Delete the Queued Comment as it has been posted successfully
            $pendingComment->delete();

        }else if($responseCode == 400){ // Error posting from API

            $errorType = ArrayHelper::getValue($response, 'meta.error_type');
            // When media that this queued comment is for has been deleted before posting
            if($errorType == "APINotFoundError"){
                // Delete the pending comment as its no longer required.
                $pendingComment->delete();
                Yii::error("[APINotFoundError] Media was deleted before comment was posted, deleted comment from queue.", __METHOD__);
            }

        }else Yii::error("[Fatal Error] Issue posting a comment to Instagram", __METHOD__);

    }

    /**
     * Updates the number of comments and likes on a specific media
     * @param \common\models\User $user the user that has token which will be used for this request
     * @param \common\models\Media $media the media that will be crawled
     */
    private function updateMediaCounters($user, $media)
    {
        $mediaInstagramId = $media->media_instagram_id;

        $response = $this->apiWithUser($user,
            "media/$mediaInstagramId",
            'GET');

        $media->media_num_comments = ArrayHelper::getValue($response, 'data.comments.count');
        $media->media_num_likes = ArrayHelper::getValue($response, 'data.likes.count');
        $media->save(false);
    }


    /**
     * Checks whether the user is allowed to make any POST/DELETE api requests
     * Also manages user Instagram API rate limits
     * Limit: 30 Requests/Hour on Sandbox. 60 Requests/Hour on Production.
     * @param \common\models\InstagramUser $user
     * @param string $type the type of API request
     * @return boolean whether the user can make api calls [due to rate limits]
     */
    public function userAllowedToMakeApiCall($user, $type)
    {
        $rollingHourEndsAt = new \DateTime();
        $rollingHourEndsAt->setTimestamp(strtotime($user->user_api_rolling_datetime));
        $rollingHourEndsAt->modify("+1 hour");

        $currentDatetime = new \DateTime();

        //Check if rolling hour ended by comparing with current time
        if($currentDatetime > $rollingHourEndsAt){
            //Rolling hour passed, reset his rate limits
            $user->user_api_post_requests_this_hour = 0;
            $user->user_api_delete_requests_this_hour = 0;
            $user->user_api_rolling_datetime = new Expression('NOW()');
            $user->save(false);
        }

        //POST - If Hourly Rate limit for the endpoint hasn't passed
        if($type == "post" && ($user->user_api_post_requests_this_hour < Yii::$app->params['instagram.endpointHourlyRateLimit'])){
            return true;
        }
        //DELETE - If Hourly Rate limit for the endpoint hasn't passed
        if($type == "delete" && ($user->user_api_delete_requests_this_hour < Yii::$app->params['instagram.endpointHourlyRateLimit'])){
            return true;
        }
        //else user is not allowed to make an API call
        return false;
    }

    /**
     * Gets the latest n number of posts by all users then updates db with their details.
     */
    public function getUsersLatestPosts()
    {
        $numPostsToCrawl = Yii::$app->params['instagram.numberOfPastPostsToCrawl']; //Around 20
        $activeUsers = InstagramUser::find()->active();

        //Loop through active users in batches of 50
        foreach($activeUsers->each(50) as $user)
        {
            // Is this the first time we crawl this user?
            $initialCrawl = false;
            if(!$user->user_initially_crawled){
                // New user, this is his first crawl *cute*
                $initialCrawl = true;
            }

            //Get the latest 20 posts from the user
            $output = $this->apiWithUser($user ,
                        'users/self/media/recent',
                        'GET',
                        [
                            'count' => $numPostsToCrawl,
                        ]);

            if($output)
            {
                /**
                 * Loop Through The Posts
                 */
                $posts = ArrayHelper::getValue($output, 'data');
                foreach($posts as $post)
                {
                    $tempMedia = new Media();
                    $tempMedia->user_id = $user->user_id;
                    $tempMedia->media_instagram_id = ArrayHelper::getValue($post, 'id');
                    $tempMedia->media_type = ArrayHelper::getValue($post, 'type');
                    $tempMedia->media_link = ArrayHelper::getValue($post, 'link');
                    $tempMedia->media_num_comments = ArrayHelper::getValue($post, 'comments.count');
                    $tempMedia->media_num_likes = ArrayHelper::getValue($post, 'likes.count');
                    $tempMedia->media_caption = ArrayHelper::getValue($post, 'caption.text');

                    $tempMedia->media_image_lowres = ArrayHelper::getValue($post, 'images.low_resolution.url');
                    $tempMedia->media_image_thumb = ArrayHelper::getValue($post, 'images.thumbnail.url');
                    $tempMedia->media_image_standard = ArrayHelper::getValue($post, 'images.standard_resolution.url');

                    $tempMedia->media_video_lowres = ArrayHelper::getValue($post, 'videos.low_resolution.url');
                    $tempMedia->media_video_lowbandwidth = ArrayHelper::getValue($post, 'videos.low_bandwidth.url');
                    $tempMedia->media_video_standard = ArrayHelper::getValue($post, 'videos.standard_resolution.url');

                    $tempMedia->media_location_name = ArrayHelper::getValue($post, 'location.name');
                    $tempMedia->media_location_longitude = ArrayHelper::getValue($post, 'location.longitude');
                    $tempMedia->media_location_latitude = ArrayHelper::getValue($post, 'location.latitude');

                    //Convert unix time to datetime
                    $unixTime = ArrayHelper::getValue($post, 'created_time');
                    $tempMedia->media_created_datetime = new yii\db\Expression("FROM_UNIXTIME($unixTime)");


                    $media = Media::find()->with('comments')->where(['media_instagram_id' => $tempMedia->media_instagram_id])->one();
                    //If Media already exists
                    if($media)
                    {
                        $oldCommentCount = $media->media_num_comments;

                        //Update Existing Media
                        $media->media_num_comments = $tempMedia->media_num_comments;
                        $media->media_num_likes = $tempMedia->media_num_likes;
                        $media->media_caption = $tempMedia->media_caption;
                        $media->save();

                        //If Number of Comments has changed, Crawl comments again
                        if($oldCommentCount != $media->media_num_comments)
                        {
                            $this->crawlComments($user, $media, $initialCrawl);
                        }

                    }else{//If Media doesn't exist
                        //Create new Media record and have its comments crawled
                        if($tempMedia->save())
                        {
                            //Crawl this medias comments as it is newly added to our db
                            $this->crawlComments($user, $tempMedia, $initialCrawl);
                        }else{
                            Yii::error("[Fatal Error] Issue saving new media. ".print_r($tempMedia->errors, true), __METHOD__);
                        }
                    }

                }

                // If Initial crawl, we're done! Mark user as already crawled *now grown up!*
                if($initialCrawl){
                    $user->user_initially_crawled = 1;
                    $user->save(false);
                }

            }
        }
    }

    /**
     * Crawls comments belonging to a users media
     * @param \common\models\User $user the user that has token which will be used for this request
     * @param \common\models\Media $media the media that will be crawled
     * @return array API response
     */
    public function crawlComments($user, $media, $initialCrawl = false)
    {
        //Crawl comments
        $output = $this->apiWithUser($user ,
                'media/'.$media->media_instagram_id.'/comments',
                'GET');

        //Array of comments currently live on IG account for this media [deleted ignored]
        $liveCommentsArray = array();

        /** $oldCommentsArray returns a map of old Instagram comment id mapped to its ID in our database
         * Example Output:
         *    [17856567064059917] => 22
         *    [17856289873059917] => 21
        */
        $oldCommentsArray = ArrayHelper::map($media->comments, 'comment_instagram_id', 'comment_id');

        //Process and save comments from Instagram
        $this->processCrawledComments($output['data'], $user, $media, $liveCommentsArray, $oldCommentsArray, $initialCrawl);

        // Soft delete comments available in our db but not available on Instagram
        $this->processDeletedComments($liveCommentsArray, $oldCommentsArray);

        return true;
    }

    /**
     * Processes comments returned from API request,
     * saves to db or deletes from db
     * @param array $crawledComments output from Instagram comment GET request
     * @param \common\models\User $user the user that has token which will be used for this request
     * @param \common\models\Media $media the media that will be crawled
     * @param array $liveCommentsArray comments returned from Instagram api
     * @param array $oldCommentsArray comments in our db
     */
    private function processCrawledComments($crawledComments, $user, $media, &$liveCommentsArray, &$oldCommentsArray, $initialCrawl)
    {
        // Loop through comments returned from Instagram for this media
        foreach($crawledComments as $instagramComment)
        {
            $commentInstagramId = ArrayHelper::getValue($instagramComment, 'id');
            $liveCommentsArray[$commentInstagramId] = 1;

            //Check if this comment doesn't already exist in our database
            if(!isset($oldCommentsArray[$commentInstagramId]))
            {
                //Add it to our database
                $comment = new Comment();
                $comment->media_id = $media->media_id;
                $comment->user_id = $user->user_id;
                $comment->comment_instagram_id = $commentInstagramId;
                $comment->comment_text = ArrayHelper::getValue($instagramComment, 'text');
                $comment->comment_by_username = ArrayHelper::getValue($instagramComment, 'from.username');
                $comment->comment_by_photo = ArrayHelper::getValue($instagramComment, 'from.profile_picture');
                $comment->comment_by_id = ArrayHelper::getValue($instagramComment, 'from.id');
                $comment->comment_by_fullname = ArrayHelper::getValue($instagramComment, 'from.full_name');

                // Mark comment handled/etc. if this is his initial crawl
                if($initialCrawl){
                    $comment->comment_pushnotif_sent = 1;
                    $comment->comment_notification_email_sent = 1;
                    $comment->comment_handled = 1;
                }

                $unixTime = ArrayHelper::getValue($instagramComment, 'created_time');
                $comment->comment_datetime = new yii\db\Expression("FROM_UNIXTIME($unixTime)");

                if(!$comment->save())
                {
                    Yii::error("[Fatal Error] Unable to save comment ".print_r($comment->errors, true), __METHOD__);
                }else{
                    //Add this new saved comment to oldCommentsArray to know what comments our db has for this post
                    $oldCommentsArray[$comment->comment_instagram_id] = $comment->comment_id;
                }
            }

        }
    }

    /**
     * Soft Deletes comments that are in our db but not on Instagram
     * @param array $liveCommentsArray comments returned from Instagram api
     * @param array $oldCommentsArray comments in our db
     */
    private function processDeletedComments(&$liveCommentsArray, &$oldCommentsArray)
    {
        //Check if there are any comments in our database for this media that aren't on Instagram (manually deleted by someone)
        $deletedComments = array_diff_key($oldCommentsArray, $liveCommentsArray);
        $commentIdsToDelete = array_values($deletedComments);

        //If there are comments to delete, execute the query for soft deletion
        if(!empty($commentIdsToDelete))
        {
            //Soft delete any deleted comments that aren't already marked as soft-deleted
            //to avoid repetitive/overwriting issues
            $query = Yii::$app->db->createCommand()->update('comment', [
                'comment_deleted' => Comment::DELETED_TRUE,
            ],[
                'comment_deleted' => Comment::DELETED_FALSE,
                'comment_id' => $commentIdsToDelete,
            ])->execute();
        }
    }

    /**
     * Updates all users data once a day
     * Also creates a record for the date to keep track of changes over time
     */
    public function updateUserData()
    {
        $activeUsers = InstagramUser::find()->active();

        //Loop through users in batches of 50
        foreach($activeUsers->each(50) as $user)
        {

            $output = $this->apiWithUser($user,
                    'users/self',
                    'GET');

            if($output)
            {
                /**
                 * Update User Data
                 */
                $user->user_name = ArrayHelper::getValue($output, 'data.username');
                $user->user_bio = ArrayHelper::getValue($output, 'data.bio');
                $user->user_website = ArrayHelper::getValue($output, 'data.website');
                $user->user_profile_pic = ArrayHelper::getValue($output, 'data.profile_picture');
                $user->user_fullname = ArrayHelper::getValue($output, 'data.full_name');
                $user->user_media_count = ArrayHelper::getValue($output, 'data.counts.media');
                $user->user_follower_count = ArrayHelper::getValue($output, 'data.counts.followed_by');
                $user->user_following_count = ArrayHelper::getValue($output, 'data.counts.follows');
                $user->save();

                /**
                 * Add a Record for media,follower,following count for this date
                 */
                $record = new Record();
                $record->user_id = $user->user_id;
                $record->record_media_count = $user->user_media_count;
                $record->record_follower_count = $user->user_follower_count;
                $record->record_following_count = $user->user_following_count;
                $record->save();

            }
        }
    }


    /**
     * Core Functions listed below
     */

    /**
     * Performs request to the OAuth API.
     * @param \common\models\User $user the user that has token which will be used for this request
     * @param string $apiSubUrl API sub URL, which will be append to [[apiBaseUrl]], or absolute API URL.
     * @param string $method request method.
     * @param array $params request parameters.
     * @param array $headers additional request headers.
     * @return array API response
     * @throws Exception on failure.
     */
    public function apiWithUser($user, $apiSubUrl, $method = 'GET', array $params = [], array $headers = [])
    {
        if (preg_match('/^https?:\\/\\//is', $apiSubUrl)) {
            $url = $apiSubUrl;
        } else {
            $url = $this->apiBaseUrl . '/' . $apiSubUrl;
        }

        return $this->apiInternalWithUser($user, $url, $method, $params, $headers);
    }

    /**
     * Takes user instead of access token
     */
    protected function apiInternalWithUser($user, $url, $method, array $params, array $headers)
    {
        $accessToken = $user->user_ig_access_token;

        try{
            $response = $this->sendRequest($method, $url . '?access_token=' . $accessToken, $params, $headers);
        }catch(InvalidResponseException $e){
            /**
             * If the request is not successful ie. Metacode 400
             * Example:
             * 400 Error - OAuthAccessTokenException: The access_token provided is invalid.
             */
            $metaResponse = json_decode($e->responseBody);

            $errorCode = ArrayHelper::getValue($metaResponse, 'meta.code', "???");
            $errorType = ArrayHelper::getValue($metaResponse, 'meta.error_type', "Not set");
            $errorMessage = ArrayHelper::getValue($metaResponse, 'meta.error_message', "Not set");

            if($errorCode == "???" && $errorType == "Not set" && $errorType == "Not set"){
                // Ignore this error as Facebook might have some issues
                return false;
            }


            Yii::error("[Fatal Error] $errorCode Error - $errorType: $errorMessage", __METHOD__);

            //For discovering new errors as we've been getting: ??? Error - Not set: Not set
            Yii::error("[Error Contents] ".print_r($e->responseBody, true), __METHOD__);

            /**
             * Disable User Account with Invalid Access Token
             */
            if($errorCode == 400 && $errorType == "OAuthAccessTokenException"){
                $user->disableForInvalidToken();
            }

            return false;
        }

        return $response;
    }


}
