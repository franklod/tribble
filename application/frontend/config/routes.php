<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
// CI RESERVED ROUTES
$route['default_controller'] = "post/newer";
$route['404_override'] = '';
// LIST THE NEWEST POSTS
$route['new'] = 'post/newer';
$route['new/page/(:num)'] = 'post/newer/$1';
// LIST THE MOST COMMENTED POSTS
$route['buzzing'] = 'post/buzzing';
$route['buzzing/page/(:num)'] = 'post/buzzing/$1';
// LIST THE MOST LIKED POSTS
$route['loved'] = 'post/loved';
$route['loved/page/(:num)'] = 'post/loved/$1';
// SEARCH POSTS
$route['search/(:any)'] = 'post/search/$1';
$route['search/page/(:num)'] = 'post/search/$1';
$route['dosearch'] = 'post/dosearch';
// VIEW A SINGLE POST
$route['view/(:num)'] = 'post/view/$1';

$route['upload'] = 'post/upload';
// SEARCH
$route['search/(:any)/(:num)'] = 'post/view/$1/$2';
// COMMENTS
$route['comment/delete/(:num)/(:num)/(:num)'] = 'post/delete_comment/$1/$2/$3';
$route['comment/add'] = 'post/add_comment';
// LIKES
$route['like/add/(:num)'] = 'post/add_like/$1';
$route['like/remove/(:num)'] = 'post/remove_like/$1';
// TAGS
$route['tag/(:any)'] = 'post/tag/$1';
$route['tag/(:any)/page/(:num)'] = 'post/tag/$1/$2';
// LOGIN
$route['login'] = 'auth/login';
$route['login/(:any)'] = 'auth/login/$1';
$route['logout'] = 'auth/logout';
$route['logout/(:any)'] = 'auth/logout/$1';
// TAGS
$route['tags'] = 'post/tags';

/* End of file routes.php */
/* Location: ./application/config/routes.php */