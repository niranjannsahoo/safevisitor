<div class="col-sm-12 col-md-6">
	<form name="contactform" id="contactforms" action="" method="post">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="text" class="form-control" name="name" value="<?=$name?>" placeholder="Name"/>
					<?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>				
				</div>
			</div>
        <div class="col-md-6">
            <div class="form-group">
					<input type="text" class="form-control"  name="email" value="<?=$email?>" placeholder="Email"/>
					<?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>				
				</div>
			</div>
			<div class="col-md-6">
            <div class="form-group">
					<input type="text" class="form-control"  name="phone" value="<?=$phone?>"  placeholder="Phone"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<input type="text" name="subject" class="form-control" value="<?=$subject?>"  placeholder="Subject"/>
					<?php echo form_error('subject', '<div class="text-danger">', '</div>'); ?>				
				</div>
			</div>
			<div class="col-sm-12 col-md-12">
            <div class="form-group">
					<textarea name="message"  class="form-control"  cols="45" rows="5" placeholder="Message"><?=$message?></textarea>
					<?php echo form_error('message', '<div class="text-danger">', '</div>'); ?>				
				</div>
			</div>
			<div class="col-sm-12 col-md-12">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
   </form>
</div>