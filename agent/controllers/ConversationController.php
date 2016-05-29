<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use agent\models\CommentQueue;
use common\models\InstagramUser;

class ConversationController extends \yii\web\Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], //only allow authenticated users to job actions
                    ],
                ],
            ],
        ];
    }


    /**
     * Manage an Instagram Account in Conversation View
     * @param integer $accountId the account id we're looking to manage
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $conversations = $instagramAccount->conversations;

        return $this->render('list',[
            'account' => $instagramAccount,
            'conversations' => $conversations,
        ]);
    }

    /**
     * View conversation with user who'se userId is provided
     * @param integer $accountId the instagram account id we're managing
     * @param integer $commenterId the instagram id of the user we're talking with
     */
    public function actionView($accountId, $commenterId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $commenterDetails = (new \yii\db\Query())
                    ->select(['comment_by_id', 'comment_by_username'])
                    ->from('comment')
                    ->where([
                        'user_id' => (int) $accountId,
                        'comment_by_id' => (int) $commenterId
                    ])
                    ->limit(1)
                    ->one();

        $commenterId = $commenterDetails['comment_by_id'];
        $commenterUsername = $commenterDetails['comment_by_username'];

        $comments = $instagramAccount->getConversationWithUser($commenterId, $commenterUsername);


        /**
         * Form to Submit Comment Response as Agent
         * Places the comment in the queue if it passes all validation
         */
        $commentQueueForm = new CommentQueue();
        $commentQueueForm->scenario = "newConversationComment";
        $commentQueueForm->respondingToUsername = $commenterUsername;
        $commentQueueForm->media_id = $comments[0]['media_id']; //respond to last media item we've talked on
        $commentQueueForm->user_id = $instagramAccount->user_id;
        $commentQueueForm->agent_id = Yii::$app->user->identity->agent_id;
        $commentQueueForm->queue_text = $commentQueueForm->queue_text?$commentQueueForm->queue_text:"@$commenterUsername";
        if ($commentQueueForm->load(Yii::$app->request->post()) && $commentQueueForm->save()) {
            return $this->refresh();
        }

        return $this->render('view',[
            'account' => $instagramAccount,
            'commenterUsername' => $commenterUsername,
            'comments' => $comments,
            'commentQueueForm' => $commentQueueForm,
        ]);
    }



}
