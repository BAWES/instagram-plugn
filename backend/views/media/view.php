<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = "Post by ".$model->user->user_name;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-view">

    <h1><?= Html::img($model->media_image_thumb); ?> <?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'media_id',
            'user.user_name',
            'media_instagram_id',
            'media_type',
            'media_link:url',
            'media_num_comments',
            'media_num_likes',
            'media_caption:ntext',
            'media_image_lowres:url',
            'media_image_thumb:url',
            'media_image_standard:url',
            'media_video_lowres:url',
            'media_video_lowbandwidth:url',
            'media_video_standard:url',
            'media_location_name',
            'media_location_longitude',
            'media_location_latitude',
            'media_created_datetime',
        ],
    ]) ?>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->media_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
