<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */

$this->title = $model->agent_id;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agent_id',
            'agent_name',
            'agent_email:email',
            'agent_email_verified:email',
            'agent_auth_key',
            'agent_password_hash',
            'agent_password_reset_token',
            'agent_status',
            'agent_limit_email:email',
            'agent_created_at',
            'agent_updated_at',
        ],
    ]) ?>

</div>
