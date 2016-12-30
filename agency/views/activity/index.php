<?php

use yii\helpers\Html;
use agency\assets\DataTableAsset;

/* @var $this yii\web\View */

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

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>


<h3>
	<i class="font-icon font-icon-zigzag"></i>
	Account Activity <small class="text-muted"> by your assigned agents</small>
</h3>


<div class="container-fluid">

	<table id="mytable" class="display table table-bordered" cellspacing="0" width="100%">
		<thead>
		<tr>
			<th>When?</th>
			<th>Who?</th>
			<th>What?</th>
		</tr>
		</thead>
	    <?php
		/*<tfoot>
		<tr>
			<th>When?</th>
			<th>Who?</th>
			<th>What?</th>
		</tr>
		</tfoot>*/
	    ?>
		<tbody>
	        <?php foreach($activities as $activity){ ?>
	            <tr>
	                <td><?= Yii::$app->formatter->asRelativeTime($activity->activity_datetime) ?></td>
	                <td><?= $activity->agent->agent_name ?></td>
	                <td><?= $activity->activity_detail ?></td>
	            </tr>
	        <?php } ?>
		</tbody>
	</table>

</div><!--.container-fluid-->
