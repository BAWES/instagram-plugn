<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Agent;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Agent */

$this->title = 'Register as an Agent';
$this->registerMetaTag([
      'name' => 'description',
      'content' => 'Register as an agent on Plugn.io'
]);
$this->params['breadcrumbs'][] = $this->title;

$googleAuthUrl = Url::to(['site/auth', 'authclient' => 'google']);
$liveAuthUrl = Url::to(['site/auth', 'authclient' => 'live']);
$slackAuthUrl = Url::to(['site/auth', 'authclient' => 'slack']);

$this->registerCss(".help-block{margin-bottom:0}");
?>

<div style='text-align:center; margin-bottom:5px'>
    <img src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="" style='width:180px'>
    <h4>Sign up and start managing accounts today!</h4>
</div>

<?php $form = ActiveForm::begin(['id' => 'signup-form', 'errorCssClass' => 'form-group-error', 'options' => ['class' => 'sign-box']]); ?>

    <a href='<?= $googleAuthUrl ?>' class='btn btn-primary' style="margin-top:0; background-color:#df4a32; border-color:#df4a32">
        <i class="font-icon font-icon-google-plus"  aria-hidden="true"></i> Create Account with Google
    </a>
    <?php if(Yii::$app->params['microsoftLoginEnabled']){ ?>
    <a href='<?= $liveAuthUrl ?>' class='btn btn-primary' style="margin-top:0;">
        <i class="fa fa-windows" aria-hidden="true"></i> Create Account with Live
    </a>
    <?php } ?>
    <a href='<?= $slackAuthUrl ?>' class='btn btn-secondary' style="margin-top:0;">
        <i class="fa fa-slack" aria-hidden="true"></i> Create Account with Slack
    </a>

    <span class="or-wrapper">
		<span class="or-text">or</span>
	</span>

    <?= $form->field($model, 'agent_name', [
        'template' => '{input}{error}',
    ])->textInput([
        'maxlength' => true,
        'placeholder' => 'Your Full Name',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'agent_email', [
        'template' => '{input}{error}',
    ])->input('email', [
        'maxlength' => true,
        'placeholder' => 'Your Email Address',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'agent_password_hash', [
        'template' => '{input}{error}',
    ])->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Your Password',
            'class' => 'form-control'
        ]) ?>

    <?= Html::submitButton('Create Account', ['class' => 'btn btn-rounded', 'name' => 'signup-button']) ?>

    <p class="sign-note">Have an account? <a href="<?= Url::to(['site/login']) ?>">Sign in</a></p>



<?php ActiveForm::end(); ?>
