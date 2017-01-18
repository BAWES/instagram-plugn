<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Agency Login';
$this->registerMetaTag([
      'name' => 'description',
      'content' => 'Log in to your agency account on Plugn.io'
]);
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(".help-block{margin-bottom:0}");
?>

<div style='text-align:center; margin-bottom:5px'>
    <img src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="" style='width:180px; margin-bottom:20px;'>
    <h3 style='margin-bottom:5px; padding-bottom:0;'>Agency Login</h3>
    <pre>Note: Your agency account is not your agent account.<br/><a href='https://plugn.io/features/account-types/' target='_blank'>More info on the different account types</a></pre>
</div>

<?php $form = ActiveForm::begin(['id' => 'login-form', 'errorCssClass' => 'form-group-error', 'options' => ['class' => 'sign-box']]); ?>

    <?= $form->field($model, 'email', [
        'template' => '{input}{error}',
    ])->input('email', [
        'maxlength' => true,
        'placeholder' => 'Your Email Address',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'password', [
        'template' => '{input}{error}',
    ])->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Your Password',
            'class' => 'form-control'
        ]) ?>

    <div class="form-group">
            <?= $form->field($model, 'rememberMe', [
                'options' => ['class' => 'checkbox float-left']
            ])->checkbox([
                'template' => "{input}\n{label}\n{hint}\n{error}"
            ]) ?>

        <div class="float-right reset">
            <a href="<?= Url::to(['site/request-password-reset']) ?>">Forgot password?</a>
        </div>
    </div>

    <?= Html::submitButton('Sign in', ['class' => 'btn btn-rounded', 'name' => 'signin-button']) ?>

    <p class="sign-note">Don't have an account? <a href="<?= Url::to(['site/registration']) ?>">Create account</a></p>

<?php ActiveForm::end(); ?>
