<?php

use yii\db\Migration;

/**
 * Handles the creation for table `agent`.
 */
class m160514_130322_create_agent_auth extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('agent', [
            'agent_id' => $this->bigPrimaryKey()->unsigned(),
            'agent_email' => $this->string()->notNull()->unique(),
            'agent_auth_key' => $this->string(32)->notNull(),
            'agent_password_hash' => $this->string(), //Can be null if they're using social login
            'agent_password_reset_token' => $this->string()->unique(),

            'agent_status' => $this->smallInteger()->notNull()->defaultValue(10),
            'agent_created_at' => $this->integer()->notNull(),
            'agent_updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('agent_auth', [
            'auth_id' => $this->bigPrimaryKey()->unsigned(),
            'agent_id' => $this->bigInteger()->unsigned()->notNull(),
            'auth_source' => $this->string()->notNull(),
            'auth_source_id' => $this->string()->notNull()
        ]);

        // creates index for column `agent_id` in agent_auth table
        $this->createIndex(
            'idx-auth-agent_id',
            'agent_auth',
            'agent_id'
        );

        // add foreign key for `agent_id` in table `agent`
        $this->addForeignKey(
            'fk-auth-agent_id',
            'agent_auth',
            'agent_id',
            'agent',
            'agent_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `agent_auth`
        $this->dropForeignKey(
            'fk-auth-agent_id',
            'agent_auth'
        );

        // drops index for column `agent_auth`
        $this->dropIndex(
            'idx-auth-agent_id',
            'agent_auth'
        );

        //Drop Tables
        $this->dropTable('agent');
        $this->dropTable('agent_auth');
    }
}
