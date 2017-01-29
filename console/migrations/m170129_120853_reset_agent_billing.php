<?php

use yii\db\Migration;

class m170129_120853_reset_agent_billing extends Migration
{
    public function up()
    {
        $this->execute("UPDATE agent SET agent_billing_active_until='2017-01-25';");
    }

    public function down()
    {
    }
}
