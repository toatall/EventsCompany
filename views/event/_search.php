<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'theme') ?>

    <?= $form->field($model, 'date1') ?>

    <?= $form->field($model, 'date2') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'member_users') ?>

    <?php // echo $form->field($model, 'member_organizations') ?>

    <?php // echo $form->field($model, 'is_photo') ?>

    <?php // echo $form->field($model, 'is_video') ?>

    <?php // echo $form->field($model, 'photo_path') ?>

    <?php // echo $form->field($model, 'video_path') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <?php // echo $form->field($model, 'date_delete') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'log_change') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'date_activity') ?>

    <?php // echo $form->field($model, 'thumbnail') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'members_other') ?>

    <?php // echo $form->field($model, 'user_on_photo') ?>

    <?php // echo $form->field($model, 'user_on_video') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
