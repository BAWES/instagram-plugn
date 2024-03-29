<?php
namespace agent\models;

use Yii;
use yii\base\Model;
use common\models\Agent;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    /**
     * @var \common\models\Agent
     */
    private $_agent = false;


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
            $agent = $this->getAgent();
            if (!$agent || !$agent->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            }
        }
    }

    /**
     * Logs in an agent using the provided email and password.
     *
     * @return boolean whether the agent is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            //Check if Agent has verified their email
            $agent = $this->getAgent();
            if($agent){
                if($agent->agent_email_verified == Agent::EMAIL_NOT_VERIFIED){

                    $resendLink = \yii\helpers\Url::to(["site/resend-verification",
                        'id' => $agent->agent_id,
                        'email' => $agent->agent_email,
                    ], true);

                    $message = Yii::t('agent',"Please click the verification link sent to you by email to activate your account.", [
                            'resendLink' => $resendLink,
                        ]);

                    //Set Flash that agent needs to verify his email + resend option
                    Yii::$app->session->setFlash("warning", $message);

                }else{
                    //Log him in
                    return Yii::$app->user->login($this->getAgent(), 0);
                }
            }
        }

        return false;
    }

    /**
     * Finds Agent by email
     *
     * @return Agent|null
     */
    public function getAgent()
    {
        if ($this->_agent === false) {
            $this->_agent = Agent::findByEmail($this->email);
        }

        return $this->_agent;
    }
}
