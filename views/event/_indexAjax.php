<?php 
use app\models\DateHelper;
use yii\helpers\Url;
use app\models\Event;

    /**     
     * @var array $years
     * @var array $modelByYears
     * @var Event $model
     */
    
    
    if (Event::$currentYear != date('Y', strtotime($model->date_activity)))
    {
        
        Event::$currentYear = date('Y', strtotime($model->date_activity));
        ?>
        
        <div class="clearfix"></div>
		<div class="row page-header text-center">                
            <p style="color: #428bca; font-size:60px; font-weight: bold;"><?= Event::$currentYear ?></p>
    	</div>  
        <?php 
        
        /*
        if (Event::$currentYear != 0)
        {
            ?>
				</div>
        	</div>
        	<div class="col-md-1 col-lg-1"></div>        		       
		</div>
            <?php 
        }
        
        Event::$currentYear = date('Y', strtotime($model->date_activity));
        
        ?>
        <div class="row page-header text-center">                
            <p style="color: #428bca; font-size:60px; font-weight: bold;"><?= Event::$currentYear ?></p>
    	</div>        	
    	<div class="row">
    		<div class="col-md-1 col-lg-1"></div>
    		<div class="col-md-10 col-lg-10">
    			<div class="row">
        <?php 
        */
    }
    ?>

		<div class="well col-sm-4 col-md-4 col-lg-4" style="height:500px;">
        	<div class="article_snippet">
        		<div class="article_snippet__image_wrap">
            		<div class="article_snippet__image" style="wight:100%; height:100%; background: url('<?= Event::thumnnailImageSrc($model['thumbnail']) ?>'); background-position: 50%;"></div>
        		</div>
            	<div class="article_snippet__fade"></div>
        		<div class="article_snippet__info">
        			<div class="article_snippet__title">
        				<?= $model['theme'] ?>
        			</div>
        			<div>
        				<?php if ($model['is_photo']): ?>
            				<span class="label label-default">Фото</span>
            				<?php endif; ?>
            				<?php if ($model['is_video']): ?> 
            				<span class="label label-default">Видео</span>
            				<?php endif; ?>
        			</div>
        			<div class="article_snippet__author">
        				<strong>
        				<?= date('d', strtotime($model['date_activity'])) ?>
                    	<?= DateHelper::monthByIndex(date('m', strtotime($model['date_activity']))) ?>
                    	<?= date('Y', strtotime($model['date_activity'])) ?>
                    	</strong>
                    	<hr />            	
            		</div>
            		<div class="btn-group article_snippet__bookmark_button">
                		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                			<i class="glyphicon glyphicon-pencil"></i> <span class="caret"></span>
                		</button>
						<ul class="dropdown-menu">
                            <li><a href="#">Изменить</a></li>
                            <li><a href="#">Удалить</a></li>                                           
						</ul>
            		</div>
        			<a href="<?= Url::toRoute(['event/view', 'id'=>$model['id']]) ?>" class="modal-link btn btn-default article_snippet__read_btn" data-pjax="0">
        				<i class="glyphicon glyphicon-flash"></i> Смотреть
        			</a>
        		</div>	
        	</div>
        </div>
                        

