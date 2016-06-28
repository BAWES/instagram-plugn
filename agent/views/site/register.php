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
    <h2>Sign up and start managing accounts today!</h4>
</div>

<?php $form = ActiveForm::begin(['id' => 'signup-form', 'errorCssClass' => 'form-group-error', 'options' => ['class' => 'sign-box']]); ?>

    <a href='<?= $googleAuthUrl ?>' class='btn btn-primary' style="margin-top:0; background-color:#df4a32; border-color:#df4a32">
        <i class="font-icon font-icon-google-plus"  aria-hidden="true"></i> Create Account with Google
    </a>
    <a href='<?= $liveAuthUrl ?>' class='btn btn-primary' style="margin-top:0;">
        <i class="fa fa-windows" aria-hidden="true"></i> Create Account with Live
    </a>
    <a href='<?= $slackAuthUrl ?>' class='btn btn-secondary' style="margin-top:0;">
        <i class="fa fa-slack" aria-hidden="true"></i> Create Account with Slack
    </a>



        <?= $form->field($model, 'agent_name')->textInput(['placeholder' => 'Your Full Name']) ?>
        <?= $form->field($model, 'agent_email')->input('email', ['placeholder' => 'email@gmail.com']) ?>
        <?= $form->field($model, 'agent_password_hash')->passwordInput(['placeholder' => '***']) ?>


        <div class="col-md-5 col-md-offset-3">
                <?= Html::submitButton('Sign Up', ['class' => 'btn btn-success btn-block btn-ripple', 'name' => 'signup-button']) ?>
        </div>

<?php ActiveForm::end(); ?>
