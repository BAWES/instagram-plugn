<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InstagramUser;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class InstagramUserSearch extends InstagramUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_status', 'user_instagram_id', 'user_media_count', 'user_following_count', 'user_follower_count'], 'integer'],
            [['user_name', 'user_fullname', 'user_auth_key', 'user_created_datetime', 'user_updated_datetime', 'user_profile_pic', 'user_bio', 'user_website', 'user_ig_access_token'], 'safe'],
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
        $query = InstagramUser::find();

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
            'user_id' => $this->user_id,
            'user_status' => $this->user_status,
            'user_created_datetime' => $this->user_created_datetime,
            'user_updated_datetime' => $this->user_updated_datetime,
            'user_instagram_id' => $this->user_instagram_id,
            'user_media_count' => $this->user_media_count,
            'user_following_count' => $this->user_following_count,
            'user_follower_count' => $this->user_follower_count,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_fullname', $this->user_fullname])
            ->andFilterWhere(['like', 'user_auth_key', $this->user_auth_key])
            ->andFilterWhere(['like', 'user_profile_pic', $this->user_profile_pic])
            ->andFilterWhere(['like', 'user_bio', $this->user_bio])
            ->andFilterWhere(['like', 'user_website', $this->user_website])
            ->andFilterWhere(['like', 'user_ig_access_token', $this->user_ig_access_token]);

        return $dataProvider;
    }
}
