<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use agent\models\CommentQueue;
use agent\models\InstagramUser;
use agent\models\Media;
use agent\models\Activity;
use common\models\Comment;

class MediaController extends \yii\web\Controller {

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
     * Manage an Instagram Account in Media View
     * @param string $accountName the account name we're looking to manage
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = $instagramAccount->getMedia()->with('unhandledComments')->all();


        return $this->render('list',[
            'account' => $instagramAccount,
            'media' => $media
        ]);
    }

    /**
     * View conversation with user who'se userId is provided
     * @param integer $accountId the instagram account id we're managing
     * @param integer $mediaId the media id we're interested in
     * @param integer $deleteComment comment id to delete
     * @param boolean $handleComments whether to handle all media comments or not
     * @throws \yii\web\NotFoundHttpException if no media found
     */
    public function actionView($accountId, $mediaId, $deleteComment = false, $handleComments = false)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = Media::find()->where([
            'media_id' => (int) $mediaId,
            'user_id' => $instagramAccount->user_id,
        ])->with('user')->one();

        if(!$media){
            throw new NotFoundHttpException('Media not found.');
        }

        /**
         * Mark Handled Functionality
         */
        if($handleComments){
            $media->handleMediaComments();
            Yii::$app->session->setFlash('success', 'Comments have been marked as handled');
            return $this->redirect(['media/view', 'accountId' => $instagramAccount->user_id, 'mediaId' => $media->media_id]);
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
                Activity::log($instagramAccount->user_id, "Deleted comment from media #".$commentToDelete->media_id." (".$commentToDelete->comment_text.")");
            }

            return $this->redirect(['media/view', 'accountId' => $instagramAccount->user_id, 'mediaId' => $media->media_id]);
        }

        /**
         * Form to Submit a new Comment as Agent
         * Places the comment in the queue if it passes all validation
         */
        $commentQueueForm = new CommentQueue();
        $commentQueueForm->scenario = "newMediaComment";
        $commentQueueForm->media_id = $media->media_id;
        $commentQueueForm->user_id = $instagramAccount->user_id;
        $commentQueueForm->agent_id = Yii::$app->user->identity->agent_id;
        if ($commentQueueForm->load(Yii::$app->request->post())) {
            if($commentQueueForm->save()){
                //Log that agent made change
                Activity::log($instagramAccount->user_id, "Posted on media #".$media->media_id.": ".$commentQueueForm->queue_text);

                return $this->refresh();
            }else{
                //Display error message to user
                if(isset($commentQueueForm->errors['queue_text'][0])){
                    Yii::$app->session->setFlash('error', "[Unable to post comment] ".$commentQueueForm->errors['queue_text'][0]);
                }
            }
        }

        //Get comments merged with Queued
        $comments = $media->commentsWithQueued;

        return $this->render('view',[
            'account' => $instagramAccount,
            'media' => $media,
            'comments' => $comments,
            'commentQueueForm' => $commentQueueForm,
        ]);
    }


}
