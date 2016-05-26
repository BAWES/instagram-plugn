<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $media \common\models\Media */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;
?>


<h2>
    <?= $account->user_fullname ?> -
    <?= Html::a("@".$account->user_name, "http://instagram.com/".$account->user_name, ['target' => '_blank']) ?>
</h2>
<div class="row">
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Media</h4>
        <?= $account->user_media_count ?>
    </div>
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Following</h4>
        <?= $account->user_following_count ?>
    </div>
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Followers</h4>
        <?= $account->user_follower_count ?>
    </div>
</div>

<ul class="nav nav-tabs" style='margin-top:1.5em;'>
  <li role="presentation"><a href="<?= Url::to(['conversation/list' ,'accountId' => $account->user_id]) ?>">Conversation View</a></li>
  <li role="presentation" class="active"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Media View</a></li>
  <li role="presentation"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Stats</a></li>
</ul>

<br/><br/>
<b>What this page should do</b>
<ul>
    <li>Mark comments that haven't been "Handled"</li>
    <li>Once users are done responding to comments on a post, they mark it as "Handled"</li>
    <li>A handled post marks all comments under it as handled by that agent</li>
</ul>

<h1>Media View</h1>

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
    <?= $media->comments?"":"<h4 style='color:red'>No comments</h4>" ?>
    <ul>
    <?php foreach($media->comments as $comment){ ?>
        <li style="<?= $comment->comment_deleted?"color:red;":"" ?>">
            <b><?= Yii::$app->formatter->asDatetime($comment->comment_datetime, "medium") ?></b><br/>
            <i><?= $comment->comment_by_username ?>:</i> <?= $comment->comment_text ?>
        </li>
    <?php } ?>
    </ul>
</div>
