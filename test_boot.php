<?php
define('ENVIRONMENT', 'development');

define('FCPATH', __DIR__ . '/');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('VIEWPATH', __DIR__ . '/views/');
define('EXT', '.php');
define('MB_ENABLED', extension_loaded('mbstring'));
define('ICONV_ENABLED', extension_loaded('iconv'));

$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '8888';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost:8888';

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'init.php';
require_once APPPATH . 'config/constants.php';
require_once BASEPATH . 'core/Common.php';

require_once(BASEPATH.'core/compat/mbstring.php');
require_once(BASEPATH.'core/compat/hash.php');
require_once(BASEPATH.'core/compat/password.php');
require_once(BASEPATH.'core/compat/standard.php');

$GLOBALS['CFG'] =& load_class('Config', 'core');
$GLOBALS['UNI'] =& load_class('Utf8', 'core');
$GLOBALS['URI'] =& load_class('URI', 'core');
$GLOBALS['RTR'] =& load_class('Router', 'core');
$GLOBALS['OUT'] =& load_class('Output', 'core');
$GLOBALS['SEC'] =& load_class('Security', 'core');
$GLOBALS['IN']  =& load_class('Input', 'core');
$GLOBALS['LANG'] =& load_class('Lang', 'core');

require_once BASEPATH . 'core/Controller.php';

function &get_instance() {
    return CI_Controller::get_instance();
}

require_once APPPATH . 'third_party/MX/Base.php';
require_once APPPATH . 'third_party/MX/Controller.php';
require_once APPPATH . 'core/MAIN_Controller.php';

$CI = new MAIN_Controller();

echo "CI Controller Initialized Successfully!\n";
