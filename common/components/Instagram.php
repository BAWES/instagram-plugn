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

        $activeUsers = InstagramUser::find()->active()->with(['commentQueues.agent', 'commentQueues.media']);
        //Loop through active users in batches of 50
        foreach($activeUsers->each(50) as $user)
        {
            //Get Queued Comments for Each User, then Post all Pending Comments
            $queuedComments = $user->commentQueues;
            foreach($queuedComments as $pendingComment){
                //Post the pending comment
                $agent = $pendingComment->agent;
                //$media = $pendingComment->media;
                $postOrDeleteAction = $pendingComment->comment_id ? "delete" : "post";

                echo "<hr><hr>";

                //Check if user is allowed to make api call (based on Instagram rate limits)
                if($this->userAllowedToMakeApiCall($user)){
                    echo "User allowed to make api call";

                    if($postOrDeleteAction == "post"){
                        //Post

                        //Create two functions, one for posting Instagram comment, another for deleting
                        //--- Update user_api_requests_this_hour +1 for each post/delete request made
                    }elseif($postOrDeleteAction == "delete"){
                        //Delete
                    }


                }else echo "Rate limit reached, can't make api call"

                echo "<hr>";


            }
        }
    }

    /**
     * Checks whether the user is allowed to make any POST/DELETE api requests
     * Also manages user Instagram API rate limits
     * Limit: 30 Requests/Hour on Sandbox. 60 Requests/Hour on Production.
     * @param \common\models\InstagramUser $user
     * @return boolean whether the user can make api calls [due to rate limits]
     */
    public function userAllowedToMakeApiCall($user)
    {
        $rollingDatetime = new \DateTime($user->user_api_rolling_datetime);
        $rollingHourEndsAt = clone $rollingDatetime;
        $rollingHourEndsAt->modify("+1 hour");

        //Check if rolling hour ended by comparing with current time
        $currentDatetime = new \DateTime();
        if($currentDatetime > $rollingHourEndsAt){
            //Rolling hour passed, reset his rate limits
            $user->user_api_requests_this_hour = 0;
            $user->user_api_rolling_datetime = new Expression('NOW()');
            $user->save(false);
        }


        //If Hourly Rate limit for the endpoint hasn't passed
        if($user->user_api_requests_this_hour >= Yii::$app->params['instagram.endpointHourlyRateLimit']){
            return true;
        }
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
                            $this->crawlComments($user, $media);
                        }

                    }else{//If Media doesn't exist
                        //Create new Media record and have its comments crawled
                        if($tempMedia->save())
                        {
                            //Crawl this medias comments as it is newly added to our db
                            $this->crawlComments($user, $tempMedia);
                        }else{
                            Yii::error("[Fatal Error] Issue saving new media. ".print_r($tempMedia->errors, true), __METHOD__);
                        }
                    }

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
    public function crawlComments($user, $media)
    {
        //Crawl comments
        $output = $this->apiWithUser($user ,
                'media/'.$media->media_instagram_id.'/comments',
                'GET');

        $liveCommentsArray = array();
        $oldCommentsArray = ArrayHelper::map($media->comments, 'comment_instagram_id', 'comment_id');
        /** $oldCommentsArray returns a map of old Instagram IDs mapped to its ID in our database
         * Example Output:
         *    [17856567064059917] => 22
         *    [17856289873059917] => 21
        */

        // Loop through comments returned from Instagram for this media
        foreach($output['data'] as $instagramComment)
        {

            $commentInstagramId = ArrayHelper::getValue($instagramComment, 'id');
            $liveCommentsArray[$commentInstagramId] = 1;

            //Check if this comment doesn't already exist in our database
            if(!isset($oldCommentsArray[$commentInstagramId]))
            {
                //TODO Make sure if this comment is made by current medias user , then the
                //source of this comment is through Instagram and not through Plugn App.
                //This we can only develop once we program history of users who responded to comments
                //This will help track the blame for each comment
                //Note: This might not be needed once we develop the commenting feature


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
                'comment_deleted_reason' => Comment::REASON_DELETED_DEFAULT,
            ],[
                'comment_deleted' => Comment::DELETED_FALSE,
                'comment_id' => $commentIdsToDelete,
            ])->execute();
        }

        return true;
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

            /**
             * Disable User Account with Invalid Access Token
             */
            Yii::error("[Fatal Error] Disabling user for invalid response. $errorCode Error - $errorType: $errorMessage", __METHOD__);
            $user->disableForInvalidToken();

            return false;
        }

        return $response;
    }


}
