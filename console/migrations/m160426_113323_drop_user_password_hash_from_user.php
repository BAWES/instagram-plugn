<?php

use yii\db\Migration;

class m160426_113323_drop_user_password_hash_from_user extends Migration
{
    public function up()
    {
        $this->dropColumn('user', 'user_password_hash');
        $this->dropColumn('user', 'user_password_reset_token');
    }

    public function down()
    {
        echo "m160426_113323_drop_user_password_hash_from_user cannot be reverted.\n";

        return false;
    }
}
