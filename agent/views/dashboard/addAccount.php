<?php

/* @var $this yii\web\View */

$this->title = 'Add Account';

$instagramHomeUrl = Yii::$app->urlManagerFrontend->createUrl('site/index');
?>

<div class="container-fluid">
	<div class="box-typical box-typical-full-height">
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<div class="add-customers-screen-user">
					<i class="font-icon font-icon-user"></i>
				</div>

				<h2>Add Instagram Account</h2>

				<p class="lead color-blue-grey-lighter">
					<?= Yii::$app->user->identity->agent_name ?>, once an account owner adds you as an agent,<br/> you'll be able to manage the accounts comments
				</p>

				<p class="lead color-blue-grey-lighter">
					Your agent id: <?= Yii::$app->user->identity->agent_email ?>
				</p>

				<a href="<?= $instagramHomeUrl ?>" class="btn btn-inline btn-primary ladda-button" data-style="expand-left">
					<span class="ladda-label">Login with Instagram</span>
				</a>

				<p class="m-t-md color-blue-grey-lighter">Note: You'll be automatically logged in if you're already logged in on <a href='http://instagram.com' target='_blank'>Instagram.com</a></p>
			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->
