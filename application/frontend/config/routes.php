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

$route['default_controller'] = "tribble/newer";
$route['404_override'] = '';

$route['new'] = 'tribble/newer';
$route['new/page/(:num)'] = 'tribble/newer/$1';

$route['buzzing'] = 'tribble/buzzing';
$route['buzzing/page/(:num)'] = 'tribble/buzzing/$1';

$route['loved'] = 'tribble/loved';
$route['loved/page/(:num)'] = 'tribble/loved/$1';

$route['search/(:any)'] = 'tribble/search/$1';
$route['search/page/(:num)'] = 'tribble/search/$1';

$route['dosearch'] = 'tribble/dosearch';

$route['view/(:num)'] = 'tribble/view/$1';

$route['upload'] = 'tribble/upload';

$route['search/(:any)/(:num)'] = 'tribble/view/$1/$2';


/* End of file routes.php */
/* Location: ./application/config/routes.php */