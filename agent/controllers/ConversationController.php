<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use agent\models\CommentQueue;
use agent\models\InstagramUser;
use agent\models\Activity;
use common\models\Comment;

class ConversationController extends \yii\web\Controller {

    public $layout = 'account';

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
     * @param integer $deleteComment comment id to delete
     * @param boolean $handleComments whether to handle all conversation comments or not
     */
    public function actionView($accountId, $commenterId, $deleteComment = false, $handleComments = false)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        //Get Commenter Details and conversation for display
        $commenterDetails = (new \yii\db\Query())
                    ->select(['comment_by_id', 'comment_by_username', 'comment_by_fullname', 'comment_by_photo'])
                    ->from('comment')
                    ->where([
                        'user_id' => $instagramAccount->user_id,
                        'comment_by_id' => (int) $commenterId
                    ])
                    ->limit(1)
                    ->one();

        $commenterId = $commenterDetails['comment_by_id'];
        $commenterUsername = $commenterDetails['comment_by_username'];
        $commenterFullname = $commenterDetails['comment_by_fullname'];
        $commenterPhoto = $commenterDetails['comment_by_photo'];

        /**
         * Mark Handled Functionality
         */
        if($handleComments){
            $handled = $instagramAccount->handleConversationComments($commenterId, $commenterUsername);
            if($handled){
                Yii::$app->session->setFlash('success', 'Comments have been marked as handled');
                return $this->redirect(['conversation/view', 'accountId' => $instagramAccount->user_id, 'commenterId' => $commenterId]);
            }
        }

        /**
         * Delete Comment Functionality
         */
        if($deleteComment)
        {
            //Get Comment that user wishes to delete (to ensure he owns this comment)
            $commentToDelete = Comment::find()->where([
                'user_id' => $instagramAccount->user_id,
                'comment_id' => (int) $deleteComment,
                'comment_deleted' => Comment::DELETED_FALSE,
                ])->one();

            if($commentToDelete){
                //Queue the comment for deletion
                $deleteQueue = new CommentQueue();
                $deleteQueue->media_id = $commentToDelete->media_id;
                $deleteQueue->user_id = $instagramAccount->user_id;
                $deleteQueue->agent_id = Yii::$app->user->identity->agent_id;
                $deleteQueue->comment_id = $commentToDelete->comment_id;
                $deleteQueue->save(false);

                //Mark comment as queued for deletion
                $commentToDelete->comment_deleted = Comment::DELETED_QUEUED_FOR_DELETION;
                $commentToDelete->save(false);

                //Log that agent made change
                Activity::log($instagramAccount->user_id, "Deleted comment from conversation with @$commenterUsername (".$commentToDelete->comment_text.")");
            }

            return $this->redirect(['conversation/view', 'accountId' => $instagramAccount->user_id, 'commenterId' => $commenterId]);
        }

        //Get Comments/Converstion for Display to End User
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
        if ($commentQueueForm->load(Yii::$app->request->post())) {

            if($commentQueueForm->save()){
                //Log that agent made change
                Activity::log($instagramAccount->user_id, "Posted comment on conversation with @$commenterUsername: ".$commentQueueForm->queue_text);

                return $this->refresh();
            }else{
                //Display error message to user
                if(isset($commentQueueForm->errors['queue_text'][0])){
                    Yii::$app->session->setFlash('error', "[Unable to post comment] ".$commentQueueForm->errors['queue_text'][0]);
                }
            }

        }

        return $this->render('view',[
            'account' => $instagramAccount,
            'commenterId' => $commenterId,
            'commenterUsername' => $commenterUsername,
            'commenterFullname' => $commenterFullname,
            'commenterPhoto' => $commenterPhoto,
            'comments' => $comments,
            'commentQueueForm' => $commentQueueForm,
        ]);
    }



}
