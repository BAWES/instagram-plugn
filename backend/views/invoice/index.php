<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'invoice_id',
            'billing_id',
            'pricing_id',
            'agency_id',
            'message_id',
            // 'message_type',
            // 'message_description',
            // 'vendor_id',
            // 'sale_id',
            // 'sale_date_placed',
            // 'vendor_order_id',
            // 'payment_type',
            // 'auth_exp',
            // 'invoice_status',
            // 'fraud_status',
            // 'invoice_usd_amount',
            // 'customer_ip',
            // 'customer_ip_country',
            // 'item_id_1',
            // 'item_name_1',
            // 'item_usd_amount_1',
            // 'item_type_1',
            // 'item_rec_status_1',
            // 'item_rec_date_next_1',
            // 'item_rec_install_billed_1',
            // 'timestamp',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
