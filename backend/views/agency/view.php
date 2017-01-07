<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agency */

$this->title = $model->agency_fullname;
if($model->agency_company){
    $this->title = $model->agency_company." by '".$model->agency_fullname."'";
}
$this->params['breadcrumbs'][] = ['label' => 'Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agency_id',
            'agency_email:email',
            'agency_email_verified:boolean',
            //'agency_auth_key',
            //'agency_password_hash',
            //'agency_password_reset_token',

            [
                'label' => 'Status',
                'value' => $model->status,
            ],
            'agency_trial_days',
            'agency_limit_email:datetime',
            'agency_created_at:datetime',
            'agency_updated_at:datetime',
        ],
    ]) ?>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->agency_id], ['class' => 'btn btn-primary']) ?>
    </p>


</div>
