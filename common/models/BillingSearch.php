<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Billing;

/**
 * BillingSearch represents the model behind the search form about `common\models\Billing`.
 */
class BillingSearch extends Billing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['billing_id', 'user_id', 'pricing_id', 'country_id'], 'integer'],
            [['billing_name', 'billing_email', 'billing_city', 'billing_state', 'billing_zip_code', 'billing_address_line1', 'billing_address_line2', 'billing_currency', '2co_token', '2co_order_num', '2co_transaction_id', '2co_response_code', '2co_response_msg', 'billing_datetime'], 'safe'],
            [['billing_total'], 'number'],
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
        $query = Billing::find();

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
            'billing_id' => $this->billing_id,
            'user_id' => $this->user_id,
            'pricing_id' => $this->pricing_id,
            'country_id' => $this->country_id,
            'billing_total' => $this->billing_total,
            'billing_datetime' => $this->billing_datetime,
        ]);

        $query->andFilterWhere(['like', 'billing_name', $this->billing_name])
            ->andFilterWhere(['like', 'billing_email', $this->billing_email])
            ->andFilterWhere(['like', 'billing_city', $this->billing_city])
            ->andFilterWhere(['like', 'billing_state', $this->billing_state])
            ->andFilterWhere(['like', 'billing_zip_code', $this->billing_zip_code])
            ->andFilterWhere(['like', 'billing_address_line1', $this->billing_address_line1])
            ->andFilterWhere(['like', 'billing_address_line2', $this->billing_address_line2])
            ->andFilterWhere(['like', 'billing_currency', $this->billing_currency])
            ->andFilterWhere(['like', '2co_token', $this->2co_token])
            ->andFilterWhere(['like', '2co_order_num', $this->2co_order_num])
            ->andFilterWhere(['like', '2co_transaction_id', $this->2co_transaction_id])
            ->andFilterWhere(['like', '2co_response_code', $this->2co_response_code])
            ->andFilterWhere(['like', '2co_response_msg', $this->2co_response_msg]);

        return $dataProvider;
    }
}
