<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pricing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pricing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pricing_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pricing_features')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pricing_price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
