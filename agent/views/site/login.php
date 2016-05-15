<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <div style="color:#999;margin:1.5em 0">
                    <?= Yii::t("agent", "If you forgot your password you can") ?>
                    <?= Html::a(Yii::t("employer",'reset it'), ['site/request-password-reset']) ?>
                    <br/>
                    <?= Yii::t("agent", "Don't have an account? <a href='{url}'>Register</a>", [
                        'url' => Url::to(['site/registration']),
                    ]) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
