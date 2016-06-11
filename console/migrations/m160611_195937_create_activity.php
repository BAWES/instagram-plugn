<?php

use yii\db\Migration;

/**
 * Handles the creation for table `activity`.
 * Has foreign keys to the tables:
 *
 * - `instagram_user`
 * - `agent`
 */
class m160611_195937_create_activity extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('activity', [
            'activity_id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'agent_id' => $this->bigInteger()->unsigned()->notNull(),
            'activity_detail' => $this->text()->notNull(),
            'activity_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-activity-user_id',
            'activity',
            'user_id'
        );

        // add foreign key for table `instagram_user`
        $this->addForeignKey(
            'fk-activity-user_id',
            'activity',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-activity-agent_id',
            'activity',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-activity-agent_id',
            'activity',
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
            'fk-activity-user_id',
            'activity'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-activity-user_id',
            'activity'
        );

        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-activity-agent_id',
            'activity'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-activity-agent_id',
            'activity'
        );

        $this->dropTable('activity');
    }
}
