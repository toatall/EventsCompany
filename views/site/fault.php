<?php 
    /**
     * @var string $message
     * @var yii\web\Controller $this
     */

    $this->title = "Ошибка";
?>
<div class="row">
	<div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
		<div class="alert alert-danger">
			<h4 class=""><?= $this->title ?></h4>
			<?= $message ?>
		</div>
	</div>
</div>
