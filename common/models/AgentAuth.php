<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agent_auth".
 *
 * @property integer $auth_id
 * @property integer $agent_id
 * @property string $auth_source
 * @property string $auth_source_id
 *
 * @property Agent $agent
 */
class AgentAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_id', 'auth_source', 'auth_source_id'], 'required'],
            [['agent_id'], 'integer'],
            [['auth_source', 'auth_source_id'], 'string', 'max' => 255],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auth_id' => 'Auth ID',
            'agent_id' => 'Agent ID',
            'auth_source' => 'Auth Source',
            'auth_source_id' => 'Auth Source ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }
}
