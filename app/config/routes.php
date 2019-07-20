<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'pages';
$route['404_override'] = 'pages/error';
$route['translate_uri_dashes'] = FALSE;

// Special Case Routes

$route[ADMIN_PATH.'/(:any)'] = "$1";
$route[ADMIN_PATH.'/([a-zA-Z_-]+)/(:any)'] = "$1/$2";
$route[ADMIN_PATH.'/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)'] = "$1/$2/$3";
$route[ADMIN_PATH.'/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)/(:num)'] = '$1/$2/$3/$4';
$route[ADMIN_PATH.'/login'] = "common/login";
$route[ADMIN_PATH] = "common/dashboard";

$route[API_PATH] = "welcome";
$route[API_PATH.'/(:any)'] = "$1";
$route[API_PATH.'/([a-zA-Z_-]+)/(:any)'] = "$1/$2";
$route[API_PATH.'/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)'] = "$1/$2/$3";
$route[API_PATH.'/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)/(:num)'] = '$1/$2/$3/$4';



require_once( BASEPATH .'database/DB'. EXT );
$db =& DB();
$query = $db->get('slug');
$result = $query->result();
foreach( $result as $row )
{
   $route[$row->slug]  = $row->route;
}

$route['booking'] = "booking";