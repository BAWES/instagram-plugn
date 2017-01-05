<?php

use yii\db\Migration;

class m170104_151509_fix_billing_user_relation extends Migration
{
    public function up()
    {
        // Drop foreign key
        $this->dropForeignKey(
            'fk-billing-user_id',
            'billing'
        );

        // Drop index
        $this->dropIndex(
            'idx-billing-user_id',
            'billing'
        );

        // Drop the Column user_id
        $this->dropColumn('billing', 'user_id');

        // Create column 'agency_id' in billing table
        $this->addColumn('billing', 'agency_id', $this->bigInteger()->notNull()->unsigned()->after('billing_id'));

        // creates index for column `agency_id` of billing table
        $this->createIndex(
            'idx-billing-agency_id',
            'billing',
            'agency_id'
        );

        // add foreign key for agency_id of table `agency`
        $this->addForeignKey(
            'fk-billing-agency_id',
            'billing',
            'agency_id',
            'agency',
            'agency_id',
            'CASCADE'
        );
    }

    public function down()
    {
        // Drop Foreign Key for `agency_id` of `agency`
        $this->dropForeignKey(
            'fk-billing-agency_id',
            'billing'
        );

        // Drop Index for column `agency_id` of billing table
        $this->dropIndex(
            'idx-billing-agency_id',
            'billing'
        );

        // Drop column 'agency_id' of billing table
        $this->dropColumn('billing', 'agency_id');

        // Add the user_id column again
        $this->addColumn('billing', 'user_id', $this->integer()->notNull()->unsigned()->after('billing_id'));

        // creates index for column `user_id` of billing table
        $this->createIndex(
            'idx-billing-user_id',
            'billing',
            'user_id'
        );

        // add foreign key for user_id of table `user`
        $this->addForeignKey(
            'fk-billing-user_id',
            'billing',
            'user_id',
            'instagram_user',
            'user_id',
            'CASCADE'
        );
    }

}
