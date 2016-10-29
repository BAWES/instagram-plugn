<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Main controller is an example of what a typical controller should look like
 */
class MainController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Return a List of Accounts Managed by User
     */
    public function actionManagedAccounts()
    {
        $managedAccounts = Yii::$app->accountManager->managedAccounts;

        return $managedAccounts;
    }
}
