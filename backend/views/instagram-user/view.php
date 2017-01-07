<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = "@". $model->user_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class='col-xs-12'>
        <?= Html::img($model->user_profile_pic, ['style' => 'float:left; margin-right:10px; width:100px;']) ?>
        <h1 style='margin-top:0;'><?= Html::encode($model->user_fullname) ?></h1>
        <h4>
            <a href='https://instagram.com/<?=$model->user_name?>' target='_blank'>
                @<?= Html::encode($model->user_name) ?>
            </a>
        </h4>
    </div>

    <div class='col-md-7'>
        <h3>Summary</h3>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'user_auth_key',
                'status',
                'user_bio:ntext',
                'user_website:url',
                'user_instagram_id',
                'user_ig_access_token',
                'user_created_datetime:datetime',
                'user_updated_datetime:datetime',
            ],
        ]) ?>
    </div>

    <div class='col-md-5'>
        <?php if($agency){ ?>
        <h3>Managed by Agency  <a target='_blank' href='<?= Url::to(['agency/view', 'id' => $agency->agency_id]) ?>' class='btn btn-xs btn-primary'>View Agency</a></h3>

        <?= DetailView::widget([
            'model' => $agency,
            'attributes' => [
                'agency_fullname',
                'agency_company',
            ],
        ]) ?>
        <?php } ?>

        <h3>Metrics</h3>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'user_media_count',
                'user_following_count',
                'user_follower_count',
            ],
        ]) ?>
    </div>

    <div class='col-md-12'>
        <h3>Agents managing this account</h3>

        <?= GridView::widget([
            'dataProvider' => $agentsDataProvider,
            'columns' => [
                // 'agent_id',
                'agent_name',
                'agent_email:email',
                'agent_email_verified:boolean',
                'agent_created_at',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'agent'],
            ],
        ]); ?>
    </div>

    <div class='col-md-12'>
        <h3>Agent Activity</h3>

        <?= GridView::widget([
            'dataProvider' => $activityDataProvider,
            'columns' => [
                'agent.agent_name',
                'activity_detail:ntext',
                'activity_datetime:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'activity'],
            ],
        ]); ?>
    </div>


</div>
