<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
|--------------------------------------------------------------------------
| AIO ADMIN Version
|--------------------------------------------------------------------------
|
| Defines the version number for aio admin
|
*/
define('AIOADMIN_VERSION', '2.0.0');

/*
|--------------------------------------------------------------------------
| FRONT Root Folder
|--------------------------------------------------------------------------
|
| Defines the absolute path to the root folder of aio admin
|
*/
define('FRONT_ROOT', dirname(BASEPATH) . '/front/');
define('BASE_ROOT', dirname(BASEPATH) . '/');
define('FRONT_PATH', dirname(BASEPATH) . '/');
/*
|--------------------------------------------------------------------------
| Admin Path
|--------------------------------------------------------------------------
|
*/
define('ADMIN_ROOT', dirname(BASEPATH) . '/admin/');
//define('ADMIN_PATH','admin');
/*Module Path*/
define('DIR_MODULE', dirname(BASEPATH) . '/admin/modules/');
/*
|--------------------------------------------------------------------------
| Group Types
|--------------------------------------------------------------------------
|
*/
define('USER','user');
define('ADMINISTRATOR','administrator');
define('SUPERADMIN','superadmin');
define('MASTERADMIN','masteradmin');


/*
|--------------------------------------------------------------------------
| CMS Image Cache Directory
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| User Data Storage
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| Packages
|--------------------------------------------------------------------------
|
*/
$packages = array(
	'superfish' => array(
        'javascript' => array(
            'storage/plugins/superfish/js/superfish.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/superfish/css/superfish.css',
			),
   ),
	'tablednd' => array(
        'javascript' => array(
            'storage/plugins/tablednd/js/jquery.tablednd.js',
        ),
		  'stylesheet' => array(
            'storage/plugins/tablednd/tablednd.css',
			),
   ),
	'datetimepicker' => array(
        'javascript' => array(
            'storage/plugins/datetimepicker/moment.js',
				'storage/plugins/datetimepicker/bootstrap-datetimepicker.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/datetimepicker/bootstrap-datetimepicker.min.css',
				
        ),
   ),
	'select2' => array(
        'javascript' => array(
            'storage/plugins/select2/dist/js/select2.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/select2/dist/css/select2.css',
				'storage/plugins/select2/dist/css/select2-bootstrap.css',
        ),
   ),
	'colorpicker' => array(
      'javascript' => array(
         'storage/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
      ),
		'stylesheet' => array(
         'storage/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css',
      ),
   ),
	'tags' => array(
      'javascript' => array(
         'storage/plugins/tagsinput/jquery.tagsinput.min.js',
      ),
		'stylesheet' => array(
         'storage/plugins/tagsinput/jquery.tagsinput.css',
      ),
   ),
	
	'pace' => array(
      'javascript' => array(
         'storage/plugins/pace/pace.min.js',
      ),
		'stylesheet' => array(
         'storage/plugins/pace/pace.css',
      ),
   ),
	'ckeditor' => array(
        'javascript' => array(
            'storage/plugins/ckeditor/ckeditor.js',
            'storage/plugins/ckeditor/adapters/jquery.js',
        ),
    ),
	'ckfinder' => array(
        'javascript' => array(
            'default/assets/js/plugins/ckfinder/ckfinder.js',
        ),
   ),
	'colorbox' => array(
        'javascript' => array(
            'storage/plugins/colorbox/jquery.colorbox.js',
        ),
        'stylesheet' => array(
            'storage/plugins/colorbox/colorbox.css',
        ),
    ),
	 'datatable' => array(
        'javascript' => array(
            'storage/plugins/datatables/jquery.dataTables.min.js',
				'storage/plugins/datatables/dataTables.bootstrap4.min.js',
				'storage/plugins/datatables/dataTables.buttons.min.js',
				'storage/plugins/datatables/buttons.bootstrap4.min.js',
				'storage/plugins/datatables/jszip.min.js',
				'storage/plugins/datatables/pdfmake.min.js',
				'storage/plugins/datatables/vfs_fonts.js',
				'storage/plugins/datatables/buttons.html5.min.js',
				'storage/plugins/datatables/buttons.print.min.js',
				'storage/plugins/datatables/dataTables.fixedHeader.min.js',
				'storage/plugins/datatables/dataTables.keyTable.min.js',
				'storage/plugins/datatables/dataTables.scroller.min.js',
				'storage/plugins/datatables/dataTables.responsive.min.js',
				'storage/plugins/datatables/responsive.bootstrap4.min.js',
		 ),
        'stylesheet' => array(
            'storage/plugins/datatables/jquery.dataTables.min.css',
				'storage/plugins/datatables/buttons.bootstrap4.min.css',
				'storage/plugins/datatables/fixedHeader.bootstrap4.min.css',
				'storage/plugins/datatables/responsive.bootstrap4.min.css',
				'storage/plugins/datatables/dataTables.bootstrap4.min.css',
				'storage/plugins/datatables/scroller.bootstrap4.min.css',
        ),
    ),
	 
	 'datatable_export' => array(
        'javascript' => array(
            'storage/plugins/datatables/dataTables.buttons.min.js',
				'storage/plugins/datatables/buttons.bootstrap.min.js',
				'storage/plugins/datatables/jszip.min.js',
				'storage/plugins/datatables/pdfmake.min.js',
				'storage/plugins/datatables/vfs_fonts.js',
				'storage/plugins/datatables/buttons.html5.min.js',
				'storage/plugins/datatables/buttons.print.min.js',
				'storage/plugins/datatables/dataTables.scroller.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/datatables/buttons.bootstrap.min.css',
				'storage/plugins/datatables/scroller.bootstrap.min.css',
        ),
    ),
	 
	'datepicker' => array(
		'javascript' => array(
			'storage/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
		),
		'stylesheet' => array(
			'storage/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
		),
   ),
	
	'morris_chart' => array(
		'javascript' => array(
			'storage/plugins/morris.js/morris.min.js',
			'storage/plugins/raphael/raphael-min.js',
		),
		'stylesheet' => array(
			'storage/plugins/morris.js/morris.css',
		),
   ),
	 
	'bdropdown' => array(
        'javascript' => array(
            'admin/storage/js/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        ),
    ),
	'slimscroll' => array(
        'javascript' => array(
            'admin/storage/js/plugins/jquery.slimscroll/jquery.slimscroll.min.js',
        ),
    ),
	'sidebar' => array(
        'javascript' => array(
            'admin/storage/js/sidebar.js',
        ),
    ),
	'panels' => array(
        'javascript' => array(
            'admin/storage/js/panels.js',
        ),
    ),
	
    'app' => array(
        'javascript' => array(
            'admin/storage/js/app.js',
        ),
    ),
	
	
    'fancybox' => array(
        'javascript' => array(
            'admin/storage/js/plugins/fancybox/jquery.fancybox-1.3.4.pack.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/plugins/fancybox/jquery.fancybox-1.3.4.css',
        ),
    ),
	
	'admin_jqueryui' => array(
        'javascript' => array(
            'admin/storage/js/jqueryui/jquery-ui-1.10.4.custom.js',
            'admin/storage/js/jquery-ui-timepicker-addon.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/jqueryui/smoothness/jquery-ui-1.10.4.custom.css',
        ),
    ),
	'icheck' => array(
        'javascript' => array(
            'admin/storage/js/plugins/icheck/icheck.min.js',
        ),
		'stylesheet' => array(
            'admin/storage/css/plugins/icheck/square/grey.css',
        ),
    ),
	
    'helpers' => array(
        'javascript' => array(
            'admin/storage/js/helpers.js',
        ),
    ),
    
	
	
    'nestedSortable' => array(
        'javascript' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.css',
        ),
    ),
	'jquerynestable' => array(
        'javascript' => array(
            'storage/plugins/jquery_nestable/jquery.nestable.js',
        ),
        'stylesheet' => array(
            'storage/plugins/jquery_nestable/jquery.nestable.css',
        ),
    ),
    'codemirror' => array(
        'javascript' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.js',
            'admin/storage/js/codemirror-2.25/mode/xml/xml.js',
            'admin/storage/js/codemirror-2.25/mode/javascript/javascript.js',
            'admin/storage/js/codemirror-2.25/mode/css/css.js',
            'admin/storage/js/codemirror-2.25/mode/clike/clike.js',
            'admin/storage/js/codemirror-2.25/mode/php/php.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.css',
        ),
    ),
    
);

define('PACKAGES', serialize($packages));

/*
|--------------------------------------------------------------------------
| Admin Missing Image
|--------------------------------------------------------------------------
|
*/
define('ADMIN_NO_IMAGE', '/admin/storage/images/no_image.jpg');