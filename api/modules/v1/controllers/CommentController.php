<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\CommentQueue;
use api\models\Activity;
use api\models\Media;
use common\models\Comment;

/**
 * List and Manage Comments
 */
class CommentController extends Controller
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
     *  Post a comment
     */
    public function actionPostComment()
    {
        // Get POST params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $mediaId = Yii::$app->request->getBodyParam("mediaId");
        $commentMessage = Yii::$app->request->getBodyParam("commentMessage");
        $respondingTo = Yii::$app->request->getBodyParam("respondingTo");

        // Check for valid access to this IG account
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        if($mediaId && $commentMessage){
            // Check if the Media belongs to this user
            $media = Media::find()->where([
                'media_id' => (int) $mediaId,
                'user_id' => $instagramAccount->user_id,
            ])->one();
            if(!$media){
                return ["operation" => "error",
                    "message" => "Unable to comment on posts that do not belong to this user."];
            }

            // Prepare and Validate comment before adding it to Queue for posting
            // Scenario validation whether this is a conversation comment or not
            $commentQueueForm = new CommentQueue();
            $commentQueueForm->scenario = $respondingTo? "newConversationComment" : "newMediaComment";
            $commentQueueForm->respondingToUsername = $respondingTo;

            $commentQueueForm->media_id = $mediaId;
            $commentQueueForm->user_id = $instagramAccount->user_id;
            $commentQueueForm->agent_id = Yii::$app->user->identity->agent_id;
            $commentQueueForm->queue_text = $commentMessage;

            if($commentQueueForm->save()){
                if($respondingTo){
                    // Posted a response to user
                    Activity::log($instagramAccount->user_id, "Posted comment on conversation with @$respondingTo: ".$commentQueueForm->queue_text);
                }else{
                    // Posted a comment on Media
                    Activity::log($instagramAccount->user_id, "Posted on media #".$media->media_id.": ".$commentQueueForm->queue_text);
                }
                return [
                    "operation" => "success",
                ];
            }else{
                //Display error message to user
                if(isset($commentQueueForm->errors['queue_text'][0])){
                    return [
                        "operation" => "error",
                        "message" => $commentQueueForm->errors['queue_text'][0]
                    ];
                }
            }
        }else return [
                "operation" => "error",
                "message" => "Request data missing, please contact us for assistance."
            ];

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];
    }

    /**
     * Delete a comment
     */
    public function actionDeleteComment()
    {
        // Get POST params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $commentId = Yii::$app->request->getBodyParam("commentId");

        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        //Get Comment that user wishes to delete (to ensure he owns this comment)
        $commentToDelete = Comment::find()->where([
            'user_id' => $instagramAccount->user_id,
            'comment_id' => (int) $commentId,
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
            Activity::log($instagramAccount->user_id, "Deleted comment from @".$commentToDelete->comment_by_username." (".$commentToDelete->comment_text.")");

            return [
                "operation" => "success",
            ];
        }else{
            return [
                "operation" => "error",
                "message" => "Comment not found or already deleted."
            ];
        }

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }

    /**
     * Mark a comment as handled
     */
    public function actionHandleComment()
    {
        // Get POST params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $commentId = Yii::$app->request->getBodyParam("commentId");

        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        //Get Comment that user wishes to delete (to ensure he owns this comment)
        $commentToHandle = Comment::find()->where([
            'user_id' => $instagramAccount->user_id,
            'comment_id' => (int) $commentId,
            'comment_handled' => Comment::HANDLED_FALSE,
            ])->one();

        if($commentToHandle){
            // Set Comment as Handled
            $commentToHandle->comment_handled = Comment::HANDLED_TRUE;
            $commentToHandle->comment_handled_by = Yii::$app->user->identity->agent_id;
            $commentToHandle->comment_notification_email_sent = Comment::NOTIFICATION_EMAIL_SENT_TRUE;
            $commentToHandle->save(false);

            //Log that agent made change
            Activity::log($instagramAccount->user_id, "Handled comment by @".$commentToHandle->comment_by_username." (".$commentToHandle->comment_text.")");

            return [
                "operation" => "success",
            ];
        }else{
            return [
                "operation" => "error",
                "message" => "Comment already handled."
            ];
        }

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }
}
