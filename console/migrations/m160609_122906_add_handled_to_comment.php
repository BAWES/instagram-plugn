<?php

use yii\db\Migration;

/**
 * Handles adding comment_handled to table `comment`.
 */
class m160609_122906_add_handled_to_comment extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comment', 'comment_handled', $this->boolean()->notNull()->defaultValue(0)->after('comment_by_fullname'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('comment', 'comment_handled');
    }
}
