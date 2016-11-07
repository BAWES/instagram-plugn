<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "AgentToken".
 * It extends from \common\models\AgentToken but with custom functionality for Api application module
 *
 */
class AgentToken extends \common\models\AgentToken {

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

}
