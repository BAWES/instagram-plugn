<?php

use yii\db\Migration;

/**
 * Handles adding email_limit to table `agent`.
 */
class m160515_131039_add_email_limit_to_agent extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('agent', 'agent_limit_email', $this->datetime()->after('agent_status'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('agent', 'agent_limit_email');
    }
}
