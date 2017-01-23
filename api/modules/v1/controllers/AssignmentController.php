<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use api\models\AgentAssignment;

/**
 * Agent Assignment controller
 */
class AssignmentController extends Controller
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
     * Return list of agents managing the account
     * @param  integer $accountId
     * @return array
     */
    public function actionList($accountId)
    {
        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->ownedAccountManager->getOwnedAccount($accountId);

        $agents = $instagramAccount->agentAssignments;
        return $agents;

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }


    /**
     * Assign agent to account
     * @return array
     */
    public function actionAddAgent(){
        // Get posted params
        $accountId = Yii::$app->request->getBodyParam("accountId");
        $email = Yii::$app->request->getBodyParam("email");

        // Get Owned Account
        $instagramAccount = Yii::$app->ownedAccountManager->getOwnedAccount($accountId);

        // Validate posted input?
        $model = new AgentAssignment();
        $model->user_id = $instagramAccount->user_id;
        $model->instagramAccountModel = $instagramAccount;
        $model->assignment_agent_email = $email;

        if(!$model->save()){
            if($model->hasErrors()){
                $error = \yii\helpers\Html::encode($model->errors['assignment_agent_email'][0]);
                return [
                    "operation" => "error",
                    "message" => $error
                ];
            }
        }else{
            return [
                "operation" => "success",
            ];
        }

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];

    }

    /**
     * Remove agent from account
     * @param  [type] $accountId    [description]
     * @param  [type] $assignmentId [description]
     * @return array
     */
    public function actionRemoveAgent($accountId, $assignmentId){
        // Get Owned Account
        $instagramAccount = Yii::$app->ownedAccountManager->getOwnedAccount($accountId);

        $assignmentModel = AgentAssignment::findOne([
            'assignment_id' => (int) $assignmentId,
            'user_id' => $instagramAccount->user_id
        ]);

        if(!$assignmentModel){
            // Error for cases not accounted for
            return [
                "operation" => "error",
                "message" => "Agent is no longer assigned to your account."
            ];
        }

        // Delete the assignment
        if($assignmentModel->delete()){
            return [
                "operation" => "success"
            ];
        }

        // Error for cases not accounted for
        return [
            "operation" => "error",
            "message" => "Unknown error occured, please contact us for assistance."
        ];
    }


}
