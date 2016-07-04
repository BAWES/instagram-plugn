<?php

use yii\db\Migration;

class m160704_105911_comment_add_deletedby extends Migration
{
    public function up()
    {
        $this->addColumn('comment', 'comment_deleted_by', $this->bigInteger()->unsigned()->after('comment_deleted'));

        // creates index for column `comment_deleted_by`
        $this->createIndex(
            'idx-comment-comment_deleted_by',
            'comment',
            'comment_deleted_by'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-comment-comment_deleted_by',
            'comment',
            'comment_deleted_by',
            'agent',
            'agent_id',
            'CASCADE'
        );
    }

    public function down()
    {
        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-comment-comment_deleted_by',
            'comment'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-comment-comment_deleted_by',
            'comment'
        );

        $this->dropColumn('comment', 'comment_deleted_by');
    }
}
