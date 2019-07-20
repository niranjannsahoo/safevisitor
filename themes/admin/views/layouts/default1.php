<?php echo Modules::run('common/header/index');?>
	<div class="container-fluid">
		<?php if ($this->user->isLogged()): ?>
		<div class="row">
			<div class="col-sm-12">
				<h2 class="pull-left page-title"><?php echo isset($heading_title)?$heading_title:""; ?></h2>			
			</div>
		</div>
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
		<?endif; ?>
		<?php echo $template['body']; ?>			
	</div> <!-- container -->               
<?php echo Modules::run('common/footer/index'); ?>
