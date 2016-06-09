<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $comment_id
 * @property integer $media_id
 * @property integer $user_id
 * @property integer $agent_id
 * @property string $comment_instagram_id
 * @property string $comment_text
 * @property string $comment_by_username
 * @property string $comment_by_photo
 * @property string $comment_by_id
 * @property string $comment_by_fullname
 * @property integer $comment_handled
 * @property integer $comment_deleted
 * @property string $comment_deleted_reason
 * @property string $comment_datetime
 *
 * @property Agent $agent
 * @property Media $media
 * @property InstagramUser $user
 * @property CommentQueue[] $commentQueues
 */
class Comment extends \yii\db\ActiveRecord
{
    const DELETED_QUEUED_FOR_DELETION = 2;
    const DELETED_TRUE = 1;
    const DELETED_FALSE = 0;

    const HANDLED_TRUE = 1;
    const HANDLED_FALSE = 0;

    const REASON_DELETED_DEFAULT = "Deleted through Instagram";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'user_id', 'comment_instagram_id', 'comment_datetime'], 'required'],
            [['media_id', 'user_id', 'comment_handled', 'comment_deleted'], 'integer'],
            [['comment_text', 'comment_deleted_reason'], 'string'],
            [['comment_datetime'], 'safe'],
            [['comment_instagram_id', 'comment_by_username', 'comment_by_photo', 'comment_by_id', 'comment_by_fullname'], 'string'],
            [['comment_instagram_id'], 'unique'],
            //[['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'media_id']],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstagramUser::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'media_id' => 'Media ID',
            'user_id' => 'User ID',
            'comment_instagram_id' => 'Comment Instagram ID',
            'comment_text' => 'Comment Text',
            'comment_by_username' => 'Comment By Username',
            'comment_by_photo' => 'Comment By Photo',
            'comment_by_id' => 'Comment By ID',
            'comment_by_fullname' => 'Comment By Fullname',
            'comment_handled' => 'Comment Handled',
            'comment_deleted' => 'Comment Deleted',
            'comment_deleted_reason' => 'Comment Deleted Reason',
            'comment_datetime' => 'Comment Datetime',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentQueues()
    {
        return $this->hasMany(CommentQueue::className(), ['comment_id' => 'comment_id']);
    }
}
