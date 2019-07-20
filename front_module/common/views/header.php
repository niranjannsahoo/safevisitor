<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="assets/images/favicon.ico">
		<?php echo $this->template->metadata() ?>
		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Roboto:100,300,400,500,700|Philosopher:400,400i,700,700i" rel="stylesheet">

		<!-- Bootstrap css -->
		<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
		<link href="<?php echo theme_url('assets/lib/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">

		<!-- Libraries CSS Files -->
		<link href="<?php echo theme_url('assets/lib/owlcarousel/assets/owl.carousel.min.css');  ?>" rel="stylesheet">
		<link href="<?php echo theme_url('assets/lib/owlcarousel/assets/owl.theme.default.min.css');  ?>" rel="stylesheet">
		<link href="<?php echo theme_url('assets/lib/font-awesome/css/font-awesome.min.css');  ?>" rel="stylesheet">
		<link href="<?php echo theme_url('assets/lib/animate/animate.min.css');  ?>" rel="stylesheet">
		<link href="<?php echo theme_url('assets/lib/modal-video/css/modal-video.min.css');  ?>" rel="stylesheet">

		<!-- Main Stylesheet File --> 
		<link href="<?php echo theme_url('assets/css/style.css');  ?>" rel="stylesheet">

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
	<!-- NAVBAR================================================== -->
	<body class="<?=$class?>">
		<header id="header" class="header header-hide">
			<div class="container">

				<div id="logo" class="pull-left">
					<h1><a href="#body" class="scrollto"><?php echo $site_name; ?></a></h1>
					<!-- Uncomment below if you prefer to use an image logo -->
					<!-- <a href="#body"><img src="img/logo.png" alt="" title="" /></a>-->
				</div>

				<nav id="nav-menu-container">
					<?php echo $menu;?>
					<!--<ul class="nav-menu">
					 <li class="menu-active"><a href="#hero">Home</a></li>
					 <li><a href="#about-us">About</a></li>
					 <li><a href="#features">Features</a></li>
					 <li><a href="#screenshots">Screenshots</a></li>
					 <li><a href="#team">Team</a></li>
					 <li><a href="#pricing">Pricing</a></li>
					 <li class="menu-has-children"><a href="">Drop Down</a>
						<ul>
						  <li><a href="#">Drop Down 1</a></li>
						  <li><a href="#">Drop Down 3</a></li>
						  <li><a href="#">Drop Down 4</a></li>
						  <li><a href="#">Drop Down 5</a></li>
						</ul>
					 </li>
					 <li><a href="#blog">Blog</a></li>
					 <li><a href="#contact">Contact</a></li>
					</ul>-->
				</nav><!-- #nav-menu-container -->
			</div>
		</header><!-- #header -->
		
	