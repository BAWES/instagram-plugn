<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $title string */

$this->title = "You have verified your email";

$this->registerJs("
");
?>

<h1><?= $this->title ?></h1>

<p>
You may now use Plugn to manage Instagram accounts via the <a href='https://agent.plugn.io/app'>agent web portal</a>
or one of our mobile apps.
</p>

<a href='https://agent.plugn.io/app' class='btn btn-lg'>Launch Plugn</a>


<div style='margin-top:4em'>
<a href='https://itunes.apple.com/gr/app/plugn-for-instagram/id1196833693?mt=8'>
    <?= Html::img('@web/img/app-btn-apple-retina.png', ['style' => 'width:100px']); ?>
</a>
<a href='https://play.google.com/store/apps/details?id=net.bawes.plugn'>
    <?= Html::img('@web/img/app-btn-android-retina.png', ['style' => 'width:100px']); ?>
</a>
</div>
