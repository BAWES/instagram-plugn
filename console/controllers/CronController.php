<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\db\Expression;
use common\models\InstagramUser;
use common\models\Agent;

/**
 * All Cron actions related to this project
 */
class CronController extends \yii\console\Controller {

    public $instagram;


    public function init() {
        parent::init();
        $this->instagram = Yii::$app->authClientCollection->clients['instagram'];

        //Delete this later
        /*
        $this->instagram->on("newline", function(){
            $this->stdout("\n===================================", Console::FG_YELLOW, Console::BOLD);
            $this->stdout("\n===================================\n", Console::FG_YELLOW, Console::BOLD);
        });
        */
        //End Deletethis later
    }


    /**
     * Used for testing only
     */
    public function actionIndex(){
        $this->stdout("Testing Agent Trial Deductions \n", Console::FG_RED, Console::BOLD);
        //Agent::deductTrialDayFromAllActiveAgents();
    }

    /**
     * Method called once a day
     */
    public function actionDaily() {
        // Update User data & Take Daily Stats
        // of following/follower stats over time
        $this->instagram->updateUserData();

        // Deduct a Day From Agents on Trial
        Agent::deductTrialDayFromAllActiveAgents();

        //Send email notifications to agents with account summaries
        //InstagramUser::broadcastEmailNotifications();

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called by cron every minute
     */
    public function actionEveryMinute() {
        //Check if user uploaded any new media
        //Get latest posts and crawl comments
        $this->instagram->getUsersLatestPosts();

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Method called every 15 seconds
     */
    public function actionEvery15Seconds() {
        //Process Queued Comments
        $this->instagram->processQueuedComments();

        return self::EXIT_CODE_NORMAL;
    }


}
