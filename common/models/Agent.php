<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

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
 * @property integer $agent_trial_days
 * @property string $agent_billing_active_until
 * @property integer $agent_email_preference
 * @property string $agent_limit_email
 * @property string $agent_created_at
 * @property string $agent_updated_at
 *
 * @property Activity[] $activities
 * @property InstagramUser[] $accountsManaged
 * @property InstagramUser[] $instagramUsers
 * @property AgentAssignment[] $agentAssignments
 * @property AgentAuth[] $agentAuths
 * @property AgentToken[] $accessTokens
 * @property Comment[] $comments
 * @property Comment[] $handledComments
 * @property Comment[] $deletedComments
 * @property Billing[] $billings
 * @property Invoice[] $invoices
 */
class Agent extends ActiveRecord implements IdentityInterface
{
    //Values for `agent_status`
    const STATUS_DELETED = 0;
    const STATUS_BANNED = 5;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20; // Billing / Trial Expired

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

    public static function find()
    {
        return new AgentQuery(get_called_class());
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
            'agent_trial_days' => 'Trial Days Left',
            'agent_billing_active_until' => 'Billing Active Until',
            'agent_email_preference' => 'Email Preference',
            'agent_limit_email' => 'Limit Email',
            'agent_created_at' => 'Created At',
            'agent_updated_at' => 'Updated At',
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus(){
        switch($this->agent_status){
            case self::STATUS_ACTIVE:
                return "Active";
                break;
            case self::STATUS_INACTIVE:
                return "Inactive (Billing or Trial Expired)";
                break;
            case self::STATUS_BANNED:
                return "Banned";
                break;
            case self::STATUS_DELETED:
                return "Deleted";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * BeforeSave
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->agent_billing_active_until = new Expression('SUBDATE(NOW(), 1)');
            }

            return true;
        }
    }

