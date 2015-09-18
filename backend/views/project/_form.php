<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'subcategory_id')->textInput() ?>

    <?= $form->field($model, 'project_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
