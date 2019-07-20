<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href="assets/images/favicon.ico">
		<?php echo $this->template->metadata() ?>
      <!-- Base Css Files -->
		<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/bootstrap.min.css');  ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/font-awesome.min.css');?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/style.css');  ?>" />
		
		<!-- Controller Defined Stylesheets -->
      <?php echo $this->template->stylesheets() ?>
		
		<script src="<?php echo theme_url('assets/js/modernizr.min.js');  ?>"></script>       
		<script type="text/javascript">
         var BASE_URL = '<?php echo base_url(); ?>';
         var THEME_URL = '<?php echo theme_url(); ?>';
      </script>
      <!-- Controller Defined JS Files -->
      <?php echo $this->template->javascripts() ?>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
   <body class="<?=$class?>">
      <div class="mainheader">
			<div class="headertop">
				<div class="container">
					<div class="phone">
						<a href="tel:8888888888"><i class="fa fa-phone" aria-hidden="true"></i>888-888-8888</a>
					</div>
					<div class="social">
						<a href="#"><i class="fa fa-facebook"></i></a>
						<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
						<a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
						<a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
					</div>
					
					<div class="dropdown show-on-hover login-dropdown">
					<a href="#" class="login dropdown-toggle" data-toggle="dropdown" id="login"><i class="fa fa-user" aria-hidden="true"></i> <span class="hidden-xs"><?=$this->user->isLogged() ? 'Account':'Login'?></span></a>
					
					<ul class="dropdown-menu pull-right" aria-labelledby="login">
						<?php if ($this->user->isLogged()){ ?>
						<li><a href="<?php echo base_url('account'); ?>">My Account</a></li>
						<li><a href="<?php echo base_url('common/logout'); ?>">Logout </a></li>
						<?}else{?>
						<li><a href="<?php echo base_url('broker/login'); ?>">Broker</a></li>
						<li><a href="<?php echo base_url('contractor/login'); ?>">Contractor </a></li>
						<?}?>
					</ul>
					</div>
				</div>
			</div>
			<div class="header">
				<div class="container">
					<div class="row">
						<div class="col-xs-10 col-sm-4 col-md-3 logo">
							<?php if ($logo) { ?>
							<a href="<?php echo base_url(); ?>"><img class="img-responsive" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  /></a>
							<?php } else { ?>
							<a href="<?php echo base_url(); ?>"><span><?php echo $site_name; ?></span></a>
							<?php } ?>
						</div>
    
						<div class="col-xs-2 col-sm-8 col-md-9 menu">
							<button class="expand-btn visible-xs visible-sm" id="snav" type="button">
								<i class="fa fa-bars" aria-hidden="true"></i>
							</button>
							<div class="nav navmenu" id="nav">
								<?php echo $menu;?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="headerspace"></div>
		</div>    
         
		
		