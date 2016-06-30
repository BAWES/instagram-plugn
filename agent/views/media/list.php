<?php

/* @var $this yii\web\View */
/* @var $account \common\models\InstagramUser */
/* @var $media common\models\Media */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $account->user_name;

//Pass Instagram Account to Layout for Rendering
$this->params['instagramAccount'] = $account;
?>


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

    <h3>Media with Unhandled Messages</h3>
    <?= $unhandledMedia ?>
    <br style='clear:both'/>

<?php } ?>

    <h3>Your Media</h3>
    <?= $handledMedia ?>

<br style='clear:both'/>
