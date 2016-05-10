<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = "Comment by ". $model->comment_by_username;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <h1><?= Html::img($model->comment_by_photo) ?> <?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'comment_id',
            'media_id',
            'comment_instagram_id',
            'comment_text:ntext',
            'comment_by_username',
            'comment_by_photo:url',
            'comment_by_id',
            'comment_by_fullname',
            'comment_deleted',
            'comment_deleted_reason:ntext',
            'comment_datetime:datetime',
        ],
    ]) ?>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->comment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
