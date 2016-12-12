<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use common\models\PushNotification;

/**
 * Notification Processing
 */
class NotificationController extends \yii\console\Controller {

    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Processing Push Notifications \n", Console::FG_RED, Console::BOLD);
        PushNotification::notifyNewComments();

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * # CRON every 15 seconds
* * * * * php ~/www/yii cron/every15-seconds > /dev/null 2>&1
* * * * * sleep 15; php ~/www/yii cron/every15-seconds > /dev/null 2>&1
* * * * * sleep 30; php ~/www/yii cron/every15-seconds > /dev/null 2>&1
* * * * * sleep 45; php ~/www/yii cron/every15-seconds > /dev/null 2>&1
     */

    // public function actionTest(){
    //     $this->stdout("Testing Notification Query \n", Console::FG_RED, Console::BOLD);
    //
    //     $notif = new \common\models\PushNotification();
    //     $notif->send();
    //
    //     return self::EXIT_CODE_NORMAL;
    // }

}
