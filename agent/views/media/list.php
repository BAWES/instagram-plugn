<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */

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
  <li role="presentation" class="active"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Media View</a></li>
  <li role="presentation"><a href="<?= Url::to(['media/list' ,'accountId' => $account->user_id]) ?>">Stats</a></li>
</ul>

<br/><br/>
This is where account will be managed in media view.<br/>
Comment management tickets go here, consider using any of your existing implementations: GridView or the one in Frontend panel

<ul>
    <li>List all media with num comments etc.</li>
    <li>Highlight media that has comments we've ignored, show it on top with easy access</li>
    <li>Once users are done responding to comments on a post, they mark it as "Handled"</li>
    <li>A handled post marks all comments under it as handled by that agent</li>
</ul>
