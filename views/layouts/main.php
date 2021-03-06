<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/css/all.min.css" rel="stylesheet"></link>
    <?= $this->registerJsFile('/js/bootstrap-datepicker.min.js', ['position' => yii\web\View::POS_END]) ?>
    <?= $this->registerJsFile('/js/bootstrap-datepicker.ru.min.js', ['position' => yii\web\View::POS_END]) ?>
    <?= $this->registerJsFile('/js/site.js', ['position' => yii\web\View::POS_END]) ?>
    <?= $this->registerJsFile('/js/typeahead.bundle.min.js', ['position' => yii\web\View::POS_END]) ?>
    <link href="/css/timeline.css" rel="stylesheet"></link>
    <link href="/css/flip.css" rel="stylesheet"></link>
    <link href="/css/up_button.css" rel="stylesheet"></link>
    
    <?= $this->registerJsFile('/js/up_button.js') ?>
</head>
<body>
<?php $this->beginBody() ?>

<a id="button-up">
	<span class="fas fa-angle-up"></span>
</a>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Организации', 'url' => ['/organization/index'], 'visible'=>Yii::$app->user->can('admin')],
            ['label' => 'Пользователи', 'url' => ['/user/index'], 'visible'=>Yii::$app->user->can('admin')],
            ['label' => 'Управление мероприятиями', 'url' => ['/event/index'], 
                'visible'=>Yii::$app->user->can('moderator') || Yii::$app->user->can('admin')],
            Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                        )
                    . Html::endForm()
                    . '</li>'
                    ),                
        ],
    ]);
    NavBar::end();
    ?>
	<div class="container-fluid" style="margin-top: 80px;">   		
    	<div class="row">
    		<div class="col-md-1 col-lg-1"></div>
    		<div class="col-md-10 col-lg-10">
        		      
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>                      
                <?= Alert::widget() ?>
                <?= $content ?>
                
    		</div>
    		<div class="col-md-1 col-lg-1"></div>
    	</div>	
	</div>			
    
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; УФНС России по ХМАО-Югре <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
