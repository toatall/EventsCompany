<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление мероприятиями';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить мероприятие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'theme',
            'date_activity',
            'date1',
            'date2',
            //'description:ntext',
            //'member_users',
            //'member_organizations',
            //'is_photo',
            //'is_video',
            //'photo_path',
            //'video_path',
            //'date_create',
            //'date_update',
            //'date_delete',
            //'user_login',
            //'log_change',
            //'tags',            
            //'thumbnail',
            //'location',
            //'members_other',
            //'user_on_photo',
            //'user_on_video',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
