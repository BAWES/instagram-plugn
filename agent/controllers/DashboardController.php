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
        //Getting a list of accounts this agent manages will be part of a component that gets bootstrapped
        // any action/view must always have access to the list
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM agent_assignment WHERE agent_id='.Yii::$app->user->identity->agent_id,
        ]);

        $cacheDuration = 60*15; //15 minutes then delete from cache

        $accountsManaged = InstagramUser::getDb()->cache(function($db) {
            return Yii::$app->user->identity->accountsManaged;
        }, $cacheDuration, $cacheDependency);

        return $this->render('index',[]);
    }

    /**
     * Finds the Instagram Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstagramUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstagramUser::findOne(['user_id' => $id, 'user_id' => Yii::$app->user->identity->user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
