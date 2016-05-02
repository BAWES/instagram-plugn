<?php

use yii\db\Migration;

class m160424_141145_fix_user_columns extends Migration
{
    public function up()
    {
        $this->dropColumn('user', 'user_contact_number');
        $this->dropColumn('user', 'user_bio');
    }

    public function down()
    {
        echo "m160424_141145_fix_user_columns cannot be reverted.\n";

        return false;
    }

}
