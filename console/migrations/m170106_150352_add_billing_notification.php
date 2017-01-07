<?php

use yii\db\Migration;

class m170106_150352_add_billing_notification extends Migration
{
    public function up()
    {
        $this->createTable('invoice', [
            'invoice_id' => $this->bigPrimaryKey()->unsigned(),
            'billing_id' => $this->bigInteger()->unsigned()->notNull(), // which bill this belongs to
            'pricing_id' => $this->integer()->notNull(), // which pricing this belongs to
            'agency_id' => $this->bigInteger()->unsigned()->notNull(), // which agency this belongs to

            'message_id' => $this->string(64),
            'message_type' => $this->string(64)->notNull(),
            'message_description' => $this->string(128)->notNull(),

            'vendor_id' => $this->string(64),
            'sale_id' => $this->string(64),
            'sale_date_placed' => $this->date(),
            'vendor_order_id' => $this->string(64),
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

        // creates index for column `agency_id`
        $this->createIndex(
            'idx-invoice-agency_id',
            'invoice',
            'agency_id'
        );
        // creates index for column `billing_id`
        $this->createIndex(
            'idx-invoice-billing_id',
            'invoice',
            'billing_id'
        );
        // creates index for column `pricing_id`
        $this->createIndex(
            'idx-invoice-pricing_id',
            'invoice',
            'pricing_id'
        );

        // add foreign key for table `agency`
        $this->addForeignKey(
            'fk-invoice-agency_id',
            'invoice',
            'agency_id',
            'agency',
            'agency_id',
            'CASCADE'
        );
        // add foreign key for table `billing`
        $this->addForeignKey(
            'fk-invoice-billing_id',
            'invoice',
            'billing_id',
            'billing',
            'billing_id',
            'CASCADE'
        );
        // add foreign key for table `pricing`
        $this->addForeignKey(
            'fk-invoice-pricing_id',
            'invoice',
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
            'fk-invoice-billing_id',
            'invoice'
        );
        $this->dropForeignKey(
            'fk-invoice-pricing_id',
            'invoice'
        );
        $this->dropForeignKey(
            'fk-invoice-agency_id',
            'invoice'
        );

        // Drop indexes
        $this->dropIndex(
            'idx-invoice-agency_id',
            'invoice'
        );
        $this->dropIndex(
            'idx-invoice-billing_id',
            'invoice'
        );
        $this->dropIndex(
            'idx-invoice-pricing_id',
            'invoice'
        );

        $this->dropTable('invoice');
    }
}
