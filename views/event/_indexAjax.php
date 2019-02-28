<?php 
use app\models\DateHelper;
use yii\helpers\Url;
use app\models\Event;
use yii\bootstrap\Html;

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
            		<?php if (Yii::$app->user->identity->isAllow($model['org_code'], ['moderator', 'admin'])): ?>
            		<div class="btn-group article_snippet__bookmark_button">
                		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                			<i class="glyphicon glyphicon-pencil"></i> <span class="caret"></span>
                		</button>
						<ul class="dropdown-menu">
                            <li>                            	
                            	<?= Html::a('Изменить', ['event/update', 'id'=>$model['id']], ['class'=>'modal-link']) ?>
                            </li>
                            <li>
                            	<?= Html::a('Удалить', ['event/query-remove', 'id'=>$model['id']], ['class'=>'modal-link']) ?>                            
                            </li>                                           
						</ul>
            		</div>
            		<?php endif; ?>
        			<a href="<?= Url::toRoute(['event/view', 'id'=>$model['id']]) ?>" class="modal-link btn btn-default article_snippet__read_btn" data-pjax="0">
        				<i class="glyphicon glyphicon-flash"></i> Смотреть
        			</a>
        		</div>	
        	</div>
        </div>
                        

