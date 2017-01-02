<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agency;

/**
 * AgencySearch represents the model behind the search form about `common\models\Agency`.
 */
class AgencySearch extends Agency
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id', 'agency_email_verified', 'agency_status', 'agency_trial_days'], 'integer'],
            [['agency_fullname', 'agency_company', 'agency_email', 'agency_auth_key', 'agency_password_hash', 'agency_password_reset_token', 'agency_limit_email', 'agency_created_at', 'agency_updated_at'], 'safe'],
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
        $query = Agency::find();

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
            'agency_id' => $this->agency_id,
            'agency_email_verified' => $this->agency_email_verified,
            'agency_limit_email' => $this->agency_limit_email,
            'agency_status' => $this->agency_status,
            'agency_trial_days' => $this->agency_trial_days,
            'agency_created_at' => $this->agency_created_at,
            'agency_updated_at' => $this->agency_updated_at,
        ]);

        $query->andFilterWhere(['like', 'agency_fullname', $this->agency_fullname])
            ->andFilterWhere(['like', 'agency_company', $this->agency_company])
            ->andFilterWhere(['like', 'agency_email', $this->agency_email])
            ->andFilterWhere(['like', 'agency_auth_key', $this->agency_auth_key])
            ->andFilterWhere(['like', 'agency_password_hash', $this->agency_password_hash])
            ->andFilterWhere(['like', 'agency_password_reset_token', $this->agency_password_reset_token]);

        return $dataProvider;
    }
}
