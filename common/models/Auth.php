<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property integer $auth_id
 * @property integer $auth_user_id
 * @property string $auth_source
 * @property string $auth_source_id
 *
 * @property User $authUser
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_user_id', 'auth_source', 'auth_source_id'], 'required'],
            [['auth_user_id'], 'integer'],
            [['auth_source', 'auth_source_id'], 'string', 'max' => 255],
            [['auth_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['auth_user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auth_id' => 'Auth ID',
            'auth_user_id' => 'Auth User ID',
            'auth_source' => 'Auth Source',
            'auth_source_id' => 'Auth Source ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'auth_user_id']);
    }
}
