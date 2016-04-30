<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "record".
 *
 * @property integer $record_id
 * @property integer $user_id
 * @property integer $record_media_count
 * @property integer $record_following_count
 * @property integer $record_follower_count
 * @property string $record_date
 *
 * @property User $user
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'record_date'], 'required'],
            [['user_id', 'record_media_count', 'record_following_count', 'record_follower_count'], 'integer'],
            [['record_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => 'Record ID',
            'user_id' => 'User ID',
            'record_media_count' => 'Record Media Count',
            'record_following_count' => 'Record Following Count',
            'record_follower_count' => 'Record Follower Count',
            'record_date' => 'Record Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
}
