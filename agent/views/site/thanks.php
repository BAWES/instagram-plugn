<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('register', 'Thank You!');
$this->params['breadcrumbs'][] = Yii::t('register', 'Thank You!');

?>

<div>
    <div class="" style="text-align:center;">
        <h1 style="font-size:60px"><?= Yii::t('register', 'Thank You!') ?></h1>
        <i class="fa fa-envelope" style="font-size:200px; color:grey; margin-top:-20px; padding-bottom: 20px"></i>
        <p class="sub"><?= Yii::t('agent', 'Thanks for signing up, please click on the link sent to you by email to verify your account') ?></p>
    </div>
</div>
