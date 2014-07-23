<?php
/**
 * Created by PhpStorm.
 * User: yoophi
 * Date: 6/9/14
 * Time: 11:56 AM
 */

if (defined('CAKE')) {
    return;
}


// Let's bake some CakePHP
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
    define('ROOT', dirname(__FILE__));
}
if (!defined('APP_DIR')) {
    define('APP_DIR', 'app');
}
if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', APP_DIR . DS . 'webroot');
}
if (!defined('WWW_ROOT')) {
define('WWW_ROOT', WEBROOT_DIR . DS);
}
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    if (function_exists('ini_set')) {
        ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
    }
    if (!include ('Cake' . DS . 'bootstrap.php')) {
        $failed = true;
    }
} else {
    if (!include (CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
        $failed = true;
    }
}
if (!empty($failed)) {
    trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}
if (Configure::read('debug') < 1) {
    die(__d('cake_dev', 'Debug setting does not allow access to this url.'));
}

// Do some reconfiguration
Configure::write('Error', array());
Configure::write('Exception', array());

// Bootstrap CakePHP
require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteDispatcher.php';
require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteCommand.php';

//pr(get_defined_constants());
//pr(WEBROOT_DIR);
App::uses('ClassRegistry', 'Utility');
