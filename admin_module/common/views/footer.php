
<?php if ($this->user->isLogged()): ?>
<footer class="footer text-right"><span class="pull-left">Page rendered in <strong>{elapsed_time}</strong> seconds.</span> Copyright &copy; <?php echo date('Y'); ?>&nbsp; v<?php echo AIOADMIN_VERSION ?></footer>
<?endif; ?>

<div id="loading" style="display:none;">
<img src="<?php echo theme_url('assets/images/ajax-loader.gif');?>" alt="Loading">Processing...
</div>
<script>
   var resizefunc = [];
</script>

<!-- Main  -->
<script src="<?php echo theme_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/detect.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/fastclick.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/waves.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>

<script src="<?php echo theme_url('assets/js/jquery.app.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/common.js'); ?>"></script>	

<?php echo $this->template->footer_javascript() ?>
</body>
</html>