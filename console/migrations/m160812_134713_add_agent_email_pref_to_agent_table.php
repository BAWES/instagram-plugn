<?php

use yii\db\Migration;

class m160812_134713_add_agent_email_pref_to_agent_table extends Migration
{
    public function up()
    {
        $this->addColumn('agent', 'agent_email_preference', $this->smallInteger()->notNull()->defaultValue(1)->after('agent_status'));
    }

    public function down()
    {
        $this->dropColumn('agent', 'agent_email_preference');
    }
}
