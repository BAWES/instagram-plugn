<?php

/* @var $this yii\web\View */

$this->title = 'Add Account';

$instagramHomeUrl = Yii::$app->urlManagerFrontend->createUrl('site/login-via-agent');

$this->registerJs("
$('#addAccount').click(function(){
	$('body').append(\"<div style='display:none'><iframe id='iglog' src='https://instagram.com/accounts/logout/' width='0' height='0'></iframe></div>\");

	$('#iglog').on('load', function(){
		window.location='".$instagramHomeUrl."';
	});

	return false;
});
");
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
					You'll be able to manage an Instagram accounts comments<br/> once an account owner adds you as an agent
				</p>

				<p class="lead color-blue-grey-lighter">
					Your agent id: <?= Yii::$app->user->identity->agent_email ?>
				</p>

				<a id='addAccount' href="<?= $instagramHomeUrl ?>" class="btn btn-inline btn-primary ladda-button" data-style="expand-left">
					<span class="ladda-label">Add an Instagram Account</span>
				</a>

			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->
