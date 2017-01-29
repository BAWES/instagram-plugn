<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "AgentAssignment".
 * It extends from \common\models\AgentAssignment but with custom functionality for API application module
 */
class AgentAssignment extends \common\models\AgentAssignment {

    /**
     * @inheritdoc
     */
    public function fields()
    {
        // Whitelisted fields to return
        return [
            'assignment_id',
            'email' => 'assignment_agent_email',
            'dateAssigned' => function($model) {
                return Yii::$app->formatter->asDate($model->assignment_created_at);
            }
        ];
    }

}
