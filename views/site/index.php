<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use app\models\Organization;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var $this yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\EventSearch $searchModel
 *
 * */

$this->title = 'Электронный архив';
?>
<?php Pjax::begin(['id'=>'search-form']) ?>
<?php $form = ActiveForm::begin([
    'action' => ['site/index'],
    'id' => 'form-search',
    'options' => ['data-pjax' => true ],
    'method' => 'get',
]) ?>

    <div class="jumbotron" style="padding-top: 0;">
    	<h1 class="page-header"><?= $this->title ?></h1>
    	<div class="page-header col-md-8 col-md-offset-2">
    		<?php echo $form->field($searchModel, 'org_code')->widget(Select2::className(), [
        	    'data' => Organization::allOrganizaions(),    		    
        	    'name' => 'organization',        	    
                'size'=>Select2::LARGE,
        	])->label(false) ?>
        	<br />
    	</div>
    	<div class="row">
        	<div class="well col-sm-8 col-md-offset-2">    		    			      
                <div class="form-group">
                    <div class="input-group">
                        <!-- input type="text" name="term" id="term" class="form-control" placeholder="Поиск..." value="<?= $searchModel->term ?>" /-->
                        <?= Html::activeTextInput($searchModel, 'term', ['class'=>'form-control', 'placeholder'=>'Поиск...'])  // $form->field($searchModel, 'term')->textInput(['placeholder'=>'Поиск...'])->label(false) ?>
                        <div class="input-group-btn">
                        	<a type="button" class="btn btn-default" role="button" title="Настройки поиска" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch" style="height: 34px; font-size: 14px; padding: 6px 12px;">
                        		<i class="glyphicon glyphicon-menu-hamburger"></i>
                        	</a>
                            <button type="submit" class="btn btn-primary" style="height: 34px; font-size: 14px; padding: 6px 12px;">
    							<i class="glyphicon glyphicon-search"></i> Поиск
    						</button>
                        </div>
                    </div>
                    <div class="row collapse" id="collapseSearch">
                    	<br />
                    	<span class="alert alert-info">Дополнительные параметры поиска</span>
                    </div>
                </div>            
        	</div>
    	</div>
    	<hr />
    </div>

<?php ActiveForm::end() ?>



<div class="site-index" id="events-conrainer">
	<div class="row">
		<div class="col-md-1 col-lg-1"></div>
			<div class="col-md-10 col-lg-10">
            <?php                    
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '../event/_indexAjax',
                    'layout' => "<div class=\"row\">{pager}</div><div class=\"clearfix\"></div>\n{summary}\n{items}\n<div class=\"clearfix\"></div><div class=\"row\">{pager}</div>",
                ]);
                
            ?>
    	</div>
    	<div class="col-md-1 col-lg-1"></div>
    </div>
</div>
<script type="text/javascript">
    $('#<?= Html::getInputId($searchModel, 'org_code') ?>').change(function() {		        
    	$('#form-search').submit();    	
    });	
</script>

<?php 
    Pjax::end(); 
?>


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
<script type="text/javascript">

	/*
    $("#search-form").on("pjax:end", function() {
        $.pjax.reload({
            container:"#events-listview"
        });  
    });*/
   
    $("body").on('click', 'a.modal-link', function () {
        ajaxJson($(this).attr('href'), '#modal-title', '#modal-body');
        $('#modal-dialog').modal('show');            
        return false;
    });
	/*
	$(document).ready(function () {
		
		var url = '<?= Url::to(['event/index', 'term'=>'_term_', 'organization'=>'_organization_']) ?>';
		var organization = $('#organization').val();
		var term = $('#term').val();
		
		loadEvents(replaceUrl(url, term, organization));

		// change organization
		$('#organization').change(function() {			
			loadEvents(replaceUrl(url, '', $(this).val()));
		}); 
		
	});
	*/
	$(document).on('pjax:send', function() {
	  	alert('1');
	});
	$(document).on('pjax:complete', function() {
	  	alert('2');
	});
    
</script>

