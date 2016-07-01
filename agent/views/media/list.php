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
    $mediaUrl = Url::to(['media/view', 'accountId' => $mediaItem->user_id, 'mediaId' => $mediaItem->media_id]);

    $mediaContentItem = "<div class='col-md-3 col-sm-4 col-xs-6'>"
                        . "<a href='$mediaUrl'>"
                        . "<div class='ribbon-block round relative'>"
                        . Html::img($mediaItem->media_image_thumb, ['style'=>'width:100%']);


    //Add ribbon showing number of unhandled comments
    if($unhandledCommentsCount > 0){
        $mediaContentItem .= "
            <div class='ribbon red right-top'>
                <i class='fa fa-comment'></i> $unhandledCommentsCount
            </div>";
    }


    $mediaContentItem .= "<div class='ribbon-km right-bottom'>
                            <i class='fa fa-heart-o'></i> ".$mediaItem->media_num_likes."
                        </div>
                        <div class='ribbon-km left-bottom'>
                            <i class='fa fa-comments-o'></i> ".$mediaItem->media_num_comments."
                        </div>
                    </div>
                </a>
            </div>";

    //Determine where it will be listed (handled or unhandled)
    if($unhandledCommentsCount > 0){
        $unhandledMedia .= $mediaContentItem;
    }else $handledMedia .= $mediaContentItem;
}
?>


<?php if($unhandledMedia){ ?>
<!-- Unhandled Media  -->
<header class="card-header card-header-lg" style="margin-bottom:10px">
	Unhandled Media
</header>
<div class='row'>

    <?= $unhandledMedia ?>

</div>
<!-- Unhandled Media  -->
<?php } ?>

<!-- Other Media  -->
<header class="card-header card-header-lg" style="margin-bottom:10px">
	Media
</header>
<div class='row'>

    <?= $handledMedia ?>

</div>
