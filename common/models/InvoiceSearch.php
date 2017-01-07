<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `common\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'billing_id', 'pricing_id', 'agency_id', 'item_rec_install_billed_1'], 'integer'],
            [['message_id', 'message_type', 'message_description', 'vendor_id', 'sale_id', 'sale_date_placed', 'vendor_order_id', 'payment_type', 'auth_exp', 'invoice_status', 'fraud_status', 'customer_ip', 'customer_ip_country', 'item_id_1', 'item_name_1', 'item_type_1', 'item_rec_status_1', 'item_rec_date_next_1', 'timestamp'], 'safe'],
            [['invoice_usd_amount', 'item_usd_amount_1'], 'number'],
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
        $query = Invoice::find();

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
            'invoice_id' => $this->invoice_id,
            'billing_id' => $this->billing_id,
            'pricing_id' => $this->pricing_id,
            'agency_id' => $this->agency_id,
            'sale_date_placed' => $this->sale_date_placed,
            'auth_exp' => $this->auth_exp,
            'invoice_usd_amount' => $this->invoice_usd_amount,
            'item_usd_amount_1' => $this->item_usd_amount_1,
            'item_rec_date_next_1' => $this->item_rec_date_next_1,
            'item_rec_install_billed_1' => $this->item_rec_install_billed_1,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'message_id', $this->message_id])
            ->andFilterWhere(['like', 'message_type', $this->message_type])
            ->andFilterWhere(['like', 'message_description', $this->message_description])
            ->andFilterWhere(['like', 'vendor_id', $this->vendor_id])
            ->andFilterWhere(['like', 'sale_id', $this->sale_id])
            ->andFilterWhere(['like', 'vendor_order_id', $this->vendor_order_id])
            ->andFilterWhere(['like', 'payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'invoice_status', $this->invoice_status])
            ->andFilterWhere(['like', 'fraud_status', $this->fraud_status])
            ->andFilterWhere(['like', 'customer_ip', $this->customer_ip])
            ->andFilterWhere(['like', 'customer_ip_country', $this->customer_ip_country])
            ->andFilterWhere(['like', 'item_id_1', $this->item_id_1])
            ->andFilterWhere(['like', 'item_name_1', $this->item_name_1])
            ->andFilterWhere(['like', 'item_type_1', $this->item_type_1])
            ->andFilterWhere(['like', 'item_rec_status_1', $this->item_rec_status_1]);

        return $dataProvider;
    }
}
