<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * List and Manage Conversations
 */
class ConversationController extends Controller
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
     * Return a List of Conversations for specified account id
     * @param  integer $accountId
     * @return array
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $conversations = $instagramAccount->conversations;

        return $conversations;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();

    }

    /**
     * Return conversation detail between the account and its commenter
     * @param  integer $accountId
     * @param  integer $commenterId
     * @param  string $commenterUsername
     * @return array
     */
    public function actionDetail($accountId, $commenterId, $commenterUsername)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        //Get Comments in this conversation
        $comments = $instagramAccount->getConversationWithUser($commenterId, $commenterUsername);

        return ['conversationComments' => $comments];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();

    }

    /**
     * Mark the conversation as handled
     * @param  integer $accountId
     * @param  integer $commenterId
     * @param  string $commenterUsername
     * @return array
     */
    public function actionHandle()
    {
        // Get POST params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $commenterId = Yii::$app->request->getBodyParam("commenterId");
        $commenterUsername = Yii::$app->request->getBodyParam("commenterUsername");

        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        // Mark Conversation as handled
        $handled = $instagramAccount->handleConversationComments($commenterId, $commenterUsername);
        if($handled){
            return [
                "operation" => "success",
            ];
        }

        return [
            "operation" => "error",
            "message" => "All of the comments within this conversation are already handled."
        ];
    }
}
