<?php

use yii\db\Migration;

class m161209_210033_add_user_crawled_field extends Migration
{
    public function up()
    {
        $this->addColumn('instagram_user', 'user_initially_crawled', $this->boolean()->defaultValue(0));
        $this->addColumn('comment', 'comment_pushnotif_sent', $this->boolean()->defaultValue(0)->after('comment_notification_email_sent'));
    }

    public function down()
    {
        $this->dropColumn('instagram_user', 'user_initially_crawled');
        $this->dropColumn('comment', 'comment_pushnotif_sent');
    }

}
