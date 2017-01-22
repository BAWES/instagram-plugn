<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $conversations \common\models\Comment */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<section class="chat-list-agent">
	<!--
	<div class="chat-list-search">
		<input type="text" class="form-control form-control-rounded" placeholder="Search"/>
	</div>
	-->
	<div class="chat-list-in scrollable-block">

        <?php foreach($conversations as $comment){
            $numberOfUnhandledMessages = isset($comment['unhandledCount'])?$comment['unhandledCount']:0;
            ?>

            <a href='<?= Url::to(['conversation/view',
                            'accountId' => $account->user_id,
                            'commenterId' => $comment['comment_by_id'],
                            ]) ?>' class="chat-list-item">
				<div class="chat-list-item-photo">
                    <?= Html::img($comment['comment_by_photo']) ?>
				</div>
				<div class="chat-list-item-header">
					<div class="chat-list-item-name">
						<span class="name"><?= $comment['comment_by_fullname'] ?> <i>@<?= $comment['comment_by_username'] ?></i></span>
					</div>
					<div class="chat-list-item-date">
                        <?= Yii::$app->formatter->asRelativeTime($comment['comment_datetime']) ?>
                    </div>
				</div>
				<div class="chat-list-item-cont">
					<div class="chat-list-item-txt <?= $numberOfUnhandledMessages?"":"writing" ?>"><?= $comment['comment_text'] ?></div>
                    <?php if($numberOfUnhandledMessages){ ?>
					    <div class="chat-list-item-count"><?= $numberOfUnhandledMessages ?></div>
                    <?php } ?>
				</div>
			</a>

        <?php } ?>

	</div><!--.chat-list-in-->
</section><!--.chat-list-->
