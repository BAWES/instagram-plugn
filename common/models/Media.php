<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property string $media_id
 * @property integer $user_id
 * @property string $media_instagram_id
 * @property string $media_type
 * @property string $media_link
 * @property integer $media_num_comments
 * @property integer $media_num_likes
 * @property string $media_caption
 * @property string $media_image_lowres
 * @property string $media_image_thumb
 * @property string $media_image_standard
 * @property string $media_video_lowres
 * @property string $media_video_lowbandwidth
 * @property string $media_video_standard
 * @property string $media_location_name
 * @property string $media_location_longitude
 * @property string $media_location_latitude
 * @property string $media_created_datetime
 *
 * @property InstagramUser $user
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'media_instagram_id', 'media_type', 'media_link', 'media_num_comments', 'media_num_likes', 'media_created_datetime'], 'required'],
            [['user_id', 'media_num_comments', 'media_num_likes'], 'integer'],
            [['media_caption'], 'string'],
            [['media_type', 'media_link', 'media_image_lowres', 'media_image_thumb', 'media_image_standard', 'media_video_lowres', 'media_video_lowbandwidth', 'media_video_standard', 'media_location_name'], 'string'],
            [['media_instagram_id'], 'unique'],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstagramUser::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'media_id' => 'Media ID',
            'user_id' => 'User ID',
            'media_instagram_id' => 'Media Instagram ID',
            'media_type' => 'Media Type',
            'media_link' => 'Media Link',
            'media_num_comments' => 'Media Num Comments',
            'media_num_likes' => 'Media Num Likes',
            'media_caption' => 'Media Caption',
            'media_image_lowres' => 'Media Image Lowres',
            'media_image_thumb' => 'Media Image Thumb',
            'media_image_standard' => 'Media Image Standard',
            'media_video_lowres' => 'Media Video Lowres',
            'media_video_lowbandwidth' => 'Media Video Lowbandwidth',
            'media_video_standard' => 'Media Video Standard',
            'media_location_name' => 'Media Location Name',
            'media_location_longitude' => 'Media Location Longitude',
            'media_location_latitude' => 'Media Location Latitude',
            'media_created_datetime' => 'Media Created Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['media_id' => 'media_id'])->orderBy("comment_datetime DESC");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
