<?php

namespace agent\models;

use Yii;
use common\models\Comment;

/**
 * This is the model class for table "Media".
 * It extends from \common\models\Media but with custom functionality for Agent application module
 *
 */
class Media extends \common\models\Media {

    /**
     * Handle all comments under this Media
     * @param array $comments list of comment Ids
     * @return boolean
     */
    public function handleMediaComments()
    {
        Yii::$app->db->createCommand("
            UPDATE comment
            SET comment_handled = ".Comment::HANDLED_TRUE.", comment_handled_by = ".Yii::$app->user->identity->agent_id."
            WHERE
                media_id=:mediaId
            AND
                comment_handled = ".Comment::HANDLED_FALSE."
            ")
            ->bindValue(':mediaId', $this->media_id)
            ->execute();

        //Log that agent made change
        Activity::log($this->user_id, "Marked all comments on media #".$this->media_id." as handled");

        return true;
    }

}
