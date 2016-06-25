<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "agent_assignment".
 *
 * @property integer $assignment_id
 * @property integer $user_id
 * @property integer $agent_id
 * @property string $assignment_agent_email
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
            [['assignment_agent_email'], 'required'],
            [['assignment_agent_email'], 'string', 'max' => 255],
            [['assignment_agent_email'], 'email'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'assignment_created_at',
                'updatedAtAttribute' => 'assignment_updated_at',
                'value' => new Expression('NOW()'),
            ],
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
            'assignment_agent_email' => 'Email',
            'assignment_created_at' => 'Assignment Created At',
            'assignment_updated_at' => 'Assignment Updated At',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                //If agent with this email exists, set agent_id to his id
                $agent = Agent::findOne(['agent_email'=>$this->assignment_agent_email]);
                if($agent){
                    $this->agent_id = $agent->agent_id;
                }
            }

            return true;
        }
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
