<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Agency */

$this->title = $model->agency_fullname;
if($model->agency_company){
    $this->title = $model->agency_company." by '".$model->agency_fullname."'";
}
$this->params['breadcrumbs'][] = ['label' => 'Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class='col-md-12'>
        <h1><?= Html::encode($this->title) ?></h1>
        <p><?= Html::a('Update', ['update', 'id' => $model->agency_id], ['class' => 'btn btn-primary']) ?></p>
    </div>

    <div class='col-md-6'>
        <h2>Summary</h2>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'agency_id',
                'agency_email:email',
                'agency_email_verified:boolean',
                //'agency_auth_key',
                //'agency_password_hash',
                //'agency_password_reset_token',

                [
                    'label' => 'Status',
                    'value' => $model->status,
                ],
                'agency_trial_days',
                'agency_billing_active_until:datetime',
                'agency_limit_email:datetime',
                'agency_created_at:datetime',
                'agency_updated_at:datetime',
            ],
        ]) ?>
    </div>

    <div class='col-md-6'>
        <h2>Managed Accounts</h2>
        <?= GridView::widget([
            'dataProvider' => $accountsDataProvider,
            'columns' => [
                'user_name',
                'user_follower_count',
                'status',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'instagram-user'],
            ],
        ]); ?>

    </div>


    <div class='col-md-12'>
        <h2>Invoices</h2>

        <?= GridView::widget([
            'dataProvider' => $invoiceDataProvider,
            'columns' => [
                'invoice_id',
                // 'billing_id',
                // 'pricing_id',
                //'agency.agency_company',
                // 'message_id',

                // 'vendor_id',
                'sale_id',
                'invoice_usd_amount:currency',
                // 'vendor_order_id',
                // 'payment_type',
                // 'auth_exp',
                'invoice_status',
                'fraud_status',
                'message_type',
                // 'message_description',
                // 'customer_ip',
                // 'customer_ip_country',
                // 'item_id_1',
                // 'item_name_1',
                // 'item_usd_amount_1',
                // 'item_type_1',
                // 'item_rec_status_1',
                // 'item_rec_date_next_1',
                // 'item_rec_install_billed_1',
                'timestamp:datetime',
                //'sale_date_placed',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'invoice'],
            ],
        ]); ?>
    </div>


    <div class='col-md-12'>
        <h2>Billing Attempts</h2>

        <?= GridView::widget([
            'dataProvider' => $billingDataProvider,
            'columns' => [
                'billing_id',
                'twoco_transaction_id',
                'twoco_order_num',
                'billing_total:currency',
                'twoco_response_code',
                'twoco_response_msg',
                'billing_datetime:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'billing'],
            ],
        ]); ?>
    </div>


</div>
