<?php

namespace frontend\controllers;

use Twocheckout;
use Twocheckout_Charge;
use Twocheckout_Error;
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

            // Your sellerId(account number) and privateKey are required to make the Payment API Authorization call.
            Twocheckout::privateKey(Yii::$app->params['2co.sandbox.privateKey']);
            Twocheckout::sellerId(Yii::$app->params['2co.sandbox.sellerId']);
            // Your username and password are required to make any Admin API call.
            Twocheckout::username(Yii::$app->params['2co.sandbox.username']);
            Twocheckout::password(Yii::$app->params['2co.sandbox.password']);
            // If you want to turn off SSL verification (Please don't do this in your production environment)
            Twocheckout::verifySSL(Yii::$app->params['2co.sandbox.verifySSL']);  // this is set to true by default
            // To use your sandbox account set sandbox to true
            Twocheckout::sandbox(Yii::$app->params['2co.isSandbox']);


            // Use the token to create a sale
            try {
                $charge = Twocheckout_Charge::auth([
                    "merchantOrderId" => "123",
                    "token" => $token,
                    "currency" => 'USD',
                    "total" => '10.00',
                    "billingAddr" => [
                        "name" => 'Testing Tester',
                        "addrLine1" => '123 Test St',
                        "city" => 'Columbus',
                        "state" => 'OH',
                        "zipCode" => '43123',
                        "country" => 'USA',
                        "email" => 'testingtester@2co.com',
                        "phoneNumber" => '555-555-5555'
                    ]
                ]);

                // Die with response from 2co server
                die(print_r($charge));

            } catch (Twocheckout_Error $e) {
                die(print_r($e->getMessage()));
                // Log error to slack maybe?
            }

        }
    }

}
