<?php
session_cache_expire(3600);
if (!session_id())
    session_start();

if(isset($_SESSION['timeout_idle']) && $_SESSION['timeout_idle'] < time()) 
{
	session_destroy();
	session_regenerate_id();
}
$_SESSION['timeout_idle'] = time() + session_cache_expire();
date_default_timezone_set('UTC');

Global $appPath;
Global $basePath;
$appPath = pathinfo(__FILE__,PATHINFO_DIRNAME).'/';
$pos     = strrpos($appPath,'application');
$basePath = $pos?substr($appPath,0,$pos):$appPath;

// Magic class load : load the classes from the library
function __autoload($class_name) 
{
   Global $basePath;
   $parts = explode('_', $class_name);
   $class_name = array_pop($parts);
   if (!empty($parts))
       $parts[] = '';
   $class_file = str_replace($class_name[0], strtolower($class_name[0]), $class_name).'.php';
   
   /* Search for the file in the Library folder */
   $class_file_name = str_replace('\\','/',$basePath.'library/'.strtolower(implode('/',$parts)).$class_file);

   /* If not found Search for the file in the model folder */
   if (!is_file($class_file_name)) 
   {
       /* Search for the file in the application folder inside the model folder*/
       $class_file_name = $basePath.'application/'.strtolower(implode('/',$parts)).'models/'.$class_file;
   }
   if (is_file($class_file_name)) {
            require_once ($class_file_name);
    } else {
            echo "class_file_name:$class_file_name<br>";   
            echo 'No file found by outoload to load the class '.$class_name;
    }
}

// Get the data from the URL 
$controller = Controller::getInstance($basePath);

// Testing Localhost
if ((($controller->address == '127.0.0.1') || stripos($controller->server_name, 'localhost') !== false) && ($controller->system == 'WINDOWS')) {
	define('APPLICATION_ENVIRONMENT', 'TESTING');
	error_reporting(E_ALL);
} else {	// Production
	define('APPLICATION_ENVIRONMENT', 'PRODUCTION');	
	if (isset($controller->request['debug']))
		error_reporting(E_ALL);
	else
		error_reporting(0);
}
global $debug;
$debug = isset($controller->request['debug']);
// load configuration file
$config = Config::getInstance();
$config->globalizeProperties();

/* Call the page */
include Bootstrap::controller();
//$controller->printMe();
//$config->printMe();
?>