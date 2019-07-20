<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>Add Gallery Images</h3>
				<div class="panel-tools pull-right">
					<button type="submit" form="form-banner" data-toggle="tooltip" title="Add page" class="btn btn-primary btn-sm"><i class="fa fa-save"></i></button>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open_multipart(null, 'id="form-banner" class="form-horizontal"'); ?>			
				<table id="banner_images" class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="nodrag nodrop">
							<th style="width: 20px;">Drag</th>
							<th class="text-left">Image</th>
							<th class="text-left">Name</th>
							<th class="text-right">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $image_row = 0; ?>
						<?php foreach ($gallery_images as $gallery_image) { ?>
						<tr id="image-row<?php echo $image_row; ?>">
							<td class="drag_handle" style="padding:0;text-align:center;"><i class="fa fa-arrows" aria-hidden="true" style="font-size:20px;"></i></td>
							<td class="text-left">
								<div class="fileinput">
									<div class="thumbnail file-browse">
										<img src="<?php echo $gallery_image['thumb']; ?>" alt="" id="thumb-image<?php echo $image_row; ?>" />
										<input type="hidden" name="gallery_image[<?php echo $image_row; ?>][image]" value="<?php echo $gallery_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
									</div>
									<div class="btn-group" role="group">
										<a class="btn btn-primary btn-xs" onclick="image_upload('input-image<?php echo $image_row; ?>','thumb-image<?php echo $image_row; ?>')">Browse</a>
										<a class="btn btn-danger btn-xs" onclick="$('#thumb-image<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#input-image<?php echo $image_row; ?>').attr('value', '');">Clear</a>
									</div>
								</div>
							</td>
							<td class="text-left">
								<input type="text" name="gallery_image[<?php echo $image_row; ?>][name]" value="<?php echo  $gallery_image['name']; ?>" placeholder="Name" class="form-control" />
							</td>
							<td class="text-right"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></button></td>
						</tr>
						<?php $image_row++; ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<td class="text-right"><button type="button" onclick="addImage();" data-toggle="tooltip" title="Banner Add" class="btn btn-primary"><i class="fa fa-plus"></i></button></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>	
<?php js_start(); ?>
<script type="text/javascript">
	var thin_config = {
		toolbar : [
			{ name: 'basicstyles', items : [ 'Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink','Source'] }
		],
		skin : 'office2013',
		entities : true,
		entities_latin : false,
		allowedContent: true,
		enterMode : CKEDITOR.ENTER_BR,
		resize_maxWidth : '400px',
		width : '550px',
		height : '120px'
  };
 
  $(document).ready(function() {
      initDnD = function() {
			
         // Sort images (table sort)
         $('#banner_images').tableDnD({
            onDrop: function(table, row) {
               order = $('#banner_images').tableDnDSerialize()
               $.post('<?php echo base_url(ADMIN_PATH . '/banner/images_order') ?>', order, function() {
                  
               });
            },
            dragHandle: ".drag_handle"
         });
      }
      initDnD();
      $('textarea.description').ckeditor(thin_config);
   });
	function image_upload(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("assets/");
				var fileName=url.substring(lastSlash+7);
				url=url.replace("images", ".thumbs/images"); 
				$('#'+thumb).attr('src', "<?=front_url()?>"+ url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
			}
		};
		$.colorbox({href:THEME_URL+"assets/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
	};
</script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	
   html = '<tr class="image-row' +image_row + '">';
	html += '	<td class="drag_handle"></td>';
	html += '  	<td class="text-left">';
	html += '		<div class="fileinput">';
	html += '			<div class="thumbnail file-browse">';
	html += '				<img src="<?php echo $no_image; ?>" alt="" id="thumb-image' + image_row + '" />';
	html += '					<input type="hidden" name="gallery_image[' + image_row + '][image]" value="" id="input-image'+image_row+'" />';
	html += '			</div>';
	html += '			<div class="btn-group" role="group">';
	html += '				<a class="btn btn-primary btn-xs" onclick="image_upload(\'input-image' + image_row + '\',\'thumb-image' + image_row + '\')">Browse</a>';
	html += '				<a class="btn btn-danger btn-xs" onclick="$(\'#thumb-image' + image_row  + '\').attr(\'src\',  \'<?php echo $no_image; ?>\'); $(\'#input-image'+ image_row +'\').attr(\'value\', \'\');">Clear</a>';
	html += '			</div>';
	html += '		</div>';
	html += '	</td>';
	html +=	'  <td class="text-left"><input type="text" name="gallery_image[' + image_row + '][name]" value="" placeholder="Name" class="form-control" /></td>';	
	html += '<td class="text-right"><button type="button" onclick="$(\'.image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#banner_images tbody').append(html);
	$('textarea.description').ckeditor(thin_config);
	image_row++;
}
function removeimage(j)
{
	$(".image-row"+j).remove();
	var instance="banner_image["+j+"][description]";
	var editor = CKEDITOR.instances[instance];
	if (editor) { editor.destroy(true); }
	//$('textarea.description').ckeditor(thin_config);
	
}
//--></script>
<?php js_end(); ?>

