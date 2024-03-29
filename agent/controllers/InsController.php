<?php
namespace agent\controllers;

use Yii;
use yii\web\Controller;
use common\models\Invoice;
use common\models\Billing;

/**
 * 2Checkout INS controller
 */
class InsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Accepts and processes all 2Checkout INS Notifications
     *
     * @return mixed
     */
    public function actionNotification()
    {
        $invoiceId = Yii::$app->request->post('invoice_id');
        if(!$invoiceId){
            Yii::error("[INS Error] Notification url called without an invoice_id", __METHOD__);
            die();
        }

        // Create New vs Update Existing Invoice?
        $model = Invoice::findOne(['invoice_id' => $invoiceId]);
        if(!$model){
            $model = new Invoice();
        }
        $model->scenario = "INSNotification"; // To Validate MD5 Hash

        $oldInstallmentDate = $model->item_rec_date_next_1;

        $model->attributes = Yii::$app->request->post();
        $model->billing_id = $model->vendor_order_id;
        $model->pricing_id = $model->item_id_1;

        // Keep old installment date if the new one is null
        if(!$model->item_rec_date_next_1){
            $model->item_rec_date_next_1 = $oldInstallmentDate;
        }

        // Find the Bill belonging to this invoice
        $billing = Billing::findOne(['billing_id' => $model->billing_id]);
        if(!$billing){
            Yii::error("[INS Error] Unable to find the billing record associated with this notification", __METHOD__);
            die();
        }
        $model->agent_id = $billing->agent_id;

        if(!$model->save()){
            // Log to Slack that INS has failed to save.
            if($model->hasErrors()){
                $errors = \yii\helpers\Html::encode(print_r($model->errors, true));
                Yii::error("[INS Save Error] ".$errors, __METHOD__);
            }
        }

    }

}
