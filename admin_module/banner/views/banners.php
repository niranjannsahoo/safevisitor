<ul id="menu-group">
	<li id="add-content-group"><a id="add-banner" href="<?php echo admin_url('banner/add'); ?>" title="Add Page">Add Banner</a></li>
</ul>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-desktop"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-banner">        
					<div class="" id="ns-header">
						<table class="table" id="banner_list">
							<thead>
								<tr class="nodrag nodrop">
									<th>Banner Name</th>
									<th>Status</th>
									<th class="text-right no-sort">Actions</th>
								</tr>
							</thead>				
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#banner_list').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".banner_list_error").html("");
				$("#banner_list").append('<tbody class="banner_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#banner_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
//--></script>
<?php js_end(); ?>