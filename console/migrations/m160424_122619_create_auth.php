<?php

use yii\db\Migration;

class m160424_122619_create_auth extends Migration
{
    public function up()
    {
        $this->createTable('auth', [
            'auth_id' => $this->primaryKey(),
            'auth_user_id' => $this->integer()->unsigned()->notNull(),
            'auth_source' => $this->string()->notNull(),
            'auth_source_id' => $this->string()->notNull()
        ]);

        // creates index for column `auth_user_id`
        $this->createIndex(
            'idx-auth-auth_user_id',
            'auth',
            'auth_user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-auth-auth_user_id',
            'auth',
            'auth_user_id',
            'user',
            'user_id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('auth');
    }
}
