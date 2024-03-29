<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pricing */

$this->title = 'Update Price Option: ' . $model->pricing_id;
$this->params['breadcrumbs'][] = ['label' => 'Pricings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pricing_id, 'url' => ['view', 'id' => $model->pricing_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pricing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
