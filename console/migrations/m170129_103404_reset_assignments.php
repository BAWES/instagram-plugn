<?php

use yii\db\Migration;

class m170129_103404_reset_assignments extends Migration
{
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");

        // Remove all admins and disable existing accounts
        $this->execute("UPDATE instagram_user SET agent_id=NULL, user_status=20;");
        // Delete all agent assignments
        $this->truncateTable('agent_assignment');

        $this->execute("SET foreign_key_checks = 1;");
    }

    public function down()
    {
    }
}
