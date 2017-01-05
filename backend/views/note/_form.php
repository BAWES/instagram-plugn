<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Note */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="note-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'note_about_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_by_agent_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by_agent_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
