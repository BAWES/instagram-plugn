<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;

/**
 * Notification Processing
 */
class NotificationController extends \yii\console\Controller {

    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Testing Notification Query \n", Console::FG_RED, Console::BOLD);

        $notif = new \common\models\PushNotification();
        $notif->send();

        return self::EXIT_CODE_NORMAL;
    }

}
