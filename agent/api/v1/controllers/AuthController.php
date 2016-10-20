<?php

namespace agent\api\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;

/**
 * Auth controller provides the initial access token that is required for further requests
 * It initially authorizes via Http Basic Auth using a base64 encoded username and password
 */
class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Basic Auth accepts Base64 encoded username/password and decodes it for you
        $behaviors['basicAuth'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => function ($username, $password) {
                die("$username - $password");
                // $user = User::find()->where(['username' => $username])->one();
                // if ($user->verifyPassword($password)) {
                //     return $user;
                // }
                return null;
            },
        ];
        return $behaviors;
    }

    /**
     * Returns the BEARER access token required for futher requests to the API
     * @return string
     */
    public function actionIndex()
    {
        $accessToken = Yii::$app->user->identity->accessToken->token_value;
        return $accessToken;
    }
}
