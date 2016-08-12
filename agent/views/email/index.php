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



				<div class="col-sm-6">
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

				<div class="col-sm-6">
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

				<div class='col-xs-12'>
					<input type='submit' class='btn btn-block btn-primary' value='Save'/>
				</div>

			</div><!--.row-->
		</div>
	</section>





</div><!--.container-fluid-->
