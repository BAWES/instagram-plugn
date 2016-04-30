<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\authclient\InvalidResponseException;
use yii\helpers\ArrayHelper;
use common\models\User;


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

        //TO DO: All queries must take an IG User model as a parameter, not an AccessToken.
        //Then to get access token, the functions will query $user->ig_access_token
        //MAKE SURE IG USER IS DISABLED ON INVALID ACCESS TOKEN + ACC MANAGERS ARE EMAILED


        //BAWES ACCESS Token
        //1512951558.a9d7f8a.e6a6122d8a0a486ebb351b25c9f4ad86
        //KHALID ACCESS Token
        //35734335.a9d7f8a.5a08489a4f8b4a5a8b512dfbf01c5586
    }

    /**
     * Gets the latest n number of posts by all users then updates db with their details.
     */
    public function updateUsersLatestPosts(){
        $numPostsToCrawl = Yii::$app->params['instagram.numberOfPastPostsToCrawl'];
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
                //Update User Data
                $user->user_name = ArrayHelper::getValue($output, 'data.username');
                $user->user_bio = ArrayHelper::getValue($output, 'data.bio');
                $user->user_website = ArrayHelper::getValue($output, 'data.website');
                $user->user_profile_pic = ArrayHelper::getValue($output, 'data.profile_picture');
                $user->user_fullname = ArrayHelper::getValue($output, 'data.full_name');
                $user->user_media_count = ArrayHelper::getValue($output, 'data.counts.media');
                $user->user_follower_count = ArrayHelper::getValue($output, 'data.counts.followed_by');
                $user->user_following_count = ArrayHelper::getValue($output, 'data.counts.follows');
                $user->save();

                //Add a Record for media,follower,following count for this date
                



                print_r($user->user_bio);

                //trigger newline event // delete this later
                $this->trigger("newline");

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