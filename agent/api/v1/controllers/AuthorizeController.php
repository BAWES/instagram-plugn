<?php

namespace agent\api\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;

/**
 * Public Controller that triggers the 3rd party auth actions and returns a BEARER token
 */
class AuthorizeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthMobileSuccess'],
            ],
        ];
    }

    /**
     *  Handle successful authentication on Mobile.
     *  Should return a temporary invalid access token on success
     *
     *  Invalid access tokens need to be exchanged for valid ones once device details are provided
     */
    public function onAuthMobileSuccess($client)
    {
        if($client instanceof yii\authclient\clients\Live){
            //Handle Microsoft Live Authentication
            (new LiveAuthHandler($client, "mobile"))->handle();
        }elseif($client instanceof yii\authclient\clients\GoogleOAuth){
            //Handle Google Authentication
            (new GoogleAuthHandler($client, "mobile"))->handle();
        }elseif($client instanceof \agent\components\SlackAuthClient){
            //Handle Slack Authentication
            (new SlackAuthHandler($client, "mobile"))->handle();
        }

        $response = "";
        if(!Yii::$app->user->isGuest){
            // Send a token back to app which will be used in future requests
            $response = Yii::$app->user->identity->getAccessToken()->token_value;
        }else $response = "Error during login, please contact us for assistance";

        $response = "
        <script>
        var resp = '".$response."';
        localStorage.setItem('response', resp );
        </script>";


        /**
         * Send Oauth Response to Mobile for handling
         */
        Yii::$app->response->content = $response;
        return Yii::$app->response;
    }
}
