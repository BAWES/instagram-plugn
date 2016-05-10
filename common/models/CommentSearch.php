<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;

/**
 * CommentSearch represents the model behind the search form about `common\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'media_id', 'comment_deleted'], 'integer'],
            [['comment_instagram_id', 'comment_text', 'comment_by_username', 'comment_by_photo', 'comment_by_id', 'comment_by_fullname', 'comment_deleted_reason', 'comment_datetime'], 'safe'],
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
        $query = Comment::find();

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
            'comment_id' => $this->comment_id,
            'media_id' => $this->media_id,
            'comment_deleted' => $this->comment_deleted,
            'comment_datetime' => $this->comment_datetime,
        ]);

        $query->andFilterWhere(['like', 'comment_instagram_id', $this->comment_instagram_id])
            ->andFilterWhere(['like', 'comment_text', $this->comment_text])
            ->andFilterWhere(['like', 'comment_by_username', $this->comment_by_username])
            ->andFilterWhere(['like', 'comment_by_photo', $this->comment_by_photo])
            ->andFilterWhere(['like', 'comment_by_id', $this->comment_by_id])
            ->andFilterWhere(['like', 'comment_by_fullname', $this->comment_by_fullname])
            ->andFilterWhere(['like', 'comment_deleted_reason', $this->comment_deleted_reason]);

        return $dataProvider;
    }
}
