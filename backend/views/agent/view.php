<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->title = $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class='col-md-6'>
        <h2>Summary</h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'agent_id',
                'agent_name',
                'agent_email:email',
                'agent_email_verified:boolean',
                'linkedAccountLimit',
                [
                    'label' => 'Status',
                    'value' => $model->status,
                ],

                'agent_billing_active_until:date',
                'billingDaysLeft',
                'agent_trial_days',

                'agent_limit_email:datetime',
                'agent_created_at:datetime',
                'agent_updated_at:datetime',
            ],
        ]) ?>

    </div>

    <div class='col-md-6'>
        <h2>Owned Accounts</h2>
        <?= GridView::widget([
            'dataProvider' => $ownedAccountsDataProvider,
            'columns' => [
                'user_name',
                'user_follower_count',
                'status',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'instagram-user'],
            ],
        ]); ?>

        <h2>Assigned Accounts</h2>
        <?= GridView::widget([
            'dataProvider' => $assignedAccountsDataProvider,
            'columns' => [
                'user_name',
                'user_follower_count',
                'status',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'instagram-user'],
            ],
        ]); ?>

    </div>

    <div class='col-md-12'>
        <h2>Invoices</h2>

        <?= GridView::widget([
            'dataProvider' => $invoiceDataProvider,
            'columns' => [
                'invoice_id',
                // 'billing_id',
                // 'pricing_id',
                //'agency.agency_company',
                // 'message_id',

                // 'vendor_id',
                'sale_id',
                'invoice_usd_amount:currency',
                // 'vendor_order_id',
                // 'payment_type',
                // 'auth_exp',
                'invoice_status',
                'fraud_status',
                'message_type',
                // 'message_description',
                // 'customer_ip',
                // 'customer_ip_country',
                // 'item_id_1',
                // 'item_name_1',
                // 'item_usd_amount_1',
                // 'item_type_1',
                // 'item_rec_status_1',
                // 'item_rec_date_next_1',
                // 'item_rec_install_billed_1',
                'invoice_updated_at:datetime',
                //'sale_date_placed',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'invoice'],
            ],
        ]); ?>
    </div>


    <div class='col-md-12'>
        <h2>Billing Attempts</h2>
        <?php if($model->getBillingDaysLeft()){ ?>
        <p>
            <?= Html::a('Cancel Billing Plan',
            ['cancel-billing-plan', 'id' => $model->agent_id],
            [
                'class' => 'btn btn-danger',
                'data-confirm' => "Are you sure you want to cancel billing?",
                'data-method' => "post"
            ]) ?>
        </p>
        <?php } ?>

        <?= GridView::widget([
            'dataProvider' => $billingDataProvider,
            'columns' => [
                'billing_id',
                'twoco_transaction_id',
                'twoco_order_num',
                'billing_total:currency',
                'twoco_response_code',
                'twoco_response_msg',
                'billing_datetime:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'billing'],
            ],
        ]); ?>
    </div>


    <div class='col-md-12'>
        <h3>Activity</h3>

        <?= GridView::widget([
            'dataProvider' => $activityDataProvider,
            'columns' => [
                'user.user_name',
                'activity_detail:ntext',
                'activity_datetime:datetime',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller'=>'activity'],
            ],
        ]); ?>
    </div>



</div>
