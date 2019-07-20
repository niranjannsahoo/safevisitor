<?php echo form_open_multipart(null, 'id="form-page"'); ?>
<div class="row">
	<div class="col-xl-9">
		<div class="card">
			<div class="card-header ">
				<h3 class="card-title float-left"><?php echo $text_form; ?></h3>
				
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="title">Title</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'title', 'id' => 'title', 'placeholder'=>'Title','value' => set_value('title', $title))); ?>
					<?php echo form_error('title', '<div class="text-danger">', '</div>'); ?>		
				</div>
				<div class="form-group">
					<label for="title">Content</label>
					<textarea name="content" rows="10" cols="40" class="ckeditor_textarea form-control" id = "content"><?=$content?></textarea>
				</div>	
			</div> 
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Meta Details</h3>
			</div>
			<div class="card-body"> 
				<div class="form-group">
					<label for="slug" >Seo Url</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'slug', 'id' => 'slug', 'placeholder'=>'Seo url','value' => set_value('slug', $slug))); ?>
					<?php echo form_error('slug', '<div class="text-danger">', '</div>'); ?>		
				</div>
				<div class="form-group">
					<label for="meta_title" >Meta Title</label>
					<?php echo form_input(array('class'=>'form-control','name' => 'meta_title', 'id' => 'meta_title', 'placeholder'=>'Meta Title','value' => set_value('meta_title', $meta_title))); ?>
				</div>
				<div class="form-group">
					<label for="meta_keywords" >Meta Keywords</label>
					<?php echo form_textarea(array('name'  => 'meta_keywords','class' => 'form-control','id' => 'meta_keywords','rows'=>'3','value'=>set_value('meta_keywords',$meta_keywords))); ?>
				</div>
				<div class="form-group">
					<label for="meta_description" >Meta Description</label>
					<?php echo form_textarea(array('name'  => 'meta_description','class' => 'form-control','id' => 'meta_description','rows'=>'3','value'=>set_value('meta_description',$meta_description))); ?>
				</div>
			</div>
		</div>
			
	</div> 
	<div class="col-xl-3">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Publish</h3>
			</div>
			<div class="card-body"> 
				<div class="form-group">
					<label for="status" >Status</label>
					<?php echo form_dropdown('status', array('published'=>'Published', 'draft'=>'Draft', 'disabled' => 'Disabled'), set_value('status', $status), 'id=\'status\' class=\'form-control\'')?>
				</div>
				<div class="form-group">
					<label for="visibilty" >Visibilty</label>
					<?php echo form_dropdown('visibilty', array('public'=>'Public', 'private'=>'Private', 'password protected' => 'Password Protected'), set_value('visibilty', $visibilty), 'id=\'visibilty\' class=\'form-control\'')?>
				</div>
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-secondary">Preview</button>
				
				<button type="submit" form="form-page" class="btn btn-primary float-right">Save</button>
					
			</div>
		</div>
		
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Page Attribute</h3>
			</div>
			<div class="card-body">
				
				<div class="form-group">
					<label for="template">Template</label>
					<?php echo form_dropdown('layout', $layouts, set_value('layout', $layout), 'id="layout" class="form-control"'); ?>
				</div>
				
				<div class="form-group">
					<label for="parent">Parent</label>
					<?php echo form_dropdown('parent_id', option_array_value($parents, 'id', 'title','No Parent'), set_value('parent_id', $parent_id),array('class'=>'form-control','id'=>'parent_id')); ?>
				</div>
				
				<div class="form-group">
					<label for="sort-order">Sort Order</label>
					<?php echo form_input(array('name' => 'sort_order', 'class'=>'form-control', 'id' => 'sort_order','value' => set_value('sort_order', $sort_order))); ?>
					
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left">Feature Image</h3>
			</div>
			<div class="card-body"> 
				<div class="form-group">
					<label for="input-image">Feature Image</label>
					<div class="fileinput">
						<div class="thumb" style="width: 130px; height: 130px;">
							<img src="<?php echo $thumb_feature_image; ?>" alt="" id="thumb_feature_image" />
							<input type="hidden" name="feature_image" value="<?php echo $feature_image?>" id="feature_image" />
						</div>
						<div>
							<a class="btn btn-primary btn-xs" onclick="image_upload('feature_image','thumb_feature_image')"><?php echo $text_image; ?></a>
							<a class="btn btn-danger btn-xs" onclick="$('#thumb_feature_image').attr('src', '<?php echo $no_image; ?>'); $('#feature_image').attr('value', '');"><?php echo $text_clear; ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('textarea.ckeditor_textarea').each(function(index) {
			
			ckeditor_config.height = $(this).height();
			
			CKEDITOR.replace($(this).attr('name'), ckeditor_config); 
		});
		
		$('#title').keyup( function(e) {
			$('#slug').val($(this).val().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, ''))
		});
	});
//--></script>
<script type="text/javascript"><!--
	function image_upload(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("uploads/");
				
				var fileName=url.substring(lastSlash+8);
				url=url.replace("images", ".thumbs/images"); 
				$('#'+thumb).attr('src', url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
			}
		};
		$.colorbox({href:BASE_URL+"storage/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
	};
//--></script>

<?php js_end(); ?>