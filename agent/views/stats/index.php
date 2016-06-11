<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $records common\models\Record */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;
?>


<h2>
    <?= $account->user_fullname ?> -
    <?= Html::a("@".$account->user_name, "http://instagram.com/".$account->user_name, ['target' => '_blank']) ?>
</h2>
<div class="row">
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Media</h4>
        <?= $account->user_media_count ?>
    </div>
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Following</h4>
        <?= $account->user_following_count ?>
    </div>
    <div class='col-xs-4'>
        <h4 style='margin-bottom:0; margin-top:0;'>Followers</h4>
        <?= $account->user_follower_count ?>
    </div>
</div>

<ul class="nav nav-tabs" style='margin-top:1.5em;'>
  <li role="presentation"><a href="<?= Url::to(['conversation/list' ,'accountId' => $account->user_id]) ?>">Conversation View</a></li>
  <li role="presentation"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Media View</a></li>
  <li role="presentation" class="active"><a href="<?= Url::to(['stats/index' ,'accountId' => $account->user_id]) ?>">Stats</a></li>
</ul>


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
