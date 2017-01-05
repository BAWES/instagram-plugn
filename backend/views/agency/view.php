<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agency */

$this->title = $model->agency_id;
$this->params['breadcrumbs'][] = ['label' => 'Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->agency_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->agency_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agency_id',
            'agency_fullname',
            'agency_company',
            'agency_email:email',
            'agency_email_verified:email',
            'agency_auth_key',
            'agency_password_hash',
            'agency_password_reset_token',
            'agency_limit_email:email',
            'agency_status',
            'agency_trial_days',
            'agency_created_at',
            'agency_updated_at',
        ],
    ]) ?>

</div>
