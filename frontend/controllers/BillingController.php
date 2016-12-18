<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BillingController
 */
class BillingController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], //only allow authenticated users to all actions
                    ],
                ],
            ],
        ];
    }

    /**
     * Display main view
     * @return mixed
     */
    public function actionIndex()
    {
        // Token returned from 2CO after creditcard input
        if(Yii::$app->request->post('token')){
            $token = Yii::$app->request->post('token');

            // Use the token to create a sale

        }

        return $this->render('index', [
            'environment' => Yii::$app->params['2co.sandbox.environment'],
            'sellerId' => Yii::$app->params['2co.sandbox.sellerId'],
            'publishableKey' => Yii::$app->params['2co.sandbox.publishableKey'],
        ]);
    }

}
