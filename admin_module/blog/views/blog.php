<ul id="menu-group">
	<li id="add-content-group"><a id="add-cast" href="<?php echo site_url('blog/add'); ?>" title="Add Page">Add Artist</a></li>
</ul>
<div class="row cast">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-desktop"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-cast">        
					<div class="" id="ns-header">
						<table class="table" id="cast_list">
							<thead>
								<tr class="nodrag nodrop">
									<th>Order</th>
									<th>Artist Name</th>
									<th>id</th>
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
	var datatable=$('#cast_list').DataTable({
		"pageLength": 50,
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ 
				"targets":[2], 
				"visible": false,
				"searchable": false 
			}
		],
		"rowReorder": {
            selector: '.sorting_1',
			update:false
        },     
		"ajax":{
			url :"<?=$datatable_url?>", // json datasource
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".cast_list_error").html("");
				$("#cast_list").append('<tbody class="cast_list_error"><tr><th colspan="5">No data found.</th></tr></tbody>');
				$("#cast_list_processing").css("display","none");
				
			},
			dataType:'json'
		},
	});
	datatable.on( 'row-reorder', function ( e, diff, edit ) {    
		var result = {};
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            var rowData = datatable.row( diff[i].node ).data();
            result[rowData[2]] = diff[i].newData;
        }
		 $.ajax({		
			url :"<?=$datatable_reorder_url?>", // json datasource
			type: "post",  // method  , by default get
			data:{sortdata:result},			
			 success: function (data) {
				datatable.ajax.reload( null, false );
			}
		})
    } );
});
//--></script>
<?php js_end(); ?>