    /**
     * AfterSave
     */
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
     * Accounts owned by this agent
     * @return \yii\db\ActiveQuery
     */
    public function getInstagramUsers()
    {
        return $this->hasMany(InstagramUser::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Get all Instagram accounts this agent owns
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsOwned()
    {
        return $this->hasMany(InstagramUser::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Get all Instagram accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged()
    {
        return $this->hasMany(\api\models\InstagramUser::className(), ['user_id' => 'user_id'])
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
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail() {
        //Update agent last email limit timestamp
        $this->agent_limit_email = new Expression('NOW()');
        $this->save(false);

        // Generate Reset Link
        $verificationUrl = Yii::$app->urlManagerAgent->createAbsoluteUrl([
            'site/email-verify',
            'code' => $this->agent_auth_key,
            'verify' => $this->agent_id
        ]);


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
     * Check whether the agent has hit his owned account limit
     * @return boolean
     */
    public function getIsAtAccountLimit(){
        $accountCount = count($this->instagramUsers);
        $accountLimit = $this->linkedAccountLimit;
        if($accountCount >= $accountLimit){
            return true;
        }
        return false;
    }

    /**
     * Return the number of owned Instagram accounts allowed for this agent
     * @return integer
     */
    public function getLinkedAccountLimit(){
        // If Billing is Active, check pricing plan for the limit.
        $billingDaysLeft = $this->getBillingDaysLeft();
        if($billingDaysLeft){
            $invoice = $this->getInvoices()->with('pricing')
                ->orderBy('invoice_created_at DESC')->limit(1)->one();
            if($invoice && $invoice->pricing){
                return $invoice->pricing->pricing_account_quantity;
            }
        }

        return 9999;
    }

    /**
     * Sets the new date for the deadline.
     * It can be set to both future and past dates
     * Function should check if billing is active after setting the new date then
     * act accordingly
     * @param  string $deadlineDate Date when the next payment is due
     * @return mixed
     */
    public function updateBillingDeadline($deadlineDate){
        $this->agent_billing_active_until = $deadlineDate;
        $this->save(false);
        $this->refresh();

        $billingDaysLeft = $this->getBillingDaysLeft();

        // Disable Trial If Billing is Active
        if($billingDaysLeft){
            $this->agent_trial_days = 0;
        }
        $this->save(false);

        // Disable Agent and owned accounts if both billing and trial ran out
        // OTHERWISE Enable Agent and his owned accounts if not already enabled.
        if(!$billingDaysLeft && !$this->hasActiveTrial()){
            $this->_disableAgentAndManagedAccounts();
        }else{
            $this->_enableAgentAndManagedAccounts();
        }
    }

    /**
     * Return the number of days left on his billing plan payment (if any)
     * @return integer
     */
    public function getBillingDaysLeft(){
        $expiresOn = new \DateTime($this->agent_billing_active_until);
        $today = new \DateTime();

        $daysLeft = $expiresOn->diff($today)->days;

        if($expiresOn > $today){
            return $daysLeft + 1;
        }

        // Allow 24 hours additional for user to sort out billing issues
        if($daysLeft == 0){
            return 1;
        }

        return 0;
    }


    /**
     * Check if this agent has a valid active trial
     * @return boolean
     */
    public function hasActiveTrial(){
        if($this->agent_email_verified == Agent::EMAIL_VERIFIED
            && $this->agent_status == Agent::STATUS_ACTIVE
            && $this->agent_trial_days > 0 && !$this->getBillingDaysLeft()){
            return true;
        }
        return false;
    }

    /**
     * Deducts a trial day from all active agencies
     */
    public static function deductTrialDayFromAllActiveAgents()
    {
        $agentsWithActiveTrial = static::find()->validTrial();
        foreach($agentsWithActiveTrial->each(50) as $agent){
            // Deduct a trial day only if the agent owns Instagram accounts
            if(count($agent->instagramUsers) > 0){
                $agent->deductTrialDay();
            }
        }
    }

    /**
     * Deducts a day from the active trial
     * @return mixed
     */
    public function deductTrialDay(){
        if($this->hasActiveTrial()){
            // Deduct a day from trial days
            $this->agent_trial_days = $this->agent_trial_days - 1;
            $this->save(false);

            // Disable Trial if no days left
            if($this->agent_trial_days == 0){
                $this->_disableAgentAndManagedAccounts();

                // Log to Slack that this customer trial expired & no billing setup
                Yii::warning("[Agent #".$this->agent_id." Trial Expired] Owned by ".$this->agent_name." and has ".count($this->instagramUsers)." accounts", __METHOD__);

                // Send Email to Customer that his trial expired & need to setup billing
                Yii::$app->mailer->compose([
                        'html' => 'billing/trial-expired',
                            ], [
                        'agent' => $this,
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                    ->setTo($this->agent_email)
                    ->setSubject('Your trial has expired. Thanks for giving Plugn a try!')
                    ->send();
            }
        }
    }


    /**
     * Disable the active ig accounts when an agent account expires
     * either by end of trial or expired billing
     * @return mixed
     */
    private function _disableAgentAndManagedAccounts(){
        // Do nothing if already disabled
        if($this->agent_status == Agent::STATUS_INACTIVE) return;

        // Update the status of the IG accounts owned by agent
        foreach($this->instagramUsers as $user){
            $user->activateAccountIfPossible();
        }

        $this->agent_status = Agent::STATUS_INACTIVE;
        $this->save(false);
    }

    /**
     * Enable the active ig accounts when an agent account is reactivated
     * either by new trial or billing
     * @return mixed
     */
    private function _enableAgentAndManagedAccounts(){
        // Do nothing if already enabled
        if($this->agent_status == Agent::STATUS_ACTIVE) return;

        // Update the status of the IG accounts owned by this agent
        foreach($this->instagramUsers as $user){
            $user->activateAccountIfPossible();
        }

        $this->agent_status = Agent::STATUS_ACTIVE;
        $this->save(false);
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
        $token = AgentToken::find()->where(['token_value' => $token])->with('agent')->one();
        if($token){
            return $token->agent;
        }
    }

    /**
     * Find identity by Auth Key
     */
    public static function loginViaAuthKey($authKey) {
        // Log agent out before attempting to log him in
        if(!Yii::$app->user->isGuest){
            Yii::$app->user->logout();
        }

        $agent = static::findOne(['agent_auth_key' => $authKey]);
        if($agent){
            // Reset the previously used auth key
            $agent->generateAuthKey();
            $agent->save(false);

            // Log the agent in
            return Yii::$app->user->login($agent, 0);
        }

        return false;
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
     * Generates auth key [1 time use token]
     */
    public function generateAuthKey() {
        $this->agent_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generate, save, and return an auth key for this account [1 time use token]
     * @return string
     */
    public function generateAuthKeyAndSave() {
        $this->generateAuthKey();
        $this->save(false);

        return $this->agent_auth_key;
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

/**
 * Custom queries for easier management of selection
 */
class AgentQuery extends ActiveQuery
{
    public function validTrial()
    {
        return $this->with("instagramUsers")
        ->andWhere(['agent_email_verified' => Agent::EMAIL_VERIFIED])
        ->andWhere(['agent_status' => Agent::STATUS_ACTIVE])
        ->andWhere(['>', 'agent_trial_days', 0]);
    }
}
