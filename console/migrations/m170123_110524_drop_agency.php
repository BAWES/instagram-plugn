<?php

use yii\db\Migration;

class m170123_110524_drop_agency extends Migration
{
    public function up()
    {
        $this->dropTable('agency');
    }

    public function down()
    {
        $this->createTable('agency', [
            'agency_id' => $this->bigPrimaryKey()->unsigned(),
            'agency_fullname' => $this->string()->notNull(),
            'agency_company' => $this->string(),
            'agency_email' => $this->string()->notNull()->unique(),
            'agency_email_verified' => $this->boolean()->defaultValue(0),
            'agency_auth_key' => $this->string(32)->notNull(),
            'agency_password_hash' => $this->string(), //Can be null if they're using social login
            'agency_password_reset_token' => $this->string()->unique(),
            'agency_limit_email' => $this->datetime(),
            'agency_status' => $this->smallInteger()->notNull()->defaultValue(10),
            'agency_trial_days' => $this->integer()->notNull()->defaultValue(14),
            'agency_billing_active_until' => $this->date()->notNull(),
            'agency_created_at' => $this->datetime()->notNull(),
            'agency_updated_at' => $this->datetime()->notNull(),
        ]);
    }

}
