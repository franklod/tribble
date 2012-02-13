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

$route['default_controller'] = "docs";
$route['404_override'] = 'docs';
// GET LIST OF RECENT POSTS
$route['posts/list/new/(:num)/(:num)'] = 'posts/list/type/new/page/$1/limit/$2';
$route['posts/list/new/(:num)/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/new/page/$1/limit/$2/format/$3';
$route['posts/list/new/(:num)'] = 'posts/list/type/new/page/$1';
$route['posts/list/new/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/new/page/$1/format/$2';
$route['posts/list/new'] = 'posts/list/type/new/';
$route['posts/list/new/format/(xml|json|serialized)'] = 'posts/list/type/new/format/$1';
// GET LIST OF MOST COMMENTED POSTS
$route['posts/list/buzzing/(:num)/(:num)'] = 'posts/list/type/buzzing/page/$1/limit/$2';
$route['posts/list/buzzing/(:num)/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/buzzing/page/$1/limit/$2/format/$3';
$route['posts/list/buzzing/(:num)'] = 'posts/list/type/buzzing/page/$1';
$route['posts/list/buzzing/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/buzzing/page/$1/format/$2';
$route['posts/list/buzzing'] = 'posts/list/type/buzzing/';
$route['posts/list/buzzing/format/(xml|json|serialized)'] = 'posts/list/type/buzzing/format/$1';
// GET LIST OF MOST LIKED POSTS
$route['posts/list/loved/(:num)/(:num)'] = 'posts/list/type/loved/page/$1/limit/$2';
$route['posts/list/loved/(:num)/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/loved/page/$1/limit/$2/format/$3';
$route['posts/list/loved/(:num)'] = 'posts/list/type/loved/page/$1';
$route['posts/list/loved/(:num)/format/(xml|json|serialized)'] = 'posts/list/type/loved/page/$1/format/$2';
$route['posts/list/loved'] = 'posts/list/type/loved/';
$route['posts/list/loved/format/(xml|json|serialized)'] = 'posts/list/type/loved/format/$1';
// GET A SINGLE POST
$route['posts/single/(:num)'] = 'posts/detail/id/$1';
$route['posts/single/(:num)/format/(xml|json|serialized)'] = 'posts/detail/id/$1/format/$2';
// GET POST COUNT TOTAL
$route['posts/count'] = 'posts/total';
$route['posts/count/format/(xml|json|serialized)'] = 'posts/total/format/$1';
// GET REPLIES TO A POST
$route['posts/replies/(:num)'] = 'posts/getRepliesByPostId/$1';
// GET A LIST OF POST THAT MATCH THE SEARCH STRING 
$route['posts/search/(:any)'] = 'posts/searchPostsText/$1';
$route['posts/search/(:any)/(:num)'] = 'posts/searchPostsText/$1/$2';
// PUT A NEW POST
$route['posts/upload'] = 'posts/post';
// DELETE A POST
$route['posts/delete'] = 'posts/post';
// PUT/DELETE COMMENT
$route['reply/post'] = 'posts/reply';
$route['reply/comment'] = 'replies/comment';
// PUT A LIKE ON A POST
$route['likes/add'] = 'posts/like';
$route['likes/remove'] = 'posts/like';
// TAGS GET
$route['meta/tags'] = 'meta/tags/limit/12';
$route['meta/tags/(:num)'] = 'meta/tags/limit/$1';
// GET POST LIST BY TAG
$route['posts/tag/(:any)'] = 'posts/tagged/tag/$1';
$route['posts/tag/(:any)/(:num)'] = 'posts/tagged/tag/$1/page/$2';
$route['posts/tag/(:any)/(:num)/(:num)'] = 'posts/tagged/tag/$1/page/$2/limit/$3';
// GET USER PROFILE
$route['users/profile/update'] = 'users/updateprofile';
$route['users/profile/(:num)'] = 'users/profile/id/$1';
$route['users/check/password'] = 'users/checkOldPassword';
$route['users/update/password'] = 'users/password';

/* End of file routes.php */
/* Location: ./application/config/routes.php */