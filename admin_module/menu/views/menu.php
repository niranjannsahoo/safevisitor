<div class="row">
	<div class="col-md-8">
		<ul id="menu-group">
			<?php foreach((array)$menu_groups as $key => $value) : ?>
			<li id="group-<?php echo $value['id']; ?>" class="<?=($value['id']==$menu_group_id)?'current':''?>"> <a href="<?php echo admin_url("menu/index/{$value['id']}"); ?>"> <?php echo $value['title']; ?> </a> </li>
			<?php endforeach; ?>
			<li id="add-group"><a class="groupaction" href="<?php echo admin_url('menu/group'); ?>" title="Add Menu Group">+</a></li>
		</ul>
		<div class="clear"></div>
		<?php echo form_open($action,array('class' => 'form-horizontal', 'id' => 'form-menu','role'=>'form')); ?>
			<div class="row">
				<div class="col-md-3"><h4><strong>Title</strong></h4></div>
				<div class="col-md-4"><h4><strong>URL</strong></h4></div>
				<div class="col-md-3"><h4><strong>Class</strong></h4></div>
				<div class="col-md-2"><h4><strong>Action</strong></h4></div>
			</div>
			<div id="menu_area" class="dd">
			<?php echo $menu; ?>
			</div>
			<div id="ns-footer">
				<button type="submit" class="btn btn-primary" id="btn-save-menu">Update Menu</button>
			</div>
			<br />
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-4">
		<div class="box info">
			<h2>Info</h2>
			<section>
			  <p>Drag the menu list to re-order, and click <b>Update Menu</b> to save the position.</p>
			  <p>To add a menu, use the <b>Add Menu</b> form below.</p>
			</section>
		</div>
		<div class="box">
			<h2>Current Menu Group</h2>
			<section> 
				<span id="edit-group-input"><?php echo $menugroup['title']; ?></span> (ID: <b><?php echo $menu_group_id; ?></b>)
				<div id="edit-group" > <a class="groupaction" href="<?=admin_url("menu/group/$menu_group_id")?>">Edit</a>
				 <?php if ($menu_group_id > 1) : ?>
				 &middot; <a id="delete-group" href="#">Delete</a>
				 <?php endif; ?>
			  </div>
			</section>
		</div>
		<div class="box">
			<h2>Add Menu</h2>
			<section>
				<form id="form-add-menu" method="post" action="<?=admin_url('menu/add')?>">
					<div class="form-group">
						<label for="menu-title">Title</label>
						<input type="text" class="form-control" id="menu-title" name="title" placeholder="Title">
					</div>
					<div class="form-group">
						<label for="menu-url">URL</label>
						<input type="text" class="form-control" id="menu-url" name="url" placeholder="URL">
					</div>
					<div class="form-group">
						<label for="menu-class">Class</label>
						<input type="text" class="form-control" id="menu-class" name="class" placeholder="Class">
					</div>
					
					<p class="buttons">
						<input type="hidden" name="menu_group_id" value="<?php echo $menu_group_id; ?>">
						<button id="add-menu" type="submit" class="btn btn-primary">Add Menu</button>
					</p>
			  </form>
			</section>
		</div>
	</div>
