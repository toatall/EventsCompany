<?php
use app\models\Event;
use yii\bootstrap\Html;

/**
 * @var Event $model
 */

?>

<h4>Вы уверены, что хотите удалить это событие?</h4><br />
<?= Html::a('Удалить', ['event/delete', 'id'=>$model->id], ['class' => 'btn btn-danger', 'id'=>'del']) ?>

<script type="text/javascript">
	$("#del").on('click',function() {		
		$.post($(this).attr('href')).done(function() {
			$.pjax.reload({container:'#search-form'});
			$('#modal-dialog').modal('hide');
		});
		return false;			
	});
</script>