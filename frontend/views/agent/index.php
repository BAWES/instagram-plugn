<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agent Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Agent Assignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'assignment_id',
            //'user_id',
            //'agent_id',
            'assignment_agent_email:email',
            'assignment_created_at',
            // 'assignment_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{delete}'],
        ],
    ]); ?>
</div>

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

                            <?= $form->field($model, 'assignment_agent_email', ['template' => '{input}'])
                            ->input('email', [
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
