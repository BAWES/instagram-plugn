<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->user_fullname. " - @". $model->user_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::img($model->user_profile_pic) ?>
        <?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'user_id',
            'user_name',
            'user_fullname',
            'user_auth_key',
            'user_status',
            //'user_profile_pic:image',
            'user_bio:ntext',
            'user_website:url',
            'user_instagram_id',
            'user_media_count',
            'user_following_count',
            'user_follower_count',
            'user_ig_access_token',
            'user_created_datetime:datetime',
            'user_updated_datetime:datetime',
        ],
    ]) ?>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
