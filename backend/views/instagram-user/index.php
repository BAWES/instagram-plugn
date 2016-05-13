<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'user_id',
            'user_name',
            //'user_fullname',
            //'user_auth_key',
            'user_status',
            // 'user_updated_datetime',
            // 'user_profile_pic',
            // 'user_bio:ntext',
            // 'user_website',
            // 'user_instagram_id',
            'user_media_count',
            'user_following_count',
            'user_follower_count',
            'user_created_datetime',
            // 'user_ig_access_token',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
