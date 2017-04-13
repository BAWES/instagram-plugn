<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "coupon_used".
 *
 * @property integer $used_id
 * @property integer $coupon_id
 * @property string $agent_id
 * @property string $used_datetime
 *
 * @property Agent $agent
 * @property Coupon $coupon
 */
class CouponUsed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon_used';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'agent_id'], 'required'],
            [['coupon_id', 'agent_id'], 'integer'],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            [['coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coupon::className(), 'targetAttribute' => ['coupon_id' => 'coupon_id']],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'used_datetime',
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
            'used_id' => 'Used ID',
            'coupon_id' => 'Coupon ID',
            'agent_id' => 'Agent ID',
            'used_datetime' => 'Used Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['coupon_id' => 'coupon_id']);
    }
}
