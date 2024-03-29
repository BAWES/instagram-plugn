<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoice_id
 * @property string $billing_id
 * @property integer $pricing_id
 * @property string $agent_id
 * @property string $message_id
 * @property string $message_type
 * @property string $message_description
 * @property string $vendor_id
 * @property string $sale_id
 * @property string $sale_date_placed
 * @property string $vendor_order_id
 * @property string $payment_type
 * @property string $auth_exp
 * @property string $invoice_status
 * @property string $fraud_status
 * @property string $invoice_usd_amount
 * @property string $customer_ip
 * @property string $customer_ip_country
 * @property string $item_id_1
 * @property string $item_name_1
 * @property string $item_usd_amount_1
 * @property string $item_type_1
 * @property string $item_rec_status_1
 * @property string $item_rec_date_next_1
 * @property integer $item_rec_install_billed_1
 * @property string $timestamp
 * @property string $invoice_created_at
 * @property string $invoice_updated_at
 *
 * @property Agent $agent
 * @property Billing $billing
 * @property Pricing $pricing
 */
class Invoice extends \yii\db\ActiveRecord
{
    // Hash used for validating authenticity of request
    public $md5_hash;

    // Secret word used for INS Hash Validation
    private $_secretWord = "builtawesome";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'agent_id', 'billing_id', 'pricing_id', 'message_type', 'message_description'], 'required'],
            [['md5_hash'], 'required', 'on' => 'INSNotification'],
            [['billing_id', 'pricing_id', 'item_rec_install_billed_1'], 'integer'],
            [['sale_date_placed', 'auth_exp', 'item_rec_date_next_1', 'timestamp'], 'safe'],
            [['invoice_usd_amount', 'item_usd_amount_1'], 'number'],
            [['message_id', 'message_type', 'vendor_id', 'sale_id', 'vendor_order_id', 'payment_type', 'invoice_status', 'fraud_status', 'item_id_1', 'item_type_1', 'item_rec_status_1'], 'string', 'max' => 64],
            [['message_description', 'customer_ip', 'customer_ip_country', 'item_name_1'], 'string', 'max' => 128],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            [['billing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Billing::className(), 'targetAttribute' => ['billing_id' => 'billing_id']],
            [['pricing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pricing::className(), 'targetAttribute' => ['pricing_id' => 'pricing_id']],

            // Validate MD5 Hash on new Notification
            ['md5_hash', 'validateHash'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'invoice_created_at',
                'updatedAtAttribute' => 'invoice_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Validate the Md5 Hash Sent from 2Checkout
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateHash($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $sellerId = Yii::$app->params['2co.sellerId'];

            $saleId = $this->sale_id;
            $invoiceId = $this->invoice_id;

            $stringToHash = strtoupper(md5($saleId . $sellerId . $invoiceId . $this->_secretWord));

            if ($stringToHash != $this->md5_hash) {
                // Log error. The supplied hash is invalid
                $this->addError($attribute, 'Invalid MD5 Hash');
                Yii::error("[INS Hash Validation] Failed to validate the hash", __METHOD__);
            }

        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => 'Invoice ID',
            'billing_id' => 'Billing ID',
            'pricing_id' => 'Pricing ID',
            'agent_id' => 'Agent ID',
            'message_id' => 'Message ID',
            'message_type' => 'Message Type',
            'message_description' => 'Message Description',
            'vendor_id' => 'Vendor ID',
            'sale_id' => 'Sale ID',
            'sale_date_placed' => 'Sale Date Placed',
            'vendor_order_id' => 'Vendor Order ID',
            'payment_type' => 'Payment Type',
            'auth_exp' => 'Auth Exp',
            'invoice_status' => 'Invoice Status',
            'fraud_status' => 'Fraud Status',
            'invoice_usd_amount' => 'Invoice Usd Amount',
            'customer_ip' => 'Customer Ip',
            'customer_ip_country' => 'Customer Ip Country',
            'item_id_1' => 'Price Plan',
            'item_name_1' => 'Name',
            'item_usd_amount_1' => 'Price',
            'item_type_1' => 'Type',
            'item_rec_status_1' => 'Recurring Payment Status',
            'item_rec_date_next_1' => 'Date of next recurring installment',
            'item_rec_install_billed_1' => '# of successful recurring installments',
            'timestamp' => 'Timestamp',
            'invoice_created_at' => 'Created',
            'invoice_updated_at' => 'Last Updated',
        ];
    }

    /**
     * The account owner of this invoice
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilling()
    {
        return $this->hasOne(Billing::className(), ['billing_id' => 'billing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPricing()
    {
        return $this->hasOne(Pricing::className(), ['pricing_id' => 'pricing_id']);
    }

    /**
     * After Save
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        // Refresh if fresh insert. To get updated datetime from expressions.
        if($insert){
            $this->refresh();
        }

        // Slack Update about Invoice
        $this->sendSlackAboutInvoice();

        /**
         * If Billing Stopped, Add Remaining Billing Days as Trial Days
         */
        if($this->message_type == "RECURRING_STOPPED"){
            $this->billing->processBillingCancellation("2Checkout INS RECURRING_STOPPED");
        }else{
            // Update Owner Billing Deadline based on INS output
            $this->agent->updateBillingDeadline($this->item_rec_date_next_1);
        }

        /**
         * If Recurring Payment Failed, Email Customer Notifying about Issue
         * Also Cancel the Billing for this Customer
         */
        if($this->message_type == "RECURRING_INSTALLMENT_FAILED"){
            $this->emailCustomerPaymentFailed();

            // Cancel Recurring Billing
            $this->billing->cancelRecurring();
        }

        // When a new invoice is created
        if($this->message_type == "ORDER_CREATED"){
            // Send invoice via email to customer
            $this->emailInvoiceToCustomer();
        }
    }

    /**
     * BeforeSave
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            return true;
        }
    }

    /**
     * Send Slack update about this invoice
     */
    public function sendSlackAboutInvoice(){
        $customerName = $this->billing->billing_name;
        $pricePlanName = $this->item_name_1;
        $paymentAmount = Yii::$app->formatter->asCurrency($this->item_usd_amount_1);
        $country = $this->customer_ip_country;
        $expiresOn = Yii::$app->formatter->asDate($this->item_rec_date_next_1);

        $description = $this->message_description;

        if($this->message_type == "INVOICE_STATUS_CHANGED"){
            $description .= " to ".$this->invoice_status;
        }else if($this->message_type == "FRAUD_STATUS_CHANGED"){
            $description .= " to ".$this->fraud_status;
        }


        $logMessage = "[$description. Invoice #".$this->invoice_id."] $pricePlanName @ $paymentAmount for $customerName in $country expires on $expiresOn";

        if($this->message_type == "REFUND_ISSUED" || $this->message_type == "FRAUD_STATUS_CHANGED" || $this->message_type == "RECURRING_RESTARTED"){
            Yii::info($logMessage, __METHOD__);
        }else if($this->message_type == "RECURRING_STOPPED" || $this->message_type == "RECURRING_COMPLETE" || $this->message_type == "RECURRING_INSTALLMENT_FAILED"){
            Yii::info($logMessage, __METHOD__);
        }else{
            Yii::info($logMessage, __METHOD__);
        }
    }


    /**
     * Send the invoice to the customer via email
     */
    public function emailInvoiceToCustomer(){
        return Yii::$app->mailer->compose([
                    'html' => 'billing/invoice',
                        ], [
                    'invoice' => $this,
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                ->setTo($this->billing->billing_email)
                ->setSubject('Thanks for your payment. You are making Plugn possible!')
                ->send();
    }

    /**
     * Email Customer about Recurring Payment Stopped
     */
    public function emailCustomerRecurringStopped($originalBillingEndDate){
        return Yii::$app->mailer->compose([
                'html' => 'billing/billing-cancelled',
                    ], [
                'stopDate' => $originalBillingEndDate,
            ])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
            ->setTo($this->billing->billing_email)
            ->setSubject('Your Plugn subscription will end soon')
            ->send();
    }
    /**
     * Email Customer about failed recurring payment
     */
    public function emailCustomerPaymentFailed(){
        return Yii::$app->mailer->compose([
                'html' => 'billing/payment-issue',
                    ], [
                //'invoice' => $this,
            ])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
            ->setTo($this->billing->billing_email)
            ->setSubject('We were unable to process your payment')
            ->send();
    }
}
