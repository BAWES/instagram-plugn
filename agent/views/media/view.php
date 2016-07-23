<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $media \common\models\Media */
/* @var $commentQueueForm \agent\models\CommentQueue */
/* @var $comments \common\models\Comment */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Comment;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;

//Auto Scroll to bottom of chat on page load
$this->registerJs("
$(document).ready(function(){
	$('#commentList').scrollTop($('#commentList')[0].scrollHeight);
});
");
?>

<div class="box-typical chat-container">
	<section>
		<div class="chat-area-header">
			<div class="chat-list-item" style='cursor:default'>
				<div class="chat-list-media-photo">
					<a href='<?= $media->media_link ?>' target='_blank'>
						<?= Html::img($media->media_image_thumb) ?>
					</a>
				</div>
				<div class="chat-list-item-name">
					<span class="name">Media posted on <?= Yii::$app->formatter->asDate($media->media_created_datetime) ?></span>
				</div>
				<div class="chat-list-item-txt writing">
					<a href='<?= $media->media_link ?>' target='_blank'>
					    <?= Html::encode($media->media_caption) ?>
					</a>
				</div>
			</div>
			<div class="chat-area-header-action">
					<a class="btn btn-primary btn-sm"
                        href="<?= Url::to(['media/view', 'accountId' => $account->user_id, 'mediaId' => $media->media_id, 'handleComments' => true]) ?>">
						Mark Handled
					</a>
			</div>
		</div><!--.chat-area-header-->

		<div id="commentList" class="chat-dialog-area scrollable-block">

            <?php if($comments){ ?>
                <?php foreach($comments as $comment){ ?>

                    <?php
                    $commentClass = "";
                    $additionalMessage = "";

					//Is it an unhandled comment?
					$commentHandled = true;
                    if(isset($comment['comment_handled'])){
                        if($comment['comment_handled'] == Comment::HANDLED_FALSE){
                            $commentClass = "selected";
							$commentHandled = false;
                        }
                    }
					//Is it already handled by one of our agents?
					if($commentHandled){
						if($comment['commentType']!="queue"){
							//Show who handled it if its not a comment one of our agents posted for this account
							if($comment['comment_by_id'] != $account->user_instagram_id){
								$additionalMessage = "<span style='font-size:9px'>Handled by ".$comment['handler_name']."</span>";
							}
						}else{
							//If Queued to be posted
							$commentClass = "queued";
							$additionalMessage = "<span class='que'><i class='fa fa-circle-o-notch fa-spin'></i> Queued to be posted by ".$comment['agent_name']."</span>";
						}
					}

                    //Is it deleted or queued for deletion?
                    if(isset($comment['comment_deleted']))
                    {
                        switch($comment['comment_deleted'])
                        {
                            case Comment::DELETED_TRUE:
                                $commentClass = "deleted";
                                $additionalMessage = "<span class='que'><i class='fa fa-trash'></i> Deleted by ".$comment['deleter_name']."</span>";
                                break;

                            case Comment::DELETED_QUEUED_FOR_DELETION:
                                $commentClass = "deleted";
                                $additionalMessage = "<span class='que'><i class='fa fa-circle-o-notch fa-spin'></i> Queued to be deleted</span>";
                                break;
                        }
                    }
                    ?>


                    <div class="comment-row-item <?= $commentClass ?>">
        				<div class="avatar-preview avatar-preview-32">
        					<a href="http://instagram.com/<?= $comment['comment_by_username'] ?>" target='_blank'>
        						<?= Html::img($comment['comment_by_photo']) ?>
        					</a>
        				</div>
        				<div class="tbl comment-row-item-header">
        					<div class="tbl-row">
        						<div class="tbl-cell tbl-cell-name">
                                    <?= $comment['agent_name']?$comment['agent_name']:$comment['comment_by_fullname'] ?>
                                    <i>@<?= $comment['comment_by_username'] ?></i>
                                </div>
        						<div class="tbl-cell tbl-cell-date">
                                    <?= Yii::$app->formatter->asRelativeTime($comment['comment_datetime']) ?>
                                </div>
        					</div>
        				</div>
        				<div class="comment-row-item-content">
        					<p><?= $comment['comment_text'] ?></p>

                            <?= $additionalMessage ?>


                            <?php if($commentClass != 'deleted' && $comment['commentType']!="queue"){ ?>
                                <a href='<?= Url::to(['media/view', 'accountId' => $account->user_id, 'mediaId' => $media->media_id, 'deleteComment' => $comment['comment_id']]) ?>'
                                    class="comment-row-item-action del" data-confirm="Are you sure you wish to delete this comment?">
            						<i class="font-icon font-icon-trash"></i>
            					</a>
                            <?php } ?>

        				</div>

        				<?php /*<a href="#" class="comment-row-item-reply">Reply</a> */ ?>
        			</div>

                <?php } ?>
            <?php } ?>


		</div><!--.chat-dialog-area-->

		<div class="chat-area-bottom">
            <?php $form = ActiveForm::begin([
                'id' => 'response-form',
                'errorCssClass' => 'form-group-error',
                'options' => [
                    'class' => 'write-message'
                ]
            ]); ?>

				<div class="avatar">
					<img src="<?= $account->user_profile_pic ?>" alt="">
				</div>

                <?= $form->field($commentQueueForm, 'queue_text', [
                    'template' => '{input}{error}',
                ])->textArea([
                    'maxlength' => true,
                    'placeholder' => 'Type a message',
                    'rows' => 2,
                    'class' => 'form-control'
                ]) ?>

                <?= Html::submitButton('Send', ['class' => 'btn btn-rounded float-left', 'name' => 'send-button']) ?>
			</form>
            <?php ActiveForm::end(); ?>
		</div><!--.chat-area-bottom-->
    </section><!--.chat-area-->
</div><!--.chat-area-in-->
