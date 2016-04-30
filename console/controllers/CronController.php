<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Testing Instagram Query \n", Console::FG_RED, Console::BOLD);

        $instagram = Yii::$app->authClientCollection->clients['instagram'];

        //Delete this later
        $instagram->on("newline", function(){
            $this->stdout("\n-------- \n", Console::FG_YELLOW, Console::BOLD);
        });
        //End Deletethis later
        
        $instagram->updateUserData();


    }

    /**
     * Method called by cron every 5 minutes or so
     */
    public function actionMinute() {
        //Process next job in queue
        //JobProcessQueue::processNextJob();

        return self::EXIT_CODE_NORMAL;
    }


}