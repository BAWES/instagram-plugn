<?php

namespace agent\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "agent".
 * It extends from \common\models\Agent but with custom functionality for Agent application module
 *
 */
class Agent extends \common\models\Agent {

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        /*
        $scenarios['changeEmailPreference'] = ['agent_email_preference'];
        $scenarios['changePassword'] = ['agent_password_hash'];

        $scenarios['updateCompanyInfo'] = ['agent_company_name', 'agent_website', 'city_id', 'industry_id', 'agent_num_employees', 'agent_company_desc'];
        $scenarios['updatePersonalInfo'] = ['agent_contact_firstname', 'agent_contact_lastname', 'agent_contact_number'];
        $scenarios['updateSocialDetails'] = ['agent_social_twitter', 'agent_social_instagram', 'agent_social_facebook'];
        */

        return $scenarios;
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
                ->setSubject('[StudentHub] Email Verification')
                ->send();

    }

    /**
     * Signs user up.
     * @param boolean $validate - whether to validate before Signing up
     * @return static|null the saved model or null if saving fails
     */
    public function signup($validate = false) {
        $this->setPassword($this->agent_password_hash);
        $this->generateAuthKey();

        if ($this->save($validate)) {
            $this->sendVerificationEmail();

            /**
             * Send email here to Admins notifying that a new agent has signed up
             */
            Yii::$app->mailer->compose([
                    'html' => "admin/new-agent-html",
                        ], [
                    'agent' => $this,
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo([\Yii::$app->params['supportEmail']])
                ->setSubject('[StudentHub] New Agent - '.$this->agent_email)
                ->send();

            //Log agent signup
            Yii::info("[New Agent Signup] ".$this->agent_email, __METHOD__);

            return $this;
        }

        return null;
    }

}
