<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pricing */

$this->title = 'Create Pricing';
$this->params['breadcrumbs'][] = ['label' => 'Pricings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
