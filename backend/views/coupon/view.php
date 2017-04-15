<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = $model->coupon_name;
$this->params['breadcrumbs'][] = ['label' => 'Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <h1>Coupon: <?= Html::encode($this->title) ?></h1>

    <div class='col-md-6'>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'coupon_id',
                'coupon_name',
                'coupon_reward_days',
                'coupon_user_limit',
                'coupon_expires_at:date',
            ],
        ]) ?>
    </div>

    <div class='col-md-6'>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'coupon_id',
                'coupon_created_at:datetime',
                'coupon_updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>

<div class='row'>
<h2>Coupon Usage</h2>
<?= GridView::widget([
    'dataProvider' => $usageProvider,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],

        'agent.agent_name',
        'agent.agent_email',
        'agent.status',
        'used_datetime:datetime',
        // 'coupon_updated_at',

        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'agent'],
    ],
]); ?>
</div>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->coupon_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->coupon_id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
</p>
