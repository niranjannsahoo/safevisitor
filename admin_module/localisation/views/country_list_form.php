<div class="row">
	<div class="col-sm-4">
		  <div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
					<div class="panel-tools pull-right">
						<button type="submit" form="form-country" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
						<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($action, 'id="form-country" class="form"'); ?>
						<div class="form-group required">
							<label class=" control-label" for="input-name">Country Name</label>
							<div class="">
								<?php echo form_input(array('class'=>'form-control','name' => 'name', 'id' => 'input-name', 'placeholder'=>'Country Name','value' => set_value('name', $name))); ?>
								<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group required">
							<label class=" control-label" for="input-name">Country Code</label>
							<div class="">
								<?php echo form_input(array('class'=>'form-control','name' => 'iso_code_2', 'id' => 'input-iso_code_2', 'placeholder'=>'Country Code','value' => set_value('iso_code_2', $iso_code_2))); ?>
							</div>
						</div>
						<div class="form-group">
							<label class=" control-label" for="input-status">Status</label>
							<div class="">
								<?php  echo form_dropdown('status', array('1'=>'Enable','0'=>'Disable'), set_value('status',$status),array('class'=>'form-control select2','id' => 'input-status')); ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div> <!-- panel-body -->
		  </div> <!-- panel -->
	 </div>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-desktop"></i> <?php echo $heading_title; ?></h3>
				
				<div class="panel-tools pull-right">
					<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-country').submit() : false;"><i class="fa fa-trash-o"></i></button>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-country">        
				<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap">
					<thead>
						<tr>
							<th style="width: 1px;" class="text-center no-sort"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
							<th>Country Name</th>
							<th>Country Code</th>
							<th>Country Status</th>
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