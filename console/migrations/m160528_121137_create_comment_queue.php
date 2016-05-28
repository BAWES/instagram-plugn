<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comment_queue`.
 * Has foreign keys to the tables:
 *
 * - `media`
 * - `instagram_user`
 * - `agent`
 * - `comment`
 */
class m160528_121137_create_comment_queue extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment_queue', [
            'queue_id' => $this->primaryKey(),
            'media_id' => $this->bigInteger()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'agent_id' => $this->bigInteger()->unsigned()->notNull(),
            'comment_id' => $this->bigInteger()->unsigned(),
            'queue_text' => $this->text(),
            'queue_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `media_id`
        $this->createIndex(
            'idx-comment_queue-media_id',
            'comment_queue',
            'media_id'
        );

        // add foreign key for table `media`
        $this->addForeignKey(
            'fk-comment_queue-media_id',
            'comment_queue',
            'media_id',
            'media',
            'media_id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-comment_queue-user_id',
            'comment_queue',
            'user_id'
        );

        // add foreign key for table `instagram_user`
        $this->addForeignKey(
            'fk-comment_queue-user_id',
            'comment_queue',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-comment_queue-agent_id',
            'comment_queue',
            'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-comment_queue-agent_id',
            'comment_queue',
            'agent_id',
            'agent',
            'agent_id',
            'CASCADE'
        );

        // creates index for column `comment_id`
        $this->createIndex(
            'idx-comment_queue-comment_id',
            'comment_queue',
            'comment_id'
        );

        // add foreign key for table `comment`
        $this->addForeignKey(
            'fk-comment_queue-comment_id',
            'comment_queue',
            'comment_id',
            'comment',
            'comment_id',
            'CASCADE'
        );


        //Set Text to UTF8MB4 for Emoji Text
        $this->execute("ALTER TABLE comment_queue MODIFY COLUMN queue_text text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `media`
        $this->dropForeignKey(
            'fk-comment_queue-media_id',
            'comment_queue'
        );

        // drops index for column `media_id`
        $this->dropIndex(
            'idx-comment_queue-media_id',
            'comment_queue'
        );

        // drops foreign key for table `instagram_user`
        $this->dropForeignKey(
            'fk-comment_queue-user_id',
            'comment_queue'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-comment_queue-user_id',
            'comment_queue'
        );

        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-comment_queue-agent_id',
            'comment_queue'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-comment_queue-agent_id',
            'comment_queue'
        );

        // drops foreign key for table `comment`
        $this->dropForeignKey(
            'fk-comment_queue-comment_id',
            'comment_queue'
        );

        // drops index for column `comment_id`
        $this->dropIndex(
            'idx-comment_queue-comment_id',
            'comment_queue'
        );

        $this->dropTable('comment_queue');
    }
}
