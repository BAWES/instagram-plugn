<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Record */

$this->title = "Record #".$model->record_id;
$this->params['breadcrumbs'][] = ['label' => 'Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'record_id',
            'user.user_name',
            'record_media_count',
            'record_following_count',
            'record_follower_count',
            'record_date:date',
        ],
    ]) ?>

    <a target='_blank'
    href='<?= Url::to(['instagram-user/view', 'id' => $model->user->user_id]) ?>' class='btn btn-lg btn-primary'>
        Go to Account (@<?=$model->user->user_name ?>)</a>

</div>
