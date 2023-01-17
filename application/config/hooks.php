<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

//hook to load webconfig
$hook['post_controller_constructor'][] = array(
    'class' => 'siteConfigLoader',
    'function' => 'initialize',
    'filename' => 'siteConfigLoader.php',
    'filepath' => 'hooks'
);
//hook to compress flies
//$hook['display_override'][] = array(
//	'class' => 'compress',
//	'function' => 'initialize',
//	'filename' => 'compress.php',
//	'filepath' => 'hooks'
//	);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
