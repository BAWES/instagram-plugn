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


		<form id="myCCForm"
			action="https://www.mysite.com/examplescript.php" method="post">
		  <input name="token" type="hidden" value="" />
		  <div>
		    <label>
		      <span>Card Number</span>
		      <input class="form-control" id="ccNo" type="text" value="" autocomplete="off" required />
		    </label>
		  </div>
		  <div>
		    <label>
		      <span>Expiration Date (MM/YYYY)</span>
		  	</label>
		    <input class="form-control" id="expMonth" type="text" size="2" placeholder="MM" required />
		    <span> / </span>
		    <input class="form-control" id="expYear" type="text" size="4" placeholder="YYYY" required />
		  </div>
		  <div>
		    <label>
		      <span>CVC</span>
		      <input class="form-control" id="cvv" type="text" value="" autocomplete="off" required />
		    </label>
		  </div>

		  <input class="btn btn-primary" type="submit" value="Submit Payment" style="margin-top:10px;"/>
		</form>


	</div><!--.box-typical-->
</div><!--.container-fluid-->
