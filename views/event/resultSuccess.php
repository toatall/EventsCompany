<?php 
    /**
     * @var string $message
     */
    
?>
<div class="alert alert-success">
	<?= $message ?>
	<br />	
</div>
<script type="text/javascript">
	var flagTimeout = true;
	setTimeout(function(){
		if (flagTimeout)
		{
            $.pjax.reload({container:'#search-form'});
            $('#modal-dialog').modal('hide');
		}
	},2000);
	
</script>