<?php

use yii\widgets\DetailView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

?>
<div class="main-view">
    
    
    <?php 
        $attributes = [];
        $attributes[] = 'id';
        $attributes[] = [
            'attribute' => 'thumbnail',
            'value' => function($data) {
            return '<a href="' . $data->thumbnailImageSrc . '" target="_blank"><img src="' . $data->thumbnailImageSrc . '" style="max-width: 800px;" /></a>';
            },
            'format' => 'raw',
            ];
        $attributes[] = 'theme';
        $attributes[] = 'location';
        $attributes[] = 'date_activity';
        $attributes[] = 'date1';
        $attributes[] = 'date2';
        $attributes[] = 'description:ntext';
        $attributes[] = [
            'attribute'=>'member_users',
            'value'=>$model->linksMemberUsers,
            'format'=>'raw',
        ];
        $attributes[] = [
            'attribute'=>'member_organizations',
            'value'=>$model->linksMemberOrganizations,
            'format'=>'raw',
        ];
        $attributes[] = [
            'attribute'=>'members_other',
            'value'=>$model->linksMemberOthers,
            'format'=>'raw',
        ];
        
        if ($model->is_photo)
        {
            $attributes[] = 'is_photo:boolean';
            $attributes[] = [
                'attribute'=>'user_on_photo',
                'value'=>$model->linksUserOnPhoto,
                'format'=>'raw',
            ];
            $attributes[] = [
                'attribute' => 'photo_path',
                'value' => function ($data) {
                return Html::a($data->photo_path, $data->photo_path, ['target'=>'_blank']);
                },
                'format'=>'raw',
            ];
        }
        
        if ($model->is_video)
        {
            $attributes[] = 'is_video:boolean';
            $attributes[] = [
                'attribute'=>'user_on_video',
                'value'=>$model->linksUserOnVideo,
                'format'=>'raw',
            ];
            $attributes[] = [
                'attribute' => 'video_path',
                'value' => function ($data) {
                return Html::a($data->video_path, $data->video_path, ['target'=>'_blank']);
                },
                'format'=>'raw',
            ];
        }
        
        if ($model->files != null && count($model->files) > 0)
        {
            $attributes[] = [
                'attribute' => 'attachmentFiles',
                'value'=>$model->attachmentFileWithUri,
                'format'=>'raw',
            ];
        }
        
        $attributes[] = 'date_create';
        $attributes[] = 'date_update';
        $attributes[] = 'username';
        $attributes[] = 'userProfile.userfio';
        
    
    ?>
    
     
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes, 
    ]) ?>

</div>
