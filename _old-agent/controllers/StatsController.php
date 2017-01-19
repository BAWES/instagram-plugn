<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class StatsController extends \yii\web\Controller {

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
     * Display Account Statistics
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


}
