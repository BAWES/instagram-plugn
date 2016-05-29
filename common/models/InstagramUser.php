<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * InstagramUser model
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_fullname
 * @property string $user_auth_key
 * @property integer $user_status
 * @property integer $user_created_datetime
 * @property integer $user_updated_datetime
 * @property string $user_profile_pic
 * @property string $user_bio
 * @property string $user_website
 * @property integer $user_instagram_id
 * @property integer $user_media_count
 * @property integer $user_following_count
 * @property integer $user_follower_count
 * @property string $user_ig_access_token
 *
 * @property AgentAssignment[] $agentAssignments
 * @property Media[] $media
 * @property Record[] $records
 */
class InstagramUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INVALID_ACCESS_TOKEN = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%instagram_user}}';
    }

    public static function find()
    {
        return new InstagramUserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'user_created_datetime',
                'updatedAtAttribute' => 'user_updated_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_status', 'user_instagram_id', 'user_media_count', 'user_following_count', 'user_follower_count'], 'integer'],
            [['user_instagram_id'], 'required'],
            [['user_bio'], 'string'],
            ['user_status', 'default', 'value' => self::STATUS_ACTIVE],
            ['user_status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_INVALID_ACCESS_TOKEN]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_fullname' => 'User Fullname',
            'user_auth_key' => 'User Auth Key',
            'user_status' => 'User Status',
            'user_created_datetime' => 'User Created Datetime',
            'user_updated_datetime' => 'User Updated Datetime',
            'user_profile_pic' => 'User Profile Pic',
            'user_bio' => 'User Bio',
            'user_website' => 'User Website',
            'user_instagram_id' => 'User Instagram ID',
            'user_media_count' => 'User Media Count',
            'user_following_count' => 'User Following Count',
            'user_follower_count' => 'User Follower Count',
            'user_ig_access_token' => 'User Ig Access Token',
        ];
    }

    /**
     * Get Agent Assignment Records
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments()
    {
        return $this->hasMany(AgentAssignment::className(), ['user_id' => 'user_id']);
    }

    /**
     * Get all comments made on this user account
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'user_id']);
    }

    /**
     * Get Media Posted on Instagram by this user
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['user_id' => 'user_id'])
                    ->orderBy("media_created_datetime DESC");
    }

    /**
     * Records are day by day summaries of number of media/followers/following
     * @return \yii\db\ActiveQuery
     */
    public function getRecords()
    {
        return $this->hasMany(Record::className(), ['user_id' => 'user_id']);
    }

    /**
     * Get All Conversations belonging to this account
     * ie, "Get all comments for this user, group by commenter id,
     * order by datetime desc. WHERE commenter is not this user"
     * @return array of conversations from latest to oldest
     */
    public function getConversations()
    {
        $query = (new \yii\db\Query())
                ->select('t1.*')
                ->from(['t1' => 'comment'])
                ->join('JOIN', [
                    't2' => (new \yii\db\Query())
                            ->select(['comment_by_id', 'latest' => 'MAX(comment_datetime)'])
                            ->from('comment')
                            ->where(['!=', 'comment_by_id', $this->user_instagram_id])
                            ->andWhere(['user_id' => $this->user_id])
                            ->groupBy('comment_by_id')
                ],
                    //JOIN ON
                    't1.comment_by_id = t2.comment_by_id AND t1.comment_datetime = t2.latest'
                )
                ->orderBy('t1.comment_datetime DESC')
                ->all();

        return $query;
    }

    /**
     * Get conversation between this account and user
     * @param integer $commenterId the id of the commenter
     * @param string $commenterUsername the username of the commenter
     * @return array comments between this account and commenterId
     */
    public function getConversationWithUser($commenterId, $commenterUsername)
    {

        $postedConversation = Yii::$app->db->createCommand("
            SELECT *, 'posted' as commentType FROM comment WHERE (user_id=:accountId AND comment_by_id=:commenterId)
            OR (user_id=:accountId AND comment_text LIKE '%@".$commenterUsername."%')
            ORDER BY comment_datetime DESC")
            ->bindValue(':accountId', $this->user_id)
            ->bindValue(':commenterId', $commenterId)
            ->queryAll();

        $queuedComments = Yii::$app->db->createCommand("
            SELECT queue_id as comment_id, agent_id, media_id,
            queue_text as comment_text, :username as comment_by_username, :photo as comment_by_photo,
            :fullname as comment_by_fullname, queue_datetime as comment_datetime, 'queue' as commentType
            FROM comment_queue WHERE comment_id is NULL AND
            (user_id=:accountId AND queue_text LIKE '%@".$commenterUsername."%')
            ORDER BY queue_datetime DESC")
            ->bindValue(':accountId', $this->user_id)
            ->bindValue(':username', $this->user_name)
            ->bindValue(':fullname', $this->user_fullname)
            ->bindValue(':photo', $this->user_profile_pic)
            ->queryAll();

        $actualConversation = ArrayHelper::merge($queuedComments, $postedConversation);

        //die(print_r($actualConversation, true));

        return $actualConversation;
    }

    /**
     * Disable this users account for invalid access token
     */
    public function disableForInvalidToken(){
        $this->user_status = self::STATUS_INVALID_ACCESS_TOKEN;
        $this->save();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'user_status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->user_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->user_auth_key = Yii::$app->security->generateRandomString();
    }

}

/**
 * Custom queries for easier management of selection
 */
class InstagramUserQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['user_status' => InstagramUser::STATUS_ACTIVE]);
    }
}
