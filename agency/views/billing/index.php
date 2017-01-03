<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billing';
?>

<header class="page-content-header">
	<div class="container-fluid">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3><i class="font-icon font-icon-build"></i> Billing</h3>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="container-fluid">
	<section class="box-typical box-typical-padding">

		<!-- Pricing Tables -->
		<div class="box-typical-center">
			<div class="box-typical-center-in prices-page">
				<header class="prices-page-title">Affordable pricing. No long term contract.</header>
				<p class="prices-page-subtitle">Try free for 14 days with no obligation.</p>

				<article class="price-card">
					<header class="price-card-header">Enterprise</header>
					<div class="price-card-body">
						<div class="price-card-amount">$499</div>
						<div class="price-card-amount-lbl">per month</div>
						<ul class="price-card-list">
							<li style='text-align:center'>
								up to <span style='font-size:1.1em; font-weight:bold'>5</span> Instagram accounts
							</li>
						</ul>
						<div class="clear"></div>
						<a href="<?= Url::to(['billing/setup']) ?>" class="btn btn-rounded">Buy now</a>
					</div>
				</article>

				<div class="prices-page-bottom">
					<p>Larger plans available upon request.</p>
					<p><a href="https://plugn.io/contact/">Contact us</a> for more information.</p>
				</div>
			</div>
		</div>
		<!-- END Pricing Tables -->



	</section><!--.box-typical-->
</div><!--.container-fluid-->
