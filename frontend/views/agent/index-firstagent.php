<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Add yourself as an Agent';
?>

<div class="container-fluid">
	<div class="box-typical box-typical-full-height">
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<div class="add-customers-screen-user">
					<i class="font-icon font-icon-users"></i>
				</div>

				<h2>Agents</h2>
				<p class="lead color-blue-grey-lighter">Agents have access to manage your account<br/> Add yourself as an agent</p>

				<?php $form = ActiveForm::begin(['errorCssClass' => 'form-group-error']); ?>

					<div class='row'>
						<div class='col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8'>

                            <?= $form->field($model, 'assignment_agent_email', [
                                'template' => '<div class="form-control-wrapper form-control-icon-left">{input}<i class="font-icon font-icon-mail"></i></div>{error}',

                            ])->input('email', [
                                'maxlength' => true,
                                'placeholder' => 'Your email address',
                                'class' => 'form-control'
                                ])->label(false) ?>

                                <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'style'=>'margin-top:0']) ?>

						</div>
					</div>



				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->