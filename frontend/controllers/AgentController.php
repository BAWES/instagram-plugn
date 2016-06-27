<?php

namespace frontend\controllers;

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
     * Lists all AgentAssignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getAgentAssignments(),
        ]);

        $model = new AgentAssignment();
        $model->user_id = Yii::$app->user->identity->user_id;

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                //Set Flash here for error dialog with validation issues
                if($model->hasErrors()){
                    $error = \yii\helpers\Html::encode($model->errors['assignment_agent_email'][0]);
                    Yii::$app->getSession()->setFlash('error', "[Unable to Add Agent] ".$error. " Please try again");
                }
            }
        }

        //Change View Displayed based on number of agents this account has
        $viewToDisplay = $dataProvider->totalCount>0 ? 'index' : 'index-firstagent';

        return $this->render($viewToDisplay, [
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AgentAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgentAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgentAssignment::findOne(['assignment_id' => $id, 'user_id' => Yii::$app->user->identity->user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
