<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->registerJsFile("https://www.2checkout.com/checkout/api/2co.min.js");
//, ['position' => \yii\web\View::POS_HEAD]
$this->registerJs("
TCO.loadPubKey('sandbox');
");

$this->title = 'Billing';
?>

<header class="page-content-header">
	<div class="container-fluid">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3><i class="font-icon font-icon-build"></i> Billing </h3>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="container-fluid">
	<div class="box-typical box-typical-padding">



		<div class="add-customers-screen tbl">
			<div class="add-customers-screen-in">

				<div class="add-customers-screen-user">
					<i class="font-icon font-icon-build"></i>
				</div>

				<h2>Billing</h2>
				<p class="lead color-blue-grey-lighter">
					Plugn is free to use during our beta period
                </p>
			</div>
		</div>



	</div><!--.box-typical-->
</div><!--.container-fluid-->
