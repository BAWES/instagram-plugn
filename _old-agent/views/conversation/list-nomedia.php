<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $media common\models\Media */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<div class="container-fluid">
	<div class="box-typical box-typical-full-height">
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<div class="add-customers-screen-user">
					<i class="font-icon font-icon-comments-2"></i>
				</div>

				<h2>Conversation View</h2>
				<p class="lead color-blue-grey-lighter">You are able to manage all conversations with users interacting with your account here</p>

			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->
