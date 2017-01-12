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

		<?php if($isBillingActive){ ?>
			<h4>Current Plan: Up to <?= $invoices[0]->pricing->pricing_account_quantity ?> Instagram Accounts</h4>
			<p>
				You're currently paying <b><?= Yii::$app->formatter->asCurrency($invoices[0]->invoice_usd_amount) ?> per month</b> on the <?= $invoices[0]->item_name_1 ?>.
			</p>

			<a href='<?= Url::to(['billing/cancel-plan']) ?>'
				data-method='post'
				data-confirm="Cancel your current billing plan? The remaining days will be added to your account under trial mode."
				class="btn btn-inline btn-danger-outline">Cancel Plan</a>

			<p style='margin-top:10px; margin-bottom:0;'>
				<b>Looking to upgrade?</b>
				You'll need to cancel your current plan to upgrade for more Instagram accounts.
				<br/>
				<a href='https://plugn.io/pricing' target='_blank'>
					Browse Available Plans
				</a>
			</p>
		<?php } ?>


		<?php if(!$isBillingActive){ ?>
		<!-- Pricing Tables -->
		<div class="box-typical-center">
			<div class="box-typical-center-in prices-page">
				<header class="prices-page-title">Affordable pricing. No long term contract.</header>

				<?php if($isTrial){ ?>
					<p class="prices-page-subtitle"><u>Up to 30% Discount</u> on the <i>first month</i> when setting up billing during the trial period.</p>
				<?php } ?>

				<?php foreach($availablePriceOptions as $pricing){ ?>
					<article class="price-card">
						<header class="price-card-header"><?= $pricing->pricing_title ?></header>
						<div class="price-card-body">
							<div class="price-card-amount">
								$<?= $isTrial? round($pricing->pricing_price * 0.7) : (int) $pricing->pricing_price ?>

								<?php if($isTrial){ ?>
									<br/>
									<span style='font-size:0.45em;'>
										for the first month, then
									</span>
									<br/>
									<span style='font-size:0.8em'>
										$<?= (int) $pricing->pricing_price ?>
									</span>

								<?php } ?>

							</div>
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
		<?php } ?>



	</section><!--.box-typical-->

	<?php if($invoices){ ?>
	<section class="box-typical box-typical-max-280">
		<header class="box-typical-header">
			<div class="tbl-row">
				<div class="tbl-cell tbl-cell-title">
					<h3>Invoices</h3>
				</div>
			</div>
		</header>
		<div class="box-typical-body">
			<div class="table-responsive">
				<table class="table table-hover">
					<tbody>
						<?php foreach($invoices as $invoice){ ?>
						<tr>
							<td>
								<?= Yii::$app->formatter->asDate($invoice->invoice_created_at) ?>
							</td>
							<td>
								<a target='_blank'
									href="<?= Url::to(['billing/invoice', 'id' => $invoice->invoice_id]) ?>">
									#<?= $invoice->invoice_id ?>
								</a>
							</td>
							<td><?=$invoice->item_name_1?>. <?= Yii::$app->formatter->asDate($invoice->invoice_created_at) ?> to <?= Yii::$app->formatter->asDate($invoice->item_rec_date_next_1) ?></td>
							<td style='font-weight:bold;'>
								<?= Yii::$app->formatter->asCurrency($invoice->invoice_usd_amount) ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div><!--.box-typical-body-->
	</section><!--.box-typical-->
	<?php } ?>

</div><!--.container-fluid-->
