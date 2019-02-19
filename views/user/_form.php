<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Organization;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userfio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rolename')->widget(Select2::className(), [       
        'data'=>$model->allRoles,
    ]) ?>  
    
    <div class="well" id="organizations_div">
    	<?= $form->field($model, 'organizations')->checkboxList(Organization::allOrganizaions()) ?>
    </div>         
    	
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	$('#<?= Html::getInputId($model, 'rolename') ?>').on('change', function() {		
		$('#organizations_div').toggle($(this).val() !== 'admin');
	});
	$('#organizations_div').toggle($('#<?= Html::getInputId($model, 'rolename') ?>').val() !== 'admin');	
</script>
