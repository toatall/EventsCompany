<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date1')->textInput() ?>

    <?= $form->field($model, 'date2')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'member_users')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_organizations')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_photo')->textInput() ?>

    <?= $form->field($model, 'is_video')->textInput() ?>

    <?= $form->field($model, 'photo_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_create')->textInput() ?>

    <?= $form->field($model, 'date_update')->textInput() ?>

    <?= $form->field($model, 'date_delete')->textInput() ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'log_change')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_activity')->textInput() ?>

    <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'members_other')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_on_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_on_video')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
