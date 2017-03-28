<?php

use yii\db\Migration;

class m170328_120648_name_fix extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE agent MODIFY COLUMN agent_name text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m170328_120648_name_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
