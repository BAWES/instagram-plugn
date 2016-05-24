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
     * Manage an Instagram Account in Conversation View
     * @param string $accountName the account name we're looking to manage
     */
    public function actionConversations($accountName)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountName);

        $conversations = $instagramAccount->conversations;

        //die(print_r($instagramAccount->getConversations()->createCommand()->sql, true));

        return $this->render('conversations',[
            'account' => $instagramAccount,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Manage an Instagram Account in Media View
     * @param string $accountName the account name we're looking to manage
     */
    public function actionMedia($accountName)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountName);


        return $this->render('media',[
            'account' => $instagramAccount
        ]);
    }


}
