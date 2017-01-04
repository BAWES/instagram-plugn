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

				<?php foreach($availablePriceOptions as $pricing){ ?>
					<article class="price-card">
						<header class="price-card-header"><?= $pricing->pricing_title ?></header>
						<div class="price-card-body">
							<div class="price-card-amount">$<?= (int) $pricing->pricing_price ?></div>
							<div class="price-card-amount-lbl">per month</div>
							<ul class="price-card-list">
								<li style='text-align:center'>
									<?= $pricing->pricing_features ?>
								</li>
							</ul>
							<div class="clear"></div>
							<a href="<?= Url::to(['billing/setup', 'plan' => $pricing->pricing_id]) ?>" class="btn btn-rounded">Buy now</a>
						</div>
					</article>
				<?php } ?>

				<div class="prices-page-bottom">
					<p>Larger plans available upon request.</p>
					<p><a href="https://plugn.io/contact/" target='_blank'>Contact us</a> for more information.</p>
				</div>
			</div>
		</div>
		<!-- END Pricing Tables -->



	</section><!--.box-typical-->
</div><!--.container-fluid-->
