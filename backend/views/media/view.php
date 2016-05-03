<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = $model->media_id;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'media_id',
            'user_id',
            'media_instagram_id',
            'media_type',
            'media_link',
            'media_num_comments',
            'media_num_likes',
            'media_caption:ntext',
            'media_image_lowres',
            'media_image_thumb',
            'media_image_standard',
            'media_video_lowres',
            'media_video_lowbandwidth',
            'media_video_standard',
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
