<?php

/* @var $this yii\web\View */
/* @var $agentModel common\models\Agent */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Agent;

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

				<?php $form = ActiveForm::begin([
	                'id' => 'notification-preference-form',
	                'errorCssClass' => 'form-group-error',
	            ]); ?>

					<div class="col-sm-6">
						<div class="checkbox-detailed">
							<input type="radio" name="notif-preference" id="notif-once-a-day" value="<?= Agent::PREF_EMAIL_DAILY ?>"
							<?= $agentModel->agent_email_preference==Agent::PREF_EMAIL_DAILY?"checked":"" ?>/>
							<label for="notif-once-a-day">
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
							<input type="radio" name="notif-preference" id="notif-off" value="<?= Agent::PREF_EMAIL_OFF ?>"
							<?= $agentModel->agent_email_preference==Agent::PREF_EMAIL_OFF?"checked":"" ?>/>
							<label for="notif-off">
							<span class="checkbox-detailed-tbl">
								<span class="checkbox-detailed-cell">
									<span class="checkbox-detailed-title">Off</span>
									Don't send me comment notifications
								</span>
							</span>
							</label>
						</div>
					</div>

					<div class='col-xs-12'>
						<?= Html::submitButton('Save', [
							'class' => 'btn btn-block btn-primary',
							'name' => 'save-button']) ?>
					</div>

				<?php ActiveForm::end(); ?>

			</div><!--.row-->
		</div>
	</section>





</div><!--.container-fluid-->
