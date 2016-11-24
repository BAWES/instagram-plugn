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
     * @param  integer $mediaId
     * @return array
     */
    public function actionDetail($mediaId)
    {
        // Find the Media
        $media = Media::find()->where([
            'media_id' => (int) $mediaId
        ])->one();
        if($media){
            // Get Instagram account from Account Manager component
            // (To check that the requester has permission to get the details)
            $instagramAccount = Yii::$app->accountManager->getManagedAccount($media->user_id);

            //Return comments merged with Queued Comments
            return $media->commentsWithQueued;
        }else return [
            "operation" => "error",
            "message" => "Unable to find requested media"
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }

    /**
     * Mark the media as handled
     * @param  integer $accountId
     * @param  integer $mediaId
     * @return array
     */
    public function actionHandle()
    {
        // Get POST params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $mediaId = Yii::$app->request->getBodyParam("mediaId");

        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        // Find the media object
        $mediaToHandle = Media::find()->where([
            'media_id' => (int) $mediaId,
            'user_id' => $instagramAccount->user_id,
        ])->one();

        if($mediaToHandle){
            // Mark Conversation as handled
            if($mediaToHandle->handleMediaComments()){
                return [
                    "operation" => "success",
                ];
            }else return [
                "operation" => "error",
                "message" => "All comments within this media already handled."
            ];
        }

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];
    }
}
