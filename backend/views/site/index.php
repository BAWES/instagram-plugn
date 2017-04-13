<?php

/* @var $this yii\web\View */
/* @var $revenue integer */
/* @var $activeAccounts integer */

$this->title = 'Admin Panel';
?>
<div class="site-index">

    <div class="col-md-6" style='text-align:center'>
        <h2>Active Instagram Accounts</h2>
        <h3><?= $activeAccounts ?></h3>
    </div>

    <div class="col-md-6" style='text-align:center'>
        <h2>Revenue</h2>
        <h3><?= Yii::$app->formatter->asCurrency($revenue) ?></h3>
    </div>

</div>
