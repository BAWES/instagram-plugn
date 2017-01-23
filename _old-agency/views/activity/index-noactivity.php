<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<div class="container-fluid">
	<div class="box-typical">
		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<div class="add-customers-screen-user">
					<i class="font-icon font-icon-zigzag"></i>
				</div>

				<h2>Account Activity</h2>
				<p class="lead color-blue-grey-lighter">All activities done by your assigned agents will be listed here for for future reference</p>

			</div>
		</div>
	</div><!--.box-typical-->
</div><!--.container-fluid-->
