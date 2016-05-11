<?php

/* @var $this yii\web\View */
/* @var $media common\models\Media */

use yii\helpers\Html;

$this->title = 'Media';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-media">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class='row'>
        <?= Html::img($media->media_image_standard)?>
    </div>
</div>
