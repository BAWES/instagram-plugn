<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->title = $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class='col-md-6'>
        <h2>Summary</h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'agent_id',
                'agent_name',
                'agent_email:email',
                'agent_email_verified:boolean',
                // 'agent_auth_key',
                // 'agent_password_hash',
                // 'agent_password_reset_token',
                // 'agent_status',
                'agent_limit_email:datetime',
                'agent_created_at:datetime',
                'agent_updated_at:datetime',
            ],
        ]) ?>
    </div>

    <div class='col-md-6'>
        <h2>Assigned Accounts</h2>
        <?= GridView::widget([
            'dataProvider' => $accountsDataProvider,
            'columns' => [
                'user_name',
                'user_follower_count',
                'status',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'instagram-user'],
            ],
        ]); ?>

    </div>


    <div class='col-md-12'>
        <h3>Activity</h3>

        <?= GridView::widget([
            'dataProvider' => $activityDataProvider,
            'columns' => [
                'user.user_name',
                'activity_detail:ntext',
                'activity_datetime:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'activity'],
            ],
        ]); ?>
    </div>



</div>
