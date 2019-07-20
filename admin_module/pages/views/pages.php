<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-book').submit() : false;"><i class="fa fa-trash-o"></i></button>
				</div>
			</div>
			<div class="card-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-pages">        
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<table id="page_list" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
							<thead>
								<tr>
									<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
									<th>Title</th>
									<th>URL</th>
									<th>Template</th>
									<th>Status</th>
									<th class="text-right no-sort">Actions</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#page_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".page_list_error").html("");
				$("#page_list").append('<tbody class="page_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#page_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
function delete_page(title,id){

	gbox.show({
		content: '<h2>Delete Manager</h2>Are you sure you want to delete this Manager?<br><b>'+title,
		buttons: {
			'Yes': function() {
				$.post('<?php echo admin_url('members.delete');?>',{user_id:id}, function(data) {
					if (data.success) {
						gbox.hide();
						$('#member_list').DataTable().ajax.reload();
					} else {
						gbox.show({
							content: 'Failed to delete this Manager.'
						});
					}
				});
			},
			'No': gbox.hide
		}
	});
	return false;
}
//--></script>
<?php js_end(); ?>