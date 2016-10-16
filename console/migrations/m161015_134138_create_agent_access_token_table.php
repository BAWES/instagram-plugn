<?php

use yii\db\Migration;

/**
 * Handles the creation for table `agent_access_token_table`.
 * Has foreign keys to the tables:
 *
 * - `agent`
 */
class m161015_134138_create_agent_access_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('agent_token', [
            'token_id' => $this->bigPrimaryKey()->unsigned(),
            'agent_id' => $this->bigInteger()->unsigned()->notNull(),
            'token_value' => $this->string()->notNull(),
            'token_device' => $this->string(),
            'token_device_id' => $this->string(),
            'token_status' => $this->smallInteger()->notNull(),
            'token_last_used_datetime' => $this->datetime(),
            'token_expiry_datetime' => $this->datetime(),
            'token_created_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-agent_token-agent_id',
            'agent_token',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-agent_token-agent_id',
            'agent_token',
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
        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-agent_token-agent_id',
            'agent_token'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-agent_token-agent_id',
            'agent_token'
        );

        $this->dropTable('agent_token');
    }
}
