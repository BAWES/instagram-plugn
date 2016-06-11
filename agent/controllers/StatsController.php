<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class StatsController extends \yii\web\Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], //only allow authenticated users to job actions
                    ],
                ],
            ],
        ];
    }


    /**
     * Manage an Instagram Account in Conversation View
     * @param integer $accountId the account id we're looking to manage
     */
    public function actionIndex($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $records = $instagramAccount->records;

        return $this->render('index',[
            'account' => $instagramAccount,
            'records' => $records,
        ]);
    }

    /**
     * Display all agents activities with this account
     * @param integer $accountId the account id we're looking to manage
     */
    public function actionActivity($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $activities = $instagramAccount->getActivities()->with('agent')->all();

        return $this->render('activity',[
            'account' => $instagramAccount,
            'activities' => $activities,
        ]);
    }


}
