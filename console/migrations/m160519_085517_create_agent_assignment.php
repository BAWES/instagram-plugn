<?php

use yii\db\Migration;

/**
 * Handles the creation for table `agent_assignment`.
 * Has foreign keys to the tables:
 *
 * - `instagram_user`
 * - `agent`
 */
class m160519_085517_create_agent_assignment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('agent_assignment', [
            'assignment_id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'agent_id' => $this->bigInteger()->unsigned(),
            'agent_email' => $this->string()->notNull(),
            'assignment_created_at' => $this->datetime()->notNull(),
            'assignment_updated_at' => $this->datetime()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-agent_assignment-user_id',
            'agent_assignment',
            'user_id'
        );

        // add foreign key for table `instagram_user`
        $this->addForeignKey(
            'fk-agent_assignment-user_id',
            'agent_assignment',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-agent_assignment-agent_id',
            'agent_assignment',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-agent_assignment-agent_id',
            'agent_assignment',
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
        // drops foreign key for table `instagram_user`
        $this->dropForeignKey(
            'fk-agent_assignment-user_id',
            'agent_assignment'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-agent_assignment-user_id',
            'agent_assignment'
        );

        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-agent_assignment-agent_id',
            'agent_assignment'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-agent_assignment-agent_id',
            'agent_assignment'
        );

        $this->dropTable('agent_assignment');
    }
}
