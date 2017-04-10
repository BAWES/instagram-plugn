<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PricingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Price Options';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Price Option', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'pricing_id',
            'pricing_title',
            //'pricing_features:ntext',
            'pricing_account_quantity',
            'pricing_price:currency',
            //'pricing_created_at',
            // 'pricing_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
