<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing`.
 */
class m161218_190531_create_billing_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('billing', [
            'billing_id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(), // which IG account this belongs to
            'country_id' => $this->integer()->notNull(), // which billing country
            'billing_name' => $this->string(128)->notNull(),
            'billing_email' => $this->string(64)->notNull(),
            'billing_city' => $this->string(64)->notNull(),
            'billing_zip_code' => $this->string(16),
            'billing_address_line1' => $this->string(64)->notNull(),
            'billing_address_line2' => $this->string(64),
            'billing_total' => $this->decimal(7,2)->notNull(),
            'billing_currency' => $this->string(12)->notNull(),
            '2co_token' => $this->string(),
            '2co_order_num' => $this->string(),
            '2co_transaction_id' => $this->string(),
            '2co_response_code' => $this->string(),
            '2co_response_msg' => $this->string(),
            'billing_datetime' => $this->datetime()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-billing-user_id',
            'billing',
            'user_id'
        );
        // creates index for column `country_id`
        $this->createIndex(
            'idx-billing-country_id',
            'billing',
            'country_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-billing-user_id',
            'billing',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );
        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-billing-country_id',
            'billing',
            'country_id',
            'country',
            'country_id',
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
            'fk-billing-user_id',
            'billing'
        );
        $this->dropForeignKey(
            'fk-billing-country_id',
            'billing'
        );

        // Drop indexes
        $this->dropIndex(
            'idx-billing-user_id',
            'billing'
        );
        $this->dropIndex(
            'idx-billing-country_id',
            'billing'
        );

        $this->dropTable('billing');
    }
}
