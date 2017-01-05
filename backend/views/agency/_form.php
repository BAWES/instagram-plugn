<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Agency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'agency_fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_email_verified')->textInput() ?>

    <?= $form->field($model, 'agency_auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agency_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
