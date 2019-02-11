<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_photo', 'is_video'], 'integer'],
            [['theme', 'date1', 'date2', 'description', 'member_users', 'member_organizations', 'photo_path', 
                'video_path', 'date_create', 'date_update', 'date_delete', 'username', 'log_change', 'tags', 
                'date_activity', 'thumbnail', 'location', 'members_other', 'user_on_photo', 'user_on_video'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Event::find();

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
            'id' => $this->id,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'is_photo' => $this->is_photo,
            'is_video' => $this->is_video,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            'date_delete' => $this->date_delete,
            'date_activity' => $this->date_activity,
        ]);

        $query->andFilterWhere(['like', 'theme', $this->theme])
            ->andFilterWhere(['like', 'description', $this->description])
            //->andFilterWhere(['like', 'member_users', $this->member_users])
            //->andFilterWhere(['like', 'member_organizations', $this->member_organizations])
            ->andFilterWhere(['like', 'photo_path', $this->photo_path])
            ->andFilterWhere(['like', 'video_path', $this->video_path])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'log_change', $this->log_change])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'location', $this->location])
            /*->andFilterWhere(['like', 'members_other', $this->members_other])
            ->andFilterWhere(['like', 'user_on_photo', $this->user_on_photo])
            ->andFilterWhere(['like', 'user_on_video', $this->user_on_video])*/;

        return $dataProvider;
    }
    
    /**
     * Поиск мероприятий
     * @param string $term
     * @return \yii\data\ActiveDataProvider
     */
    public function searchLike($term)
    {
        $model = self::find();
        if ($term != null)
        {
            $model = $model->where(['like', 'theme', $term])
            ->orWhere(['like', 'member_users', $term])
            ->orWhere(['like', 'member_organizations', $term])
            ->orWhere(['like', 'location', $term])
            ->orWhere(['like', 'members_other', $term])
            ->orWhere(['like', 'user_on_photo', $term])
            ->orWhere(['like', 'user_on_video', $term]);
        }
        return new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
    }
}
