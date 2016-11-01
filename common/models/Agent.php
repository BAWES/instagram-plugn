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
 * @property integer $agent_email_preference
 * @property string $agent_limit_email
 * @property string $agent_created_at
 * @property string $agent_updated_at
 *
 * @property Activity[] $activities
 * @property InstagramUser[] $accountsManaged
 * @property AgentAssignment[] $agentAssignments
 * @property AgentAuth[] $agentAuths
 * @property AgentToken[] $accessTokens
 * @property Comment[] $comments
 * @property Comment[] $handledComments
 * @property Comment[] $deletedComments
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

    //Email notification preference for `agent_email_preference`
    const PREF_EMAIL_DAILY = 1;
    const PREF_EMAIL_OFF = 0;

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

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        //New Agent Signup, Link him to accounts hes assigned to.
        if($insert){
            //Update All AgentAssignments where his email is mentioned to use his Agent ID
            AgentAssignment::updateAll([
                'agent_id' => $this->agent_id
                ],[
                'assignment_agent_email' => $this->agent_email
            ]);
        }
    }

    /**
     * Get all Instagram accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged()
    {
        return $this->hasMany(\agent\models\InstagramUser::className(), ['user_id' => 'user_id'])
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
     * Access tokens used to login on devices
     * @return \yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(AgentToken::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * All Auth records made for this agent (eg: Oauth2 Google/live/Slack etc)
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAuths()
    {
        return $this->hasMany(AgentAuth::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Activity log of this agent, what has he done?
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['agent_id' => 'agent_id'])
                    ->orderBy('activity_datetime DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHandledComments()
    {
        return $this->hasMany(Comment::className(), ['comment_handled_by' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedComments()
    {
        return $this->hasMany(Comment::className(), ['comment_deleted_by' => 'agent_id']);
    }

    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail() {
        //Update agent last email limit timestamp
        $this->agent_limit_email = new Expression('NOW()');
        $this->save(false);

        // Generate Different Reset Link If API is calling
        if(Yii::$app->id == "app-api"){
            // API application calling
            $verificationUrl = Yii::$app->urlManagerAgent->createAbsoluteUrl([
                'site/email-verify',
                'code' => $this->agent_auth_key,
                'verify' => $this->agent_id
            ]);
        }else{
            // Agent portal calling
            $verificationUrl = Yii::$app->urlManager->createAbsoluteUrl([
                'site/email-verify',
                'code' => $this->agent_auth_key,
                'verify' => $this->agent_id
            ]);
        }

        return Yii::$app->mailer->compose([
                    'html' => 'agent/verificationEmail-html',
                    'text' => 'agent/verificationEmail-text',
                        ], [
                    'agent' => $this,
                    'verificationUrl' => $verificationUrl
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
            Yii::info("[New Agent Signup Manual] ".$this->agent_email, __METHOD__);

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
        return AgentToken::find()->where(['token_value' => $token])->with('agent')->one()->agent;
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

    /**
     * Create an Access Token Record for this Agent
     * if the agent already has one, it will return it instead
     * @return \common\models\AgentToken
     */
    public function getAccessToken(){
        // Return existing inactive token if found
        $token = AgentToken::findOne([
            'agent_id' => $this->agent_id,
            'token_status' => AgentToken::STATUS_ACTIVE
        ]);
        if($token){
            return $token;
        }

        // Create new inactive token
        $token = new AgentToken();
        $token->agent_id = $this->agent_id;
        $token->token_value = AgentToken::generateUniqueTokenString();
        $token->token_status = AgentToken::STATUS_ACTIVE;
        $token->save(false);

        return $token;
    }
}
