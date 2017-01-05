<?php

use yii\db\Migration;

class m170102_194119_update_invalid_db_field_names extends Migration
{
    public function up()
    {
        $this->renameColumn('billing', '2co_token', 'twoco_token');
        $this->renameColumn('billing', '2co_order_num', 'twoco_order_num');
        $this->renameColumn('billing', '2co_transaction_id', 'twoco_transaction_id');
        $this->renameColumn('billing', '2co_response_code', 'twoco_response_code');
        $this->renameColumn('billing', '2co_response_msg', 'twoco_response_msg');
    }

    public function down()
    {
        $this->renameColumn('billing', 'twoco_token', '2co_token');
        $this->renameColumn('billing', 'twoco_order_num', '2co_order_num');
        $this->renameColumn('billing', 'twoco_transaction_id', '2co_transaction_id');
        $this->renameColumn('billing', 'twoco_response_code', '2co_response_code');
        $this->renameColumn('billing', 'twoco_response_msg', '2co_response_msg');
    }

}
