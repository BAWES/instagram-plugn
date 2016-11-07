<?php

namespace api\models;

use Yii;
use common\models\Comment;
use api\models\InstagramUser;

/**
 * This is the model class for table "Agent".
 * It extends from \common\models\Agent but with custom functionality for API application module
 */
class Agent extends \common\models\Agent {

    /**
     * Get all Instagram accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged()
    {
        return $this->hasMany(InstagramUser::className(), ['user_id' => 'user_id'])
                ->via('agentAssignments');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return AgentToken::find()->where(['token_value' => $token])->with('agent')->one()->agent;
    }

}
