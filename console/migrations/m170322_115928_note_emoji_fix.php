<?php

use yii\db\Migration;

class m170322_115928_note_emoji_fix extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE note MODIFY COLUMN 	note_text text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m170322_115928_note_emoji_fix cannot be reverted.\n";

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
