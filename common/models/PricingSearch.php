<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pricing;

/**
 * PricingSearch represents the model behind the search form about `common\models\Pricing`.
 */
class PricingSearch extends Pricing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pricing_id'], 'integer'],
            [['pricing_title', 'pricing_features', 'pricing_created_at', 'pricing_updated_at'], 'safe'],
            [['pricing_price'], 'number'],
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
        $query = Pricing::find();

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
            'pricing_id' => $this->pricing_id,
            'pricing_price' => $this->pricing_price,
            'pricing_created_at' => $this->pricing_created_at,
            'pricing_updated_at' => $this->pricing_updated_at,
        ]);

        $query->andFilterWhere(['like', 'pricing_title', $this->pricing_title])
            ->andFilterWhere(['like', 'pricing_features', $this->pricing_features]);

        return $dataProvider;
    }
}
