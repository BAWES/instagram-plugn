<?php
/* @var $this yii\web\View */
/* @var $agent common\models\Agent */

$verificationUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/email-verify', 'code' => $agent->agent_auth_key, 'verify' => $agent->agent_id]);
?>
<tr>
    <td>
        <h1>Hi, <?= $agent->agent_contact_firstname ?></h1>
        <p class="lead">Thanks for joining <strong>Plugn</strong>. Please click the following link to verify your email.</p>
    </td>
    <td class="expander"></td>
</tr>
<tr>
    <td>
        <table class="button success">
            <tbody>
                <tr>
                    <td>
                        <a href="<?= $verificationUrl ?>">Verify Email</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="expander"></td>
</tr>
