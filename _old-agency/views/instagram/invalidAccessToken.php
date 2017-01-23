<?php

/* @var $this yii\web\View */

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;

$instagramHomeUrl = \yii\helpers\Url::to(["instagram/authorize"]);
?>

<div class="container-fluid">
	<div class="box-typical">
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<h2>Fix Instagram Account Access</h2>

				<p class="lead color-blue-grey-lighter">
					There's a slight problem connecting to your Instagram account.<br/>
					Don't worry though, it's very quick to fix.
				</p>

				<a id='addAccount' href="<?= $instagramHomeUrl ?>" class="btn btn-inline btn-primary ladda-button" data-style="expand-left">
					<span class="ladda-label">Re-connect my Instagram Account</span>
				</a>

			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->
