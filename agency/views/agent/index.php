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

	<h5 style='margin-bottom:0;'>How to manage an account?</h5>
	<p>
		Once invited as an agent, you can start managing an account by downloading Plugn's mobile apps
		or logging into the
		<a href='http://agent.plugn.io' target='_blank'>agents' web portal</a>
	</p>

	<h5 style='margin-bottom:0;'>Who is assigned to @<?= $account->user_name ?>?</h5>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'assignment_id',
            //'user_id',
            //'agent_id',
            'assignment_agent_email:email',
            'assignment_created_at:date',
            // 'assignment_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>


    <div class="box-typical box-typical-padding" style='margin-top:1em;'>
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<h3>Invite Additional Agents</h3>

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

</div>
