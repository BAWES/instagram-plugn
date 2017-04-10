<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pricing */

$this->title = $model->pricing_title;
$this->params['breadcrumbs'][] = ['label' => 'Pricings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pricing_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'pricing_id',
            //'pricing_title',
            'pricing_account_quantity',
            'pricing_features:html',
            'pricing_price:currency',
            'pricing_created_at:datetime',
            'pricing_updated_at:datetime',
        ],
    ]) ?>

</div>
