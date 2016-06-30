<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $conversations \common\models\Comment */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;
?>

<!-- Old Code Above -->

<div class="profile-header-photo">
	<div class="profile-header-photo-in">
		<div class="tbl-cell">
			<div class="info-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-offset-0">
							<div class="tbl info-tbl">
								<div class="tbl-row">
									<div class="tbl-cell">
										<p class="maintitle"><?= $account->user_fullname ?></p>
										<p>@<?= $account->user_name ?></p>
									</div>
									<div class="tbl-cell tbl-cell-stat">
										<div class="inline-block">
											<p class="title"><?= $account->user_media_count ?></p>
											<p class="count">Media</p>
										</div>
									</div>
									<div class="tbl-cell tbl-cell-stat">
										<div class="inline-block">
											<p class="title"><?= $account->user_following_count ?></p>
											<p class="count">Following</p>
										</div>
									</div>
									<div class="tbl-cell tbl-cell-stat">
										<div class="inline-block">
											<p class="title"><?= $account->user_follower_count ?></p>
											<p class="count">Followers</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href='http://instagram.com/<?= $account->user_name ?>' class="change-cover" target='_blank'>
		<i class="font-icon font-icon-picture-double"></i>
		Profile on Instagram
	</a>
</div><!--.profile-header-photo-->


<div class="container-fluid">

	<section class="tabs-section">
		<div class="tabs-section-nav tabs-section-nav-inline">
			<ul class="nav" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" href="<?= Url::to(['conversation/list' ,'accountId' => $account->user_id]) ?>">
						Conversation View
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">
						Media View
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= Url::to(['stats/index' ,'accountId' => $account->user_id]) ?>">
						Statistics
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= Url::to(['stats/activity' ,'accountId' => $account->user_id]) ?>">
						Agent Activity
					</a>
				</li>
			</ul>
		</div><!--.tabs-section-nav-->

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active">

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


			</div><!--.tab-pane-->
		</div><!--.tab-content-->
	</section><!--.tabs-section-->

</div><!--.container-fluid-->
