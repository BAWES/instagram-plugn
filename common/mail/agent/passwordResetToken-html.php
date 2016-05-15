<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $agent common\models\Agent */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $agent->agent_password_reset_token]);
?>

<tr>
    <td>
        <h1>Hello <?= $agent->agent_name ?>,</h1>
        <p class="lead">Follow the link below to reset your password</p>
    </td>
    <td class="expander"></td>
</tr>
<tr>
    <td>
        <table class="button success">
            <tbody>
                <tr>
                    <td>
                        <?= Html::a("Reset Password", $resetLink) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="expander"></td>
</tr>
