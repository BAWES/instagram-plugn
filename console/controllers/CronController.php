<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\db\Expression;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    public $instagram;


    public function init() {
        parent::init();
        $this->instagram = Yii::$app->authClientCollection->clients['instagram'];

        //Delete this later
        $this->instagram->on("newline", function(){
            $this->stdout("\n===================================", Console::FG_YELLOW, Console::BOLD);
            $this->stdout("\n===================================\n", Console::FG_YELLOW, Console::BOLD);
        });
        //End Deletethis later
    }


    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Testing Instagram Query \n", Console::FG_RED, Console::BOLD);

        //Get latest 20 posts, see if user uploaded any new media or got updates on existing media.
        $this->instagram->getUsersLatestPosts();

    }

    /**
     * Method called once a day
     */
    public function actionDaily() {
        //Update user data once a day to keep track of progress over time
        $this->instagram->updateUserData();

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called every 30 minutes
     */
    public function actionEvery10Min() {
        //Check if user uploaded any new media

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called by cron every minute
     */
    public function actionEveryMinute() {

        return self::EXIT_CODE_NORMAL;
    }


}
