<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subcategory;

/**
 * SubcategorySearch represents the model behind the search form about `common\models\Subcategory`.
 */
class SubcategorySearch extends Subcategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subcategory_id', 'category_id'], 'integer'],
            [['subcategory_name'], 'safe'],
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
        $query = Subcategory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subcategory_id' => $this->subcategory_id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'subcategory_name', $this->subcategory_name]);

        return $dataProvider;
    }
}
