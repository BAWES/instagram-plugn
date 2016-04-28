<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * User model
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
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INVALID_ACCESS_TOKEN = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['user_id' => 'user_id']);
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
class UserQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['user_status' => User::STATUS_ACTIVE]);
    }
}
