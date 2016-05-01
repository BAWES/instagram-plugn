<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\authclient\InvalidResponseException;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Record;
use common\models\Media;


class Instagram extends \kotchuprik\authclient\Instagram
{
    /**
     * All functions to Interact with Instagram will be listed here
     */
    public function testRandom(){

        $user = User::findIdentity(3);
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
     * Gets the latest n number of posts by all users then updates db with their details.
     */
    public function getUsersLatestPosts(){
        $numPostsToCrawl = Yii::$app->params['instagram.numberOfPastPostsToCrawl']; //Around 20
        $activeUsers = User::find()->active();

        //Loop through users in batches of 50
        foreach($activeUsers->each(50) as $user){

            //Get the latest 20 posts from the user
            $output = $this->apiWithUser($user ,
                        'users/self/media/recent',
                        'GET',
                        [
                            'count' => 1//$numPostsToCrawl,
                        ]);

            if($output){
                /**
                 * Loop Through The Posts
                 */
                $posts = ArrayHelper::getValue($output, 'data');
                foreach($posts as $post){

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

                    //Check if Media already exists
                    $media = Media::findOne(['media_instagram_id' => $tempMedia->media_instagram_id]);
                    
                    if($media){
                        $oldCommentCount = $media->media_num_comments;
                        
                        //Update Existing Media
                        $media->media_num_comments = $tempMedia->media_num_comments;
                        $media->media_num_likes = $tempMedia->media_num_likes;
                        $media->media_caption = $tempMedia->media_caption;
                        
                        //If Number of Comments has changed, Crawl comments again
                        //Make sure to soft-delete comments that have been manually deleted via Instagram
                        //All soft-deleted comments must have "Source" to know who soft deleted it
                        
                        if($oldCommentCount != $media->media_num_comments){
                            //Send media to have comments crawled
                            //$this->crawlComments($user, $media);
                        }
                        
                        print_r("media exists");
                    }else{
                        //Create new Media record
                        if($tempMedia->save()){
                            //Send media to have comments crawled
                            //$this->crawlComments($user, $media);
                            
                        }else{
                            Yii::error(print_r($tempMedia->errors, true));
                        }
                    }

                    // delete this later
                    print_r($tempMedia->media_type);
                    $this->trigger("newline");
                }

            }
        }

    }

    /**
     * Updates all users data once a day
     * Also creates a record for the date to keep track of changes over time
     */
    public function updateUserData(){
        $activeUsers = User::find()->active();

        //Loop through users in batches of 50
        foreach($activeUsers->each(50) as $user){

            $output = $this->apiWithUser($user,
                    'users/self',
                    'GET');

            if($output){
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
     * @param common\models\User $user the user that has token which will be used for this request
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
            Yii::error("Disabling user for invalid response. $errorCode Error - $errorType: $errorMessage", __METHOD__);
            $user->disableForInvalidToken();

            return false;
        }

        return $response;
    }


}
