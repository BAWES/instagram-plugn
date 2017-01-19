<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class ActivityController extends \yii\web\Controller {

    public $layout = 'account';

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
     * Display all agents activities with this account
     * @param integer $accountId the account id we're looking to manage
     */
    public function actionIndex($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $activities = $instagramAccount->getActivities()->with('agent')->all();

        return $this->render('index',[
            'account' => $instagramAccount,
            'activities' => $activities,
        ]);
    }


}
