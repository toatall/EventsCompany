<?php
use app\models\Event;
use app\models\DateHelper;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @var Main $model
 */

?>
<?php 
    
    $year = date('Y', strtotime($model->date_activity));
    
    if (Event::$currentYear != $year)
    {
        Event::$currentYear = $year;
        ?>
        <div class="message-item">
            <div class="message">
                <p style="color: #428bca; font-size:60px; font-weight: bold;"><?= $year ?></p>
            </div>
        </div>
        <?php 
    }
?>
<div class="message-item">
	<div class="message-inner">				
		<div class="message-head clearfix breadcrumb">
			<div class="avatar pull-left">
				<a href="<?= Url::toRoute(['event/view', 'id'=>$model->id]) ?>" class="modal-link">
					<img src="<?= $model->thumbnailImageSrc ?>" style="width: 300px;" class="thumbnail">
				</a>
     		</div>
    		<div class="user-detail">
    			<p class="item-header"><?= $model->theme ?></p>
    			<div class="">
					<div class="activity-date"><b><?= date('d', strtotime($model->date_activity)) ?>
					<?= DateHelper::monthByIndex(date('m', strtotime($model->date_activity))) ?>
					<?= $year ?></b>
					</div>
				</div>    			
    		</div>
		</div>
		<div class="qa-message-content text-justify">	
			<div class="well" style="background: white;">
				
				<?php if ($model->member_users != null): ?>
					<p><?= Html::activeLabel($model, 'member_users') ?>: <?= $model->linksMemberUsers ?></p>
				<?php endif; ?>
				
				<?php if ($model->member_organizations != null): ?>
					<p><?= Html::activeLabel($model, 'member_organizations') ?>: <?= $model->linksMemberOrganizations ?></p>
				<?php endif; ?>
				
				<?php if ($model->member_others): ?>
					<p><?= Html::activeLabel($model, 'member_others') ?>: <?= $model->linksMemberOthers ?></p>
				<?php endif; ?>
				
				<?php if ($model->user_on_photo): ?>
					<p><?= Html::activeLabel($model, 'user_on_photo') ?>: <?= $model->linksUserOnPhoto ?>
				<?php endif; ?>
				
				<?php if ($model->user_on_video): ?>
					<p><?= Html::activeLabel($model, 'user_on_video') ?>: <?= $model->linksUserOnVideo ?>
				<?php endif; ?>
										
			</div>
			<hr />
			<div><a href="<?= Url::toRoute(['event/view', 'id'=>$model->id]) ?>" class="btn btn-primary modal-link">Подробнее</a></div>
		</div>
	</div>
</div>