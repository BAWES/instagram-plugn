<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingNotification */

$this->title = 'Create Billing Notification';
$this->params['breadcrumbs'][] = ['label' => 'Billing Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
