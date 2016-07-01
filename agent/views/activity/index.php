<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $activities common\models\Activity */

use yii\helpers\Html;
use yii\helpers\Url;
use agent\assets\DataTableAsset;

$this->title = $account->user_name;

//DataTables Register
DataTableAsset::register($this);
$this->registerJs("
$(function() {
	$('#mytable').DataTable({
		responsive: true
	});
});
");

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>


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
