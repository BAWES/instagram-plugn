<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Billing */

$this->title = $model->billing_id;
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
            'user_id',
            'pricing_id',
            'country_id',
            'billing_name',
            'billing_email:email',
            'billing_city',
            'billing_state',
            'billing_zip_code',
            'billing_address_line1',
            'billing_address_line2',
            'billing_total',
            'billing_currency',
            '2co_token',
            '2co_order_num',
            '2co_transaction_id',
            '2co_response_code',
            '2co_response_msg',
            'billing_datetime',
        ],
    ]) ?>

</div>
