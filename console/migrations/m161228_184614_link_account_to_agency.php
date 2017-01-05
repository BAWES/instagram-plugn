<?php

use yii\db\Migration;

class m161228_184614_link_account_to_agency extends Migration
{
    public function up()
    {
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

    public function down()
    {
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
    }
}
