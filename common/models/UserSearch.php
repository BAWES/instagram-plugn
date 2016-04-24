<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_status'], 'integer'],
            [['user_name', 'user_email', 'user_auth_key', 'user_password_hash', 'user_password_reset_token', 'user_created_datetime', 'user_updated_datetime'], 'safe'],
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
        $query = User::find();

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
            'user_id' => $this->user_id,
            'user_status' => $this->user_status,
            'user_created_datetime' => $this->user_created_datetime,
            'user_updated_datetime' => $this->user_updated_datetime,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'user_auth_key', $this->user_auth_key])
            ->andFilterWhere(['like', 'user_password_hash', $this->user_password_hash])
            ->andFilterWhere(['like', 'user_password_reset_token', $this->user_password_reset_token]);

        return $dataProvider;
    }
}
