<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agent_assignment".
 *
 * @property integer $assignment_id
 * @property integer $user_id
 * @property string $agent_id
 * @property string $agent_email
 * @property string $assignment_created_at
 * @property string $assignment_updated_at
 *
 * @property Agent $agent
 * @property InstagramUser $user
 */
class AgentAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'agent_email', 'assignment_created_at', 'assignment_updated_at'], 'required'],
            [['user_id', 'agent_id'], 'integer'],
            [['assignment_created_at', 'assignment_updated_at'], 'safe'],
            [['agent_email'], 'string', 'max' => 255],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstagramUser::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assignment_id' => 'Assignment ID',
            'user_id' => 'User ID',
            'agent_id' => 'Agent ID',
            'agent_email' => 'Agent Email',
            'assignment_created_at' => 'Assignment Created At',
            'assignment_updated_at' => 'Assignment Updated At',
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
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
