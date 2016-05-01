<?php

use yii\db\Migration;

class m160501_113156_fix_utf8 extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE media MODIFY COLUMN media_caption text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $this->execute("ALTER TABLE user MODIFY COLUMN user_fullname varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $this->execute("ALTER TABLE user MODIFY COLUMN user_bio text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    public function down()
    {
        echo "m160501_113156_fix_utf8 cannot be reverted.\n";

        //return false;
    }
}
