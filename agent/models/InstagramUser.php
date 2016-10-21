<?php

namespace agent\models;

use Yii;
use common\models\Comment;

/**
 * This is the model class for table "Instagram_User".
 * It extends from \common\models\InstagramUser but with custom functionality for Agent application module
 *
 */
class InstagramUser extends \common\models\InstagramUser {

    public $unhandledCount = 0;

    /**
     * @inheritdoc
     */
    public function fields()
    {
        // Whitelisted fields to return
        return [
            'user_id',
            'user_name',
            'user_fullname',
            'user_profile_pic',
            'user_bio',
            'user_website',
            'user_media_count',
            'user_following_count',
            'user_follower_count',
            'unhandledCount'
            // 'name' => function ($model) {
            //     return $model->first_name . ' ' . $model->last_name;
            // },
        ];
    }

    /**
     * Mark comments provided as handled by this agent
     * @param array $comments list of comment Ids
     * @return boolean
     */
    public function handleConversationComments($commenterId, $commenterUsername)
    {
        Yii::$app->db->createCommand("
            UPDATE comment
            SET
                comment_handled = ".Comment::HANDLED_TRUE.",
                comment_handled_by = ".Yii::$app->user->identity->agent_id.",
                comment_notification_email_sent = ".Comment::NOTIFICATION_EMAIL_SENT_TRUE."
            WHERE
                ((user_id=:accountId AND comment_by_id=:commenterId)
                OR (user_id=:accountId AND comment_text LIKE '%@".$commenterUsername."%'))
            AND
                comment_handled = ".Comment::HANDLED_FALSE."
            ")
            ->bindValue(':accountId', $this->user_id)
            ->bindValue(':commenterId', $commenterId)
            ->execute();

        //Log that agent made change
        Activity::log($this->user_id, "Marked the conversation with @$commenterUsername as handled");

        return true;
    }



}
