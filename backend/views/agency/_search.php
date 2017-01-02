<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AgencySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'agency_id') ?>

    <?= $form->field($model, 'agency_fullname') ?>

    <?= $form->field($model, 'agency_company') ?>

    <?= $form->field($model, 'agency_email') ?>

    <?= $form->field($model, 'agency_email_verified') ?>

    <?php // echo $form->field($model, 'agency_auth_key') ?>

    <?php // echo $form->field($model, 'agency_password_hash') ?>

    <?php // echo $form->field($model, 'agency_password_reset_token') ?>

    <?php // echo $form->field($model, 'agency_limit_email') ?>

    <?php // echo $form->field($model, 'agency_status') ?>

    <?php // echo $form->field($model, 'agency_trial_days') ?>

    <?php // echo $form->field($model, 'agency_created_at') ?>

    <?php // echo $form->field($model, 'agency_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
