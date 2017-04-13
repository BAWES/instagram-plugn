<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $coupon_id
 * @property string $coupon_name
 * @property integer $coupon_user_limit
 * @property string $coupon_expires_at
 * @property string $coupon_created_at
 * @property string $coupon_updated_at
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_name', 'coupon_user_limit', 'coupon_expires_at'], 'required'],
            [['coupon_user_limit'], 'integer'],
            [['coupon_name'], 'string', 'max' => 255],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'coupon_created_at',
                'updatedAtAttribute' => 'coupon_updated_at',
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
            'coupon_id' => 'Coupon ID',
            'coupon_name' => 'Coupon Name',
            'coupon_user_limit' => 'Coupon User Limit',
            'coupon_expires_at' => 'Coupon Expires At',
            'coupon_created_at' => 'Coupon Created At',
            'coupon_updated_at' => 'Coupon Updated At',
        ];
    }
}
