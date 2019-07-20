<ul id="menu-group">
	<li id="add-content-group"><a id="add-member" href="<?php echo site_url('member/add'); ?>" title="Add Page">Add Member</a></li>
</ul>
<div class="row">
	 <div class="col-sm-12">
		  <div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
					<div class="panel-tools pull-right">
						<button type="submit" form="form-user" data-toggle="tooltip" title="save" class="btn btn-primary btn-sm"><i class="fa fa-save"></i></button>
						<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="cancel" class="btn btn-danger btn-sm"><i class="fa fa-reply"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart(null, 'id="form-user" class="form-horizontal"'); ?>
						<ul class="nav nav-tabs" id="tab-form">
							<li class="active"><a href="#tab-basic" data-toggle="tab">Basic</a></li>
							<li><a href="#tab-account" data-toggle="tab">Account Details</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-basic">
								<h3>Basic Information</h3>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-user-group">Member Type</label>
									<div class="col-md-10">
										<?php echo form_dropdown('user_group_id', option_array_value($user_groups, 'user_group_id', 'name'), set_value('user_group_id', $user_group_id),"id='user_group_id' class='form-control'"); ?>
										<?php echo form_error('user_group_id', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-firstname">Name</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'firstname', 'id' => 'input-firstname', 'placeholder'=>'Name','value' => set_value('firstname', $firstname))); ?>
										<?php echo form_error('firstname', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-email">Email</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>'Email','value' => set_value('email', $email))); ?>
										<?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-company">Company</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'company', 'id' => 'input-company', 'placeholder'=>'Company','value' => set_value('company', $company))); ?>
										<?php echo form_error('company', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-phone">Phone</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'phone', 'id' => 'input-phone', 'placeholder'=>'phone','value' => set_value('phone', $phone))); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-address">Address</label>
									<div class="col-md-10">
										<textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
									</div>
								</div>
								<div class="form-group" id="business-type-div" style="display:none;">
									<label class="col-sm-2 control-label" for="input-business-type">Type of Business</label>
									<div class="col-md-10">
										<?php echo form_dropdown('business_id', option_array_value($businesstypes, 'business_id', 'name'), set_value('business_id', $business_id),"id='business_id' class='form-control'"); ?>
										<?php echo form_error('business_id', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab-account">
								<h3>Account Information</h3>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-username">Username</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'username', 'id' => 'input-username', 'placeholder'=>'Username','value' => set_value('username', $username))); ?>
										<?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-password">Password</label>
									<div class="col-md-10">
										<?php echo form_input(array('class'=>'form-control','name' => 'password', 'id' => 'input-password', 'placeholder'=>'Password','value' => set_value('password', $password))); ?>
										<?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-status">Status</label>
									<div class="col-md-10">
										<?php  echo form_dropdown('enabled', array('1'=>'Enable','0'=>'Disable'), set_value('enabled',$enabled),array('class'=>'form-control','id' => 'input-status')); ?>
									</div>
								</div>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div> <!-- panel-body -->
		  </div> <!-- panel -->
	 </div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
	function initChangeEvent() {
	  $("#user_group_id").change(function() {
			//alert($(this).val());
			if($(this).val() == 3) {
				$("#business-type-div").show();
			} else {
				$("#business-type-div").hide();
			}
		});
	}

	$(function(){
		initChangeEvent();
		$("#user_group_id").trigger('change');  
	})
//--></script>
<?php js_end(); ?>