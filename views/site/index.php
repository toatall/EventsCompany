<?php

use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * @var $this yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $term
 *
 * */

$this->title = 'Электронный архив';
?>
<div class="site-index">    
	<div class="well">
    	<div class="jumbotron">    	
        <h1><?= $this->title ?></h1>
        <p>УФНС России по Ханты-Мансийскому автономному округу - Югре</p>
        <form class="" action="<?= Url::toRoute('site/index') ?>" method="get" role="search">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="term" class="form-control" placeholder="Поиск..." value="<?= $term ?>" />
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-success" style="height: 34px; font-size: 14px; padding: 6px 12px;">Поиск</button>
                    </div>
                </div>                
            </div>
        </form>        
    	</div>
   </div>
	<hr />
	
	<div class="qa-message-list" id="wallmessages">
		<?php 
	       echo ListView::widget([
	           'dataProvider' => $dataProvider,
	           'itemView' => '_index',	   
	           //'summary'=>'',
	       ]); 		
		?>
	</div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('a.modal-link').on('click', function () {
            ajaxJson($(this).attr('href'), '#modal-title', '#modal-body');
            $('#modal-dialog').modal('show');
            return false;
        });
    });
</script>

<div class="modal fade" id="modal-dialog" tabindex="1" role="dialog">
    <div class="modal-dialog modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                <h2 id="modal-title" style="font-weight: bold">Load title...</h2>
            </div>
            <div class="modal-body" id="modal-body">
                Load body...
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
            </div>
        </div>
    </div>
</div>
