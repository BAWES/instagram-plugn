<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\InstagramUser;

class ConversationController extends \yii\web\Controller {

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
     * @param string $accountName the account name we're looking to manage
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $conversations = $instagramAccount->conversations;

        //die(print_r($instagramAccount->getConversations()->createCommand()->sql, true));

        return $this->render('list',[
            'account' => $instagramAccount,
            'conversations' => $conversations,
        ]);
    }


}
