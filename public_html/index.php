<?php
$BASE = realpath(dirname(__FILE__) . '/../') . '/';
define('BASE_PATH', $BASE);
define('APP_PATH', $BASE.'application/');
define('LIBRARY_PATH', $BASE.'library/');
define('PUBLIC_PATH', realpath(dirname(__FILE__)) . '/');
define('PUBLIC_URL', dirname($_SERVER['PHP_SELF']).'/');
require_once APP_PATH.'bootstrap.php';
?>
