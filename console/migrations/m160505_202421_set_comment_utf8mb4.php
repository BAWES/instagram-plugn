<?php

use yii\db\Migration;

class m160505_202421_set_comment_utf8mb4 extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE comment MODIFY COLUMN comment_text text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m160505_202421_set_comment_utf8mb4 cannot be reverted.\n";

        return false;
    }
}
