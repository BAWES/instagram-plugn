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
                        'roles' => ['@'], //only allow authenticated users to job actions
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Renders Dashboard
     */
    public function actionIndex()
    {


        return $this->render('index',[]);
    }

    /**
     * Manage an Instagram Account
     * @param string $accountName the account name we're looking to manage
     */
    public function actionManage($accountName)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountName);
        

        return $this->render('index',[]);
    }


}
