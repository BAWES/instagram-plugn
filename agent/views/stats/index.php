<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $records common\models\Record */

use yii\helpers\Html;
use yii\helpers\Url;
use agent\assets\DataTableAsset;
use agent\assets\ChartC3Asset;

$this->title = $account->user_name;

// Register Assets
DataTableAsset::register($this);
ChartC3Asset::register($this);

$tableRecords = "";
$xValues = $mediaValues = $followingValues = $followerValues = "";

foreach($records as $record){
	$tableRecords .= "
	<tr>
		<td>".Yii::$app->formatter->asDate($record->record_date)."</td>
		<td>".$record->record_media_count."</td>
		<td>".$record->record_following_count."</td>
		<td>".$record->record_follower_count."</td>
	</tr>";

	//Plot values for generating the chart
	$xValues .= ", '".$record->record_date."'";
	$mediaValues .= ", '".$record->record_media_count."'";
	$followingValues .= ", '".$record->record_following_count."'";
	$followerValues .= ", '".$record->record_follower_count."'";
}

$this->registerJs("
$(function() {
	$('#mytable').DataTable({
		responsive: true
	});
});

var lineChart = c3.generate({
    bindto: '#line-chart',
    data: {
		x : 'x',
        columns: [ //2013-01-01
			['x' $xValues],
			['Media' $mediaValues],
			['Following' $followingValues],
            ['Followers' $followerValues]
        ]
    },
    axis: {
        x: {
			type : 'timeseries',
			tick: {
                format: '%Y-%m-%d'
            }
        }
    }
});
");

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<div id="line-chart" style='margin-top:15px;'></div>

<table id="mytable" class="display table table-bordered" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th>Date</th>
		<th>Media</th>
		<th>Following</th>
		<th>Followers</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th>Date</th>
		<th>Media</th>
		<th>Following</th>
		<th>Followers</th>
	</tr>
	</tfoot>
	<tbody>
		<?= $tableRecords ?>
	</tbody>
</table>
