<?php

namespace app\controllers;

use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['view', 'index_ajax'],
                        'allow' => true,
                        'roles' => ['admin', 'moderator', 'user'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'query-remove', 'query',
                            'memberuserslist', 'listtheme', 'listlocation', 'listmemberusers'],
                        'allow' => true,
                        'roles' => ['admin', 'moderator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    
    
    
    /** -----------------------------------------------------------------------
     *  < ACTIONS >
     */
    
    
    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (\Yii::$app->request->isAjax)
            return $this->renderPartial('indexAjax', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'pagination' => $dataProvider->getPagination(),
            ]);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax)
        {
            $resultJson = [
                'title'=>$model->theme,
                'content' => $this->renderPartial('viewJson', ['model'=>$model]),
            ];
            return Json::encode($resultJson);
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($org=null)
    {
        $model = new Event();
        
        if ($org!=null)
            $model->org_code=$org;
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->thumbnailImage = UploadedFile::getInstance($model, 'thumbnailImage');
            $model->uploadThumbnail();
                        
            if ($model->save())
            {
                $model->attachmentFiles = UploadedFile::getInstances($model, 'attachmentFiles');
                $model->uploadAttachmentFiles();
                if (\Yii::$app->request->isAjax)
                {
                    $resultJson = [
                        'title'=>$model->theme,
                        'content' => $this->renderAjax('resultSuccess', ['message'=>'Событие успешно сохранено!']),
                    ];
                    return Json::encode($resultJson);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        if (\Yii::$app->request->isAjax)
        {
            $resultJson = [
                'title' => 'Новое событие',
                'content' => $this->renderAjax('create', ['model'=>$model]),
            ];
            return Json::encode($resultJson);            
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->thumbnailImage = UploadedFile::getInstance($model, 'thumbnailImage');
            $model->attachmentFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->uploadThumbnail();
            if ($model->save())
            {               
                $model->deleteSelectedAttachemnts();
                $model->attachmentFiles = UploadedFile::getInstances($model, 'attachmentFiles');                
                $model->uploadAttachmentFiles();
                if (\Yii::$app->request->isAjax)
                {
                    $resultJson = [
                        'title'=>$model->theme,
                        'content' => $this->renderAjax('resultSuccess', ['message'=>'Событие успешно сохранено!']),
                    ];
                    return Json::encode($resultJson);
                }                
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        if (\Yii::$app->request->isAjax)
        {
            $resultJson = [
                'title'=>$model->theme,
                'content' => $this->renderAjax('update', ['model'=>$model]),
            ];
            return Json::encode($resultJson);            
        }
            
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        if (!\Yii::$app->request->isAjax)
            return $this->redirect(['index']);
    }
    
    /*
    public function actionIndex_ajax($term=null, $organization=null, $column=null, $sort=null)
    {        
        
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->searchLike($term);
        print_r($dataProvider->getModels());exit;
        
        
        // find first 30 records
        $result = new \yii\db\Query();
        $result = $result->from('ec_event t')->limit(30);
        // sort
        if ($sort != null && in_array($sort, ['date_activity-', 'date_activity', 'id', 'id-']))
        {
            $sort = str_replace('-', ' desc', $sort);
        }
        else 
        {
            $sort = 'date_activity desc';
        }
        
        // where
        if ($column!=null && in_array($column, ['all', 'term', 'description', 'location', 'member_users', 
            'member_organizations', 'member_others', 'user_on_photo', 'user_on_video', 'date_activity']))
        {
            if ($column == 'all')
            {
                $result = $result->where('theme=:term1 or ');
            }
            else
            {
                
            }
        } 
        elseif ($term!=null)
        {
            exit('term elseif');
        }
        
        $result = $result->all();
        
        // search years
        $years = []; $modelByYears = [];     
        foreach ($result as $r)
        {
            $y = (isset($r['date_activity']) ? date('Y', strtotime($r['date_activity'])) : '');
            if (!in_array($y, $years))
                $years[] = $y;    
            $modelByYears[$y][] = $r;
        }
        return $this->renderAjax('indexAjax', [
            'years'=>$years,
            'model'=>$result,
            'modelByYears' => $modelByYears,
        ]);
    }
    */
    
    /**
     * Return list themes
     * @param string $term
     * @return string
     */
    public function actionListtheme($term=null)
    {        
        return json::encode(Event::listField('theme', $term));
    }
    
    /**
     * Return list locations
     * @param string $term
     * @return string
     */
    public function actionListlocation($term=null)
    {        
        return Json::encode(Event::listField('location', $term));
    }
    
    /**
     * List member_users
     * @param string $term
     * @return string
     */
    public function actionMemberuserslist($term = null)
    {
        $termFlag = ($term != null) && (!empty($term));
        
        $model = Event::find()->select('member_users');
        if ($termFlag)
        {
            $model = $model->where(['like', 'member_users', $term]);
        }
        $model = $model->asArray()->all();
        
        $resultList = [];
        foreach ($model as $m)
        {
            $tempUsers = preg_split('/,/', $m['member_users']);
            $resultList = array_merge($resultList, $tempUsers);
        }
        
        $resultList = array_unique($resultList);
        sort($resultList);
        if ($termFlag)
        {
            $resultList = array_filter($resultList, function ($value) use ($term) {
                return strpos($value, $term) !== false;
            });
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Json::encode($resultList);
    }
    
    /**
     * 
     * @param string $q
     * @return string
     */
    /*
    public function actionListmemberusers($q=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // 1. get all data from db
        $query = new \yii\db\Query();
        
        $query = $query->select('member_users')->from('ec_event');
        
        if ($q != null)
            $query->where(['like', 'member_users', $q]);
        
        $result = $query->all();
        
        // 2. convert rows to list
        $resultList = [];
        foreach ($result as $r)
        {
            if ($r['member_users'] == null) continue;
            $tempUsers = explode(',', $r['member_users']);                        
            $resultList = array_merge($resultList, $tempUsers);
        }
        
        // 3. distinct array and sort
        $resultList = array_unique($resultList);        
        sort($resultList);
                
        if ($q != null)
        {
            $resultList = array_filter($resultList, function ($value) use ($q) {                
                return mb_strripos($value, $q) !== false;
            });
        }
        $result['results'] = array_values($resultList);
        return $result;      
        //return Json::encode($resultList);        
    }
    */
    
    
    public function actionQueryRemove($id)
    {
        $model = $this->findModel($id);
        
        $resultJson = [
            'title'   => $model->theme,
            'content' => $this->renderPartial('remove', ['model'=>$model]),
        ];
        return Json::encode($resultJson);
    }
    
    public function actionQuery($term)
    {
        if ($term==null)
            return null;
        
        $query = new \yii\db\Query();
        $resultQuery = $query->from('ec_query')
            ->distinct(true)            
            ->where(['like', 'text', $term])
            ->orWhere(['like', 'text_right', $term])
            ->all();
        
        return Json::encode(ArrayHelper::map($resultQuery, 'text_right', 'text_right'));
    }
        
        
    /** 
     *  < / ACTIONS >
     *  -----------------------------------------------------------------------
     */
    
    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    
}
