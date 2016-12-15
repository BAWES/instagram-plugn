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
	if (data.errorCode === 200) {
	  // This error code indicates that the ajax call failed. We recommend that you retry the token request.
	} else {
	  alert(data.errorMsg);
	}
};

var tokenRequest = function() {
	// Setup token request arguments
    var args = {
        sellerId: '103110406',
        publishableKey: '88992314-9CE9-4E91-96B8-66FB6845F51A',
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
    TCO.loadPubKey('sandbox');

    $('#myCCForm').submit(function(e) {
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
