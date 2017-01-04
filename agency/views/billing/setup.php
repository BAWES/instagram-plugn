<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

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

// JS to show fields for required countries
$this->registerJs("
var zipStateRequiredCountries = $zipStateCountries;
var addrLineRequiredCountries = $addrCountries;

var addrInput = $('.field-billing-billing_address_line2');
var stateInput = $('.field-billing-billing_state');
var zipInput = $('.field-billing-billing_zip_code');

addrInput.hide();
stateInput.hide();
zipInput.hide();

$('#billing-country_id').change(function(){
	// Check if selected country is in adr line
	var addrLine2Required = false;
	addrLineRequiredCountries.forEach(function(country){
		if(country.country_id == $('#billing-country_id').val()){
			addrLine2Required = true;
		}
	});
	if(addrLine2Required){
		addrInput.show();
	}else addrInput.hide();

	// Check if selected country is in zip / state
	var zipStateRequired = false;
	zipStateRequiredCountries.forEach(function(country){
		if(country.country_id == $('#billing-country_id').val()){
			zipStateRequired = true;
		}
	});
	if(zipStateRequired){
		stateInput.show();
		zipInput.show();
	}else{
		stateInput.hide();
		zipInput.hide();
	}


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
		<?php
        // Field Templates
        $fieldTemplate = "{label}\n{beginWrapper}\n"
                        . "<div class='inputer'>\n<div class='input-wrapper'>\n"
                        . "{input}"
                        . "</div>\n</div>\n{hint}\n{error}\n"
                        . "{endWrapper}";

        $selectTemplate = "{label}\n{beginWrapper}\n"
                        . "<div class=''>\n<div class=''>\n"
                        . "{input}"
                        . "</div>\n</div>\n{hint}\n{error}\n"
                        . "{endWrapper}";


        /**
         * Start Form
         */
        $form = ActiveForm::begin([
            'id' => 'myCCForm',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,

            //'layout' => 'horizontal',
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'row'],
            'fieldConfig' => [
                'template' => $fieldTemplate,
                'horizontalCssClasses' => [
                    // 'label' => 'col-md-3',
                    // 'offset' => '',
                    // 'wrapper' => "col-md-5",
                    // 'error' => '',
                    // 'hint' => '',
                ],
            ],
        ]);
        ?>
		  <!-- Card Holder Info -->
		  <div class='col-md-5'>
			  <h4>Card Holder</h4>

			  <div>
				  <?= $form->field($model, 'billing_name')->textInput(['placeholder' => 'Your name', 'required' => 'required']) ?>
				  <?= $form->field($model, 'billing_email')->input('email', ['placeholder' => 'email@company.com', 'required' => 'required']) ?>

				  <?= $form->field($model, 'country_id',[
		                'template' => $selectTemplate,
		            ])->dropDownList(
		                ArrayHelper::map(
							common\models\Country::find()->all(),
							"country_id",
		                    "country_name"),
							[
								'prompt'=>'',
								'required' => 'required'
				                // 'class' => 'selectpicker',
				                // 'data-live-search' => 'true',
				                // 'data-width' => '100%'
		            		]);
					?>
					<?= $form->field($model, 'billing_city')->textInput() ?>
					<?= $form->field($model, 'billing_address_line1')->input('text', ['required' => 'required']) ?>
					<?= $form->field($model, 'billing_address_line2')->textInput() ?>
					<?= $form->field($model, 'billing_state')->textInput() ?>
					<?= $form->field($model, 'billing_zip_code')->textInput() ?>
			  </div>
		  </div>

			<!-- Card Info -->
			<div class='col-md-4'>
				<h4>Credit Card</h4>

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
					<input class="form-control"
						style='width:40%; float:left;'
						id="expMonth" type="text" size="2" placeholder="MM" required />
					<span style='width:10%; text-align:center;float:left;'> / </span>
					<input class="form-control"
						style='width:40%; float:left;'
						id="expYear" type="text" size="4" placeholder="YYYY" required />
					<div class="clear"></div>
				</div>
				<div>
					<label>
					  <span>CVC</span>
					  <input class="form-control" id="cvv" type="text" value="" autocomplete="off" required />
					</label>
				</div>

				<img style='max-width:100%'
				src='https://www.2checkout.com/upload/images/paymentlogoshorizontal.png' alt='payment options'/>


		  	</div>

			<div class='col-md-3'>
				<article class="price-card">
					<header class="price-card-header">Payment Summary</header>
					<div class="price-card-body">
						<div class="price-card-amount">$<?= (int) $pricing->pricing_price ?></div>
						<div class="price-card-amount-lbl">per month</div>
						<ul class="price-card-list">
							<li style='text-align:center'>
								<?= $pricing->pricing_features ?>
							</li>
						</ul>
						<div class="clear"></div>
						<input id='submitBtn' class="btn btn-primary" type="submit" value="Confirm Payment"/>
					</div>
				</article>

			</div>


		<?php ActiveForm::end(); ?>

	</div><!--.box-typical-->
</div><!--.container-fluid-->
