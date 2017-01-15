<?php

use yii\db\Migration;

class m170107_152456_invoice_datetime_add extends Migration
{
    public function up()
    {
        $this->addColumn('invoice', 'invoice_created_at', $this->datetime()->notNull());
        $this->addColumn('invoice', 'invoice_updated_at', $this->datetime()->notNull());
    }

    public function down()
    {
        $this->dropColumn('invoice', 'invoice_created_at');
        $this->dropColumn('invoice', 'invoice_updated_at');
    }

}
