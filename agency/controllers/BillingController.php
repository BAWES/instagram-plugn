<?php

namespace agency\controllers;

use Twocheckout;
use Twocheckout_Charge;
use Twocheckout_Error;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\Billing;

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
     * Display Billing Summary / Options
     * @return mixed
     */
    public function actionIndex()
    {


        return $this->render('index', []);
    }

    /**
     * Setup a Recurring Billing Package
     * @return mixed
     */
    public function actionSetup()
    {
        $model = new Billing();

        // Handle AJAX Validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post())){
            // Token returned from 2CO after creditcard input
            if(Yii::$app->request->post('token') && $model->save()){
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
                            // Card holder’s name. (128 characters max)
                            "name" => 'Jasem the weird',
                            // Card holder’s street address. (64 characters max) Required
                            "addrLine1" => '123 Test St',
                            // Card holder’s street address line 2. (64 characters max)
                            // Required if “country” value is: CHN, JPN, RUS - Optional for all other “country” values.
                            "addrLine2" => '123213',
                            // Card holder’s city. (64 characters max) Required
                            "city" => 'Columbus',
                            /**
                             *  Card holder’s state. (64 characters max) Required if “country” value is ARG, AUS, BGR, CAN, CHN, CYP,
                             *  EGY, FRA, IND, IDN, ITA, JPN, MYS, MEX, NLD, PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR,
                             *   GBR, USA - Optional for all other “country” values.
                             */
                            "state" => 'OH',
                            /**
                             * Card holder’s zip. (16 characters max) Required if “country” value is ARG, AUS, BGR, CAN, CHN, CYP, EGY, FRA,
                             *  IND, IDN, ITA, JPN, MYS, MEX, NLD, PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR, GBR,
                             *  USA - Optional for all other “country” values.
                             */
                            "zipCode" => '43123',
                            // Card holder’s country. (64 characters max) Required
                            "country" => 'USA',
                            // Card holder’s email. (64 characters max) Required
                            "email" => 'testingtester@2co.com',
                            // Card holder’s phone. (16 characters max) Optional
                            // "phoneNumber" => '555-555-5555'
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

        // List of Zip / State Required Countries
        $zipStateCountries = \common\models\Country::find()->select('country_id')
            ->where(['country_zipstate_required' => 1])->asArray()->all();

        // List of Addr Line 2 required countries
        $addrCountries = \common\models\Country::find()->select('country_id')
            ->where(['country_addrline2_required' => 1])->asArray()->all();


        return $this->render('setup', [
            // Form
            'model' => $model,
            'zipStateCountries' => json_encode($zipStateCountries),
            'addrCountries' => json_encode($addrCountries),

            // 2 CO
            'processFormUrl' => Url::to(['billing/process']),
            'environment' => Yii::$app->params['2co.sandbox.environment'],
            'sellerId' => Yii::$app->params['2co.sandbox.sellerId'],
            'publishableKey' => Yii::$app->params['2co.sandbox.publishableKey'],
        ]);
    }

}
