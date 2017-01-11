<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Billing */

$this->title = "Billing Attempt #".$model->billing_id;
$this->params['breadcrumbs'][] = ['label' => 'Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class='col-xs-12' style='margin-bottom:10px;'>
        <h1><?= Html::encode($this->title) ?></h1>
        <h4><?= Yii::$app->formatter->asDateTime($model->billing_datetime, "long") ?></h4>
    </div>

    <div class='col-md-6'>

        <h2>Agency <a target='_blank' href='<?= Url::to(['agency/view', 'id' => $model->agency_id]) ?>' class='btn btn-xs btn-primary'>View Agency</a></h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'billing_name',
                'billing_email:email',
                'country.country_name',
                'billing_city',
                'billing_state',
                'billing_zip_code',
                'billing_address_line1',
                'billing_address_line2',
            ],
        ]) ?>

    </div>
    <div class='col-md-6'>

        <h2>Initial Payment <a target='_blank' href='<?= Url::to(['pricing/view', 'id' => $model->pricing_id]) ?>' class='btn btn-xs btn-primary'>View Price Plan</a></h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'pricing_id',
                'pricing.pricing_price:currency',
                'billing_total:currency',
            ],
        ]) ?>

        <h2>2Checkout Response</h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'twoco_token',
                'twoco_order_num',
                'twoco_transaction_id',
                'twoco_response_code',
                'twoco_response_msg',
            ],
        ]) ?>

    </div>



    <div class='col-md-12'>

        <h2>Invoices</h2>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'invoice_id',
                // 'billing_id',
                // 'pricing_id',
                //'agency.agency_company',
                // 'message_id',

                // 'vendor_id',
                'sale_id',
                'invoice_usd_amount',
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
                // 'timestamp:datetime',
                'invoice_created_at:datetime',
                //'sale_date_placed',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'invoice'],
            ],
        ]); ?>


    </div>

</div>
