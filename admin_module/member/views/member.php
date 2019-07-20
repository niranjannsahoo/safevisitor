<ul id="menu-group">
	<li id="add-content-group"><a id="add-member" href="<?php echo site_url('member/add'); ?>" title="Add Page">Add Member</a></li>
</ul>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-desktop"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-member">        
				<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap">
					<thead>
						<tr>
							<th>Username</th>
							<th>Name</th>
							<th>Email</th>
							<th>Status</th>
							<th class="no-sort">Action</th>
						</tr>
					</thead>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
$(function(){
	$('#datatable').DataTable({
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ targets: 'no-sort', orderable: false }
		],
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".datatable-error").html("");
				$("#datatable").append('<tbody class="datatable-error"><tr><th colspan="3">No data found.</th></tr></tbody>');
				$("#datatable_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
});
//--></script>
<?php js_end(); ?>