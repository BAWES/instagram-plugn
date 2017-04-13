<?php

use yii\db\Migration;

class m170413_212421_coupon_usage extends Migration
{

    public function safeUp()
    {
        // Add Reward column to coupon, to know how many days they get as bonus trial
        $this->addColumn('coupon', 'coupon_reward_days', $this->integer()->notNull()->after('coupon_name'));

        // Create Coupon Table
        $this->createTable('coupon_used', [
            'used_id' => $this->primaryKey(),
            'coupon_id' => $this->integer()->notNull(),
            'agent_id' => $this->bigInteger()->unsigned()->notNull(),
            'used_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `agent_id`
        $this->createIndex(
            'idx-coupon_used-agent_id',
            'coupon_used',
            'agent_id'
        );
        // add foreign key for table `agent`
        $this->addForeignKey(
            'fk-coupon_used-agent_id',
            'coupon_used',
            'agent_id',
            'agent',
            'agent_id',
            'CASCADE'
        );

        // creates index for column `coupon_id`
        $this->createIndex(
            'idx-coupon_used-coupon_id',
            'coupon_used',
            'coupon_id'
        );
        // add foreign key for table `coupon`
        $this->addForeignKey(
            'fk-coupon_used-coupon_id',
            'coupon_used',
            'coupon_id',
            'coupon',
            'coupon_id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key for table `coupon`
        $this->dropForeignKey(
            'fk-coupon_used-coupon_id',
            'coupon_used'
        );
        // drops index for column `coupon_id`
        $this->dropIndex(
            'idx-coupon_used-coupon_id',
            'coupon_used'
        );

        // drops foreign key for table `agent`
        $this->dropForeignKey(
            'fk-coupon_used-agent_id',
            'coupon_used'
        );
        // drops index for column `agent_id`
        $this->dropIndex(
            'idx-coupon_used-agent_id',
            'coupon_used'
        );

        // Drop Coupon Usage
        $this->dropTable('coupon_used');

        // Drop Coupon reward column
        $this->dropColumn('coupon', 'coupon_reward_days');
    }
}
