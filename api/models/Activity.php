<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "Activity".
 * It extends from \common\models\Activity but with custom functionality for Agent application module
 *
 */
class Activity extends \common\models\Activity {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        // Whitelisted fields to return via API
        return [
            'agent' => function($model) {
                return $this->agent->agent_name;
            },
            'account' => function($model) {
                return $this->user->user_name;
            },
            'action' => 'activity_detail',
            'datetime' => function($model) {
                return Yii::$app->formatter->asRelativeTime($this->activity_datetime);
            },
        ];
    }

    /**
     * Log the action performed by the agent on the user account
     * @param integer The instagram user id
     * @param string The message to log
     */
    public static function log($userId, $logMessage)
    {
        $activity = new static;
        $activity->user_id = $userId;
        $activity->agent_id = Yii::$app->user->identity->agent_id;
        $activity->activity_detail = $logMessage;
        $activity->save();
    }

}
