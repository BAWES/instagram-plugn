<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pricing_id')->textInput() ?>

    <?= $form->field($model, 'agency_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sale_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sale_date_placed')->textInput() ?>

    <?= $form->field($model, 'vendor_order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_exp')->textInput() ?>

    <?= $form->field($model, 'invoice_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fraud_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_usd_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_ip_country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_id_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_usd_amount_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_type_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_rec_status_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_rec_date_next_1')->textInput() ?>

    <?= $form->field($model, 'item_rec_install_billed_1')->textInput() ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
