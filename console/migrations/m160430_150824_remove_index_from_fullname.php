<?php

use yii\db\Migration;

class m160430_150824_remove_index_from_fullname extends Migration
{
    public function up()
    {
        $this->dropIndex(
            'email', //name of index
            'user' //table name
        );
    }

    public function down()
    {
        echo "m160430_150824_remove_index_from_fullname cannot be reverted.\n";

        return false;
    }
}
