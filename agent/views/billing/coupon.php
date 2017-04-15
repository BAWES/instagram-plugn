<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Coupon';
?>


<div class="container-fluid">

	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>Redeem Coupon</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="<?= Url::to('index') ?>">Billing</a></li>
						<li class="active">Redeem Coupon</li>
					</ol>
				</div>
			</div>
		</div>
	</header>

	<section class="box-typical box-typical-padding">

		<form method='post' class='row'>
			<div class="col-lg-4 col-md-6">
				<fieldset class="form-group">
					<label class="form-label semibold" for="exampleInput">Coupon Code</label>
					<input type="text" class="form-control" id="exampleInput" placeholder="Enter your coupon code here">
				</fieldset>

				<input type='submit' class='btn btn-primary' value='Redeem'/>
			</div>

		</form>

	</section><!--.box-typical-->
</div><!--.container-fluid-->
