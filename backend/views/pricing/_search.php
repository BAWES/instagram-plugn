<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PricingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pricing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pricing_id') ?>

    <?= $form->field($model, 'pricing_title') ?>

    <?= $form->field($model, 'pricing_features') ?>

    <?= $form->field($model, 'pricing_price') ?>

    <?= $form->field($model, 'pricing_created_at') ?>

    <?php // echo $form->field($model, 'pricing_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
