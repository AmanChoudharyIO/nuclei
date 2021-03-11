<?php

if (!function_exists('base_directory')) {
    function base_directory()
    {
        $file_prefix = 'Framework/';
        return substr(__DIR__, 0, -strlen($file_prefix));
    }
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require base_directory() . '/vendor/Autoload.php';

/*
|--------------------------------------------------------------------------
| Core Functions
|--------------------------------------------------------------------------
|
|System Helper have most essential functions of framework
|
*/
require 'SystemHelper.php';

/*
|--------------------------------------------------------------------------
| Essential Settings
|--------------------------------------------------------------------------
|
|System Helper have most essential functions of framework
|
*/
require 'Settings.php';


/*
|--------------------------------------------------------------------------
| Register Functions
|--------------------------------------------------------------------------
|
|System Helper have most essential functions of framework
|
*/
spl_autoload_register('class_autoloader');
set_exception_handler('global_exception_handler');
//set_error_handler();

/*
|--------------------------------------------------------------------------
| Entry Point
|--------------------------------------------------------------------------
|
|System Helper have most essential functions of framework
|
*/

use App\Views\SampleView;

$view = new SampleView();

if (isset($_GET['action']) && !empty($_GET['action'])) {
    echo $view->output($_GET['action']);
} else {
    echo $view->output();
}