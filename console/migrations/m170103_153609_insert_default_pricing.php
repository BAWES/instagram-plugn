<?php

use yii\db\Migration;
use yii\db\Expression;

class m170103_153609_insert_default_pricing extends Migration
{
    public function up()
    {
        // Add Column for Quantity of Accounts
        $this->addColumn('pricing', 'pricing_account_quantity', $this->integer()->notNull()->after('pricing_price'));

        // Insert Default Data
        $this->batchInsert('pricing', ['pricing_title', 'pricing_features', 'pricing_price', 'pricing_account_quantity', 'pricing_created_at', 'pricing_updated_at'], [
            ['Starter', 'Single Instagram Account', '7', '1', new Expression('NOW()'), new Expression('NOW()')],
            ['Medium', "up to <span style='font-size:1.1em; font-weight:bold'>5</span> Instagram accounts", '29', '5', new Expression('NOW()'), new Expression('NOW()')],
            ['Advanced', "up to <span style='font-size:1.1em; font-weight:bold'>10</span> Instagram accounts", '57', '10', new Expression('NOW()'), new Expression('NOW()')],
            ['Giant', "up to <span style='font-size:1.1em; font-weight:bold'>30</span> Instagram accounts", '159', '30', new Expression('NOW()'), new Expression('NOW()')],
        ]);
    }

    public function down()
    {
        // Drop Column for Quantity
        $this->dropColumn('pricing', 'pricing_account_quantity');

        // Empty the Pricing Table
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable('pricing');
        $this->execute("SET foreign_key_checks = 1;");
    }
}
