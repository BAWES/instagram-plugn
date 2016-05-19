<?php

use yii\helpers\Html;
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

            'assignment_id',
            'user_id',
            'agent_id',
            'assignment_agent_email:email',
            'assignment_created_at',
            // 'assignment_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{delete}'],
        ],
    ]); ?>
</div>
