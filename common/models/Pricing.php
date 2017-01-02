<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pricing".
 *
 * @property integer $pricing_id
 * @property string $pricing_title
 * @property string $pricing_features
 * @property string $pricing_price
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
    public function rules()
    {
        return [
            [['pricing_title', 'pricing_price', 'pricing_created_at', 'pricing_updated_at'], 'required'],
            [['pricing_features'], 'string'],
            [['pricing_price'], 'number'],
            [['pricing_created_at', 'pricing_updated_at'], 'safe'],
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
            'pricing_title' => 'Pricing Title',
            'pricing_features' => 'Pricing Features',
            'pricing_price' => 'Pricing Price',
            'pricing_created_at' => 'Pricing Created At',
            'pricing_updated_at' => 'Pricing Updated At',
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