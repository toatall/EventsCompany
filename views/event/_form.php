<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Organization;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="main-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off']]); ?>
        
   	<?= $form->errorSummary($model) ?>
   	
   	<?= $form->field($model, 'org_code')->dropDownList(ArrayHelper::map(Organization::find()->all(), 'code', 'full')) ?>

    <?= $form->field($model, 'theme')->widget(Typeahead::className(), [        
        'pluginOptions' => [
            'allowClear' => true,            
            'highlight'=>true,
            'scrollable' => true,           
        ],
        'dataset'=>[
            [                
                'remote' => [
                    'url'=>Url::to(['event/listtheme', 'term'=>'_QUERY_']),
                    'wildcard'  => '_QUERY_',
                ],
                'datumTokenizer' => "Bloodhound.tokenizers.whitespace('term')",
                'queryTokenizer' => "Bloodhound.tokenizers.whitespace",
            ],
        ],
        
    ]) ?>
    
    <?= $form->field($model, 'location')->widget(Typeahead::className(), [        
        'pluginOptions' => [
            'allowClear' => true,            
            'highlight'=>true,
            'scrollable' => true,           
        ],
        'dataset'=>[
            [                
                'remote' => [
                    'url'=>Url::to(['event/listlocation', 'term'=>'_QUERY_']),
                    'wildcard'  => '_QUERY_',
                ],
                'datumTokenizer' => "Bloodhound.tokenizers.whitespace('term')",
                'queryTokenizer' => "Bloodhound.tokenizers.whitespace",
            ],
        ],
        
    ]) ?>    

    <?= $form->field($model, 'date_activity')->textInput(['data-type'=>'date']) ?>

    <?= $form->field($model, 'date1')->textInput(['data-type' => 'date']) ?>

    <?= $form->field($model, 'date2')->textInput(['data-type' => 'date']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
		
    
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
	
	
	<?= $form->field($model, 'member_users')->widget(Select2::classname(), [               
            'data'=> $model->tagsMemberUsers(),            
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
                'tokenSeparators' => [',', ';', '/'],
            ],            
        ]);
    ?>
     
	<?= $form->field($model, 'member_organizations')->widget(Select2::classname(), [               
            'data'=> $model->tagsMemberOrganizations(),            
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
                'tokenSeparators' => [',', ';', '/'],
            ],            
        ]);
     ?>    
     
     <?= $form->field($model, 'member_others')->widget(Select2::classname(), [               
            'data'=> $model->tagsMemberOrhers(),            
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
                'tokenSeparators' => [',', ';', '/'],
            ],            
        ]);
     ?>   

    <?= $form->field($model, 'is_photo')->checkBox() ?>
    
    <div class="panel panel-default" id="panel-photo">
  		<div class="panel-body">
        	<?= $form->field($model, 'photo_path')->textInput() ?>
        	<?= $form->field($model, 'user_on_photo')->widget(Select2::classname(), [               
                'data'=> $model->tagsUserOnPhoto(),            
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                    'tokenSeparators' => [',', ';', '/'],
                    ],            
                ]);
             ?>  
    	</div>
    </div>

    <?= $form->field($model, 'is_video')->checkBox() ?>
	
	<div class="panel panel-default" id="panel-video">
  		<div class="panel-body">
  			<?= $form->field($model, 'video_path')->textInput() ?> 
  			<?= $form->field($model, 'user_on_video')->widget(Select2::classname(), [               
                'data'=> $model->tagsUserOnVideo(),            
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                    'tokenSeparators' => [',', ';', '/'],
                    ],            
                ]);
             ?>  
  		</div>
  	</div>
       
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

		var chkPhoto = '#<?= Html::getInputId($model, 'is_photo') ?>';		
		$('#panel-photo').toggle($(chkPhoto).prop('checked'));
		$(chkPhoto).change(function() {
			$('#panel-photo').slideToggle($(this).prop('checked'));
		});

		var chkVideo = '#<?= Html::getInputId($model, 'is_video') ?>';
		$('#panel-video').toggle($(chkVideo).prop('checked'));
		$(chkVideo).change(function() {
			$('#panel-video').slideToggle($(this).prop('checked'));
		});
				
	});
	

</script>