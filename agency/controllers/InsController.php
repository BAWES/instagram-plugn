<?php
namespace agency\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Agency;

/**
 * 2Checkout INS controller
 */
class InsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // Only Accept POST requests
                    'notification' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Accepts and processes all 2Checkout INS Notifications
     *
     * @return mixed
     */
    public function actionNotification()
    {
        $billingNotification = new \common\models\BillingNotification();
        $billingNotification->scenario = "newNotification";

        // Load POST'd data from INS into model via massive assignment
        if ($model->load(Yii::$app->request->post())) {
            $model->billing_id = $model->vendor_order_id;
            $model->pricing_id = $model->item_id_1;

            if(!$model->save()){
                // Log to Slack that INS has failed to save.
                if($model->hasErrors()){
                    $errors = \yii\helpers\Html::encode(print_r($model->errors, true));
                    Yii::error("[INS Save Error] ".$error, __METHOD__);
                }
            }
        }
    }

}
