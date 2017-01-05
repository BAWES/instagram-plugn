<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agency */

$this->title = 'Update Agency: ' . $model->agency_id;
$this->params['breadcrumbs'][] = ['label' => 'Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->agency_id, 'url' => ['view', 'id' => $model->agency_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agency-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
