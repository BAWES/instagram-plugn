<?php

use yii\db\Migration;

/**
 * Handles adding api_columns to table `instagram_user`.
 */
class m160601_171943_add_api_columns_to_instagram_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('instagram_user', 'user_api_rolling_datetime', $this->datetime()->notNull());
        $this->addColumn('instagram_user', 'user_api_requests_this_hour', $this->integer()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('instagram_user', 'user_api_rolling_datetime');
        $this->dropColumn('instagram_user', 'user_api_requests_this_hour');
    }
}
