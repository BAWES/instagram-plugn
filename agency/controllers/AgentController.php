<?php

namespace agency\controllers;

use Yii;
use common\models\AgentAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AgentController implements the CRUD actions for AgentAssignment model.
 */
class AgentController extends Controller
{
    public $layout = 'account';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], //only allow authenticated users to all actions
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AgentAssignment models for a specific account.
     * @param string $accountId the account id we're looking to manage
     * @return mixed
     */
    public function actionList($accountId)
    {
        // If billing has expired for this user, set params to notify
        if(Yii::$app->user->identity->agency_status == \common\models\Agency::STATUS_INACTIVE){
            Yii::$app->params['billingExpired'] = true;
        }

        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $model = new AgentAssignment();
        $model->user_id = $instagramAccount->user_id;
        $model->instagramAccountModel = $instagramAccount;

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                //Set Flash here for error dialog with validation issues
                if($model->hasErrors()){
                    $error = \yii\helpers\Html::encode($model->errors['assignment_agent_email'][0]);
                    Yii::$app->getSession()->setFlash('error', "[Unable to Add Agent] ".$error);
                }
            }else{
                return $this->refresh();
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $instagramAccount->getAgentAssignments(),
        ]);

        //Change View Displayed based on number of agents this account has
        $viewToDisplay = $dataProvider->totalCount>0 ? 'index' : 'index-firstagent';

        return $this->render($viewToDisplay, [
            'account' => $instagramAccount,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AgentAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $assignmentModel = $this->findModel($id);
        $account = Yii::$app->accountManager->getManagedAccount($assignmentModel->user_id);

        // Make sure to only delete the model if it belongs to the current agency
        $assignmentModel->delete();

        return $this->redirect(['list', 'accountId' => $account->user_id]);
    }

    /**
     * Finds the AgentAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id The agent assignment id
     * @return AgentAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgentAssignment::findOne(['assignment_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
