<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NoteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="note-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'note_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'note_about_username') ?>

    <?= $form->field($model, 'note_title') ?>

    <?= $form->field($model, 'note_text') ?>

    <?php // echo $form->field($model, 'created_by_agent_id') ?>

    <?php // echo $form->field($model, 'updated_by_agent_id') ?>

    <?php // echo $form->field($model, 'note_created_datetime') ?>

    <?php // echo $form->field($model, 'note_updated_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
