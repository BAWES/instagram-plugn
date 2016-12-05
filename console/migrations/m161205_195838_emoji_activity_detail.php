<?php

use yii\db\Migration;

class m161205_195838_emoji_activity_detail extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE activity MODIFY COLUMN activity_detail text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m161205_195838_emoji_activity_detail cannot be reverted.\n";

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
