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

	<h3>
		<i class="font-icon font-icon-users"></i>
		Agents <small class="text-muted">receive access to manage the account</small>
	</h3>

	<div class="box-typical box-typical-padding" style='margin-top:1em;'>
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<h3>Invite an Agent</h3>

				<?php $form = ActiveForm::begin(['errorCssClass' => 'form-group-error']); ?>
					<div class='row'>
						<div class='col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8'>

                            <?= $form->field($model, 'assignment_agent_email', [
                                'template' => '<div class="form-control-wrapper form-control-icon-left">{input}<i class="font-icon font-icon-mail"></i></div>{error}',

                            ])->input('email', [
                                'maxlength' => true,
                                'placeholder' => "Agent's email address",
                                'class' => 'form-control'
                                ])->label(false) ?>

						</div>
					</div>

					<?= Html::submitButton('Send Invite', ['class' => 'btn btn-primary', 'style'=>'margin-top:0']) ?>
				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div><!--.box-typical-->

</div><!--.container-fluid-->
