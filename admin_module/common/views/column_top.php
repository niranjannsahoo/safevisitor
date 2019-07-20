<navigation>
	<div id="menu">
	<?php echo $menu; ?>
	</div>
</navigation>
<?php js_start(); ?>
<script type="text/javascript">
		$(document).ready(function() {
			$('#menu > ul').superfish({
				hoverClass	 : 'sfHover',
				pathClass	 : 'overideThisToUse',
				delay		 : 0,
				animation	 : {height: 'show'},
				speed		 : 'normal',
				autoArrows   : false,
				dropShadows  : false, 
				disableHI	 : false, /* set to true to disable hoverIntent detection */
				onInit		 : function(){},
				onBeforeShow : function(){},
				onShow		 : function(){},
				onHide		 : function(){}
			});
			
			$('#menu > ul').css('display', 'block');
		});
	</script>
	<?php js_end(); ?>
            