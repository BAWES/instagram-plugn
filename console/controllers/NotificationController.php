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
        //$this->stdout("Gather comments to notify agents about \n", Console::FG_RED, Console::BOLD);
        PushNotification::notifyNewComments();

        return self::EXIT_CODE_NORMAL;
    }

    // public function actionTest(){
    //     $this->stdout("Testing Notification Query \n", Console::FG_RED, Console::BOLD);
    //
    //     $notif = new \common\models\PushNotification();
    //     $notif->send();
    //
    //     return self::EXIT_CODE_NORMAL;
    // }

}
