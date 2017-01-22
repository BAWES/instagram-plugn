<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $activities common\models\Activity */

use yii\helpers\Html;
use yii\helpers\Url;
use agent\assets\DataTableAsset;

$this->title = "Your Activity";

//DataTables Register
DataTableAsset::register($this);
$this->registerJs("
$(function() {
	$('#mytable').DataTable({
		responsive: true,
		ordering: false
	});
});
");
?>

<div class='container-fluid'>

	<h2>Your Activity</h2>

	<table id="mytable" class="display table table-bordered" cellspacing="0" width="100%">
		<thead>
		<tr>
			<th>When?</th>
			<th>Where?</th>
			<th>What?</th>
		</tr>
		</thead>

		<tbody>
	        <?php foreach($activities as $activity){ ?>
	            <tr>
	                <td><?= Yii::$app->formatter->asRelativeTime($activity->activity_datetime) ?></td>
					<td>
						<a target='_blank' href='http://instagram.com/<?= $activity->user->user_name ?>'>
							@<?= $activity->user->user_name ?>
						</a>
					</td>
					<td><?= $activity->activity_detail ?></td>
	            </tr>
	        <?php } ?>
		</tbody>
	</table>

</div>
