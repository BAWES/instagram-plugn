<?php

namespace agent\models;

use Yii;
use common\models\Comment;

/**
 * This is the model class for table "Instagram_User".
 * It extends from \common\models\InstagramUser but with custom functionality for Agent application module
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
            SET comment_handled = ".Comment::HANDLED_TRUE.", agent_id = ".Yii::$app->user->identity->agent_id."
            WHERE
                media_id=:mediaId
            AND
                comment_handled = ".Comment::HANDLED_FALSE."
            ")
            ->bindValue(':mediaId', $this->media_id)
            ->execute();

        return true;
    }

}
