<div class="container">
	<div class="row">
		<div class="col-md-5 no-float">
			<div class="panel panel-default">
				<div class="panel-heading"> 
					<h4 class="text-center"><strong>SignUp</strong> </h4>
				</div> 
				<div class="panel-body">
					<?php echo form_open(null,array('class' => 'form-horizontal', 'id' => 'form-register','role'=>'form')); ?>
					
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-name">Name</label>    
							<input type="text" name="name" id="input-name" class="form-control"  value="<?=$name?>" placeholder="Name">
							<input type="hidden" name="user_group_id" id="input-group" class="form-control"  value="<?=$user_group_id?>">
							<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>		
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-email">Email</label>    
							<input type="text" name="email" id="input-email" class="form-control"  value="<?=$email?>" placeholder="Email">
							<?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>		
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-company">Company</label>    
							<input type="text" name="company" id="input-company" class="form-control"  value="<?=$company?>" placeholder="Company"  >
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-phone">Phone number</label>    
							<input type="text" name="phone" id="input-phone" class="form-control"  value="<?=$phone?>" placeholder="Phone number"  >
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-phone">Address</label>    
							<textarea name="address" id="input-address" class="form-control" placeholder="Address"><?=$address?></textarea>
						</div>
					</div>
					<?php if($this->uri->segment(4)==3){?>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-business-type">Type of Business</label>    
							<select name="business_id" id="business_id" class="form-control">
								<option value="">Select Type of Business</option>
								<?php foreach($businesstypes as $businesstype){?>
								<option value="<?=$businesstype['business_id']?>" <?=($businesstype['business_id']==$business_id)?"selected='selected'":""?>><?=$businesstype['name']?></option>
								<?}?>
							</select>
							<?php echo form_error('business_id', '<div class="text-danger">', '</div>'); ?>		
						</div>
					</div>
					<?}?>
					<h4>Account Section</h4><hr />
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-username">Username</label>    
							<input type="text" name="username" id="input-username" class="form-control"  value="<?=$username?>" placeholder="Username"  >
							<?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>		
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<label for="input-password">Password</label>    
							<input type="password" name="password" id="input-password" class="form-control"  value="<?=$password?>" placeholder="Password"  >
							<?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
						</div>
					</div>
					<div class="text-center">
						<button class="btn btn-primary" type="submit">Continue</button>
					</div>
					<?php echo form_close(); ?>
				</div>                                 
			</div>
		</div>
	</div>
</div>
