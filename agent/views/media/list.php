<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $media common\models\Media */

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

<?php
$unhandledMedia = $handledMedia = "";

foreach($media as $mediaItem){
    $unhandledCommentsCount = count($mediaItem->unhandledComments);

    $mediaContentItem = "
    <div class='col-md-3 col-sm-4 col-xs-6' style='margin-bottom:10px;'>";

    if($unhandledCommentsCount > 0){
        $mediaContentItem .= "
            <div style='background:pink; text-align: center; font-weight:bold;'>
                $unhandledCommentsCount unhandled
            </div>";
    }

    $mediaContentItem .=
        Html::a(Html::img($mediaItem->media_image_thumb, ['style'=>'width:100%']),
                ['media/view', 'accountId' => $mediaItem->user_id, 'mediaId' => $mediaItem->media_id]
                ).
        "<div class=row>
            <div class=col-sm-6>
                ".$mediaItem->media_num_comments." Comments
            </div>
            <div class=col-sm-6>
                ".$mediaItem->media_num_likes." Likes
            </div>
        </div>
    </div>
    ";

    if($unhandledCommentsCount > 0){


        $unhandledMedia .= $mediaContentItem;
    }else $handledMedia .= $mediaContentItem;
}

?>
<?php if($unhandledMedia){ ?>
<div class='row'>
    <h3>Media with Unhandled Messages</h3>
    <?= $unhandledMedia ?>
</div>
<?php } ?>
<div class='row'>
    <h3>Your Media</h3>
    <?= $handledMedia ?>
</div>
