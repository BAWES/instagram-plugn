<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = "Activity #".$model->activity_id;
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view">

    <h2><?= Html::encode($model->activity_detail) ?></h2>
    <h4><?= Yii::$app->formatter->asDateTime($model->activity_datetime, "long") ?></h4>


    <a target='_blank' href='<?= Url::to(['agent/view', 'id' => $model->agent->agent_id]) ?>' class='btn btn-lg btn-primary'>Go to Agent (<?= $model->agent->agent_name ?>)</a>

    <a target='_blank' href='<?= Url::to(['instagram-user/view', 'id' => $model->user->user_id]) ?>' class='btn btn-lg btn-primary'>Go to Account (@<?=$model->user->user_name ?>)</a>

</div>
