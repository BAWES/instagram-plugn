<?php

namespace api\models;

use Yii;
use common\models\Comment;

/**
 * This is the model class for table "Media".
 * It extends from \common\models\Media but with custom functionality for Agent application module
 *
 */
class Media extends \common\models\Media {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        // Whitelisted fields to return
        return [
            'media_id' => 'media_id',
            'numComments' => 'media_num_comments',
            'numLikes' => 'media_num_likes',
            'caption' => 'media_caption',
            'image' => function ($model) {
                return [
                    'lowres' => $model->media_image_lowres,
                    'thumb' => $model->media_image_thumb,
                    'standard' => $model->media_image_standard
                ];
            },
            'datePosted' => function($model) {
                return Yii::$app->formatter->asDate($model->media_created_datetime);
            }
        ];
    }

    /**
     * Handle all comments under this Media
     * @param array $comments list of comment Ids
     * @return boolean
     */
    public function handleMediaComments()
    {
        Yii::$app->db->createCommand("
            UPDATE comment
            SET
                comment_handled = ".Comment::HANDLED_TRUE.",
                comment_handled_by = ".Yii::$app->user->identity->agent_id.",
                comment_notification_email_sent = ".Comment::NOTIFICATION_EMAIL_SENT_TRUE."
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
