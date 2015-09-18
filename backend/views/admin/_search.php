<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdminSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'admin_id') ?>

    <?= $form->field($model, 'admin_name') ?>

    <?= $form->field($model, 'admin_email') ?>

    <?= $form->field($model, 'admin_auth_key') ?>

    <?= $form->field($model, 'admin_password_hash') ?>

    <?php // echo $form->field($model, 'admin_password_reset_token') ?>

    <?php // echo $form->field($model, 'admin_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
