<?php

use yii\db\Migration;

class m170413_182243_coupon extends Migration
{
    public function safeUp()
    {
        // Create Coupon Table
        $this->createTable('coupon', [
            'coupon_id' => $this->primaryKey(),
            'coupon_name' => $this->string()->notNull(),
            'coupon_user_limit' => $this->integer()->notNull(),
            'coupon_expires_at' => $this->date()->notNull(),
            'coupon_created_at' => $this->datetime()->notNull(),
            'coupon_updated_at' => $this->datetime()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('coupon');
    }
}
