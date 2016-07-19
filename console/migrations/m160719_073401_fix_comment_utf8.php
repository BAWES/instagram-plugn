<?php

use yii\db\Migration;

class m160719_073401_fix_comment_utf8 extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE comment MODIFY COLUMN comment_by_fullname text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $this->execute("ALTER TABLE comment MODIFY COLUMN comment_by_username text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m160719_073401_fix_comment_utf8 cannot be reverted.\n";

        return false;
    }
}
