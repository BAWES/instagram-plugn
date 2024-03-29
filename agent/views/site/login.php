<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Agent Login';
$this->registerMetaTag([
      'name' => 'description',
      'content' => 'Log in to your agent account on Plugn.io'
]);
$this->params['breadcrumbs'][] = $this->title;

$googleAuthUrl = Url::to(['site/auth', 'authclient' => 'google']);
$liveAuthUrl = Url::to(['site/auth', 'authclient' => 'live']);
$slackAuthUrl = Url::to(['site/auth', 'authclient' => 'slack']);

$this->registerCss(".help-block{margin-bottom:0}");
?>

<div style='text-align:center; margin-bottom:20px'>
    <img src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="" style='width:180px'>
</div>

<?php $form = ActiveForm::begin(['id' => 'login-form', 'errorCssClass' => 'form-group-error', 'options' => ['class' => 'sign-box']]); ?>

    <a href='<?= $googleAuthUrl ?>' class='btn btn-primary' style="margin-top:0; background-color:#df4a32; border-color:#df4a32">
        <i class="font-icon font-icon-google-plus"  aria-hidden="true"></i> Log in with Google
    </a>
    <?php if(Yii::$app->params['microsoftLoginEnabled']){ ?>
    <a href='<?= $liveAuthUrl ?>' class='btn btn-primary' style="margin-top:0;">
        <i class="fa fa-windows" aria-hidden="true"></i> Log in with Live
    </a>
    <?php } ?>
    <a href='<?= $slackAuthUrl ?>' class='btn btn-secondary' style="margin-top:0;">
        <i class="fa fa-slack" aria-hidden="true"></i> Log in with Slack
    </a>

    <span class="or-wrapper">
		<span class="or-text">or</span>
	</span>

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

    <?= Html::submitButton('Sign in', ['class' => 'btn btn-rounded', 'name' => 'signin-button']) ?>

<?php ActiveForm::end(); ?>
