<?php

use yii\db\Migration;

class m160703_175227_comment_add_handledby extends Migration
{
    public function up()
    {
        $this->addColumn('comment', 'comment_handled_by', $this->bigInteger()->unsigned()->after('comment_handled'));

        // creates index for column `comment_handled_by`
        $this->createIndex(
            'idx-comment-comment_handled_by',
            'comment',
            'comment_handled_by'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-comment-comment_handled_by',
            'comment',
            'comment_handled_by',
            'agent',
            'agent_id',
            'CASCADE'
        );
    }

    public function down()
    {
        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-comment-comment_handled_by',
            'comment'
        );

        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-comment-comment_handled_by',
            'comment'
        );

        $this->dropColumn('comment', 'comment_handled_by');
    }
}
