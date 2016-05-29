<?php

use yii\db\Migration;

/**
 * Handles adding agent_id to table `comment`.
 */
class m160529_170956_add_agent_id_to_comment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comment', 'agent_id', $this->bigInteger()->unsigned()->after('user_id'));

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-comment-agent_id',
            'comment',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-comment-agent_id',
            'comment',
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
            'fk-comment-agent_id',
            'comment'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-comment-agent_id',
            'comment'
        );

        $this->dropColumn('comment', 'agent_id');
    }
}
