<?php

use yii\db\Migration;

class m170111_191754_add_billing_deadline_to_agency extends Migration
{
    public function up()
    {
        $this->addColumn('agency', 'agency_billing_active_until', $this->date()->notNull()->after('agency_trial_days'));
    }

    public function down()
    {
        $this->dropColumn('agency', 'agency_billing_active_until');
    }
}
