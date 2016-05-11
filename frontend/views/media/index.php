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
                <table width=100% border>
                    <tr><td>Comments</td><td><?= $mediaItem->media_num_comments ?></td></tr>
                    <tr><td>Likes</td><td><?= $mediaItem->media_num_likes ?></td></tr>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
