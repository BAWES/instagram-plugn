<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $title string */

$this->title = $title;

$this->registerJs("
");
?>

<h1><?= $title ?></h1>

<p>
You may now use Plugn to manage your accounts via the <a href='https://agent.plugn.io/app'>agent web portal</a>
or one of our mobile apps.
</p>

<a href='https://itunes.apple.com/gr/app/plugn-for-instagram/id1196833693?mt=8'>
    <?= Html::img('@web/img/app-btn-apple-retina.png', ['style' => 'width:120px']); ?>
</a>
<a href='https://play.google.com/store/apps/details?id=net.bawes.plugn'>
    <?= Html::img('@web/img/app-btn-android-retina.png', ['style' => 'width:120px']); ?>
</a>
