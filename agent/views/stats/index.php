<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $records common\models\Record */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>

<h3>Statistics</h3>

<table border=1 width=100% style='text-align:center'>
    <tr>
        <th style='text-align:center'>Date</th>
        <th style='text-align:center'>Media</th>
        <th style='text-align:center'>Following</th>
        <th style='text-align:center'>Followers</th>
    </tr>

<?php foreach($records as $record){ ?>
    <tr>
        <td><?= Yii::$app->formatter->asDate($record->record_date) ?></td>
        <td><?= $record->record_media_count ?></td>
        <td><?= $record->record_following_count ?></td>
        <td><?= $record->record_follower_count ?></td>
    </tr>
<?php } ?>
</table>
