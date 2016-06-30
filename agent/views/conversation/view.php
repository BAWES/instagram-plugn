<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $commenterId integer */
/* @var $commenterUsername string */
/* @var $comments \common\models\Comment */
/* @var $commentQueueForm \agent\models\CommentQueue */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Comment;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<a class='btn btn-primary'
    href='<?= Url::to(['conversation/view', 'accountId' => $account->user_id, 'commenterId' => $commenterId, 'handleComments' => true]) ?>'>
    Mark Conversation as Handled
</a>

<h1>Conversation with @<?= $commenterUsername ?> </h1>

<?php $form = ActiveForm::begin(['id' => 'response-form']); ?>
    Send a response:<br/>
    <?= $form->field($commentQueueForm, 'queue_text') ?>
    <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
<?php ActiveForm::end(); ?>

<br/><br/>

<?php foreach($comments as $comment){ ?>
<div style='<?= $comment['commentType']=="queue"?"background:lightyellow;":"" ?>

    <?php
    //Whether the comment has been handled or not
    if(isset($comment['comment_handled'])){
        switch($comment['comment_handled'])
        {
            case Comment::HANDLED_TRUE:
                echo "border-left:3px solid green;";
                break;
            case Comment::HANDLED_FALSE:
                echo "border-left:3px solid red;";
                break;
        }
    }
    ?>

    <?php
    //Whether the comment has been deleted or not
    $deleteReason = "";
    if(isset($comment['comment_deleted']))
    {
        switch($comment['comment_deleted'])
        {
            case Comment::DELETED_TRUE:
                $deleteReason = $comment['comment_deleted_reason'];
                echo "background:red;";
                break;
            case Comment::DELETED_QUEUED_FOR_DELETION:
                $deleteReason = "Currently Queued for Deletion";
                echo "background:pink;";
                break;
        }
    }
    ?>
    '>
<div class='row'>
    <div class='col-sm-1 col-xs-2'>
        <div style='width:45px; height:45px;'>
            <?= Html::img($comment['comment_by_photo'], ['style' => 'width:45px']) ?>
        </div>
    </div>
    <div class='col-sm-7 col-xs-6'>
        <b><?= $comment['agent_name']?$comment['agent_name']:$comment['comment_by_fullname'] ?></b>
        <i>@<?= $comment['comment_by_username'] ?></i>
        <br/><span style='color:Grey;'>"<?= $comment['comment_text'] ?>"</span>
        <span style='color:white;'> <?= $deleteReason ?> </span>
        <?php if(!$deleteReason && $comment['commentType']!="queue"){ ?>
        <a href='<?= Url::to(['conversation/view', 'accountId' => $account->user_id, 'commenterId' => $commenterId, 'deleteComment' => $comment['comment_id']]) ?>'
        style='color:red; font-size:0.8em;' data-confirm="Are you sure you wish to delete this comment?">
            Delete
        </a>
        <?php } ?>
    </div>
    <div class='col-sm-4 col-xs-4'>
        <?= Yii::$app->formatter->asRelativeTime($comment['comment_datetime']) ?>

    </div>
</div>
</div>

<?php } ?>
