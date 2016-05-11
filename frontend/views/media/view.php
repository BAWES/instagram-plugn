<?php

/* @var $this yii\web\View */
/* @var $media common\models\Media */

use yii\helpers\Html;

$this->title = ucwords($media->media_type).' Post';
$this->params['breadcrumbs'][] = ["label" => "Media", "url" => ["media/index"]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class='col-sm-3'>
        <?= Html::a(Html::img($media->media_image_thumb), $media->media_link, ['target' => '_blank']) ?>
        <br/>
        <?= Html::a("View Post", $media->media_link, ['target' => '_blank']) ?>
    </div>

    <div class='col-sm-9'>
        <h4>
            Posted on <?= Yii::$app->formatter->asDatetime($media->media_created_datetime) ?>
        </h4>

        <?= Html::encode($media->media_caption) ?>
    </div>
</div>

<div class='row'>
    <h3>Comments</h3>
    <ul>
    <? foreach($media->comments as $comment){ ?>
        <li style="<?= $comment->comment_deleted?"color:red;":"" ?>">
            <b><?= Yii::$app->formatter->asDatetime($comment->comment_datetime, "medium") ?></b><br/>
            <i><?= $comment->comment_by_username ?>:</i> <?= $comment->comment_text ?>
        </li>
    <?php } ?>
    </ul>
</div>
