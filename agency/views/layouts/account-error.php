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
	<a href='<?= Url::to(['instagram/remove', 'id' => $account->user_id]) ?>'
		class="change-cover"
		data-method="post"
		data-confirm="Remove @<?= $account->user_name ?> from your agency?">
		<?php /*<i class="font-icon font-icon-del"></i>*/ ?>
		Remove Account
	</a>
</div><!--.profile-header-photo-->


<div class="container-fluid">

	<?= $content ?>

</div><!--.container-fluid-->

<?php $this->endContent(); ?>
