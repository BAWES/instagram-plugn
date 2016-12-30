<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

$account = $this->params['instagramAccount'];

$controllerName = $this->context->id;
?>

<?php $this->beginContent('@agency/views/layouts/main.php'); ?>

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
					<a class="nav-link <?= $controllerName=="media"?"active":"" ?>" href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">
						Media View
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $controllerName=="conversation"?"active":"" ?>" href="<?= Url::to(['conversation/list' ,'accountId' => $account->user_id]) ?>">
						Conversation View
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $controllerName=="stats"?"active":"" ?>" href="<?= Url::to(['stats/index' ,'accountId' => $account->user_id]) ?>">
						Statistics
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $controllerName=="activity"?"active":"" ?>" href="<?= Url::to(['activity/index' ,'accountId' => $account->user_id]) ?>">
						Agent Activity
					</a>
				</li>
			</ul>
		</div><!--.tabs-section-nav-->

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active">

        		<?= $content ?>

			</div><!--.tab-pane-->
		</div><!--.tab-content-->
	</section><!--.tabs-section-->

</div><!--.container-fluid-->

<?php $this->endContent(); ?>
