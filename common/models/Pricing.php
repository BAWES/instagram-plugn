<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "pricing".
 *
 * @property integer $pricing_id
 * @property string $pricing_title
 * @property string $pricing_features
 * @property string $pricing_price
 * @property integer $pricing_account_quantity
 * @property string $pricing_created_at
 * @property string $pricing_updated_at
 *
 * @property Billing[] $billings
 */
class Pricing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricing';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'pricing_created_at',
                'updatedAtAttribute' => 'pricing_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pricing_title', 'pricing_price'], 'required'],
            [['pricing_features'], 'string'],
            [['pricing_price', 'pricing_account_quantity'], 'number'],
            [['pricing_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pricing_id' => 'Pricing ID',
            'pricing_title' => 'Title',
            'pricing_features' => 'Features',
            'pricing_price' => 'Price',
            'pricing_account_quantity' => 'Number of Accounts',
            'pricing_created_at' => 'Created At',
            'pricing_updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::className(), ['pricing_id' => 'pricing_id']);
    }
}
