<?php

use yii\db\Migration;

class m160426_105454_merge_auth_user extends Migration
{
    public function up()
    {
        //Remove Auth Table
        $this->dropTable('auth');
        
        //Add the Instagram Column
        $this->addColumn('user', 'user_instagram_id', $this->integer()->unsigned()->notNull());
        
        //Creates Index for column `user_instagram_id`
        $this->createIndex(
            'idx-user-user_instagram_id',
            'user',
            'user_instagram_id'
        );
        
        //Add other columns for User
        $this->addColumn('user', 'user_media_count', $this->integer());
        $this->addColumn('user', 'user_following_count', $this->integer());
        $this->addColumn('user', 'user_follower_count', $this->integer());
    }

    public function down()
    {
        echo "m160426_105454_merge_auth_user cannot be reverted.\n";

        return false;
    }
}
