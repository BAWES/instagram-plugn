<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BillingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'billing_id') ?>

    <?= $form->field($model, 'agent_id') ?>

    <?= $form->field($model, 'pricing_id') ?>

    <?= $form->field($model, 'country_id') ?>

    <?= $form->field($model, 'billing_name') ?>

    <?php // echo $form->field($model, 'billing_email') ?>

    <?php // echo $form->field($model, 'billing_city') ?>

    <?php // echo $form->field($model, 'billing_state') ?>

    <?php // echo $form->field($model, 'billing_zip_code') ?>

    <?php // echo $form->field($model, 'billing_address_line1') ?>

    <?php // echo $form->field($model, 'billing_address_line2') ?>

    <?php // echo $form->field($model, 'billing_total') ?>

    <?php // echo $form->field($model, 'billing_currency') ?>

    <?php // echo $form->field($model, 'twoco_token') ?>

    <?php // echo $form->field($model, 'twoco_order_num') ?>

    <?php // echo $form->field($model, 'twoco_transaction_id') ?>

    <?php // echo $form->field($model, 'twoco_response_code') ?>

    <?php // echo $form->field($model, 'twoco_response_msg') ?>

    <?php // echo $form->field($model, 'billing_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
