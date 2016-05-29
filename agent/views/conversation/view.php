<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $commenterUsername string */
/* @var $comments \common\models\Comment */
/* @var $commentQueueForm \agent\models\CommentQueue */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

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
  <li role="presentation" class="active"><a href="<?= Url::to(['conversation/list' ,'accountId' => $account->user_id]) ?>">Conversation View</a></li>
  <li role="presentation"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Media View</a></li>
  <li role="presentation"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Stats</a></li>
</ul>

<br/><br/>
<b>What this page should do</b>
<ul>
    <li>Mark comments that haven't been "Handled"</li>
    <li>Once users are done responding to comments on a post, they mark it as "Handled"</li>
    <li>A handled post marks all comments under it as handled by that agent</li>
</ul>

<h1>Conversation with @<?= $commenterUsername ?> </h1>

<?php $form = ActiveForm::begin(['id' => 'response-form']); ?>
    Send a response:<br/>
    <?= $form->field($commentQueueForm, 'queue_text') ?>
    <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
<?php ActiveForm::end(); ?>

<br/><br/>

<?php foreach($comments as $comment){ ?>
<div style='<?= $comment['commentType']=="queue"?"background:lightyellow":"" ?>'>
<div class='row'>
    <div class='col-sm-1 col-xs-2'>
        <div style='width:45px; height:45px;'>
            <?= Html::img($comment['comment_by_photo'], ['style' => 'width:45px']) ?>
        </div>
    </div>
    <div class='col-sm-7 col-xs-6'>
        <b><?= $comment['comment_by_fullname'] ?></b> <i>@<?= $comment['comment_by_username'] ?></i>
        <br/><span style='color:Grey;'>"<?= $comment['comment_text'] ?>"</span>
    </div>
    <div class='col-sm-4 col-xs-4'>
        <b>AgentNameHere?</b> <br/>
        <?= Yii::$app->formatter->asRelativeTime($comment['comment_datetime']) ?>

    </div>
</div>
</div>

<?php } ?>
