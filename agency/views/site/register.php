<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Agency;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Agency */

$this->title = 'Register as an Agency';
$this->registerMetaTag([
      'name' => 'description',
      'content' => 'Register as an agency on Plugn.io'
]);
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(".help-block{margin-bottom:0}");
?>

<div style='text-align:center; margin-bottom:5px'>
    <img src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="" style='width:180px; margin-bottom:20px;'>
    <h3 style='margin-bottom:3px;'>Create an Agency Account</h3>
    <h4>Manage Instagram accounts and the agents assigned to them from one location</h4>
</div>

<?php $form = ActiveForm::begin(['id' => 'signup-form', 'errorCssClass' => 'form-group-error', 'options' => ['class' => 'sign-box']]); ?>

    <?= $form->field($model, 'agency_company', [
        'template' => '{input}{error}',
    ])->textInput([
        'maxlength' => true,
        'placeholder' => 'Agency Name (Optional)',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'agency_fullname', [
        'template' => '{input}{error}',
    ])->textInput([
        'maxlength' => true,
        'placeholder' => 'Your Full Name',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'agency_email', [
        'template' => '{input}{error}',
    ])->input('email', [
        'maxlength' => true,
        'placeholder' => 'Your Email Address',
        'class' => 'form-control'
        ]) ?>

    <?= $form->field($model, 'agency_password_hash', [
        'template' => '{input}{error}',
    ])->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Your Password',
            'class' => 'form-control'
        ]) ?>

    <?= Html::submitButton('Create Account', ['class' => 'btn btn-rounded', 'name' => 'signup-button']) ?>

    <p class="sign-note">Have an account? <a href="<?= Url::to(['site/login']) ?>">Sign in</a></p>



<?php ActiveForm::end(); ?>
