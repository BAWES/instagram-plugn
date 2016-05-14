<?php

/* @var $this yii\web\View */
/* @var $userMedia common\models\Media */

use yii\helpers\Html;

$this->title = 'Media';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-media">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class='row'>
        <?php foreach($userMedia as $mediaItem){ ?>
            <div class='col-md-3 col-sm-4 col-xs-6' style='margin-bottom:10px;'>
                <?= Html::a(Html::img($mediaItem->media_image_thumb, ['style'=>'width:100%']),
                        ["media/view", 'id' => $mediaItem->media_id]
                        ) ?>
                <div class=row>
                    <div class=col-sm-6>
                        <?= $mediaItem->media_num_comments ?> Comments
                    </div>
                    <div class=col-sm-6>
                        <?= $mediaItem->media_num_likes ?> Likes
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
