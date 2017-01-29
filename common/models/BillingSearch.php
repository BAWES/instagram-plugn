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
            [['billing_id', 'agent_id', 'pricing_id', 'country_id'], 'integer'],
            [['billing_name', 'billing_email', 'billing_city', 'billing_state', 'billing_zip_code', 'billing_address_line1', 'billing_address_line2', 'billing_currency', 'twoco_token', 'twoco_order_num', 'twoco_transaction_id', 'twoco_response_code', 'twoco_response_msg', 'billing_datetime'], 'safe'],
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
            'agent_id' => $this->agent_id,
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
            ->andFilterWhere(['like', 'twoco_token', $this->twoco_token])
            ->andFilterWhere(['like', 'twoco_order_num', $this->twoco_order_num])
            ->andFilterWhere(['like', 'twoco_transaction_id', $this->twoco_transaction_id])
            ->andFilterWhere(['like', 'twoco_response_code', $this->twoco_response_code])
            ->andFilterWhere(['like', 'twoco_response_msg', $this->twoco_response_msg]);

        return $dataProvider;
    }
}
