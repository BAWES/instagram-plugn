<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form about `common\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'subcategory_id'], 'integer'],
            [['project_title', 'project_image', 'project_duration', 'project_description'], 'safe'],
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
        $query = Project::find();

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
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'subcategory_id' => $this->subcategory_id,
        ]);

        $query->andFilterWhere(['like', 'project_title', $this->project_title])
            ->andFilterWhere(['like', 'project_image', $this->project_image])
            ->andFilterWhere(['like', 'project_duration', $this->project_duration])
            ->andFilterWhere(['like', 'project_description', $this->project_description]);

        return $dataProvider;
    }
}
