<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;

use common\models\Agent;

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
            'auth' => function ($email, $password) {
                die("$email - $password");
                $model = new \agent\models\LoginForm();
                $model->email = $email;
                $model->password = $password;

                $agent = Agent::findByEmail($this->email);
                if ($agent && $agent->validatePassword($password)) {
                    // Email and password are correct, check if his email has been verified
                    // If agent email has been verified, then allow him to log in
                    if($agent->agent_email_verified == Agent::EMAIL_VERIFIED){
                        return $agent;
                    }else die("Agent email not verified");
                }

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
