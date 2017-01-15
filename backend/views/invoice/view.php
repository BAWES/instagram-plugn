<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = "Invoice #".$model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <h1><?= Html::encode($this->title) ?>
        <a target='_blank' href='<?= Url::to(['billing/view', 'id' => $model->billing_id]) ?>' class='btn btn-xs btn-primary'>View Parent Billing</a>
    </h1>

    <div class='col-md-8'>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'sale_id',
                'payment_type',
                'invoice_status',
                'fraud_status',
                'invoice_usd_amount:currency',
                'sale_date_placed:date',
                'timestamp:datetime',
                'invoice_created_at:datetime',
                'invoice_updated_at:datetime',
            ],
        ]) ?>

        <h2>Agency <a target='_blank' href='<?= Url::to(['agency/view', 'id' => $model->agency_id]) ?>' class='btn btn-xs btn-primary'>View Agency</a></h2>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'agency.agency_fullname',
                'agency.agency_company',
                'customer_ip',
                'customer_ip_country',
            ],
        ]) ?>
    </div>

    <div class='col-md-4'>
        <h2>Latest INS Update</h2>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'message_id',
                'message_type',
                'message_description',
            ],
        ]) ?>

        <h2>Item <a target='_blank' href='<?= Url::to(['pricing/view', 'id' => $model->pricing_id]) ?>' class='btn btn-xs btn-primary'>View Price Plan</a></h2>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'item_name_1',
                'item_usd_amount_1',
                'item_type_1',
                'item_rec_status_1',
                'item_rec_date_next_1:date',
                'item_rec_install_billed_1',
            ],
        ]) ?>
    </div>

</div>
