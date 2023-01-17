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
  |	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'home';


$route['error'] = "home/error";

$route['blog/ajaxSave'] = "blog/ajaxSave";
$route['support/ajaxSave'] = "support/ajaxSave";
$route['blog/like']     = "blog/like";
$route['blog/dislike']  = "blog/dislike";

$route['blog']        = "blog";
$route['blog/(:any)'] = "blog/index/$1;";
$route['support']        = "support";
$route['support/(:any)'] = "support/index/$1;";

$route['register/checkEmail']    = "register/checkEmail";
$route['register/getCookie']     = "register/getCookie";
$route['register']               = "register";
$route['register/(:any)']        = "register/index/$1";
$route['register/(:any)/(:any)'] = "register/index/$1/$2";


$route['settings']      = "dashboard/settings";
$route['settings/edit'] = "dashboard/settings/edit";

$route['settings/changepassword'] = "dashboard/changepassword";
$route['welcome'] = "dashboard/welcome";

$route['marketing/get_bitly_url']  = "marketing/get_bitly_url";
$route['marketing/advance_search'] = "marketing/advance_search";
$route['marketing/shareLinkCopy']  = "marketing/shareLinkCopy";
$route['marketing/search']         = "marketing/search";
$route['marketing/search/(:any)']  = "marketing/search/$1";
$route['marketing']                = "marketing";
$route['marketing/(:any)']         = "marketing/index/$1";


$route['detail'] = "checkout/detail";

$route['404_override'] = '';
$route['admin']        = "admin/login";

$route['pages/contactus'] = "pages/contactus";
//$route['pages/(:any)'] = "pages/index/$1";

$route['forgot-password'] = "login/forgot_password";
$route['logout']          = "login/logout";

$route['tracking']  = "tracking";
$route['analytics'] = "analytics";

$route['translate_uri_dashes'] = FALSE;


$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
if (
        $uri_segments[1] == 'admin' || $uri_segments[1] == 'analytics' || $uri_segments[1] == 'api' 
        || $uri_segments[1] == 'blog' || $uri_segments[1] == 'checkout' || $uri_segments[1] == 'cron'
        || $uri_segments[1] == 'dashboard' || $uri_segments[1] == 'demo_sale' || $uri_segments[1] == 'home'
        || $uri_segments[1] == 'invoices' || $uri_segments[1] == 'login' || $uri_segments[1] == 'marketing'
        || $uri_segments[1] == 'payment' || $uri_segments[1] == 'products' || $uri_segments[1] == 'register'
        || $uri_segments[1] == 'reporting' || $uri_segments[1] == 'tracking' || $uri_segments[1] == 'wallet'
        || $uri_segments[1] == 'support' || $uri_segments[1] == 'welcome'
        
    )
{
    $route['support/(:any)'] = 'support/index/$1';
    $route['support/(:any)/(:any)'] = 'support/index/$1/$2';
}
else
{
    $route['(:any)'] = 'pages/index/$1';
    $route['(:any)/(:any)'] = 'pages/index/$1/$2';
}
