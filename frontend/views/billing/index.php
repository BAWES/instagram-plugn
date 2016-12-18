<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->registerJsFile("https://www.2checkout.com/checkout/api/2co.min.js");
//, ['position' => \yii\web\View::POS_HEAD]
$this->registerJs("
// Called when token created successfully.
var successCallback = function(data) {
	var myForm = document.getElementById('myCCForm');

	// Set the token as the value for the token input
	myForm.token.value = data.response.token.token;

	// IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
	myForm.submit();
};

// Called when token creation fails.
var errorCallback = function(data) {
	// Re-enable submit button
	$('#submitBtn').removeAttr('disabled');

	if (data.errorCode === 200) {
	  // This error code indicates that the ajax call failed. We recommend that you retry the token request.
	} else {
	  //alert(data.errorMsg);
	  swal({
		  title: 'Unable to Process Card',
		  text: data.errorMsg,
		  type: 'error',
		  confirmButtonClass: 'btn-danger',
		  confirmButtonText: 'Ok'
	  });
	}
};

var tokenRequest = function() {
	// Setup token request arguments
    var args = {
        sellerId: '$sellerId',
        publishableKey: '$publishableKey',
        ccNo: $('#ccNo').val(),
        cvv: $('#cvv').val(),
        expMonth: $('#expMonth').val(),
        expYear: $('#expYear').val()
    };

	// Make the token request
    TCO.requestToken(successCallback, errorCallback, args);
};

$(function() {
	// Pull in the public encryption key for our environment
    TCO.loadPubKey('$environment');

    $('#myCCForm').submit(function(e) {
		// Disable Submit Button
		$('#submitBtn').attr('disabled', 'disabled');
		// Call our token request function
        tokenRequest();

		// Prevent form from submitting
        return false;
    });
});
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

		<form id="myCCForm" class='row' method="post" action="<?= $processFormUrl ?>">

		  <!-- Card Holder Info -->
		  <div class='col-md-6'>
			  <h4>Card Holder</h4>

			  <div>
				  <label>
					<span>Name</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>Email</span>
					<input class="form-control" type="email" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>Address Line 1</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>Address Line 2</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>Country</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>City</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
				  <label>
					<span>Zip code</span>
					<input class="form-control" type="text" value="" autocomplete="off" />
				  </label>
			  </div>
		  </div>

			<!-- Card Info -->
			<div class='col-md-6'>
				<h4>Creditcard</h4>

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

				<img src='https://www.2checkout.com/upload/images/paymentlogoshorizontal.png' alt='payment options'/>
		  	</div>

			<div class='col-sm-12' style='text-align:center; margin-top:20px;'>
				<input id='submitBtn' class="btn btn-primary" type="submit" value="Submit Payment"/>
			</div>




		</form>



	</div><!--.box-typical-->
</div><!--.container-fluid-->
