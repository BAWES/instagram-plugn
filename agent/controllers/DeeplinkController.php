<?php
namespace agent\controllers;

use Yii;
use yii\web\Controller;
use common\models\Agent;

/**
 * Deeplink controller
 */
class DeeplinkController extends Controller
{
    /**
     * Email verification by clicking on link in email which includes the code that will verify
     * @param string $code Verification key that will verify your account
     * @param int $verify Agent ID to verify
     * @throws NotFoundHttpException if the code is invalid
     */
    public function actionEmailVerify($code, $verify) {
        $this->layout = 'signup';

        //Code is his auth key, check if code is valid
        $agent = Agent::findOne(['agent_auth_key' => $code, 'agent_id' => (int) $verify]);
        if ($agent) {
            //If not verified
            if ($agent->agent_email_verified == Agent::EMAIL_NOT_VERIFIED) {
                //Verify this agents  email
                $agent->agent_email_verified = Agent::EMAIL_VERIFIED;
                $agent->save(false);
            }

            return $this->render('success', ['title' => 'You have verified your email']);
        } else {
            //inserted code is invalid
            throw new \yii\web\BadRequestHttpException(Yii::t('register', 'Invalid email verification code'));
        }
    }

}
