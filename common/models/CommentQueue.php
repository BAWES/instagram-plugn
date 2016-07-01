<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "comment_queue".
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
class CommentQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'user_id', 'agent_id'], 'required'],
            [['media_id', 'user_id', 'agent_id', 'comment_id'], 'integer'],
            [['queue_text'], 'string'],
            //[['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            //[['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'comment_id']],
            //[['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'media_id']],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstagramUser::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'queue_datetime',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'queue_id' => 'Queue ID',
            'media_id' => 'Media ID',
            'user_id' => 'User ID',
            'agent_id' => 'Agent ID',
            'comment_id' => 'Comment ID',
            'queue_text' => 'Comment text',
            'queue_datetime' => 'Queue Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['comment_id' => 'comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['media_id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
