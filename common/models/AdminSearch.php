<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;

/**
 * AdminSearch represents the model behind the search form about `common\models\Admin`.
 */
class AdminSearch extends Admin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id'], 'integer'],
            [['admin_name', 'admin_email', 'admin_auth_key', 'admin_password_hash', 'admin_password_reset_token', 'admin_datetime'], 'safe'],
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
        $query = Admin::find();

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
            'admin_id' => $this->admin_id,
            'admin_datetime' => $this->admin_datetime,
        ]);

        $query->andFilterWhere(['like', 'admin_name', $this->admin_name])
            ->andFilterWhere(['like', 'admin_email', $this->admin_email])
            ->andFilterWhere(['like', 'admin_auth_key', $this->admin_auth_key])
            ->andFilterWhere(['like', 'admin_password_hash', $this->admin_password_hash])
            ->andFilterWhere(['like', 'admin_password_reset_token', $this->admin_password_reset_token]);

        return $dataProvider;
    }
}
