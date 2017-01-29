<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \agent\models\ResetPasswordForm */

$this->title = Yii::t('employer', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;

$fieldTemplate = "{label}\n{beginWrapper}\n"
                        . "<div class='inputer'>\n<div class='input-wrapper'>\n"
                        . "{input}\n"
                        . "</div>\n</div>\n{hint}\n{error}\n"
                        . "{endWrapper}";
?>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <h4><?= Yii::t('employer', 'Please choose your new password') ?></h4>
        </div>
    </div>

    <div class="panel-body">

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin([
                    'id' => 'reset-password-form',
                    'fieldConfig' => [
                        'template' => $fieldTemplate,
                    ],
                ]); ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '***']) ?>

                    <div class="form-group" style="margin-top:30px;">
                        <?= Html::submitButton(Yii::t('employer', 'Save'), ['class' => 'btn btn-teal']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>
