<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'theme',
            'date1',
            'date2',
            'description:ntext',
            'member_users',
            'member_organizations',
            'is_photo',
            'is_video',
            'photo_path',
            'video_path',
            'date_create',
            'date_update',
            'date_delete',
            'username',
            'log_change',
            'tags',
            'date_activity',
            'thumbnail',
            'location',
            'members_other',
            'user_on_photo',
            'user_on_video',
        ],
    ]) ?>

</div>
