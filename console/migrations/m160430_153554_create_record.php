<?php

use yii\db\Migration;

/**
 * Handles the creation for table `record`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m160430_153554_create_record extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('record', [
            'record_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'record_media_count' => $this->integer()->defaultValue(0),
            'record_following_count' => $this->integer()->defaultValue(0),
            'record_follower_count' => $this->integer()->defaultValue(0),
            'record_date' => $this->date()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-record-user_id',
            'record',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-record-user_id',
            'record',
            'user_id',
            'user',
            'user_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-record-user_id',
            'record'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-record-user_id',
            'record'
        );

        $this->dropTable('record');
    }
}
