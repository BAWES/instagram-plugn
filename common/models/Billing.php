<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "billing".
 *
 * @property string $billing_id
 * @property integer $user_id
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
 * @property string $2co_token
 * @property string $2co_order_num
 * @property string $2co_transaction_id
 * @property string $2co_response_code
 * @property string $2co_response_msg
 * @property string $billing_datetime
 *
 * @property Country $country
 * @property Pricing $pricing
 * @property InstagramUser $user
 */
class Billing extends \yii\db\ActiveRecord
{
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
            'user_id' => 'User ID',
            'pricing_id' => 'Pricing ID',
            'country_id' => 'Country',
            'billing_name' => 'Name',
            'billing_email' => 'Email',
            'billing_city' => 'City',
            'billing_state' => 'State',
            'billing_zip_code' => 'Zip Code',
            'billing_address_line1' => 'Street Address',
            'billing_address_line2' => 'Address Line 2',
            'billing_total' => 'Total',
            'billing_currency' => 'Currency',
            '2co_token' => '2co Token',
            '2co_order_num' => '2co Order Num',
            '2co_transaction_id' => '2co Transaction ID',
            '2co_response_code' => '2co Response Code',
            '2co_response_msg' => '2co Response Msg',
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
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
