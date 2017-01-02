<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Billing', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'billing_id',
            'user_id',
            'pricing_id',
            'country_id',
            'billing_name',
            // 'billing_email:email',
            // 'billing_city',
            // 'billing_state',
            // 'billing_zip_code',
            // 'billing_address_line1',
            // 'billing_address_line2',
            // 'billing_total',
            // 'billing_currency',
            // '2co_token',
            // '2co_order_num',
            // '2co_transaction_id',
            // '2co_response_code',
            // '2co_response_msg',
            // 'billing_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
