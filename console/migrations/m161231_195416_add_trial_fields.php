<?php

use yii\db\Migration;

class m161231_195416_add_trial_fields extends Migration
{
    public function up()
    {
        $this->addColumn('instagram_user', 'user_trial_days', $this->integer()->notNull()->defaultValue(14));
        $this->addColumn('agency', 'agency_trial_days', $this->integer()->notNull()->defaultValue(14)->after('agency_status'));
    }

    public function down()
    {
        $this->dropColumn('instagram_user', 'user_trial_days');
        $this->dropColumn('agency', 'agency_trial_days');
    }

}
