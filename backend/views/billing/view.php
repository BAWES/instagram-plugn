<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Billing */

$this->title = $model->billing_name;
$this->params['breadcrumbs'][] = ['label' => 'Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->billing_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->billing_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'billing_id',
            'agency.agency_company',
            'pricing.pricing_price:currency',
            'country.country_name',
            'billing_name',
            'billing_email:email',
            'billing_city',
            'billing_state',
            'billing_zip_code',
            'billing_address_line1',
            'billing_address_line2',
            'billing_total',
            'billing_currency',
            'twoco_token',
            'twoco_order_num',
            'twoco_transaction_id',
            'twoco_response_code',
            'twoco_response_msg',
            'billing_datetime',
        ],
    ]) ?>

</div>
