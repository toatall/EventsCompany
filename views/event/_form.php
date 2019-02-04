<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="main-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data']]); ?>
        
   	<?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'theme')->textInput() ?>

    <?= $form->field($model, 'location')->textInput() ?>

    <?= $form->field($model, 'date_activity')->textInput(['data-type'=>'date']) ?>

    <?= $form->field($model, 'date1')->textInput(['data-type' => 'date']) ?>

    <?= $form->field($model, 'date2')->textInput(['data-type' => 'date']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'member_users')->textInput() ?>
    
    <div class="alert alert-warning">
    <?= $form->field($model, 'thumbnailImage')->fileInput() ?>
    
    <?php
    if (!$model->isNewRecord)
    {
        $fieldThumbnail = $form->field($model, 'delThumbnail');
        echo ($model->thumbnail != null ? $fieldThumbnail->checkbox() : $fieldThumbnail->hiddenInput()->label(false));    
    }
    ?>
    
	</div>
	
    <?= $form->field($model, 'member_organizations')->textInput() ?>

    <?= $form->field($model, 'is_photo')->checkBox() ?>

    <?= $form->field($model, 'is_video')->checkBox() ?>

    <?= $form->field($model, 'photo_path')->textInput() ?>

    <?= $form->field($model, 'video_path')->textInput() ?>        
    
    <?= $form->field($model, 'members_other')->textInput() ?>

    <?= $form->field($model, 'user_on_photo')->textInput() ?>

    <?= $form->field($model, 'user_on_video')->textInput() ?>
    
    <div class="alert alert-warning">
    	<?= $form->field($model, 'attachmentFiles[]')->fileInput(['multiple'=>true]) ?>
    	
    	<?php if (!$model->isNewRecord && $model->files != null): ?>
    		<div class="panel panel-default">    			
    			<div class="panel-body">
    				<?= $form->field($model, 'delAttachmentFiles')->checkboxList(ArrayHelper::map($model->files, 'id', 'filename')) ?>
    			</div>
    		</div>
    	<?php endif; ?>
    	
	</div>
	
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

	$(document).ready(function() {

		
		
		$('input[data-type="date"]').datepicker({
			todayBtn: true,
			language: "ru",
			autoclose: true,
			todayHighlight: true
		});

		/*
		var s = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.whitespace,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: ['photo_path', 'user_on_photo', 'user_on_video']
		});

		$('.typeahead').typeahead(null, {
			name: 'aaa',
			source: s
		}); */
	});
	

</script>