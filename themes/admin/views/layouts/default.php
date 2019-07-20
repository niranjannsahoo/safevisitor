<?php echo Modules::run('common/header/index');?>
	<div class="content-page">
      <div class="content">
         <div class="container-fluid">
				<?php if ($this->user->isLogged()): ?>
				<div class="row">
					 <div class="col-sm-12">
						  <h4 class="pull-left page-title"><?php echo isset($heading_title)?$heading_title:""; ?></h4>
							<?php if(isset($breadcrumbs)){?>
							<ol class="breadcrumb pull-right">
								<li><a href="<?=base_url()?>"><i class="fa fa-home fa-lg"></i></a></li>
								<?php foreach ($breadcrumbs as $breadcrumb) { ?>
								<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
								<?php } ?>
							</ol>
							<?}?>
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
		</div>
	</div>
<?php echo Modules::run('common/footer/index'); ?>
