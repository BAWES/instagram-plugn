<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "billing".
 *
 * @property string $billing_id
 * @property integer $agency_id
 * @property integer $pricing_id
 * @property integer $country_id
 * @property string $billing_name
 * @property string $billing_email
 * @property string $billing_city
 * @property string $billing_state
 * @property string $billing_zip_code
 * @property string $billing_address_line1
 * @property string $billing_address_line2
 * @property string $billing_total
 * @property string $billing_currency
 * @property string $twoco_token
 * @property string $twoco_order_num
 * @property string $twoco_transaction_id
 * @property string $twoco_response_code
 * @property string $twoco_response_msg
 * @property string $billing_datetime
 *
 * @property Agency $agency
 * @property Country $country
 * @property Pricing $pricing
 * @property Invoice[] $invoices
 */
class Billing extends \yii\db\ActiveRecord
{
    // Possible errors that may arrise from 2Checkout payment processing
    public $errorCodes = [
        "300" => "[Unauthorized] Incorrect private key or invalid token, please refresh and try again",
        "200" => "[Unable to process the request] Incorrect attributes or malformed JSON object",
        "400" => "[Bad request - parameter error] Missing required attributes or invalid token",
        "600" => "[Authorization Failed] Credit Card failed to authorize",
        "601" => "[Invalid Expiration Date] Please update your cards expiration date and try again, or try another payment method",
        "602" => "[Payment Authorization Failed] Please verify your Credit Card details are entered correctly and try again, or try another payment method",
        "603" => "[Invalid Currency for card type] Your credit card has been declined because of the currency you are attempting to pay in.  Please change the currency of the transaction or use a different card and try again.",
        "604" => "[Payment Authorization Failed] Credit is not enabled on this type of card, please contact your bank for more information or try another payment method.",
        "605" => "[Payment Authorization Failed] Invalid transaction type for this credit card, please use a different card and try submitting the payment again, or contact your bank for more information.",
        "606" => "[Payment Authorization Failed] Please use a different credit card or payment method and try again, or contact your bank for more information.",
        "607" => "[Payment Authorization Failed] Please verify your information and try again, or try another payment method.",
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'billing_name', 'billing_email', 'billing_city', 'billing_address_line1'], 'required'],
            [['country_id'], 'integer'],
            // Address Line 2 is required for CHN, JPN, RUS countries.
            [['billing_address_line2'], 'required', 'when' => function($model){
                $countries = Country::find()->where([
                    'country_addrline2_required' => 1
                ])->all();
                foreach($countries as $country){
                    // If this is a country that needs line 2 address input is required
                    if($model->country_id == $country->country_id){
                        return true;
                    }
                }
            }, 'whenClient' => "function (attribute, value) {
                    // Ignore client side validation for this field
                    return false;
                }"
            ],
            [['billing_state', 'billing_zip_code'], 'required', 'when' => function($model){
                $countries = Country::find()->where([
                    'country_zipstate_required' => 1
                ])->all();
                foreach($countries as $country){
                    // If this is a country that requires state and zip
                    if($model->country_id == $country->country_id){
                        return true;
                    }
                }
            }, 'whenClient' => "function (attribute, value) {
                    // Ignore client side validation for this field
                    return false;
                }"
            ],
            [['billing_email'], 'email'],
            [['billing_name'], 'string', 'max' => 128],
            [['billing_email', 'billing_city', 'billing_state', 'billing_address_line1', 'billing_address_line2'], 'string', 'max' => 64],
            [['billing_zip_code'], 'string', 'max' => 16],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']]
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'billing_datetime',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'billing_id' => 'Billing ID',
            'agency_id' => 'Agency ID',
            'pricing_id' => 'Pricing ID',
            'country_id' => 'Country',
            'billing_name' => 'Name',
            'billing_email' => 'Email',
            'billing_city' => 'City',
            'billing_state' => 'State',
            'billing_zip_code' => 'Zip Code',
            'billing_address_line1' => 'Street Address',
            'billing_address_line2' => 'Address Line 2',
            'billing_total' => 'Total Paid',
            'billing_currency' => 'Currency',
            'twoco_token' => '2co Token',
            'twoco_order_num' => 'Sale ID',
            'twoco_transaction_id' => 'Invoice ID',
            'twoco_response_code' => 'Response Code',
            'twoco_response_msg' => 'Response Msg',
            'billing_datetime' => 'Billing Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPricing()
    {
        return $this->hasOne(Pricing::className(), ['pricing_id' => 'pricing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['agency_id' => 'agency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['billing_id' => 'billing_id']);
    }

    /**
     * Success response from a 2Checkout Billing Attempt
     * https://www.2checkout.com/documentation/payment-api/create-sale
     */
    public function processTwoCheckoutSuccess($charge){
        //die(print_r($charge['response']));

        $this->twoco_response_code = $charge['response']['responseCode'];
        $this->twoco_response_msg = $charge['response']['responseMsg'];
        $this->twoco_order_num = $charge['response']['orderNumber'];
        $this->twoco_transaction_id = $charge['response']['transactionId'];
        $this->save(false);

        // Display Thank You Message via Session Flash
        Yii::$app->getSession()->setFlash('success', "[".$this->twoco_response_code."] ".$this->twoco_response_msg);

        // Log The Success
        Yii::info("[Billing Setup by Agency #".$this->agency_id."] Contact: ".$this->billing_name." / Email: ".$this->billing_email.
            " \ Initial Payment: $".$this->billing_total, __METHOD__);
        Yii::info("[".$this->twoco_response_code."] ".$this->twoco_response_msg, __METHOD__);

    }

    /**
     * Error response from a 2Checkout Billing Attempt
     * https://www.2checkout.com/documentation/payment-api/create-sale
     */
    public function processTwoCheckoutError($e){
        $this->twoco_response_code = $e->getCode();
        $this->twoco_response_msg = $this->errorCodes[$this->twoco_response_code] ? $this->errorCodes[$this->twoco_response_code] : $e->getMessage();
        $this->save(false);

        // Display Error Message via Session Flash
        Yii::$app->getSession()->setFlash('error', $this->twoco_response_msg);

        // Log The Error
        Yii::error("[Failed Billing Setup by Agency #".$this->agency_id."] Contact: ".$this->billing_name." / Email: ".$this->billing_email, __METHOD__);
        Yii::error($this->twoco_response_msg, __METHOD__);

    }
}
