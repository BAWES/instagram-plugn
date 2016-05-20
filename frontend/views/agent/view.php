<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->title = $model->assignment_agent_email;
$this->params['breadcrumbs'][] = ['label' => 'Agent Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'assignment_id',
            //'user_id',
            //'agent_id',
            'assignment_agent_email:email',
            'assignment_created_at',
            //'assignment_updated_at',
        ],
    ]) ?>

    <p>
        <?= Html::a('Remove Agent from your Account', ['delete', 'id' => $model->assignment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>