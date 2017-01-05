<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Note;

/**
 * NoteSearch represents the model behind the search form about `common\models\Note`.
 */
class NoteSearch extends Note
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['note_id', 'user_id', 'created_by_agent_id', 'updated_by_agent_id'], 'integer'],
            [['note_about_username', 'note_title', 'note_text', 'note_created_datetime', 'note_updated_datetime'], 'safe'],
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
        $query = Note::find();

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
            'note_id' => $this->note_id,
            'user_id' => $this->user_id,
            'created_by_agent_id' => $this->created_by_agent_id,
            'updated_by_agent_id' => $this->updated_by_agent_id,
            'note_created_datetime' => $this->note_created_datetime,
            'note_updated_datetime' => $this->note_updated_datetime,
        ]);

        $query->andFilterWhere(['like', 'note_about_username', $this->note_about_username])
            ->andFilterWhere(['like', 'note_title', $this->note_title])
            ->andFilterWhere(['like', 'note_text', $this->note_text]);

        return $dataProvider;
    }
}
