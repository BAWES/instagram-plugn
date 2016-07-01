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
     * Renders Dashboard
     */
    public function actionIndex()
    {
        return $this->render('index',[]);
    }

    /**
     * Displays guide for user on how to add an account
     */
    public function actionAddAccount()
    {
        return $this->render('addAccount',[]);
    }


}
