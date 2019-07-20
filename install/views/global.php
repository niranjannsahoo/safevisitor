<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8" /> 
			<title>Diggity CMS Install</title>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/reset.css'); ?>" />
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/style.css'); ?>" />
			<script type="text/javascript" src="<?php echo base_url('/assets/javascript/jquery/jquery-2.1.1.min.js'); ?>"></script>
			<link href="<?php echo base_url('/assets/javascript/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet" media="screen" />
			<script src="<?php echo base_url('/assets/javascript/bootstrap/js/bootstrap.js'); ?>" type="text/javascript"></script>
			<link href="<?php echo base_url('/assets/javascript/font-awesome/css/font-awesome.min.css'); ?>" type="text/css" rel="stylesheet" />
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/stylesheet/stylesheet.css'); ?>" />

	 </head>
    <body>
        <div class="container">

            

            <!-- Main Content -->
           
					<div id="content_wrapper" class="row">
					<div class="col-md-9" id="left_column">
						 
							  <?php echo $content; ?>
						
					</div>
					<div class="col-md-3" id="right_column">
						<br/>
						<p class="text-center">
						<img alt="Header Image" class="img-responsive" src="<?php echo base_url('/assets/images/logo.png'); ?>" />
						</p>
				
                    <ul>
                        <li <?php echo ($this->uri->segment(1) == 'step1') ? 'class="current"' : ''; ?>><span class="step">1.</span> License</li>
                        <li <?php echo ($this->uri->segment(1) == 'step2') ? 'class="current"' : ''; ?>><span class="step">2.</span> Pre-Installation</li>
                        <li <?php echo ($this->uri->segment(1) == 'step3') ? 'class="current"' : ''; ?>><span class="step">3.</span> Configuration</li>
                        <li <?php echo ($this->uri->segment(1) == 'step4') ? 'class="current"' : ''; ?>><span class="step">4.</span> Finished</li>
                    </ul>
					
					</div>
					</div>
					
          

            <footer>
            </footer>

        </div><!-- container -->
    </body>
</html>