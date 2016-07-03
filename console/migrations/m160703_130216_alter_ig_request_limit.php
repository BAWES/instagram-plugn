<?php

use yii\db\Migration;

class m160703_130216_alter_ig_request_limit extends Migration
{
    public function up()
    {
        //Rename user_api_requests_this_hour to user_api_post_requests_this_hour
        $this->renameColumn('instagram_user', 'user_api_requests_this_hour', 'user_api_post_requests_this_hour');

        //Add user_api_delete_requests_this_hour
        $this->addColumn('instagram_user', 'user_api_delete_requests_this_hour', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        //Rename user_api_post_requests_this_hour to user_api_requests_this_hour
        $this->renameColumn('instagram_user', 'user_api_post_requests_this_hour', 'user_api_requests_this_hour');

        //Drop user_api_delete_requests_this_hour column
        $this->dropColumn('instagram_user', 'user_api_delete_requests_this_hour');
    }

}
