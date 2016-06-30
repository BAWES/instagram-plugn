<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $activities common\models\Activity */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<h3>Agent Activity</h3>
<p>What have the agents done with this account?</p>

<table border=1 width=100% style='text-align:center'>
    <tr>
        <th style='text-align:center'>When?</th>
        <th style='text-align:center'>Who?</th>
        <th style='text-align:center'>What?</th>
    </tr>

<?php foreach($activities as $activity){ ?>
    <tr>
        <td><?= Yii::$app->formatter->asRelativeTime($activity->activity_datetime) ?></td>
        <td><?= $activity->agent->agent_name ?></td>
        <td><?= $activity->activity_detail ?></td>
    </tr>
<?php } ?>
</table>
