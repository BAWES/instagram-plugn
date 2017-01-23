<?php
namespace agency\models;

use Yii;
use common\models\Agency;
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
                'targetClass' => '\common\models\Agency',
                'targetAttribute' => 'agency_email',
                'message' => Yii::t("agency", 'There is no agency with such email.')
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
     * @param common\models\Agency $agency
     * @return boolean whether the email was sent
     */
    public function sendEmail($agency = null)
    {
        if(!$agency){
            $agency = Agency::findOne([
                'agency_email' => $this->email,
            ]);
        }

        if ($agency) {
            if (!Agency::isPasswordResetTokenValid($agency->agency_password_reset_token)) {
                $agency->generatePasswordResetToken();
            }

            //Update agency last email limit timestamp
            $agency->agency_limit_email = new \yii\db\Expression('NOW()');

            if ($agency->save(false)) {

                // Generate Different Reset Link If API is calling
                if(Yii::$app->id == "app-api"){
                    // API application calling
                    $resetLink = Yii::$app->urlManagerAgency->createAbsoluteUrl(['site/reset-password', 'token' => $agency->agency_password_reset_token]);
                }else{
                    // Agency portal calling
                    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $agency->agency_password_reset_token]);
                }


                //Send English Email
                return \Yii::$app->mailer->compose([
                    'html' => 'agency/passwordResetToken-html',
                    'text' => 'agency/passwordResetToken-text'
                ], [
                    'agency' => $agency,
                    'resetLink' => $resetLink
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo($agency->agency_email)
                ->setSubject('[Plugn] Password Reset')
                ->send();

            }
        }

        return false;
    }
}
