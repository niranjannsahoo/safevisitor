<div class="row">
	 <div class="col-sm-12">
		  <div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
					<div class="panel-tools pull-right">
						<button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
						<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart(null, 'id="form-user" class="form-horizontal"'); ?>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'username', 'id' => 'input-username', 'placeholder'=>$entry_username,'value' => set_value('username', $username))); ?>
								<?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
							<div class="col-md-10">
								<?php echo form_dropdown('user_group_id', option_array_value($user_groups, 'user_group_id', 'name'), set_value('user_group_id', $user_group_id),"id='input-user-group' class='form-control select2'"); ?>
								<?php echo form_error('user_group_id', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'firstname', 'id' => 'input-firstname', 'placeholder'=>$entry_firstname,'value' => set_value('firstname', $firstname))); ?>
								<?php echo form_error('firstname', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'lastname', 'id' => 'input-lastname', 'placeholder'=>$entry_lastname,'value' => set_value('lastname', $lastname))); ?>
								<?php echo form_error('lastname', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'email', 'id' => 'input-email', 'placeholder'=>$entry_email,'value' => set_value('email', $email))); ?>
								<?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
							<div class="col-sm-10">
								<div class="fileinput">
									<div class="thumbnail" style="width: 130px; height: 130px;">
										<img src="<?php echo $thumb_image; ?>" alt="" id="thumb_image" />
										<input type="hidden" name="image" value="<?php echo $image?>" id="image" />
									</div>
									<div>
										<a class="btn btn-primary btn-xs" onclick="image_upload('image','thumb_image')"><?php echo $text_image; ?></a>
										<a class="btn btn-danger btn-xs" onclick="$('#thumb_image').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
							<div class="col-md-10">
								<?php echo form_input(array('class'=>'form-control','name' => 'password', 'id' => 'input-password', 'placeholder'=>$entry_password,'value' => set_value('password', $password))); ?>
								<?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
							<div class="col-md-10">
								<?php  echo form_dropdown('enabled', array('1'=>'Enable','0'=>'Disable'), set_value('enabled',$enabled),array('class'=>'form-control select2','id' => 'input-status')); ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div> <!-- panel-body -->
		  </div> <!-- panel -->
	 </div> <!-- col -->
</div>
<?php js_start(); ?>
<script type="text/javascript"><!--
	function image_upload(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("assets/");
				var fileName=url.substring(lastSlash+7);
				url=url.replace("images", ".thumbs/images"); 
				$('#'+thumb).attr('src', "<?=base_url()?>"+ url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
				/*$.post('<?php echo site_url('setting/create-thumb'); ?>', {'image_path': fileName}, function(image_path) {
					$('#'+thumb).attr('src', image_path);
					$('#'+field).attr('value', fileName);
					$.colorbox.close();
				});*/
			}
		};
		$.colorbox({href:THEME_URL+"assets/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
	};
//--></script>
<?php js_end(); ?>