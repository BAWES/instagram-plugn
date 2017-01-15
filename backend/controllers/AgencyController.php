<?php

namespace backend\controllers;

use Yii;
use common\models\Agency;
use common\models\AgencySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * AgencyController implements the CRUD actions for Agency model.
 */
class AgencyController extends Controller
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
     * Lists all Agency models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgencySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agency model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Get Managed Accounts
        $managedAccountsQuery = $model->getInstagramUsers();
        $accountsDataProvider = new ActiveDataProvider([
            'query' => $managedAccountsQuery,
        ]);

        // Data Provider to display invoices
        $invoiceQuery = \common\models\Invoice::find();
        $invoiceQuery->where(['agency_id' => $model->agency_id]);
        $invoiceQuery->orderBy('invoice_updated_at DESC');
        $invoiceDataProvider = new ActiveDataProvider([
            'query' => $invoiceQuery,
        ]);

        // Data Provider to display billing attempts
        $billingQuery = \common\models\Billing::find();
        $billingQuery->where(['agency_id' => $model->agency_id]);
        $billingQuery->orderBy('billing_datetime DESC');
        $billingDataProvider = new ActiveDataProvider([
            'query' => $billingQuery,
        ]);

        return $this->render('view', [
            'model' => $model,
            'accountsDataProvider' => $accountsDataProvider,
            'invoiceDataProvider' => $invoiceDataProvider,
            'billingDataProvider' => $billingDataProvider,
        ]);
    }

    /**
     * Updates an existing Agency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->agency_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
            die("Agency doesnt have billing active");
        }

        $customerName = $model->agency_fullname;
        $latestInvoice = $model->getInvoices()->orderBy('invoice_created_at DESC')->limit(1)->one();

        if($latestInvoice){
            Yii::error("[Admin Cancel Recurring Billing #".$latestInvoice->billing->twoco_order_num."] Customer: $customerName", __METHOD__);
            // Cancel the recurring plan
            $latestInvoice->billing->cancelRecurring();
        }

        return $this->redirect(['agency/view', 'id' => $id]);
    }

    /**
     * Deletes an existing Agency model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Agency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Agency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agency::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
