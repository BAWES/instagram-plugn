<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Email Preferences";
?>

<div class="container-fluid">

	<header class="section-header">
		<h3>Email Preferences</h3>
	</header>

	<section class="card">
		<header class="card-header">
			Notify me of unhandled comments on accounts I manage
		</header>
		<div class="card-block" style="padding-top:0">
			<div class="row m-t-lg">



				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-2" checked/>
						<label for="check-det-2">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">Once a day</span>
								Every 24 hours
							</span>
						</span>
						</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-3"/>
						<label for="check-det-3">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">Twice a day</span>
								Every 12 hours
							</span>
						</span>
						</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-4"/>
						<label for="check-det-4">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">Four times a day</span>
								Every 6 hours
							</span>
						</span>
						</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-5"/>
						<label for="check-det-5">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">Eight times a day</span>
								Every 3 hours
							</span>
						</span>
						</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-6"/>
						<label for="check-det-6">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">ALL DAY</span>
								EVERY SINGLE HOUR
							</span>
						</span>
						</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-6">
					<div class="checkbox-detailed">
						<input type="radio" name="detailed" id="check-det-1"/>
						<label for="check-det-1">
						<span class="checkbox-detailed-tbl">
							<span class="checkbox-detailed-cell">
								<span class="checkbox-detailed-title">Off</span>
								Don't send me anything
							</span>
						</span>
						</label>
					</div>
				</div>

			</div><!--.row-->
		</div>
	</section>

	<input type='submit' class='btn btn-block btn-primary' value='Save'/>



</div><!--.container-fluid-->