</div>		
<?php js_start(); ?>
<script type="text/javascript">
	$(function() {
		
	function admin_url(url) {
		return ADMIN_URL + url;
	}
	var menu_serialized; 
	var updateOutput = function(e) {
		
		var list = e.length ? e : $(e.target),
		output = list.data('output');
		if(window.JSON) {
			menu_serialized=window.JSON.stringify(list.nestable('serialize'));//, null, 2));
		}
		else {
			menu_serialized='';
		}
		//console.log(menu_serialized);
	};
	$('#menu_area').nestable({
		listNodeName:'ul',
		group: 1,
		collapsedClass:'',
		
	}).on('change', updateOutput);
	
	$('#form-menu').submit(function() {
		$('#btn-save-menu').attr('disabled', true);
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {menu:menu_serialized},
			error: function() {
				$('#btn-save-menu').attr('disabled', false);
				gbox.show({
					content: '<h2>Error</h2>Save menu error. Please try again.',
					autohide: 1000
				});
			},
			success: function(data) {
				gbox.show({
					content: '<h2>Success</h2>Menu position has been saved',
					autohide: 1000
				});
			}
		});
		return false;
	});
	
	$('.groupaction').click(function() {
		url=$(this).attr('href');
		gbox.show({
			type: 'ajax',
			url: $(this).attr('href'),
			buttons: {
				'Save': function() {
					var group_title = $('#menu-group-title').val();
					if (group_title == '') {
						$('#menu-group-title').focus();
					} else {
						$.ajax({
							type: 'POST',
							url: url,
							data: 'title=' + group_title,
							error: function() {
								//$('#gbox_ok').attr('disabled', false);
							},
							success: function(data) {
								//$('#gbox_ok').attr('disabled', false);
								switch (data.status) {
									case 1:
										gbox.hide();
										if(data.action=='edit'){
											$('#menu-group').find("#group-" + data.id +" a" ).text(group_title) ;
											$('#edit-group-input').text(group_title);
										}else{
											$('#menu-group').append('<li id="group-"'+data.id+'"><a href="' + admin_url('menu/index/' + data.id) + '">' + group_title + '</a></li>');	
										}
										break;
									case 2:
										$('<span class="error"></span>')
											.text(data.msg)
											.prependTo('#gbox_footer')
											.delay(1000)
											.fadeOut(500, function() {
												$(this).remove();
											});
										break;
									case 3:
										$('#menu-group-title').val('').focus();
										break;
								}
							}
						});
					}
				},
				'Cancel': gbox.hide
			}
		});
		return false;
	});
	
	/* delete menu group
	------------------------------------------------------------------------- */
	$('#delete-group').click(function() {
		var group_title = $('#menu-group li.current a').text();
		var param = { menu_group_id : '<?=$menu_group_id?>' };
		gbox.show({
			content: '<h2>Delete Group</h2>Are you sure you want to delete this group?<br><b>'
				+ group_title +
				'</b><br><br>This will also delete all menus under this group.',
			buttons: {
				'Yes': function() {
					$.post(admin_url('menu/delete'), param, function(data) {
						if (data.success) {
							window.location = admin_url('menu');
						} else {
							gbox.show({
								content: 'Failed to delete this menu.'
							});
						}
					});
				},
				'No': gbox.hide
			}
		});
		return false;
	});
	
	/* edit menu
	------------------------------------------------------------------------- */
	$('#menu_area').on('click',".edit-menu" ,function(event) {
		
		var menu_id = $(this).closest('.dd-item').data('id');
		var menu_div = $(this).closest('.dd-item');
		console.log(menu_div);
		gbox.show({
			type: 'ajax',
			url: admin_url('menu/edit/' + menu_id),
			buttons: {
				'Save': function() {
					$.ajax({
						type: 'POST',
						url: $('#gbox form').attr('action'),
						data: $('#gbox form').serialize(),
						dataType:'json',
						success: function(json) {
							$('.text-danger').remove();
							if(json['server_errors']) {
								for (i in json['server_errors']) {
									var element = $('#gbox form ').find('#edit-menu-' + i.replace('_', '-'));

									if ($(element).parent('p')) {
										$(element).parent().after('<div class="text-danger">' + json['server_errors'][i] + '</div>');
									} else {
										$(element).after('<div class="text-danger">' + json['server_errors'][i] + '</div>');
									}
								}
							}else{
								gbox.hide();
								menu_div.find('.title').eq(0).html(json.menu.title);
								menu_div.find('.url').eq(0).html(json.menu.url);
								menu_div.find('.class').eq(0).html(json.menu.class);
							}
						}
					});
				},
				'Cancel': gbox.hide
			}
		});
		return false;
	});
	
	/* add menu
	------------------------------------------------------------------------- */
	$('#form-add-menu').submit(function() {
		if ($('#menu-title').val() == '') {
			$('#menu-title').focus();
		} else {
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				dataType:'json',
				error: function() {
					gbox.show({
						content: 'Add menu error. Please try again.',
						autohide: 1000
					});
				},
				success: function(json) {
					$('.text-danger').remove();
					if(json['server_errors']) {
						for (i in json['server_errors']) {
							var element = $('#form-add-menu').find('#menu-' + i.replace('_', '-'));

							if ($(element).parent('.form-group')) {
								$(element).parent().after('<div class="text-danger">' + json['server_errors'][i] + '</div>');
							} else {
								$(element).after('<div class="text-danger">' + json['server_errors'][i] + '</div>');
							}
						}
					}else{
						
						switch (json.menu.status) {
							case 1:
								$('#form-add-menu')[0].reset();
								$('#menu_area > ul').append(json.menu.li);
								break;
							case 2:
								gbox.show({
									content: json.menu.msg,
									autohide: 1000
								});
								break;
							case 3:
								$('#menu-title').val('').focus();
								break;
						}
					}
					
					
					
				}
			});
		}
		return false;
	});
	
	/* delete menu
	------------------------------------------------------------------------- */
	$('#menu_area').on('click',".delete-menu" ,function(event) {
		event.preventDefault();
		
		var li = $(this).closest('li');
		var param = { menu_id : $(li).data('id') };
		
		var menu_title = $(li).find('.title').text();
		gbox.show({
			content: '<h2>Delete Menu</h2>Are you sure you want to delete this menu?<br><b>'
				+ menu_title +
				'</b><br><br>This will also delete all submenus under this menu.',
			buttons: {
				'Yes': function() {
					$.post(admin_url('menu/deleteMenuItem'), param, function(data) {
						if (data.success) {
							gbox.hide();
							li.remove();
						} else {
							gbox.show({
								content: 'Failed to delete this menu.'
							});
						}
					});
				},
				'No': gbox.hide
			}
		});
		return false;
	});
});
</script>
<?php js_end(); ?>