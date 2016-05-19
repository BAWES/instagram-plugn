<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "agent".
 *
 * @property integer $agent_id
 * @property string $agent_name
 * @property string $agent_email
 * @property integer $agent_email_verified
 * @property string $agent_auth_key
 * @property string $agent_password_hash
 * @property string $agent_password_reset_token
 * @property integer $agent_status
 * @property string $agent_limit_email
 * @property string $agent_created_at
 * @property string $agent_updated_at
 *
 * @property InstagramUser[] $accountsManaged
 * @property AgentAssignment[] $agentAssignments
 * @property AgentAuth[] $agentAuths
 */
class Agent extends ActiveRecord implements IdentityInterface
{
    //Values for `agent_status`
    const STATUS_DELETED = 0;
    const STATUS_BANNED = 5;
    const STATUS_ACTIVE = 10;

    //Email verification values for `agent_email_verified`
    const EMAIL_VERIFIED = 1;
    const EMAIL_NOT_VERIFIED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_email', 'agent_auth_key'], 'required'],
            [['agent_name', 'agent_password_hash'], 'required', 'on'=>'manualSignup'],

            [['agent_email_verified', 'agent_status'], 'integer'],
            [['agent_name', 'agent_email', 'agent_password_hash', 'agent_password_reset_token'], 'string', 'max' => 255],
            [['agent_auth_key'], 'string', 'max' => 32],
            [['agent_email'], 'unique'],
            [['agent_email'], 'email'],
            [['agent_password_reset_token'], 'unique'],
        ];
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        /*
        $scenarios['changePassword'] = ['agent_password_hash'];
        $scenarios['updatePersonalInfo'] = ['agent_contact_firstname', 'agent_contact_lastname', 'agent_contact_number'];
        */

        return $scenarios;
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'agent_created_at',
                'updatedAtAttribute' => 'agent_updated_at',
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
            'agent_id' => 'Agent ID',
            'agent_name' => 'Name',
            'agent_email' => 'Email',
            'agent_email_verified' => 'Email Verified',
            'agent_auth_key' => 'Auth Key',
            'agent_password_hash' => 'Password',
            'agent_password_reset_token' => 'Password Reset Token',
            'agent_status' => 'Status',
            'agent_limit_email' => 'Email Verif Limit',
            'agent_created_at' => 'Created At',
            'agent_updated_at' => 'Updated At',
        ];
    }

    /**
     * Get all Instagram accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged()
    {
        return $this->hasMany(InstagramUser::className(), ['user_id' => 'user_id'])
                ->via('agentAssignments');
    }

    /**
     * All assignment records made for this agent
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments()
    {
        return $this->hasMany(AgentAssignment::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * All Auth records made for this agent
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAuths()
    {
        return $this->hasMany(AgentAuth::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail() {
        //Update agent last email limit timestamp
        $this->agent_limit_email = new Expression('NOW()');
        $this->save(false);


        return Yii::$app->mailer->compose([
                    'html' => 'agent/verificationEmail-html',
                    'text' => 'agent/verificationEmail-text',
                        ], [
                    'agent' => $this
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo($this->agent_email)
                ->setSubject('[Plugn] Email Verification')
                ->send();


    }

    /**
     * Signs user up.
     * @return static|null the saved model or null if saving fails
     */
    public function signup() {
        $oldPasswordInput = $this->agent_password_hash;

        $this->setPassword($this->agent_password_hash);
        $this->generateAuthKey();

        if ($this->save()) {
            $this->sendVerificationEmail();

            //Log agent signup
            Yii::info("[New Agent Signup] ".$this->agent_email, __METHOD__);

            return $this;
        }else{
            //Reset password to hide encrypted value
            $this->agent_password_hash = $oldPasswordInput;
        }

        return null;
    }


    /**
     * Start of IdentityInterface Methods
     */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['agent_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds admin by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['agent_email' => $email]);
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
            'agent_password_reset_token' => $token,
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
        return $this->agent_auth_key;
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
        return Yii::$app->security->validatePassword($password, $this->agent_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->agent_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->agent_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->agent_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->agent_password_reset_token = null;
    }
}