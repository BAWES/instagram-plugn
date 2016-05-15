<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = Yii::t('employer', 'Request password reset');
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
            <h4><?= Yii::t('employer', 'Please fill out your email. A link to reset password will be sent there') ?></h4>
        </div>
    </div>

    <div class="panel-body">

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'fieldConfig' => [
                        'template' => $fieldTemplate,
                    ],
                ]); ?>

                    <?= $form->field($model, 'email')->input('email',['placeholder' => 'email@company.com']) ?>
                
                    <div class="form-group" style="margin-top:30px;">
                        <?= Html::submitButton(Yii::t('employer', 'Send'), ['class' => 'btn btn-teal']) ?>
                    </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
    </div>
</div>