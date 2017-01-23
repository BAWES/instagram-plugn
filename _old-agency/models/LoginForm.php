<?php
namespace agency\models;

use Yii;
use yii\base\Model;
use common\models\Agency;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @var \common\models\Agency
     */
    private $_agency = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // email must be an email
            ['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Keep me signed in'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $agency = $this->getAgency();
            if (!$agency || !$agency->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            }
        }
    }

    /**
     * Logs in an agency using the provided email and password.
     *
     * @return boolean whether the agency is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            //Check if Agency has verified their email
            $agency = $this->getAgency();
            if($agency){
                if($agency->agency_email_verified == Agency::EMAIL_NOT_VERIFIED){

                    $resendLink = \yii\helpers\Url::to(["site/resend-verification",
                        'id' => $agency->agency_id,
                        'email' => $agency->agency_email,
                    ], true);

                    $message = Yii::t('agency',"Please click the verification link sent to you by email to activate your account. <br/><br/><a href=\'{resendLink}\'>Resend Verification Email</a>", [
                            'resendLink' => $resendLink,
                        ]);

                    //Set Flash that agency needs to verify his email + resend option
                    Yii::$app->session->setFlash("warning", $message);

                }else{
                    //Log him in
                    return Yii::$app->user->login($this->getAgency(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                }
            }
        }

        return false;
    }

    /**
     * Finds Agency by email
     *
     * @return Agency|null
     */
    public function getAgency()
    {
        if ($this->_agency === false) {
            $this->_agency = Agency::findByEmail($this->email);
        }

        return $this->_agency;
    }
}
