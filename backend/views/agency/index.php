<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'agency_id',
            'agency_fullname',
            'agency_company',
            'agency_email:email',
            'agency_email_verified:boolean',
            // 'agency_auth_key',
            // 'agency_password_hash',
            // 'agency_password_reset_token',
            // 'agency_limit_email:email',
            'agency_status',
            'status',
            // 'agency_trial_days',
            'agency_created_at',
            // 'agency_updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
