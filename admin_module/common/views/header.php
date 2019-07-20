<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		
		<link rel="shortcut icon" href="assets/images/favicon.ico">
		<base href="<?=base_url()?>"/>
		<?php echo $this->template->metadata() ?>
      <!-- Base Css Files -->
		
		<link href="<?php echo theme_url('assets/css/bootstrap.min.css');  ?>" rel="stylesheet" type="text/css" />
      <link href="<?php echo theme_url('assets/css/icons.css');  ?>" rel="stylesheet" type="text/css" />
      <link href="<?php echo theme_url('assets/css/style.css');  ?>" rel="stylesheet" type="text/css" />
		<!-- Controller Defined Stylesheets -->
      <?php echo $this->template->stylesheets() ?>
		
		<script src="<?php echo theme_url('assets/js/modernizr.min.js');  ?>"></script>       
		<script type="text/javascript">
			var BASE_URL = '<?php echo base_url(); ?>';
         var ADMIN_URL = '<?php echo admin_url(); ?>';
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
   <body class="fixed-left">
      <?php if ($this->user->isLogged()){ ?>
		<div id="wrapper">
			<!-- Top Bar Start -->
			<div class="topbar">
				 <!-- LOGO -->
				 <div class="topbar-left">
					  <div class="text-center">
							<a href="<?=admin_url()?>" class="logo">
								<?php if ($logo) { ?>
								<img width="100%" height="40px" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />
								<?php } ?>
								<!--<span><?php echo $site_name; ?></span>-->
							</a>
						</div>
				 </div>
				 <!-- Button mobile view to collapse sidebar menu -->
				 
				 <nav class="navbar navbar-default">
					  <div class="container-fluid">
							<ul class="list-inline menu-left mb-0">
								 <li class="float-left">
									  <a href="#" class="button-menu-mobile open-left">
											<i class="fa fa-bars"></i>
									  </a>
								 </li>
								 <li class="hide-phone float-left">
									  <form role="search" class="navbar-form">
											<input type="text" placeholder="Type here for search..." class="form-control search-bar">
											<a href="" class="btn btn-search"><i class="fa fa-search"></i></a>
									  </form>
								 </li>
							</ul>
 
							<ul class="nav navbar-right float-right list-inline">
								 <li>
									<a class="waves-effect waves-light" target="_blank" href="<?=base_url()?>"><i class="md md-public"></i></a>
				
								 </li>
								 <li class="dropdown d-none d-sm-block">
									  
									  <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
											<i class="md md-notifications"></i> <span class="badge badge-pill badge-xs badge-danger">3</span>
									  </a>
									  <ul class="dropdown-menu dropdown-menu-lg">
											<li class="text-center notifi-title">Notification</li>
											<li class="list-group">
												 <!-- list item-->
												 <a href="javascript:void(0);" class="list-group-item">
													  <div class="media">
															<div class="media-left pr-2">
															<em class="fa fa-user-plus fa-2x text-info"></em>
															</div>
															<div class="media-body clearfix">
															<div class="media-heading">New user registered</div>
															<p class="m-0">
																 <small>You have 10 unread messages</small>
															</p>
															</div>
													  </div>
												 </a>
												 <!-- list item-->
												 <a href="javascript:void(0);" class="list-group-item">
													  <div class="media">
															<div class="media-left pr-2">
															<em class="fa fa-diamond fa-2x text-primary"></em>
															</div>
															<div class="media-body clearfix">
															<div class="media-heading">New settings</div>
															<p class="m-0">
																 <small>There are new settings available</small>
															</p>
															</div>
													  </div>
												 </a>
												 <!-- list item-->
												 <a href="javascript:void(0);" class="list-group-item">
													  <div class="media">
															<div class="media-left pr-2">
															<em class="fa fa-bell-o fa-2x text-danger"></em>
															</div>
															<div class="media-body clearfix">
															<div class="media-heading">Updates</div>
															<p class="m-0">
																 <small>There are
																	  <span class="text-primary">2</span> new updates available</small>
															</p>
															</div>
													  </div>
												 </a>
												 <!-- last list item -->
												 <a href="javascript:void(0);" class="list-group-item">
													  <small>See all notifications</small>
												 </a>
											</li>
									  </ul>
								 </li>
								 <li class="d-none d-sm-block">
									  <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="md md-crop-free"></i></a>
								 </li>
								 <li class="d-none d-sm-block">
									  <a href="#" class="right-bar-toggle waves-effect waves-light"><i class="md md-chat"></i></a>
								 </li>
								 <li class="dropdown">
										<a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?=$profile_img?>" alt="user-img" class="rounded-circle"> </a>
										<ul class="dropdown-menu">
											 <li><a href="<?=$profile?>" class="dropdown-item"><i class="md md-face-unlock mr-2"></i> Profile</a></li>
											 <li><a href="<?=$settings?>"class="dropdown-item"><i class="md md-settings mr-2"></i> Settings</a></li>
											 <li><a href="<?=$logout?>" class="dropdown-item"><i class="md md-settings-power mr-2"></i> Logout</a></li>
										</ul>
								  </li>
								 
							</ul>
					  </div>
				 </nav>
			</div>
			<?php echo $menu;?>
		<?}?>