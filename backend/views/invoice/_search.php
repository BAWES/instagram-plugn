<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?= $form->field($model, 'billing_id') ?>

    <?= $form->field($model, 'pricing_id') ?>

    <?= $form->field($model, 'agency_id') ?>

    <?= $form->field($model, 'message_id') ?>

    <?php // echo $form->field($model, 'message_type') ?>

    <?php // echo $form->field($model, 'message_description') ?>

    <?php // echo $form->field($model, 'vendor_id') ?>

    <?php // echo $form->field($model, 'sale_id') ?>

    <?php // echo $form->field($model, 'sale_date_placed') ?>

    <?php // echo $form->field($model, 'vendor_order_id') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'auth_exp') ?>

    <?php // echo $form->field($model, 'invoice_status') ?>

    <?php // echo $form->field($model, 'fraud_status') ?>

    <?php // echo $form->field($model, 'invoice_usd_amount') ?>

    <?php // echo $form->field($model, 'customer_ip') ?>

    <?php // echo $form->field($model, 'customer_ip_country') ?>

    <?php // echo $form->field($model, 'item_id_1') ?>

    <?php // echo $form->field($model, 'item_name_1') ?>

    <?php // echo $form->field($model, 'item_usd_amount_1') ?>

    <?php // echo $form->field($model, 'item_type_1') ?>

    <?php // echo $form->field($model, 'item_rec_status_1') ?>

    <?php // echo $form->field($model, 'item_rec_date_next_1') ?>

    <?php // echo $form->field($model, 'item_rec_install_billed_1') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
