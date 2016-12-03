<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "note".
 *
 * @property string $note_id
 * @property integer $user_id
 * @property string $note_title
 * @property string $note_text
 * @property string $created_by_agent_id
 * @property string $updated_by_agent_id
 * @property string $note_created_datetime
 * @property string $note_updated_datetime
 *
 * @property Agent $createdByAgent
 * @property Agent $updatedByAgent
 * @property InstagramUser $user
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_by_agent_id', 'updated_by_agent_id'], 'integer'],
            [['note_text'], 'string'],
            [['note_title'], 'string', 'max' => 255],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'note_created_datetime',
                'updatedAtAttribute' => 'note_updated_datetime',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by_agent_id',
                'updatedByAttribute' => 'updated_by_agent_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note_id' => 'Note ID',
            'user_id' => 'User ID',
            'note_title' => 'Note Title',
            'note_text' => 'Note Text',
            'created_by_agent_id' => 'Created By Agent ID',
            'updated_by_agent_id' => 'Updated By Agent ID',
            'note_created_datetime' => 'Note Created Datetime',
            'note_updated_datetime' => 'Note Updated Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'created_by_agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'updated_by_agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
