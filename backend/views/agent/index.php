<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // 'agent_id',
            'agent_name',
            'agent_email:email',
            'agent_email_verified:boolean',
            'status',
            // 'agent_auth_key',
            // 'agent_password_hash',
            // 'agent_password_reset_token',
            // 'agent_status',
            // 'agent_limit_email:email',
            'agent_created_at',
            // 'agent_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
