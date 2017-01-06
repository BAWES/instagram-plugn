<?php
namespace agency\controllers;

use Yii;
use yii\web\Controller;
use common\models\Agency;

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
        $model = new \common\models\BillingNotification();
        $model->scenario = "newNotification";

        $model->attributes = Yii::$app->request->post();
        $model->billing_id = $model->vendor_order_id;
        $model->pricing_id = $model->item_id_1;

        //Delete this
        //$output = \yii\helpers\Html::encode(print_r(Yii::$app->request->post(), true));
        //Yii::info("[INS POST] $output", __METHOD__);

        if(!$model->save()){
            Yii::info("[INS] 3", __METHOD__);
            // Log to Slack that INS has failed to save.
            if($model->hasErrors()){
                Yii::info("[INS] 4", __METHOD__);
                $errors = \yii\helpers\Html::encode(print_r($model->errors, true));
                Yii::error("[INS Save Error] ".$errors, __METHOD__);
            }
        }
        
    }

}
