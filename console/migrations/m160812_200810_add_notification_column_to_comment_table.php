<?php

use yii\db\Migration;

/**
 * Handles adding notification to table `comment`.
 */
class m160812_200810_add_notification_column_to_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('comment', 'comment_notification_email_sent', $this->boolean()->notNull()->defaultValue(0)->after('comment_deleted_reason'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('comment', 'comment_notification_email_sent');
    }
}
