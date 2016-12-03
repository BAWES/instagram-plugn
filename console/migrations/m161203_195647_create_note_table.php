<?php

use yii\db\Migration;

/**
 * Handles the creation of table `note`.
 */
class m161203_195647_create_note_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('note', [
            'note_id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(), //which IG account this belongs to
            'note_about_username' => $this->string()->notNull(), //Username we're writing a note about
            'note_title' => $this->string(),
            'note_text' => $this->text(),
            'created_by_agent_id' => $this->bigInteger()->unsigned(),
            'updated_by_agent_id' => $this->bigInteger()->unsigned(),
            'note_created_datetime' => $this->datetime()->notNull(),
            'note_updated_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `note_about_username`
        $this->createIndex(
            'idx-note-note_about_username',
            'note',
            'note_about_username'
        );
        // creates index for column `user_id`
        $this->createIndex(
            'idx-note-user_id',
            'note',
            'user_id'
        );
        // creates index for column `created_by_agent_id`
        $this->createIndex(
            'idx-note-created_by_agent_id',
            'note',
            'created_by_agent_id'
        );
        // creates index for column `updated_by_agent_id`
        $this->createIndex(
            'idx-note-updated_by_agent_id',
            'note',
            'updated_by_agent_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-note-user_id',
            'note',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );
        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-note-created_by_agent_id',
            'note',
            'created_by_agent_id',
            'agent',
            'agent_id',
            'CASCADE'
        );
        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-note-updated_by_agent_id',
            'note',
            'updated_by_agent_id',
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
        // drops foreign keys
        $this->dropForeignKey(
            'fk-note-user_id',
            'note'
        );
        $this->dropForeignKey(
            'fk-note-created_by_agent_id',
            'note'
        );
        $this->dropForeignKey(
            'fk-note-updated_by_agent_id',
            'note'
        );


        // Drop indexes
        $this->dropIndex(
            'idx-note-user_id',
            'note'
        );
        $this->dropIndex(
            'idx-note-created_by_agent_id',
            'note'
        );
        $this->dropIndex(
            'idx-note-updated_by_agent_id',
            'note'
        );

        // Drop table
        $this->dropTable('note');
    }
}
