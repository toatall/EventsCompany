<?php 
    /**
     * @var ActiveDataProvider $dataProvider
     */

use yii\widgets\ListView;

?>

<div class="row">
	<div class="col-md-1 col-lg-1"></div>
	<div class="col-md-10 col-lg-10">
<?php    
    
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_indexAjax',
        'layout' => "<div class=\"row\">{pager}</div><div class=\"clearfix\"></div>\n{summary}\n{items}\n<div class=\"clearfix\"></div><div class=\"row\">{pager}</div>",
    ]);
    
?>
	</div>
	<div class="col-md-1 col-lg-1"></div>
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