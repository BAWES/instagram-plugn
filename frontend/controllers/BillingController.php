<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
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
        return $this->render('index', [
            'processFormUrl' => Url::to(['billing/process']),
            'environment' => Yii::$app->params['2co.sandbox.environment'],
            'sellerId' => Yii::$app->params['2co.sandbox.sellerId'],
            'publishableKey' => Yii::$app->params['2co.sandbox.publishableKey'],
        ]);
    }


    /**
     * Process the payment token
     */
    public function actionProcess(){
        // Token returned from 2CO after creditcard input
        if(Yii::$app->request->post('token')){
            $token = Yii::$app->request->post('token');
            die($token);

            // Use the token to create a sale

        }
    }

}
