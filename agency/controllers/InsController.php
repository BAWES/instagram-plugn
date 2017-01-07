<?php
namespace agency\controllers;

use Yii;
use yii\web\Controller;
use common\models\Agency;
use common\models\Invoice;

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
        }

        // Create New vs Update Existing Invoice?
        $model = Invoice::findOne(['invoice_id' => $invoiceId]);
        if(!$model){
            $model = new Invoice();
        }

        $model->attributes = Yii::$app->request->post();
        $model->billing_id = $model->vendor_order_id;
        $model->pricing_id = $model->item_id_1;

        //Delete this
        //$output = \yii\helpers\Html::encode(print_r(Yii::$app->request->post(), true));
        //Yii::info("[INS POST] $output", __METHOD__);

        if(!$model->save()){
            // Log to Slack that INS has failed to save.
            if($model->hasErrors()){
                $errors = \yii\helpers\Html::encode(print_r($model->errors, true));
                Yii::error("[INS Save Error] ".$errors, __METHOD__);
            }
        }

    }

}
