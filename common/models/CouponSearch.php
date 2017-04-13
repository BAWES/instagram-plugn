<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Coupon;

/**
 * CouponSearch represents the model behind the search form about `common\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'coupon_user_limit', 'coupon_reward_days'], 'integer'],
            [['coupon_name', 'coupon_reward_days', 'coupon_expires_at', 'coupon_created_at', 'coupon_updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Coupon::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coupon_id' => $this->coupon_id,
            'coupon_reward_days' => $this->coupon_reward_days,
            'coupon_user_limit' => $this->coupon_user_limit,
            'coupon_expires_at' => $this->coupon_expires_at,
            'coupon_created_at' => $this->coupon_created_at,
            'coupon_updated_at' => $this->coupon_updated_at,
        ]);

        $query->andFilterWhere(['like', 'coupon_name', $this->coupon_name]);

        return $dataProvider;
    }
}
