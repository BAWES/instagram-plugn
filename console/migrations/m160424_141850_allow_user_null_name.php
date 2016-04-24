<?php

use yii\db\Migration;

class m160424_141850_allow_user_null_name extends Migration
{
    public function up()
    {
        $this->alterColumn("user", "user_name", $this->string());
    }

    public function down()
    {
        echo "m160424_141850_allow_user_null_name cannot be reverted.\n";

        return false;
    }
}
