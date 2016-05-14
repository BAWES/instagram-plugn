<?php

use yii\db\Migration;

class m160513_122208_rename_user extends Migration
{
    public function up()
    {
        $this->renameTable('user', 'instagram_user');
    }

    public function down()
    {
        $this->renameTable('instagram_user', 'user');
    }

}
