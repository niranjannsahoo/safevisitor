<div class="container">
	<div class="row">
		<div class="col-md-5 no-float">
			<div class="panel panel-default">
				<div class="panel-heading"> 
					<h4 class="text-center"><strong>Login</strong> </h4>
				</div> 
				<div class="panel-body">
					<?php echo form_open(null,array('class' => 'form-horizontal m-t-10', 'id' => 'form-signin','role'=>'form')); ?>
					
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-username"><?php echo $entry_username; ?></label>    
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" name="username" id="input-username" class="form-control"  value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" required="" autofocus="">
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-password"><?php echo $entry_password; ?></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" name="password" id="input-password" class="form-control" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" required="">
							</div> 
						</div>
					</div>
					<?php if ($redirect) { ?>
						<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					<?php } ?>
					<div class="form-group">
						<div class="col-sm-7">
							<!--<a href="pages-recoverpw.html"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>-->
						</div>
						<div class="col-sm-5 text-right">
							<button class="btn btn-primary w-lg waves-effect waves-light" type="submit"><i class="fa fa-key"></i> <?=$button_login?></button>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>                                 
				 
			</div>
		</div>
	</div>
</div>