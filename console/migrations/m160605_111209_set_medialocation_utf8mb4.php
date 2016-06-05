<?php

use yii\db\Migration;

class m160605_111209_set_medialocation_utf8mb4 extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE media MODIFY COLUMN media_location_name text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m160605_111209_set_medialocation_utf8mb4 cannot be reverted.\n";

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
