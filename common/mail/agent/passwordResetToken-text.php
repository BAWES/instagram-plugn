<?php

/* @var $this yii\web\View */
/* @var $agent common\models\Agent */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $agent->agent_password_reset_token]);
?>
Hello <?= $agent->agent_name ?>,

Follow the link below to reset your password:

<?= $resetLink ?>