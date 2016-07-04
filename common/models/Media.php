<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "media".
 *
 * @property integer $media_id
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
 * @property Comment[] $comments
 * @property CommentQueue[] $commentQueues
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
     * Get comments along with the ones queued for this media
     * @return array live comments along with the queued comments merged
     */
    public function getCommentsWithQueued()
    {

        $postedConversation = Yii::$app->db->createCommand("
            SELECT comment.*,
                    agentj1.agent_name as agent_name,
                    agentj2.agent_name as handler_name,
                    agentj3.agent_name as deleter_name, 
                    'posted' as commentType
            FROM comment
            LEFT JOIN agent as agentj1 on comment.agent_id = agentj1.agent_id
            LEFT JOIN agent as agentj2 on comment.comment_handled_by = agentj2.agent_id
            LEFT JOIN agent as agentj3 on comment.comment_deleted_by = agentj3.agent_id
            WHERE (user_id=:accountId AND media_id=:mediaId)
            ORDER BY comment_datetime DESC")
            ->bindValue(':accountId', $this->user_id)
            ->bindValue(':mediaId', $this->media_id)
            ->queryAll();

        $queuedComments = Yii::$app->db->createCommand("
            SELECT queue_id as comment_id, comment_queue.agent_id, agent.agent_name as agent_name, media_id,
            queue_text as comment_text, :username as comment_by_username, :photo as comment_by_photo,
            :fullname as comment_by_fullname, queue_datetime as comment_datetime, 'queue' as commentType
            FROM comment_queue
            INNER JOIN agent on comment_queue.agent_id = agent.agent_id
            WHERE comment_id is NULL AND
            (user_id=:accountId AND media_id=:mediaId)
            ORDER BY queue_datetime DESC")
            ->bindValue(':accountId', $this->user->user_id)
            ->bindValue(':mediaId', $this->media_id)
            ->bindValue(':username', $this->user->user_name)
            ->bindValue(':fullname', $this->user->user_fullname)
            ->bindValue(':photo', $this->user->user_profile_pic)
            ->queryAll();

        $allCommentsWithQueued = ArrayHelper::merge($queuedComments, $postedConversation);

        //die(print_r($allCommentsWithQueued, true));

        return $allCommentsWithQueued;
    }

    /**
     * Get a list of unhandled comments
     * @return \yii\db\ActiveQuery
     */
    public function getUnhandledComments()
    {
        return $this->getComments()->where([
            'comment_handled' => Comment::HANDLED_FALSE,
        ]);
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
    public function getCommentQueues()
    {
        return $this->hasMany(CommentQueue::className(), ['media_id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
