<?php

namespace backend\controllers;

use Yii;
use common\models\Agent;
use backend\models\AgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * AgentController implements the CRUD actions for Agent model.
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
                    'cancel-billing-plan' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Agent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agent model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Get Activity
        $activityQuery = $model->getActivities();
        $activityDataProvider = new ActiveDataProvider([
            'query' => $activityQuery,
        ]);

        // Get Assigned Accounts
        $assignedAccountsQuery = $model->getAccountsManaged();
        $assignedAccountsDataProvider = new ActiveDataProvider([
            'query' => $assignedAccountsQuery,
        ]);

        // Get Owned Accounts
        $ownedAccountsQuery = $model->getInstagramUsers();
        $ownedAccountsDataProvider = new ActiveDataProvider([
            'query' => $ownedAccountsQuery,
        ]);

        // Data Provider to display invoices
        $invoiceQuery = \common\models\Invoice::find();
        $invoiceQuery->where(['agent_id' => $model->agent_id]);
        $invoiceQuery->orderBy('invoice_updated_at DESC');
        $invoiceDataProvider = new ActiveDataProvider([
            'query' => $invoiceQuery,
        ]);

        // Data Provider to display billing attempts
        $billingQuery = \common\models\Billing::find();
        $billingQuery->where(['agent_id' => $model->agent_id]);
        $billingQuery->orderBy('billing_datetime DESC');
        $billingDataProvider = new ActiveDataProvider([
            'query' => $billingQuery,
        ]);

        return $this->render('view', [
            'model' => $model,
            'activityDataProvider' => $activityDataProvider,
            'assignedAccountsDataProvider' => $assignedAccountsDataProvider,
            'ownedAccountsDataProvider' => $ownedAccountsDataProvider,
            'invoiceDataProvider' => $invoiceDataProvider,
            'billingDataProvider' => $billingDataProvider,
        ]);
    }

    /**
     * Cancels the currently active billing plan
     * @param string $id
     */
    public function actionCancelBillingPlan($id){
        $model = $this->findModel($id);

        // Redirect back to billing page if doesnt have a plan active
        $isBillingActive = $model->getBillingDaysLeft();
        if(!$isBillingActive){
            die("Agent doesnt have billing active");
        }

        $customerName = $model->agent_name;
        $latestInvoice = $model->getInvoices()->orderBy('invoice_created_at DESC')->limit(1)->one();

        if($latestInvoice){
            Yii::error("[Admin Cancel Recurring Billing #".$latestInvoice->billing->twoco_order_num."] Customer: $customerName", __METHOD__);
            // Cancel the recurring plan
            $latestInvoice->billing->cancelRecurring();
        }

        return $this->redirect(['agent/view', 'id' => $id]);
    }

    /**
     * Finds the Agent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Agent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
