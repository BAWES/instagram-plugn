<?php

use yii\db\Migration;

class m170106_150352_add_billing_notification extends Migration
{
    public function up()
    {
        $this->createTable('billing_notification', [
            'notification_id' => $this->bigPrimaryKey()->unsigned(),
            'billing_id' => $this->bigInteger()->unsigned()->notNull(), // which bill this belongs to
            'pricing_id' => $this->integer()->notNull(), // which pricing this belongs to

            'message_id' => $this->string(64),
            'message_type' => $this->string(64)->notNull(),
            'message_description' => $this->string(128)->notNull(),

            'vendor_id' => $this->string(64),
            'sale_id' => $this->string(64),
            'sale_date_placed' => $this->date(),
            'vendor_order_id' => $this->string(64),
            'invoice_id' => $this->string(64),
            'payment_type' => $this->string(64),
            'auth_exp' => $this->date(),
            'invoice_status' => $this->string(64),
            'fraud_status' => $this->string(64),
            'invoice_usd_amount' => $this->decimal(8,2),

            'customer_ip' => $this->string(128),
            'customer_ip_country' => $this->string(128),

            'item_id_1' => $this->string(64),
            'item_name_1' => $this->string(128),
            'item_usd_amount_1' => $this->decimal(8,2),
            'item_type_1' => $this->string(64),
            'item_rec_status_1' => $this->string(64),
            'item_rec_date_next_1' => $this->date(),
            'item_rec_install_billed_1' => $this->integer(),

            'timestamp' => $this->datetime(),
        ]);

        // creates index for column `billing_id`
        $this->createIndex(
            'idx-billing_notification-billing_id',
            'billing_notification',
            'billing_id'
        );
        // creates index for column `pricing_id`
        $this->createIndex(
            'idx-billing_notification-pricing_id',
            'billing_notification',
            'pricing_id'
        );

        // add foreign key for table `billing`
        $this->addForeignKey(
            'fk-billing_notification-billing_id',
            'billing_notification',
            'billing_id',
            'billing',
            'billing_id',
            'CASCADE'
        );
        // add foreign key for table `pricing`
        $this->addForeignKey(
            'fk-billing_notification-pricing_id',
            'billing_notification',
            'pricing_id',
            'pricing',
            'pricing_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign keys
        $this->dropForeignKey(
            'fk-billing_notification-billing_id',
            'billing_notification'
        );
        $this->dropForeignKey(
            'fk-billing_notification-pricing_id',
            'billing_notification'
        );

        // Drop indexes
        $this->dropIndex(
            'idx-billing_notification-billing_id',
            'billing_notification'
        );
        $this->dropIndex(
            'idx-billing_notification-pricing_id',
            'billing_notification'
        );

        $this->dropTable('billing_notification');
    }
}
