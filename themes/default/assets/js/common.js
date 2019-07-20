$(function() {
	/* global ajax setup
	------------------------------------------------------------------------- */
	$.ajaxSetup({
		type: 'GET',
		datatype: 'json',
		timeout: 20000
	});
	$(document).ajaxStart(function() {
	  $( "#loading" ).show();
	});
	
	$(document).ajaxStop(function() {
		$( "#loading" ).hide();
	});
	/* modal box
	------------------------------------------------------------------------- */
	gbox = {
		defaults: {
			autohide: false,
			buttons: {
				'Close': function() {
					gbox.hide();
				}
			}
		},
		init: function() {
			var winHeight = $(window).height();
			var winWidth = $(window).width();
			var box =
				'<div id="gbox">' +
					'<div id="gbox_content"></div>' +
				'</div>' +
				'<div id="gbox_bg"></div>';
			$('body').append(box);
			$('#gbox').css({
				top: '15%',
				left: winWidth / 2 - $('#gbox').width() / 2
			});
			$('#gbox_close, #gbox_bg').click(gbox.hide);
		},
		show: function(options) {
			var options = $.extend({}, this.defaults, options);
			switch (options.type) {
				case 'ajax':
					$.ajax({
						type: 'GET',
						datatype: 'html',
						url: options.url,
						success: function(data) {
							options.content = data;
							gbox._show(options);
						}
					});
					break;
				default:
					this._show(options);
					break;
			}
		},
		_show: function(options) {
			$('#gbox_footer').remove();
			if (options.buttons) {
				$('#gbox').append('<div id="gbox_footer"></div>');
				$.each(options.buttons, function(k, v) {
					$('<button></button>').text(k).click(v).appendTo('#gbox_footer');
				});
			}
			$('#gbox, #gbox_bg').fadeIn();
			$('#gbox_content').html(options.content);
			$('#gbox_content input:first').focus();
			if (options.autohide) {
				setTimeout(function() {
					gbox.hide();
				}, options.autohide);
			}
		},
		hide: function() {
			$('#gbox').fadeOut(function() {
				$('#gbox_content').html('');
				$('#gbox_footer').remove();
			});
			$('#gbox_bg').fadeOut();
		}
	};
	gbox.init();
	
	/* same as site_url() in php
	------------------------------------------------------------------------- */
	function site_url(url) {
		return ADMIN_URL + '/' + url;
	}
	
	$('.text-danger').each(function() {
		var element = $(this).parents('.tab-pane');
    var id=element.attr('id');
		$("#tab-form").find("li a[href='#"+id+"']").addClass('has-error');
	});
	
	function theme_url(url) {
		return THEME_URL + '/' + url;
	}
	
	if($('.ckeditor_textarea').length){
		ckeditor_config = { 
			toolbar 	: 	[
							  { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
							  { name: 'colors', items : [ 'TextColor','BGColor' ] },
							  { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','- ','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
							  { name: 'tools', items : [ 'ShowBlocks' ] },
							  { name: 'tools', items : [ 'Maximize' ] },
													'/',
							  { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','Strike','-','RemoveFormat' ] },
							  { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
							  { name: 'editing', items : [ 'Find','Replace','-','Scayt' ] },
							  { name: 'insert', items : [ 'Image','Flash','MediaEmbed','Table','HorizontalRule','SpecialChar','Iframe' ] },
							  { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
							  { name: 'document', items : [ 'Source' ] }
							],
			skin : 'office2013',
			entities : true,
			entities_latin : false,
			allowedContent: true,
			enterMode : CKEDITOR.ENTER_BR,
			extraPlugins : 'stylesheetparser,codemirror',
			contentsCss : [theme_url('assets/plugins/ckeditor/contents.css')],
			stylesSet : [],
			height : '300px',
			filebrowserBrowseUrl 	  : theme_url('assets/plugins/kcfinder/browse.php?type=files'),
			filebrowserImageBrowseUrl : theme_url('assets/plugins/kcfinder/browse.php?type=images'),
			filebrowserFlashBrowseUrl : theme_url('assets/plugins/kcfinder/browse.php?type=flash'),
			filebrowserUploadUrl 	  : theme_url('assets/plugins/kcfinder/upload.php?type=files'),
			filebrowserImageUploadUrl : theme_url('assets/plugins/kcfinder/upload.php?type=images'),
			filebrowserFlashUploadUrl : theme_url('assets/plugins/kcfinder/upload.php?type=flash')
		};
	}


	$(".snav").click(function () {
		$('body').toggleClass('bhidden');
		$(this).toggleClass('openbtn');	
		$(this).parent().find('.topnav').animate({
			width: "toggle"
			}, 500, function() {
		});
	});
	
	$(window).on("scroll", function() {
		var fromTop = $(window).scrollTop();
		$(".scroll-indicator").toggleClass("arwhide", (fromTop > 40));
	});
	
	$(".scroll-indicator a").click(function() {
		$('html,body').animate({
		scrollTop: $(".page-content.home").offset().top-0},'slow');
	});	
});