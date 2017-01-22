<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use agent\models\CommentQueue;
use agent\models\InstagramUser;
use agent\models\Media;
use agent\models\Activity;
use common\models\Agent;
use common\models\Comment;

/**
 * EmailController handles the agent email notifications preferences
 */
class EmailController extends \yii\web\Controller {


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
     * Shows the currently selected email notification preference
     * and gives the option to change it to another or turn it off
     */
    public function actionIndex()
    {
        $agentModel = Yii::$app->user->identity;

        if(Yii::$app->request->post())
        {
            switch(Yii::$app->request->post('notif-preference'))
            {
                case Agent::PREF_EMAIL_DAILY:
                    $agentModel->agent_email_preference = Agent::PREF_EMAIL_DAILY;
                    $agentModel->save(false);
                    break;
                case Agent::PREF_EMAIL_OFF:
                    $agentModel->agent_email_preference = Agent::PREF_EMAIL_OFF;
                    $agentModel->save(false);
                    break;
            }

            Yii::$app->session->setFlash('success', 'Your email preferences have been updated');
        }

        return $this->render('index',[
            'agentModel' => $agentModel
        ]);
    }


}
