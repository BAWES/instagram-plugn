<?php

use yii\db\Migration;

class m160424_142045_allow_user_email_null extends Migration
{
    public function up()
    {
        $this->alterColumn("user", "user_email", $this->string());
    }

    public function down()
    {
        echo "m160424_142045_allow_user_email_null cannot be reverted.\n";

        return false;
    }

}
