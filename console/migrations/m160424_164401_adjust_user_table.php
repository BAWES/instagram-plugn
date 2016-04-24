<?php

use yii\db\Migration;

class m160424_164401_adjust_user_table extends Migration
{
    public function up()
    {
        //Rename Email column to fullname
        $this->renameColumn("user", "user_email", "user_fullname");

        //Add Missing Columns to User table
        $this->addColumn('user', 'user_profile_pic', $this->string());
        $this->addColumn('user', 'user_bio', $this->text());
        $this->addColumn('user', 'user_website', $this->string());
    }

    public function down()
    {
        echo "m160424_164401_adjust_user_table cannot be reverted.\n";

        return false;
    }
}
