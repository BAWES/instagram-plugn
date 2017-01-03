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
				<header class="prices-page-title">Affordable pricing. No long term contract. No per-user fees.</header>
				<p class="prices-page-subtitle">Try free for 30 days whith no obligation.</p>
				<article class="price-card">
					<header class="price-card-header">Enterprise</header>
					<div class="price-card-body">
						<div class="price-card-amount">$499</div>
						<div class="price-card-amount-lbl">per month</div>
						<ul class="price-card-list">
							<li><i class="font-icon font-icon-ok"></i>Up to 5000 bins</li>
							<li><i class="font-icon font-icon-ok"></i>UNLIMITED users</li>
						</ul>
						<div class="clear"></div>
						<a href="<?= Url::to(['billing/setup']) ?>" class="btn btn-rounded">Start your trial</a>
					</div>
				</article>
				<article class="price-card">
					<header class="price-card-header">Medium</header>
					<div class="price-card-body">
						<div class="price-card-amount">$299</div>
						<div class="price-card-amount-lbl">per month</div>
						<div class="price-card-label">Our most popular plan</div>
						<ul class="price-card-list">
							<li><i class="font-icon font-icon-ok"></i>Up to 5000 bins</li>
							<li><i class="font-icon font-icon-ok"></i>UNLIMITED users</li>
						</ul>
						<div class="clear"></div>
						<a href="<?= Url::to(['billing/setup']) ?>" class="btn btn-rounded">Start your trial</a>
					</div>
				</article>
				<article class="price-card">
					<header class="price-card-header">Small</header>
					<div class="price-card-body">
						<div class="price-card-amount">$99</div>
						<div class="price-card-amount-lbl">per month</div>
						<ul class="price-card-list">
							<li><i class="font-icon font-icon-ok"></i>Up to 5000 bins</li>
							<li><i class="font-icon font-icon-ok"></i>UNLIMITED users</li>
						</ul>
						<div class="clear"></div>
						<a href="<?= Url::to(['billing/setup']) ?>" class="btn btn-rounded">Start your trial</a>
					</div>
				</article>
				<article class="price-card">
					<header class="price-card-header">Basic</header>
					<div class="price-card-body">
						<div class="price-card-amount">$49</div>
						<div class="price-card-amount-lbl">per month</div>
						<ul class="price-card-list">
							<li><i class="font-icon font-icon-ok"></i>Up to 5000 bins</li>
							<li><i class="font-icon font-icon-ok"></i>UNLIMITED users</li>
						</ul>
						<div class="clear"></div>
						<a href="<?= Url::to(['billing/setup']) ?>" class="btn btn-rounded">Start your trial</a>
					</div>
				</article>
				<div class="prices-page-bottom">
					<p>Larger plans are available on request.</p>
					<p>Save 10% by signing up for a year in advance. <a href="#">Contact us</a> for more information.</p>
				</div>
			</div>
		</div>
		<!-- END Pricing Tables -->



	</section><!--.box-typical-->
</div><!--.container-fluid-->
