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
     * Default Actions
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


}
