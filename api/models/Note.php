<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "Note".
 * It extends from \common\models\Note but with custom functionality for Api application module
 *
 */
class Note extends \common\models\Note {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        // Whitelisted fields to return via API
        return [
            'id' => 'note_id',
            'userId' => 'user_id',
            'noteAboutUsername' => 'note_about_username',
            'title' => 'note_title',
            'content' => 'note_text',
            'created_by' => function($model) {
                return $this->createdByAgent->agent_name;
            },
            'updated_by' => function($model) {
                return $this->updatedByAgent->agent_name;
            },
            'created_datetime' => function($model) {
                return Yii::$app->formatter->asRelativeTime($this->note_created_datetime);
            },
            'updated_datetime' => function($model) {
                return Yii::$app->formatter->asRelativeTime($this->note_updated_datetime);
            },
        ];
    }

    // Create activities on save and update
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // Created Note
                Activity::log($this->user_id, "Added a note on @".$this->note_about_username."'s profile. ".$this->note_text);
            }else{
                // Updated Note
                Activity::log($this->user_id, "Updated a note on @".$this->note_about_username."'s profile. ".$this->note_text);
            }

            return true;
        }
    }

    public function beforeDelete() {
        Activity::log($this->user_id, "Deleted a note on @".$this->note_about_username."'s profile. ".$this->note_text);

        return true;
    }

}
