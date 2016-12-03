<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\Media;
use api\models\Activity;

/**
 * User and Agent Activity Controller.
 * Displays all Agent and user activity via API Endpoint
 */
class ActivityController extends Controller
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
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Get all activity made on specified account that is managed by user
     */
    public function actionActivityOnAccount($accountId)
    {
        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = $instagramAccount->mediaWithUnhandledComments;
        return $media;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }

    /**
     * Get all activity made by the logged in user across all managed accounts
     */
    public function actionPersonalActivity()
    {
        //Get All Activities with the User that activity was made on
        $activities = Activity::find()
                        ->with(['user', 'agent'])
                        ->where(['agent_id' => Yii::$app->user->identity->agent_id])
                        ->all();

        return $activities;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }
}
