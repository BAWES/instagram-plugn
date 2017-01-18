<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage your Agent Assignments';
?>
<header class="page-content-header">
	<div class="container-fluid">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3><i class="font-icon font-icon-users"></i> Agents <small class="text-muted">who have access to manage your account</small></h3>
				</div>
			</div>
		</div>
	</div>
</header>


<div class="container-fluid">

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