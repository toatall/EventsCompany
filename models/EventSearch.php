<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Html;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    
    public $term = '';
    public $error = null;
    
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
            [['term', 'org_code'], 'safe'],
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
            'sort'=> ['defaultOrder' => ['date_activity'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 30,
            ],
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
     * Public search events
     * @param string $term
     * @return \yii\data\ActiveDataProvider
     */
    public function searchLike($params)
    {        
        $this->load($params);
        if ($this->org_code == null)
        {
            $org = Organization::find()->one();            
            if ($org==null)  
            {
                $this->error = "Необходимо добавить организацию!<br />" . Html::a('Управление организациями', ['organization/index']);
            }
            else
            {
                $this->org_code = $org->code;
            }            
        }
        
        $this->saveTerm();
        
        $model = self::find()
            ->alias('t')
            ->leftJoin('ec_member m_users', 't.id=m_users.id_event and m_users.type_member='.Event::EVENT_TYPE_MEMBER_USERS)
            ->leftJoin('ec_member m_organizations', 't.id=m_organizations.id_event and m_organizations.type_member='.Event::EVENT_TYPE_MEMBER_ORGANIZATIONS)
            ->leftJoin('ec_member m_others', 't.id=m_others.id_event and m_others.type_member='.Event::EVENT_TYPE_MEMBER_OTHERS)           
            ->andWhere('t.date_delete is null')
            ->andWhere('t.org_code=:org_code', [':org_code'=>$this->org_code])
            ->andWhere(['or', 
                ['like', 't.theme', $this->term],
                ['like', 't.location', $this->term],
                ['like', 'm_users.text', $this->term],
                ['like', 'm_organizations.text', $this->term],
                ['like', 'm_others.text', $this->term],
            ])
            ->groupBy(['id', 'date1', 'date2', 'date_activity', 'theme', 'location', 'is_photo', 'is_video', 'photo_path', 'video_path', 
                'username', 'log_change', 'tags', 'thumbnail', 'date_create', 'date_update', 'date_delete', 'org_code', 'description']);
                    
        return new ActiveDataProvider([
            'query' => $model,            
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort'=> ['defaultOrder' => ['date_activity'=>SORT_DESC]],
        ]);
    }
       
}
