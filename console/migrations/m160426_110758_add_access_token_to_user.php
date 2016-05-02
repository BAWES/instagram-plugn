<?php

use yii\db\Migration;

class m160426_110758_add_access_token_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'user_ig_access_token', $this->string());
    }

    public function down()
    {
        echo "m160426_110758_add_access_token_to_user cannot be reverted.\n";

        return false;
    }
}
