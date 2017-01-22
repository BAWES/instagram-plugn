<?php

namespace agent\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "comment_queue".
 * It extends from \common\models\CommentQueue but with custom functionality
 */
class CommentQueue extends \common\models\CommentQueue {

    //Username we're responding to, used for validation when responding to user
    public $respondingToUsername;

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['queue_text'], 'required', 'on' => ['newMediaComment', 'newConversationComment']],
            [['respondingToUsername'], 'required', 'on' => 'newConversationComment'],

            [['queue_text'], 'trim'],

            //Conversation: Make sure you're mentioning the person you are responding to, and make sure
            //              that the comment response isn't empty!
            ['queue_text', 'validateMentionUser', 'on' => 'newConversationComment'],

            // Comment API Rule #1 - The total length of the comment cannot exceed 300 characters.
            ['queue_text', 'string', 'max' => 300, 'on' => ['newMediaComment', 'newConversationComment']],
            // Comment API Rule #2 - The comment cannot contain more than 4 hashtags.
            ['queue_text', 'validateMaxHashtags', 'on' => ['newMediaComment', 'newConversationComment']],
            // Comment API Rule #3 - The comment cannot contain more than 1 URL.
            ['queue_text', 'validateMaxUrl', 'on' => ['newMediaComment', 'newConversationComment']],
            // Comment API Rule #4 - The comment cannot consist of all capital letters.
            ['queue_text', 'validateNotAllCaps', 'on' => ['newMediaComment', 'newConversationComment']],
        ]);
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        $scenarios['newConversationComment'] = ['queue_text'];
        $scenarios['newMediaComment'] = ['queue_text'];

        return $scenarios;
    }

    /**
     * Validation to make sure that you're mentioning the user you are responding to
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMentionUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $username = "@".$this->respondingToUsername;

            $userMentioned = preg_match("/$username\b/", $this->queue_text);

            if(!$userMentioned){
                $this->addError($attribute, Yii::t('app', "Dont forget to mention {username}",
                ['username' => $username]));
            }else{
                //User is mentioned, now check is there any content in his reply or is it blank?
                $replyContent = trim(str_replace($username, "", $this->queue_text));
                if(empty($replyContent)){
                    //there is no content in the specified reply
                    $this->addError($attribute, Yii::t('app', 'Your response seems to be blank'));
                }
            }
        }
    }

    /**
     * Validates the comment, makes sure its not all caps to conform with Instagram's Guidelines.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMaxHashtags($attribute, $params)
    {
        if (!$this->hasErrors()) {
            //How many Hashtags (#) used in comment?
            $numHashtags = substr_count($this->queue_text, "#");
            if($numHashtags > 4){
                $this->addError($attribute, Yii::t('app', 'The comment cannot contain more than 4 hashtags'));
            }
        }
    }

    /**
     * Validates the comment, makes sure its not all caps to conform with Instagram's Guidelines.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMaxUrl($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
            $urls = array();
        	if(preg_match_all($reg_exUrl, $this->queue_text, $urls)) {
        		$numOfUrls = count($urls[0]);
                if($numOfUrls > 1){
                    $this->addError($attribute, Yii::t('app', 'The comment cannot contain more than 1 URL'));
                }
        	}
        }
    }

    /**
     * Validates the comment, makes sure its not all caps to conform with Instagram's Guidelines.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateNotAllCaps($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (mb_strtoupper($this->queue_text, 'utf-8') == $this->queue_text) {
                $this->addError($attribute, Yii::t('app', 'The comment cannot consist of all capital letters'));
            }
        }
    }

}
