<?php echo Modules::run('common/header/index'); ?>
	<div class="content-page">
		<div class="container">
			<?php if(isset($error)){?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
				</div>
			<?}else if(validation_errors()){?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="fa fa-exclamation-circle"></i> <?php echo validation_errors(); ?>
				</div>
			<?}else if($this->session->flashdata('message')){?>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('message'); ?>
				</div>
			<?}?>		
		</div> <!-- container -->
		<?php echo $template['body']; ?>		
	</div>
<?php echo Modules::run('common/footer/index'); ?>
