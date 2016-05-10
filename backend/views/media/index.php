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
            //['class' => 'yii\grid\SerialColumn'],

            //'media_id',
            'media_image_thumb:image',
            'user.user_name',
            'media_instagram_id',
            'media_type',
            'media_caption:ntext',
            //'media_link',
            'media_num_comments',
            'media_num_likes',
            'media_created_datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
