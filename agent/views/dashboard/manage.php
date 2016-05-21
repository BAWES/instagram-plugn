<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */

use yii\helpers\Html;

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


<br/><br/>
Comment management tickets go here, consider using any of your existing implementations: GridView or the one in Frontend panel

<ul>
    <li>List all media with num comments etc.</li>
    <li>Highlight media that has comments we've ignored, show it on top with easy access</li>
    <li>Once users are done responding to comments on a post, they mark it as "Handled"</li>
    <li>A handled post marks all comments under it as handled by that agent</li>
</ul>
