<?php

use yii\helpers\Html;
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
?>
<div>


    <div class="panel-body">
        <?php
        //Field Templates
        $fieldTemplate = "{label}\n{beginWrapper}\n"
                        . "<div class='inputer'>\n<div class='input-wrapper'>\n"
                        . "{input}"
                        . "</div>\n</div>\n{hint}\n{error}\n"
                        . "{endWrapper}";

        $selectTemplate = "{label}\n{beginWrapper}\n"
                        . "<div class=''>\n<div class=''>\n"
                        . "{input}"
                        . "</div>\n</div>\n{hint}\n{error}\n"
                        . "{endWrapper}";


        /**
         * Start Form
         */
        $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => $fieldTemplate,
                        'horizontalCssClasses' => [
                            'label' => 'col-md-3',
                            'offset' => '',
                            'wrapper' => "col-md-5",
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
        ]);
        ?>


        <h2>Sign up and start managing accounts today!</h4>

        <?= $form->field($model, 'agent_name')->textInput(['placeholder' => 'First Name']) ?>
        <?= $form->field($model, 'agent_email')->input('email', ['placeholder' => 'email@company.com']) ?>
        <?= $form->field($model, 'agent_password_hash')->passwordInput(['placeholder' => '***']) ?>


        <div class="col-md-5 col-md-offset-3">
                <?= Html::submitButton('Sign Up', ['class' => 'btn btn-success btn-block btn-ripple', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
