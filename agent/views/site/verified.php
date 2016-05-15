<?php
/* @var $this yii\web\View */
$this->title = Yii::t('register', 'Email Verified');
$this->params['breadcrumbs'][] = Yii::t('register', 'Email Verified');

?>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <h4><?= Yii::t('register', 'Email Verified'); ?></h4>
        </div>
    </div>

    <div class="panel-body">

        <h3><?= Yii::t('register', 'Thanks for verifying your email') ?></h3>
        
        <p>
            <?= Yii::t('register', 'You may now fully access <b>StudentHub</b>') ?>
        </p>
        
        <a href="<?= yii\helpers\Url::to(["job/create"]) ?>" class="btn btn-primary"><?= Yii::t('register', 'Post your first Job opening') ?></a>
    </div>
</div>