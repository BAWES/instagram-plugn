<?php

namespace agent\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "comment_queue".
 * It extends from \common\models\CommentQueue but with custom functionality for Employer application module
 *
 * @property integer $queue_id
 * @property string $media_id
 * @property integer $user_id
 * @property string $agent_id
 * @property string $comment_id
 * @property string $queue_text
 * @property string $queue_datetime
 *
 * @property Agent $agent
 * @property Comment $comment
 * @property Media $media
 * @property InstagramUser $user
 */
class CommentQueue extends \common\models\CommentQueue {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            ['queue_text', 'required', 'on' => 'newComment'],
            ['queue_text', 'string'],
            ['queue_text', 'validateCommentGuidelines', 'on' => 'newComment'],
        ]);
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
        /*
        $scenarios['updateCompanyInfo'] = ['employer_company_name', 'employer_website', 'city_id', 'industry_id', 'employer_num_employees', 'employer_company_desc'];
        $scenarios['updatePersonalInfo'] = ['employer_contact_firstname', 'employer_contact_lastname', 'employer_contact_number'];
        $scenarios['updateSocialDetails'] = ['employer_social_twitter', 'employer_social_instagram', 'employer_social_facebook'];
        */
        return $scenarios;
    }

    /**
     * Validates the comment to conform with Instagram Guidelines.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCommentGuidelines($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getInstaUser();

            //TODO - validation errors based on Instagram Guidelines
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'User not found'));
            }
        }
    }

    /**
     * Sends the comment to queue / to be posted if it passes validation
     *
     * @return boolean whether it was successfully processed or not
     */
    public function sendComment()
    {
        if ($this->validate()) {
            //blabla
            return true;
        }

        return false;
    }

}
