<?php

use yii\db\Migration;

class m170123_100531_remove_agency_dependencies extends Migration
{
    public function up()
    {
        /**
         * Remove instagram_user dependency on agency_id
         */
        // drops foreign key for table `agency`
        $this->dropForeignKey(
            'fk-instagram_user-agency_id',
            'instagram_user'
        );
        // drops index for column `agency_id`
        $this->dropIndex(
            'idx-instagram_user-agency_id',
            'instagram_user'
        );
        $this->dropColumn('instagram_user', 'agency_id');

        /**
         *  Add instagram_user dependency to agent_id
         */
         $this->addColumn('instagram_user', 'agent_id', $this->bigInteger()->unsigned()->after('user_id'));
         // creates index for column `agent_id`
         $this->createIndex(
             'idx-instagram_user-agent_id',
             'instagram_user',
             'agent_id'
         );
         // add foreign key for table `agency`
         $this->addForeignKey(
             'fk-instagram_user-agent_id',
             'instagram_user',
             'agent_id',
             'agent',
             'agent_id',
             'CASCADE'
         );


        /**
         *  Truncate billing and invoice tables (disable foreignkey check)
         */
         $this->execute("SET foreign_key_checks = 0;");
         $this->truncateTable('invoice');
         $this->truncateTable('billing');
         $this->execute("SET foreign_key_checks = 1;");

        /**
         *  Remove Agency ID and relations to Billing Table
         */
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

        /**
         *  Add Agent ID and relations to Billing Table
         */
         // Create column 'agent_id' in billing table
         $this->addColumn('billing', 'agent_id', $this->bigInteger()->notNull()->unsigned()->after('billing_id'));
         // creates index for column `agent_id` of billing table
         $this->createIndex(
             'idx-billing-agent_id',
             'billing',
             'agent_id'
         );
         // add foreign key for agent_id of table `agent`
         $this->addForeignKey(
             'fk-billing-agent_id',
             'billing',
             'agent_id',
             'agent',
             'agent_id',
             'CASCADE'
         );


        /**
         *  Remove Agency ID and relations to Invoice Table
         */
         // Drop Foreign Key for `agency_id` of `agency`
         $this->dropForeignKey(
             'fk-invoice-agency_id',
             'invoice'
         );
         // Drop Index for column `agency_id` of Invoice table
         $this->dropIndex(
             'idx-invoice-agency_id',
             'invoice'
         );
         // Drop column 'agency_id' of Invoice table
         $this->dropColumn('invoice', 'agency_id');


        /**
         *  Add Agent ID and relations to Invoice Table
         */
         $this->addColumn('invoice', 'agent_id', $this->bigInteger()->unsigned()->notNull()->after('pricing_id'));
         // creates index for column `agent_id` of invoice table
         $this->createIndex(
             'idx-invoice-agent_id',
             'invoice',
             'agent_id'
         );
         // add foreign key for agent_id of table `agent`
         $this->addForeignKey(
             'fk-invoice-agent_id',
             'invoice',
             'agent_id',
             'agent',
             'agent_id',
             'CASCADE'
         );

    }

    public function down()
    {
        /**
         * PRE- Truncate billing and invoice tables (disable foreignkey check)
         */
         $this->execute("SET foreign_key_checks = 0;");
         $this->truncateTable('invoice');
         $this->truncateTable('billing');
         $this->execute("SET foreign_key_checks = 1;");

        /**
         * 1- Remove Agent ID and relations to Invoice Table
         */
         // Drop Foreign Key for `agent_id` of `agency`
         $this->dropForeignKey(
             'fk-invoice-agent_id',
             'invoice'
         );
         // Drop Index for column `agent_id` of Invoice table
         $this->dropIndex(
             'idx-invoice-agent_id',
             'invoice'
         );
         // Drop column 'agent_id' of Invoice table
         $this->dropColumn('invoice', 'agent_id');

         /**
          * 2- Add Agency ID and relations to Invoice Table
          */
         $this->addColumn('invoice', 'agency_id', $this->bigInteger()->unsigned()->notNull()->after('pricing_id'));
         // creates index for column `agency_id` of invoice table
         $this->createIndex(
             'idx-invoice-agency_id',
             'invoice',
             'agency_id'
         );
         // add foreign key for agency_id of table `agency`
         $this->addForeignKey(
             'fk-invoice-agency_id',
             'invoice',
             'agency_id',
             'agency',
             'agency_id',
             'CASCADE'
         );

         /**
          * 3- Remove Agent ID and relations to Billing Table
          */
          // Drop Foreign Key for `agent_id` of `agent`
          $this->dropForeignKey(
              'fk-billing-agent_id',
              'billing'
          );
          // Drop Index for column `agent_id` of billing table
          $this->dropIndex(
              'idx-billing-agent_id',
              'billing'
          );
          // Drop column 'agent_id' of billing table
          $this->dropColumn('billing', 'agent_id');


          /**
           * 4- Add Agency ID and relations to Billing Table
           */
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

          /**
           * 5- Remove instagram_user dependency to agent_id
           */
           // drops foreign key for table `agency`
           $this->dropForeignKey(
               'fk-instagram_user-agent_id',
               'instagram_user'
           );
           // drops index for column `agent_id`
           $this->dropIndex(
               'idx-instagram_user-agent_id',
               'instagram_user'
           );
           $this->dropColumn('instagram_user', 'agent_id');



           /**
            * 6- Remove instagram_user dependency on agency_id
            */
           $this->addColumn('instagram_user', 'agency_id', $this->bigInteger()->unsigned()->after('user_id'));
           // creates index for column `agency_id`
           $this->createIndex(
               'idx-instagram_user-agency_id',
               'instagram_user',
               'agency_id'
           );
           // add foreign key for table `agency`
           $this->addForeignKey(
               'fk-instagram_user-agency_id',
               'instagram_user',
               'agency_id',
               'agency',
               'agency_id',
               'CASCADE'
           );

    }
}
