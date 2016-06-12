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
     * Mark comments provided as handled by this agent
     * @param array $comments list of comment Ids
     * @return boolean
     */
    public function handleConversationComments($commenterId, $commenterUsername)
    {
        Yii::$app->db->createCommand("
            UPDATE comment
            SET comment_handled = ".Comment::HANDLED_TRUE.", agent_id = ".Yii::$app->user->identity->agent_id."
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
