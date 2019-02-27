<?php

use yii\widgets\ListView;
use app\models\Organization;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;

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
    'options' => ['data-pjax' => true, 'autocomplete'=>'off'],
    'method' => 'get',
]) ?>

    <div class="jumbotron" style="padding-top: 0;">
    	<h1 class="page-header"><?= $this->title ?></h1>
    	<div class="page-header col-md-10 col-md-offset-1">
    		<?php echo $form->field($searchModel, 'org_code')->widget(Select2::className(), [
        	    'data' => Organization::allOrganizaions(),
        	    'name' => 'organization',
                'size'=>Select2::LARGE,
        	])->label(false) ?>
        	<br />
    	</div>
    	<div class="row">
        	<div class="well col-sm-10 col-md-offset-1">    		    			      
                <div class="form-group">
                    <div class="input-group">  
                    	<div class="text-left">                      
                        <?= Typeahead::widget([    
                                'model' => $searchModel,
                                'attribute' => 'term',
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'highlight'=>true,
                                    'scrollable' => true,
                                ],
                                'options' => [
                                    'placeholder'=>'Поиск...',
                                ],
                                'dataset'=>[
                                    [                
                                        'remote' => [
                                            'url'=>Url::to(['event/query', 'term'=>'_QUERY_']),
                                            'wildcard'  => '_QUERY_',
                                        ],
                                        'datumTokenizer' => "Bloodhound.tokenizers.whitespace('term')",
                                        'queryTokenizer' => "Bloodhound.tokenizers.whitespace",
                                    ],
                                ],
                                
                            ]) ?>
                            </div>
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
                    	<div class="form-horizontal col-sm-10">
                    		<div class="form-group">
                    			<?= Html::label('Поиск по', 'find_column', ['class'=>'col-sm-2 control-label']) ?>
                    			<div class="col-sm-10">
                    				<?= Html::dropDownList('find_column', null, [
                    				    '-'=>'Всем текстовым полям',
                    				    'theme'=>'Теме',
                    				    'description'=>'Описанию',
                    				    'location'=>'Месту проведения',
                    				    'member_user' => 'Участникам (сотрудникам)',
                    				    'member_organization' => 'Участникам (организациям)',
                    				    'member_other' => 'Участникам (сторонние)',
                    				    'user_on_photo' => 'Участникам на фотографии',
                    				    'user_on_video' => 'Участникам на видео',
                    				    'is_photo' => 'Наличию фотографий',
                    				    'is_video' => 'Наличию видеозаписей',                    				    
                    				],
                                    ['class'=>'form-control', 'id'=>'find_column']) ?>
                    			</div>
                    		</div>
                    		<div class="form-group">
                    			<?= Html::label('Сортировка', 'sort', ['class'=>'col-sm-2 control-label']) ?>
                    			<div class="col-sm-10">
                    				<?= Html::dropDownList('sort', null, ['-date_activity'=>'По дате (по убыванию)', 'date_activity'=>'По дате (по возрастанию)'],
                                        ['class'=>'form-control', 'id'=>'sort']) ?>
                    			</div>                    			
                    		</div>
                    	</div>
                    </div>                    
                </div>                 
        	</div>        	         
    	</div>
    	<div class="page-header"></div>
    	
    	<div class="page-header">
        	<?= Html::a('Добавить', ['event/create', 'org'=>'_org_'], ['class'=>'btn btn-success modal-link-create']) ?>
        	<br /><br />
        </div>        
    </div>

<?php ActiveForm::end() ?>
	

<div class="site-index" id="events-conrainer">
	<div class="row">
        <?php                    
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '../event/_indexAjax',
                'layout' => "<div class=\"row\">{pager}</div><div class=\"clearfix\"></div>\n{summary}\n{items}\n<div class=\"clearfix\"></div><div class=\"row\">{pager}</div>",
                'emptyText' => '<div class="col-md-8 col-md-offset-2 alert alert-warning text-center"><strong>Ничего не найдено</strong></div>',
            ]);
        ?>
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

<div class="modal fade" id="modal-dialog" role="dialog">
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
   
    $("body").on('click', 'a.modal-link', function () {
        ajaxJson($(this).attr('href'), '#modal-title', '#modal-body');
        $('#modal-dialog').modal('show');
        return false;
    });

    $("body").on('click', 'a.modal-link-create', function () {
        var href = $(this).attr('href');
        href = href.replace('_org_', $('#<?= Html::getInputId($searchModel, 'org_code') ?>').val());
        ajaxJson(href, '#modal-title', '#modal-body');
        $('#modal-dialog').modal('show');
        return false;
    });

/*
    $("body").on('submit', '#event-form', function() {
    	alert($(this).attr('action'));
		ajaxJson($(this).attr('action'),'#modal-title', '#modal-body', false, 'POST', $(this).serialize());		
		return false;
	});
   	
	
	$(document).on('pjax:send', function() {
	  	alert('1');
	});
	$(document).on('pjax:complete', function() {
	  	alert('2');
	});
    */
    
</script>

