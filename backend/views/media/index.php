<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Media';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'media_id',
            'user_id',
            'media_instagram_id',
            'media_type',
            'media_link',
            // 'media_num_comments',
            // 'media_num_likes',
            // 'media_caption:ntext',
            // 'media_image_lowres',
            // 'media_image_thumb',
            // 'media_image_standard',
            // 'media_video_lowres',
            // 'media_video_lowbandwidth',
            // 'media_video_standard',
            // 'media_location_name',
            // 'media_location_longitude',
            // 'media_location_latitude',
            // 'media_created_datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
