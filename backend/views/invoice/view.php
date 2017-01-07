<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = "Invoice #".$model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'invoice_id',
            'billing_id',
            'pricing_id',
            'agency_id',
            'message_id',
            'message_type',
            'message_description',
            'vendor_id',
            'sale_id',
            'sale_date_placed',
            'vendor_order_id',
            'payment_type',
            'auth_exp',
            'invoice_status',
            'fraud_status',
            'invoice_usd_amount',
            'customer_ip',
            'customer_ip_country',
            'item_id_1',
            'item_name_1',
            'item_usd_amount_1',
            'item_type_1',
            'item_rec_status_1',
            'item_rec_date_next_1',
            'item_rec_install_billed_1',
            'timestamp',
        ],
    ]) ?>

</div>
