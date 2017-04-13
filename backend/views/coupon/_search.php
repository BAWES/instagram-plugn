<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CouponSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coupon_id') ?>

    <?= $form->field($model, 'coupon_name') ?>

    <?= $form->field($model, 'coupon_user_limit') ?>

    <?= $form->field($model, 'coupon_expires_at') ?>

    <?= $form->field($model, 'coupon_created_at') ?>

    <?php // echo $form->field($model, 'coupon_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
