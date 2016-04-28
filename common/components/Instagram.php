<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\authclient\InvalidResponseException;
use yii\helpers\ArrayHelper;


class Instagram extends \kotchuprik\authclient\Instagram
{
    /**
     * All functions to Interact with Instagram will be listed here
     */
    public function testRandom(){

        print_r($this->apiWithToken('35734335.a9d7f8a.5a08489a4f8b4a5a8b512dfbf01c5586' ,
                'users/self/media/recent',
                'GET',
                [
                    'count' => 2,
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
     * Core Functions listed below
     */

    /**
     * Performs request to the OAuth API.
     * @param string $token the user token that will be used for this request
     * @param string $apiSubUrl API sub URL, which will be append to [[apiBaseUrl]], or absolute API URL.
     * @param string $method request method.
     * @param array $params request parameters.
     * @param array $headers additional request headers.
     * @return array API response
     * @throws Exception on failure.
     */
    public function apiWithToken($token, $apiSubUrl, $method = 'GET', array $params = [], array $headers = [])
    {
        if (preg_match('/^https?:\\/\\//is', $apiSubUrl)) {
            $url = $apiSubUrl;
        } else {
            $url = $this->apiBaseUrl . '/' . $apiSubUrl;
        }

        return $this->apiInternalWithToken($token, $url, $method, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function apiInternalWithToken($accessToken, $url, $method, array $params, array $headers)
    {
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

            throw new Exception("$errorCode Error - $errorType: $errorMessage");
        }

        return $response;
    }


}
