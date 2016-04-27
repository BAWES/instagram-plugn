<?php

namespace common\components;

use yii\base\Exception;


class Instagram extends \kotchuprik\authclient\Instagram
{
    /**
     * All functions to Interact with Instagram will be listed here
     */
    public function testRandom(){
        //Test Meta response code and error messages on every request to Instagram API
        //If anything other than code 200 is returned, log an error Yii2 / Maybe Slack?
        //More info: https://www.instagram.com/developer/endpoints/

        //Any errors related to the token must disable all functionality until he re-enables his token
        //User must login to Instagram section of website to update or refresh his token if the error is a token issue

        print_r($this->apiWithToken('35734335.a9d7f8a.1a5d6221613c40b6a1763c9c46fe3bc3' ,
                'users/self/media/recent',
                'GET',
                [
                    'count' => 2,
                ]));


        //BAWES ACCESS Token
        //1512951558.a9d7f8a.e6a6122d8a0a486ebb351b25c9f4ad86
        //KHALID ACCESS Token
        //35734335.a9d7f8a.1a5d6221613c40b6a1763c9c46fe3bc3
    }


    /**
     * Core Functions listed below
     */

    /**
     * Performs request to the OAuth API.
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
        return $this->sendRequest($method, $url . '?access_token=' . $accessToken, $params, $headers);
    }

}
