<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comment`.
 * Has foreign keys to the tables:
 *
 * - `media`
 */
class m160504_094753_create_comment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'comment_id' => $this->bigPrimaryKey()->unsigned(),
            'media_id' => $this->bigInteger()->notNull()->unsigned(),
            'comment_instagram_id' => $this->string()->notNull()->unique(),
            'comment_text' => $this->text(),
            'comment_by_username' => $this->string(),
            'comment_by_photo' => $this->string(),
            'comment_by_id' => $this->string(),
            'comment_by_fullname' => $this->string(),
            'comment_deleted' => $this->boolean()->defaultValue(0),
            'comment_deleted_reason' => $this->text(),
            'comment_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `comment_instagram_id`
        $this->createIndex(
            'idx-comment-comment_instagram_id',
            'comment',
            'comment_instagram_id'
        );

        // creates index for column `media_id`
        $this->createIndex(
            'idx-comment-media_id',
            'comment',
            'media_id'
        );

        // add foreign key for table `media`
        $this->addForeignKey(
            'fk-comment-media_id',
            'comment',
            'media_id',
            'media',
            'media_id',
            'CASCADE'
        );

        //Add FULLTEXT index to comment text column
        $this->execute("ALTER TABLE `comment` ADD FULLTEXT(`comment_text`)");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `media`
        $this->dropForeignKey(
            'fk-comment-media_id',
            'comment'
        );

        // drops index for column `media_id`
        $this->dropIndex(
            'idx-comment-media_id',
            'comment'
        );

        // drops index for column `comment_instagram_id`
        $this->dropIndex(
            'idx-comment-comment_instagram_id',
            'comment'
        );

        // drops index for column `comment_text`
        $this->dropIndex(
            'comment_text',
            'comment'
        );

        $this->dropTable('comment');
    }
}
