<?php
/* @var $this yii\web\View */
/* @var $agent common\models\Agent */

$verificationUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/email-verify', 'code' => $agent->agent_auth_key, 'verify' => $agent->agent_id]);
?>

Hi, <?= $agent->agent_name ?>

Thanks for joining Plugn. Please click the following link to verify your email.

<?= $verificationUrl ?>
