<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<div class="container-fluid">

	<h3 style='margin-top:5px;'>
		<i class="font-icon font-icon-users"></i>
		Agents <small class="text-muted">that receive access to manage @<?= $account->user_name ?></small>
	</h3>

	<div class='row'>
		<div class='col-md-7'>
			<p>
				Send out an invite to someone you trust with your account.
				Make sure to send one to yourself if you're also managing this account.
			</p>

			<?php $form = ActiveForm::begin(['errorCssClass' => 'form-group-error']); ?>

                <?= $form->field($model, 'assignment_agent_email', [
                    'template' => '<div class="form-control-wrapper form-control-icon-left">{input}<i class="font-icon font-icon-mail"></i></div>{error}',

                ])->input('email', [
                    'maxlength' => true,
                    'placeholder' => "Agent's email address",
                    'class' => 'form-control'
                    ])->label(false) ?>

				<?= Html::submitButton('Send Invite', ['class' => 'btn btn-primary', 'style'=>'margin-top:0']) ?>
			<?php ActiveForm::end(); ?>

		</div>
	</div>


</div><!--.container-fluid-->
