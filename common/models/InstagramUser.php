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
use common\models\Comment;

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
 * @property string $user_api_rolling_datetime
 * @property integer $user_api_post_requests_this_hour
 * @property integer $user_api_delete_requests_this_hour
 *
 * @property Activity[] $activities
 * @property AgentAssignment[] $agentAssignments
 * @property Agent[] $agents
 * @property Comment[] $comments
 * @property CommentQueue[] $commentQueues
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
            [['user_status', 'user_instagram_id', 'user_media_count', 'user_following_count', 'user_follower_count', 'user_api_post_requests_this_hour', 'user_api_delete_requests_this_hour'], 'integer'],
            [['user_instagram_id'], 'required'],
            [['user_bio'], 'string'],
            ['user_status', 'default', 'value' => self::STATUS_ACTIVE],
            ['user_api_rolling_datetime', 'default', 'value' => new Expression('NOW()')],
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
            'user_api_rolling_datetime' => 'User Api Rolling Datetime',
            'user_api_post_requests_this_hour' => 'POST Requests This Hour',
            'user_api_delete_requests_this_hour' => 'Delete Requests This Hour',
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
     * Get Agents assigned to this Instagram user
     * @return \yii\db\ActiveQuery
     */
    public function getAgents()
    {
        return $this->hasMany(Agent::className(), ['agent_id' => 'agent_id'])
                    ->via('agentAssignments');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['user_id' => 'user_id'])
                    ->orderBy("activity_datetime DESC");
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
     * @return \yii\db\ActiveQuery
     */
    public function getCommentQueues()
    {
        return $this->hasMany(CommentQueue::className(), ['user_id' => 'user_id'])
                    ->orderBy("queue_datetime ASC");
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
        return $this->hasMany(Record::className(), ['user_id' => 'user_id'])
                    ->orderBy("record_date DESC");
    }

    /**
     * Get All Conversations belonging to this account
     * ie, "Get all comments for this user, group by commenter id,
     * order by datetime desc. WHERE commenter is not this user"
     * @return array of conversations from latest to oldest
     */
    public function getConversations()
    {
        //Unhandled Count will contain the number of messages within conversation that haven't
        //been handled (which arent from us)

        $query = (new \yii\db\Query())
                ->select(['t1.*', 't3.unhandledCount'])
                ->from(['t1' => 'comment'])
                ->join('JOIN', [ //First join is to reveal contents of the latest comment from each conversation
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
                ->join('LEFT JOIN', [ //Second join is to get number of Unhandled messages within each conversation
                    't3' => (new \yii\db\Query())
                            ->select(['comment_by_id', 'unhandledCount' => 'count(*)'])
                            ->from('comment')
                            ->where(['!=', 'comment_by_id', $this->user_instagram_id])
                            ->andWhere([
                                'user_id' => $this->user_id,
                                'comment_handled' => Comment::HANDLED_FALSE,
                            ])
                            ->groupBy('comment_by_id')
                ],
                    //JOIN ON
                    't1.comment_by_id = t3.comment_by_id'
                )
                ->orderBy('t1.comment_datetime DESC')
                ->all();
                //->createCommand()->rawSql; die($query);

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
        $commentDisplayOrder = "ASC";

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
            WHERE (user_id=:accountId AND comment_by_id=:commenterId)
            OR (user_id=:accountId AND comment_text LIKE '%@".$commenterUsername."%')
            ORDER BY comment_datetime $commentDisplayOrder")
            ->bindValue(':accountId', $this->user_id)
            ->bindValue(':commenterId', $commenterId)
            ->queryAll();

        $queuedComments = Yii::$app->db->createCommand("
            SELECT queue_id as comment_id, comment_queue.agent_id, agent.agent_name as agent_name, media_id,
            queue_text as comment_text, :username as comment_by_username, :photo as comment_by_photo,
            :fullname as comment_by_fullname, queue_datetime as comment_datetime, 'queue' as commentType
            FROM comment_queue
            INNER JOIN agent on comment_queue.agent_id = agent.agent_id
            WHERE comment_id is NULL AND
            (user_id=:accountId AND queue_text LIKE '%@".$commenterUsername."%')
            ORDER BY queue_datetime $commentDisplayOrder")
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
     * Increment the number of api post calls made this hour
     */
    public function incrementNumApiPostCallsThisHour(){
        $this->updateCounters(['user_api_post_requests_this_hour' => 1]);
    }

    /**
     * Increment the number of api delete calls made this hour
     */
    public function incrementNumApiDeleteCallsThisHour(){
        $this->updateCounters(['user_api_delete_requests_this_hour' => 1]);
    }

    /**
     * Send a notification email to all agents assigned to this account
     * Notification email includes number of new comments along with a mini
     * summary of account activity
     */
    public function sendAgentsNotificationEmail(){
        //Get comments on this account which haven't been sent as notifications yet
        $comments = $this->getComments()->where([
            'comment_notification_email_sent' => Comment::NOTIFICATION_EMAIL_SENT_FALSE
            ])->orderBy('comment_datetime DESC')->asArray()->all();

        $numComments = count($comments);
        if($numComments > 0)
        {
            //Get Agents with Email Notifications Enabled
            $agents = $this->getAgents()->where([
                'agent_email_preference' => \common\models\Agent::PREF_EMAIL_DAILY,
                'agent_status' => \common\models\Agent::STATUS_ACTIVE,
            ])->asArray()->all();

            $numAgents = count($agents);
            if($numAgents > 0)
            {
                //Get Recent Account Activity
                $activities = $this->getActivities()->with('agent')->limit(5)->asArray()->all();

                $subject = Yii::t('frontend', 'You have {n,plural,=1{a new comment} other{# new comments}} on @{accountName}', ['n' => $numComments, 'accountName' => $this->user_name]);

                //Send email to all these agents with summary
                foreach($agents as $agent){
                    Yii::$app->mailer->compose([
                            'html' => 'frontend/agentNotification',
                                ], [
                            'accountName' => $this->user_name,
                            'numComments' => $numComments,
                            'comments' => $comments,
                            'activities' => $activities
                        ])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                        ->setTo($agent['agent_email'])
                        ->setSubject($subject)
                        ->send();
                }
            }

            //Mark all comments for this account as sent via email
            Comment::updateAll([
                'comment_notification_email_sent' => Comment::NOTIFICATION_EMAIL_SENT_TRUE
            ],[
                'user_id' => $this->user_id
            ]);

        }
    }

    /**
     * Broadcast Email Notifications to agents of all Active Instagram Accounts
     */
    public static function broadcastEmailNotifications()
    {
        $activeAccounts = static::find()->active();
        foreach($activeAccounts->each(50) as $account){
            $account->sendAgentsNotificationEmail();
        }
    }

    /**
     * Disable this users account for invalid access token
     */
    public function disableForInvalidToken(){
        $this->user_status = self::STATUS_INVALID_ACCESS_TOKEN;
        $this->save(false);

        /**
         * Send an email to all active agents on this account that they need to
         * re-login / authorise to enable the account
         */
        foreach($this->agents as $agent){
            Yii::$app->mailer->compose([
                        'html' => 'frontend/tokenExpired',
                            ], [
                        'accountFullName' => $this->user_fullname,
                        'accountName' => $this->user_name,
                        'accountPhoto' => $this->user_profile_pic,
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                    ->setTo($agent->agent_email)
                    ->setSubject('Problem connecting to your Instagram account @'.$this->user_name)
                    ->send();
        }


        Yii::error("[Account Disabled] Instagram account @".$this->user_name." disabled for Invalid Access Token and its agents have been notified", __METHOD__);
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
