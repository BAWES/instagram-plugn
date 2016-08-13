<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\InstagramUser;

class DashboardController extends \yii\web\Controller {

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
                        'roles' => ['@'], //only allow authenticated users to actions
                    ],
                ],
            ],
        ];
    }

    /**
     * Default Action
     * Either takes you to your top managed account or to the page to add account
     */
    public function actionIndex()
    {
        $managedAccounts = Yii::$app->accountManager->managedAccounts;

        if(isset($managedAccounts[0])){
            return $this->redirect(['media/list' ,'accountId' => $managedAccounts[0]->user_id]);
        }
        return $this->redirect(['dashboard/add-account']);
    }

    /**
     * Displays guide for user on how to add an account
     */
    public function actionAddAccount()
    {
        return $this->render('addAccount',[]);
    }

    /**
     * Displays this accounts agent activity
     */
    public function actionActivity()
    {
        //Get All Activities with the User that activity was made on
        $activities = Yii::$app->user->identity->getActivities()->with('user')->all();

        return $this->render('activity',[
            'activities' => $activities,
        ]);
    }


}
