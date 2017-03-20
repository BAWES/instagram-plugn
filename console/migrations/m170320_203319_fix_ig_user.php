<?php

use yii\db\Migration;

class m170320_203319_fix_ig_user extends Migration
{
    public function up()
    {
        $this->alterColumn("instagram_user", "user_instagram_id", $this->string()->notNull());
    }

    public function down()
    {
        $this->alterColumn("instagram_user", "user_instagram_id", $this->integer()->unsigned()->notNull());
    }
}
