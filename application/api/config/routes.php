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

$route['default_controller'] = "tribble";
$route['404_override'] = '';

$route['posts/list/recent/(:num)/(:num)'] = 'posts/getMostRecent/$1/$2';
$route['posts/list/recent/(:num)'] = 'posts/getMostRecent/$1';
$route['posts/list/recent'] = 'posts/getMostRecent';

$route['posts/list/commented/(:num)/(:num)'] = 'posts/getMostCommented/$1/$2';
$route['posts/list/commented/(:num)'] = 'posts/getMostCommented/$1';
$route['posts/list/commented'] = 'posts/getMostCommented';

$route['posts/list/popular/(:num)/(:num)'] = 'posts/getMostLiked/$1/$2';
$route['posts/list/popular/(:num)'] = 'posts/getMostLiked/$1';
$route['posts/list/popular'] = 'posts/getMostLiked';

$route['posts/list'] = 'posts/getMostRecent';

$route['posts/count'] = 'posts/countPosts';

$route['posts/(:num)'] = 'posts/getPostById/$1';

$route['posts/replies/(:num)'] = 'posts/getRepliesByPostId/$1';

$route['posts/search/(:any)'] = 'posts/searchPostsText/$1';
$route['posts/search/(:any)/(:num)'] = 'posts/searchPostsText/$1/$2';

$route['posts/upload'] = 'posts/createNewPost';
$route['posts/upload/(:num)'] = 'posts/createNewPost/$1';

$route['posts/comment/add'] = 'posts/comment';
$route['posts/comment/delete'] = 'posts/comment';

$route['likes/add'] = 'posts/like';
$route['likes/remove'] = 'posts/like';


/* End of file routes.php */
/* Location: ./application/config/routes.php */