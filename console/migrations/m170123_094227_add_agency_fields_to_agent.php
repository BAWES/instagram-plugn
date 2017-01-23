<?php

use yii\db\Migration;

class m170123_094227_add_agency_fields_to_agent extends Migration
{
    public function up()
    {
        // Remove Trial days from IG user
        $this->dropColumn('instagram_user', 'user_trial_days');

        // Add Trial Days column to agent
        $this->addColumn('agent', 'agent_trial_days', $this->integer()->notNull()->defaultValue(14)->after('agent_status'));
        // Add Agent Billing Deadline
        $this->addColumn('agent', 'agent_billing_active_until', $this->date()->notNull()->after('agent_trial_days'));
    }

    public function down()
    {
        // Re-add trial days to IG user
        $this->addColumn('instagram_user', 'user_trial_days', $this->integer()->notNull()->defaultValue(14));

        // Remove Trial days column from agent
        $this->dropColumn('agent', 'agent_trial_days');
        // Remove Agent Billing Deadline
        $this->dropColumn('agent', 'agent_billing_active_until');
    }
}
