<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "agency".
 *
 * @property string $agency_id
 * @property string $agency_fullname
 * @property string $agency_company
 * @property string $agency_email
 * @property integer $agency_email_verified
 * @property string $agency_auth_key
 * @property string $agency_password_hash
 * @property string $agency_password_reset_token
 * @property string $agency_limit_email
 * @property integer $agency_status
 * @property integer $agency_trial_days
 * @property string $agency_created_at
 * @property string $agency_updated_at
 *
 * @property InstagramUser[] $instagramUsers
 */
class Agency extends \yii\db\ActiveRecord implements IdentityInterface
{
    //Values for `agency_status`
    const STATUS_DELETED = 0;
    const STATUS_BANNED = 5;
    const STATUS_ACTIVE = 10;

    //Email verification values for `agency_email_verified`
    const EMAIL_VERIFIED = 1;
    const EMAIL_NOT_VERIFIED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agency';
    }

    public static function find()
    {
        return new AgencyQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_fullname', 'agency_email', 'agency_auth_key'], 'required'],
            [['agency_password_hash'], 'required', 'on'=>'manualSignup'],

            [['agency_email_verified', 'agency_status'], 'integer'],
            [['agency_fullname', 'agency_company', 'agency_email', 'agency_password_hash', 'agency_password_reset_token'], 'string', 'max' => 255],
            [['agency_auth_key'], 'string', 'max' => 32],
            [['agency_email'], 'unique'],
            [['agency_email'], 'email'],
            [['agency_password_reset_token'], 'unique'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'agency_created_at',
                'updatedAtAttribute' => 'agency_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agency_id' => 'Agency ID',
            'agency_fullname' => 'Full Name',
            'agency_company' => 'Company',
            'agency_email' => 'Email',
            'agency_email_verified' => 'Email Verified',
            'agency_auth_key' => 'Auth Key',
            'agency_password_hash' => 'Password',
            'agency_password_reset_token' => 'Password Reset Token',
            'agency_status' => 'Status',
            'agency_limit_email' => 'Limit Email',
            'agency_trial_days' => 'Trial Days Left',
            'agency_created_at' => 'Created At',
            'agency_updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstagramUsers()
    {
        return $this->hasMany(InstagramUser::className(), ['agency_id' => 'agency_id']);
    }

    /**
     * Signs user up.
     * @return static|null the saved model or null if saving fails
     */
    public function signup() {
        $oldPasswordInput = $this->agency_password_hash;

        $this->setPassword($this->agency_password_hash);
        $this->generateAuthKey();

        if ($this->save()) {
            // Create an Agent account with similar access details as Agency
            $this->createDuplicateAgentAccount();

            // Send Verification Email
            $this->sendVerificationEmail();

            //Log agency signup
            Yii::info("[New Agency Signup] ".$this->agency_email, __METHOD__);

            return $this;
        }else{
            //Reset password to hide encrypted value
            $this->agency_password_hash = $oldPasswordInput;
        }

        return null;
    }

    /**
     * Create an Agent account with similar access details as Agency
     * if one doesn't already exist.
     * @return mixed
     */
    public function createDuplicateAgentAccount(){
        $agentExists = Agent::findOne(['agent_email' => $this->agency_email]);
        if(!$agentExists){
            // Create an Agent account for this agency
            $agent = new Agent();
            $agent->agent_name = $this->agency_fullname;
            $agent->agent_email = $this->agency_email;
            $agent->agent_email_verified = Agent::EMAIL_NOT_VERIFIED;
            $agent->agent_auth_key = $this->agency_auth_key;
            $agent->agent_password_hash = $this->agency_password_hash;
            $agent->agent_password_reset_token = $this->agency_password_reset_token;
            $agent->agent_limit_email = $this->agency_limit_email;
            $agent->agent_created_at = $this->agency_created_at;
            $agent->agent_updated_at = $this->agency_updated_at;
            $agent->agent_status = Agent::STATUS_ACTIVE;
            $agent->agent_email_preference = Agent::PREF_EMAIL_DAILY;
            $agent->save(false);
        }
    }


    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail() {
        //Update agency last email limit timestamp
        $this->agency_limit_email = new Expression('NOW()');
        $this->save(false);

        // Generate Different Reset Link If API is calling
        if(Yii::$app->id == "app-api"){
            // API application calling
            $verificationUrl = Yii::$app->urlManageragency->createAbsoluteUrl([
                'site/email-verify',
                'code' => $this->agency_auth_key,
                'verify' => $this->agency_id
            ]);
        }else{
            // agency portal calling
            $verificationUrl = Yii::$app->urlManager->createAbsoluteUrl([
                'site/email-verify',
                'code' => $this->agency_auth_key,
                'verify' => $this->agency_id
            ]);
        }

        return Yii::$app->mailer->compose([
                    'html' => 'agency/verificationEmail-html',
                    'text' => 'agency/verificationEmail-text',
                        ], [
                    'agency' => $this,
                    'verificationUrl' => $verificationUrl
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo($this->agency_email)
                ->setSubject('[Plugn] Email Verification')
                ->send();
    }


    /**
     * Start of IdentityInterface Methods
     */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['agency_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        // $token = agencyToken::find()->where(['token_value' => $token])->with('agency')->one();
        // if($token){
        //     return $token->agency;
        // }
        return false;
    }

    /**
     * Finds admin by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['agency_email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'agency_password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->agency_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->agency_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->agency_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->agency_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->agency_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->agency_password_reset_token = null;
    }
}

/**
 * Custom queries for easier management of selection
 */
class AgencyQuery extends ActiveQuery
{
    public function validTrial()
    {
        return $this->andWhere(['agency_email_verified' => Agency::EMAIL_VERIFIED])
                ->andWhere(['>', 'agency_trial_days', 0]);
    }
}
