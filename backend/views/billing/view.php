<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Billing */

$this->title = $model->billing_name;
$this->params['breadcrumbs'][] = ['label' => 'Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class='col-md-7'>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'billing_id',
                'pricing_id',
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
                'billing_total:currency',
                'billing_datetime:datetime',
            ],
        ]) ?>
    </div>

    <div class='col-md-5'>
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


        <h2>Customer</h2>

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

</div>
