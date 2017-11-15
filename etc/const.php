<?php
if (!defined('HACKER_ATTACK')) {
    die('Hacking attempt');
}

define('INCLUDE_ROOT', dirname(dirname(__FILE__)) . '/');
define('SCRIPT_DIR', INCLUDE_ROOT . 'run/');
define('DATA_DIR', INCLUDE_ROOT . 'data/');
define('TIME_ZONE', 'America/Chicago');
define('MYSQL_ENCODING', 'utf8');
date_default_timezone_set(TIME_ZONE);

include_once(dirname(__FILE__) . '/const_peter.php');

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL ^ E_NOTICE);
}

function loadClass($class) {
    $classFile = INCLUDE_ROOT . 'lib/Class.' . $class . '.php';
    if (file_exists($classFile)) {
        include_once($classFile);
    }
}
spl_autoload_register('loadClass');