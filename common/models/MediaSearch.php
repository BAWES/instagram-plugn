<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Media;

/**
 * MediaSearch represents the model behind the search form about `common\models\Media`.
 */
class MediaSearch extends Media
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'user_id', 'media_num_comments', 'media_num_likes'], 'integer'],
            [['media_instagram_id', 'media_type', 'media_link', 'media_caption', 'media_image_lowres', 'media_image_thumb', 'media_image_standard', 'media_video_lowres', 'media_video_lowbandwidth', 'media_video_standard', 'media_location_name', 'media_location_longitude', 'media_location_latitude', 'media_created_datetime'], 'safe'],
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
        $query = Media::find();

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

        //Eager load with user to get name
        $query->with("user");

        // grid filtering conditions
        $query->andFilterWhere([
            'media_id' => $this->media_id,
            'user_id' => $this->user_id,
            'media_num_comments' => $this->media_num_comments,
            'media_num_likes' => $this->media_num_likes,
            'media_created_datetime' => $this->media_created_datetime,
        ]);

        $query->andFilterWhere(['like', 'media_instagram_id', $this->media_instagram_id])
            ->andFilterWhere(['like', 'media_type', $this->media_type])
            ->andFilterWhere(['like', 'media_link', $this->media_link])
            ->andFilterWhere(['like', 'media_caption', $this->media_caption])
            ->andFilterWhere(['like', 'media_image_lowres', $this->media_image_lowres])
            ->andFilterWhere(['like', 'media_image_thumb', $this->media_image_thumb])
            ->andFilterWhere(['like', 'media_image_standard', $this->media_image_standard])
            ->andFilterWhere(['like', 'media_video_lowres', $this->media_video_lowres])
            ->andFilterWhere(['like', 'media_video_lowbandwidth', $this->media_video_lowbandwidth])
            ->andFilterWhere(['like', 'media_video_standard', $this->media_video_standard])
            ->andFilterWhere(['like', 'media_location_name', $this->media_location_name])
            ->andFilterWhere(['like', 'media_location_longitude', $this->media_location_longitude])
            ->andFilterWhere(['like', 'media_location_latitude', $this->media_location_latitude]);

        return $dataProvider;
    }
}
