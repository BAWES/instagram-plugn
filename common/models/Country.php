<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $country_id
 * @property string $country_name
 * @property string $country_iso_code_2
 * @property string $country_iso_code_3
 * @property integer $country_zipstate_required
 * @property integer $country_addrline2_required
 *
 * @property Billing[] $billings
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_name', 'country_iso_code_2', 'country_iso_code_3'], 'required'],
            [['country_zipstate_required', 'country_addrline2_required'], 'integer'],
            [['country_name', 'country_iso_code_2', 'country_iso_code_3'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_name' => 'Country Name',
            'country_iso_code_2' => 'Country Iso Code 2',
            'country_iso_code_3' => 'Country Iso Code 3',
            'country_zipstate_required' => 'Country Zipstate Required',
            'country_addrline2_required' => 'Country Addrline2 Required',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::className(), ['country_id' => 'country_id']);
    }
}
