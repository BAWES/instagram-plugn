<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\Media;

/**
 * List and Manage Media
 */
class MediaController extends Controller
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
     * Return a List of Media for specified account id
     */
    public function actionList($accountId)
    {
        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = $instagramAccount->mediaWithUnhandledComments;
        return $media;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();

    }

    /**
     * Return media comments on this account
     * @param  integer $accountId
     * @param  integer $mediaId
     * @return array
     */
    public function actionDetail($accountId, $mediaId)
    {
        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = Media::find()->where([
            'user_id' => (int) $accountId,
            'media_id' => (int) $mediaId
        ])->one();

        //Return comments merged with Queued Comments
        return $media->commentsWithQueued;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();

    }
}
