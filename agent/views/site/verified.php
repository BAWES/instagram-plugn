<?php
/* @var $this yii\web\View */
$this->title = Yii::t('register', 'Email Verified');
$this->params['breadcrumbs'][] = Yii::t('register', 'Email Verified');

?>
<div class="panel">

    <div class="panel-body">

        <h2><?= Yii::t('register', 'Thanks for verifying your email') ?></h2>

        <p>
            <?= Yii::t('register', 'You may now fully access <b>Plugn</b>') ?>
        </p>

        <a href="<?= yii\helpers\Url::to(["dashboard/index"]) ?>" class="btn btn-primary"><?= Yii::t('register', 'Start managing Instagram accounts') ?></a>
    </div>
</div>
