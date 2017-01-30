<?php
namespace api\models;

use Yii;
use common\models\Agent;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Agent',
                'targetAttribute' => 'agent_email',
                'message' => Yii::t("agent", 'There is no agent with such email.')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @param common\models\Agent $agent
     * @return boolean whether the email was sent
     */
    public function sendEmail($agent = null)
    {
        if(!$agent){
            $agent = Agent::findOne([
                'agent_email' => $this->email,
            ]);
        }

        if ($agent) {
            if (!Agent::isPasswordResetTokenValid($agent->agent_password_reset_token)) {
                $agent->generatePasswordResetToken();
            }

            //Update agent last email limit timestamp
            $agent->agent_limit_email = new \yii\db\Expression('NOW()');

            if ($agent->save(false)) {
                $resetLink = Yii::$app->urlManagerAgent->createAbsoluteUrl(['deeplink/reset-password', 'token' => $agent->agent_password_reset_token]);

                //Send English Email
                return \Yii::$app->mailer->compose([
                    'html' => 'agent/passwordResetToken-html',
                    'text' => 'agent/passwordResetToken-text'
                ], [
                    'agent' => $agent,
                    'resetLink' => $resetLink
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo($agent->agent_email)
                ->setSubject('[Plugn] Password Reset')
                ->send();

            }
        }

        return false;
    }
}
