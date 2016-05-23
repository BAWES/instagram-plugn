<?php

use yii\db\Migration;

/**
 * Handles adding userid to table `comment`.
 */
class m160523_125500_add_userid_to_comment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // Ignore Foreign Key constraints
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        // Add Column user_id
        $this->addColumn('comment', 'user_id', $this->integer()->unsigned()->notNull()->after('media_id'));

        // creates index for column `user_id`
        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id'
        );

        // add foreign key for table `instagram_user`
        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );

        // Stop Ignoring Foreign Key constraints
        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `instagram_user`
        $this->dropForeignKey(
            'fk-comment-user_id',
            'comment'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-comment-user_id',
            'comment'
        );

        $this->dropColumn('comment', 'user_id');
    }
}
