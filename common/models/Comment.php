<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property string $comment_id
 * @property string $media_id
 * @property string $comment_instagram_id
 * @property string $comment_text
 * @property string $comment_by_username
 * @property string $comment_by_photo
 * @property string $comment_by_id
 * @property string $comment_by_fullname
 * @property integer $comment_deleted
 * @property string $comment_deleted_reason
 * @property string $comment_datetime
 *
 * @property Media $media
 */
class Comment extends \yii\db\ActiveRecord
{
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
            [['media_id', 'comment_instagram_id', 'comment_datetime'], 'required'],
            [['media_id', 'comment_deleted'], 'integer'],
            [['comment_text', 'comment_deleted_reason'], 'string'],
            [['comment_datetime'], 'safe'],
            [['comment_instagram_id', 'comment_by_username', 'comment_by_photo', 'comment_by_id', 'comment_by_fullname'], 'string', 'max' => 255],
            [['comment_instagram_id'], 'unique'],
            //[['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'media_id']],
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
            'comment_instagram_id' => 'Comment Instagram ID',
            'comment_text' => 'Comment Text',
            'comment_by_username' => 'Comment By Username',
            'comment_by_photo' => 'Comment By Photo',
            'comment_by_id' => 'Comment By ID',
            'comment_by_fullname' => 'Comment By Fullname',
            'comment_deleted' => 'Comment Deleted',
            'comment_deleted_reason' => 'Comment Deleted Reason',
            'comment_datetime' => 'Comment Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['media_id' => 'media_id']);
    }
}
