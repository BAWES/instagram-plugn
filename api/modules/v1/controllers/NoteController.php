<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\Note;

/**
 * Notes created by agent about a specific user (CRM)
 */
class NoteController extends Controller
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
     * Get all notes made for this user on this account
     * @param  [integer] $accountId
     * @param  [string] $username
     * @return array
     */
     public function actionList($accountId, $username)
     {
         // Get Instagram account from Account Manager component
         $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

         //Get All Notes made on this account for this username
         $notes = Note::find()
                         ->with(['createdByAgent', 'updatedByAgent'])
                         ->where([
                             'user_id' => $instagramAccount->user_id,
                             'note_about_username' => $username
                         ])
                         ->orderBy('note_updated_datetime DESC')
                         ->all();
         return $notes;

         // Check SQL Query Count and Duration
        //  return Yii::getLogger()->getDbProfiling();
     }

     /**
      * Create a note
      */
     public function actionCreate()
     {
         // Get the passed params
         $accountId = Yii::$app->request->getBodyParam("accountId");
         $noteAboutUsername = Yii::$app->request->getBodyParam("note_about_username");
         $noteTitle = Yii::$app->request->getBodyParam("note_title");
         $noteText = Yii::$app->request->getBodyParam("note_text");

         // Get Instagram account from Account Manager component
         $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);


         if($noteAboutUsername){
             $note = new Note();
             $note->user_id = $instagramAccount->user_id;
             $note->note_about_username = $noteAboutUsername;
             $note->note_title = $noteTitle;
             $note->note_text = $noteText;

             if($note->save()){
                 return ["operation" => "success"];
             }else{
                 return [
                     "operation" => "error",
                     "message" => isset($note->errors['note_title'][0])?$note->errors['note_title'][0]:"Validation Error"
                 ];
             }
         }

         // Unaccounted for
         return [
             "operation" => "error",
             "message" => "Request data missing, please contact us for assistance."
         ];

         // Check SQL Query Count and Duration
         return Yii::getLogger()->getDbProfiling();
     }

     /**
      * Update a note
      */
     public function actionUpdate()
     {
         // Get the passed params
         $noteId = Yii::$app->request->getBodyParam("noteId");
         $accountId = Yii::$app->request->getBodyParam("accountId");
         $noteTitle = Yii::$app->request->getBodyParam("note_title");
         $noteText = Yii::$app->request->getBodyParam("note_text");

         // Get Instagram account from Account Manager component
         $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

         // Find the Note to Update
         $note = Note::findOne(['note_id' => $noteId, 'user_id' => $instagramAccount->user_id]);

         if($note){
             $note->note_title = $noteTitle;
             $note->note_text = $noteText;

             if($note->save()){
                 return ["operation" => "success"];
             }else return [
                 "operation" => "error",
                 "message" => isset($note->errors['note_title'][0])?$note->errors['note_title'][0]:"Validation Error"
             ];

         }else return [
             "operation" => "error",
             "message" => "Note not found."
         ];

         // Unaccounted error
         return [
             "operation" => "error",
             "message" => "Error. Please contact us for assistance."
         ];

         // Check SQL Query Count and Duration
         return Yii::getLogger()->getDbProfiling();
     }

     /**
      * Delete a note
      */
     public function actionDelete()
     {
         // Get the passed params
         $noteId = Yii::$app->request->getBodyParam("noteId");
         $accountId = Yii::$app->request->getBodyParam("accountId");

         // Get Instagram account from Account Manager component
         $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

         // Find the Note to Delete
         $note = Note::findOne(['note_id' => $noteId, 'user_id' => $instagramAccount->user_id]);
         if($note){
             // Delete the note
             $note->delete();
             return ["operation" => "success"];
         }else return [
             "operation" => "error",
             "message" => "Note not found."
         ];

         // Check SQL Query Count and Duration
         return Yii::getLogger()->getDbProfiling();
     }
}
