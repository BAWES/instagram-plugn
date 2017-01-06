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
        $availablePriceOptions = \common\models\Pricing::find()->orderBy('pricing_price')->all();

        $isTrial = Yii::$app->user->identity->agency_trial_days > 0? true: false;

        return $this->render('index', [
            'availablePriceOptions' => $availablePriceOptions,
            'isTrial' => $isTrial
        ]);
    }

    /**
     * Setup a Recurring Billing Package
     * @param  integer $plan The Pricing Option ID to subscribe to
     * @return mixed
     */
    public function actionSetup($plan)
    {
        // Find the Pricing Option the user wants to setup
        $pricing = \common\models\Pricing::findOne($plan);
        if(!$pricing){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // Check if is trial
        $isTrial = Yii::$app->user->identity->agency_trial_days > 0? true: false;

        // If user is attempting to setup a package which is for less than
        // the number of accounts he has attached, show error and redirect to billing
        $currentAccountCount = count(Yii::$app->accountManager->managedAccounts);
        $planLimit = $pricing->pricing_account_quantity;
        if($planLimit < $currentAccountCount){
            Yii::$app->getSession()
            ->setFlash('warning', "[You have $currentAccountCount accounts. Selected plan has a limit of $planLimit] You may remove accounts by navigating to their management page and clicking the <b>Remove</b> button");

            return $this->redirect(['billing/index']);
        }

        // Calculate Discount if Available
        $discount = 0;
        if($isTrial){
            // Give 30% discount for trial users
            $discount = ($pricing->pricing_price - round($pricing->pricing_price * 0.7)) * -1;
        }

        // Setup new billing model
        $billingModel = new Billing();
        $billingModel->agency_id = Yii::$app->user->identity->agency_id;
        $billingModel->pricing_id = $pricing->pricing_id;
        $billingModel->billing_total = $pricing->pricing_price + $discount; // Store initial billed amount
        $billingModel->billing_email = Yii::$app->user->identity->agency_email;
        $billingModel->billing_name = Yii::$app->user->identity->agency_fullname;
        $billingModel->billing_currency = "USD";

        // Handle AJAX Validation
        if (Yii::$app->request->isAjax && $billingModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($billingModel);
        }

        if($billingModel->load(Yii::$app->request->post())){
            // Token returned from 2CO after creditcard input
            $billingModel->twoco_token = Yii::$app->request->post('token');

            // If Token exists and record of bill is saved
            if($billingModel->twoco_token && $billingModel->save()){
                $token = Yii::$app->request->post('token');

                // Your sellerId(account number) and privateKey are required to make the Payment API Authorization call.
                Twocheckout::privateKey(Yii::$app->params['2co.privateKey']);
                Twocheckout::sellerId(Yii::$app->params['2co.sellerId']);
                // Your username and password are required to make any Admin API call.
                Twocheckout::username(Yii::$app->params['2co.username']);
                Twocheckout::password(Yii::$app->params['2co.password']);
                // If you want to turn off SSL verification (Please don't do this in your production environment)
                Twocheckout::verifySSL(Yii::$app->params['2co.verifySSL']);  // this is set to true by default
                // To use your sandbox account set sandbox to true
                Twocheckout::sandbox(Yii::$app->params['2co.isSandbox']);


                // Use the token to create a sale
                try {
                    $charge = Twocheckout_Charge::auth([
                        "merchantOrderId" => $billingModel->billing_id,
                        "token" => $token,
                        "currency" => $billingModel->billing_currency,
                        "billingAddr" => [
                            // Card holder’s name. (128 characters max)
                            "name" => $billingModel->billing_name,
                            // Card holder’s street address. (64 characters max) Required
                            "addrLine1" => $billingModel->billing_address_line1,
                            // Card holder’s street address line 2. (64 characters max)
                            // Required if “country” value is: CHN, JPN, RUS - Optional for all other “country” values.
                            "addrLine2" => $billingModel->billing_address_line1? $billingModel->billing_address_line1:"",
                            // Card holder’s city. (64 characters max) Required
                            "city" => $billingModel->billing_city,
                            /**
                             *  Card holder’s state. (64 characters max) Required if “country” value is ARG, AUS, BGR, CAN, CHN, CYP,
                             *  EGY, FRA, IND, IDN, ITA, JPN, MYS, MEX, NLD, PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR,
                             *   GBR, USA - Optional for all other “country” values.
                             */
                            "state" => $billingModel->billing_state? $billingModel->billing_state:"",
                            /**
                             * Card holder’s zip. (16 characters max) Required if “country” value is ARG, AUS, BGR, CAN, CHN, CYP, EGY, FRA,
                             *  IND, IDN, ITA, JPN, MYS, MEX, NLD, PAN, PHL, POL, ROU, RUS, SRB, SGP, ZAF, ESP, SWE, THA, TUR, GBR,
                             *  USA - Optional for all other “country” values.
                             */
                            "zipCode" => $billingModel->billing_zip_code? $billingModel->billing_zip_code:"",
                            // Card holder’s country. (64 characters max) Required
                            "country" => $billingModel->country->country_iso_code_3,
                            // Card holder’s email. (64 characters max) Required
                            "email" => $billingModel->billing_email,
                            // Card holder’s phone. (16 characters max) Optional
                            // "phoneNumber" => '555-555-5555'
                        ],
                        "lineItems" => [
                            [
                                "type" => "product",
                                "name" => "Plugn ".$pricing->pricing_title." Plan",
                                "price" => $pricing->pricing_price,
                                "tangible" => "N",
                                "productId" => $pricing->pricing_id,
                                "recurrence" => "1 Month",
                                "duration" => "Forever",
                                "startupFee" => $discount?$discount:""
                            ]
                        ]
                    ]);

                    $billingModel->processTwoCheckoutSuccess($charge);
                } catch (Twocheckout_Error $e) {
                    $billingModel->processTwoCheckoutError($e);
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
            'model' => $billingModel,
            'zipStateCountries' => json_encode($zipStateCountries),
            'addrCountries' => json_encode($addrCountries),

            // Pricing
            'pricing' => $pricing,
            'isTrial' => $isTrial,

            // 2 CO
            'processFormUrl' => Url::to(['billing/process']),
            'environment' => Yii::$app->params['2co.environment'],
            'sellerId' => Yii::$app->params['2co.sellerId'],
            'publishableKey' => Yii::$app->params['2co.publishableKey'],
        ]);
    }

}
