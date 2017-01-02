<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing_options`.
 */
class m170102_180120_create_billing_options_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // Create Pricing Table
        $this->createTable('pricing', [
            'pricing_id' => $this->primaryKey(),
            'pricing_title' => $this->string()->notNull(),
            'pricing_features' => $this->text(),
            'pricing_price' => $this->decimal(7,2)->notNull(),
            'pricing_created_at' => $this->datetime()->notNull(),
            'pricing_updated_at' => $this->datetime()->notNull(),
        ]);

        // Create foreign key in billing table to link bill to price option
        $this->addColumn('billing', 'pricing_id', $this->integer()->after('user_id'));

        // creates index for column `pricing_id`
        $this->createIndex(
            'idx-billing-pricing_id',
            'billing',
            'pricing_id'
        );

        // add foreign key for table `pricing`
        $this->addForeignKey(
            'fk-billing-pricing_id',
            'billing',
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
        // drops foreign key for table `pricing`
        $this->dropForeignKey(
            'fk-billing-pricing_id',
            'billing'
        );

        // drops index for column `pricing_id`
        $this->dropIndex(
            'idx-billing-pricing_id',
            'billing'
        );

        // Drop foreignkey column
        $this->dropColumn('billing', 'pricing_id');

        // Drop Pricing Table
        $this->dropTable('pricing');
    }
}
