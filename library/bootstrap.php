<?php
/**	APPLICATION:	Framework
*	FILE:			bootstrap.php
*	DESCRIPTION:	library - methods for bootstrap
*	CREATED:        1 May 2013 by Diana De vargas
*	UPDATED:									
*/

class Bootstrap extends  Model
{
    private static $_Object;
	
    /**
     * constructor : init the class
     *
     * @return self
     */
    protected function __construct()
    {
        self::$_Object = $this;
        return self::$_Object;
    }

    /**
     * Get the Bootstrap static object
     *
     * @return self
     */
    public static function getInstance() 
    {
        $class = __CLASS__;
        if (!isset(self::$_Object)) {
                return new $class();
        }	
        return self::$_Object;
    }

    /**
     * Get the controller path
     *
     * @return self
     */
    public static function controller()
    {
        $controller = Controller::getInstance();
        $config = Config::getInstance();
        $parts = $controller->path;
        if (!empty($parts)) 
            $parts[] = '';
        $file_path = implode('/', $parts);
        $file_name = $controller->basename.'.php';

        /* Search for the folder in the application folder */
        $file_dir = $controller->appPath.strtolower($file_path);
        if (!is_dir($file_dir) || empty($file_path)) {
            $file_dir = $controller->appPath.'default/';
        }
        else {
            $config->loadConfig($file_dir.'config/config.ini');
        }

        $full_file_name = $file_dir.'controllers/'.$file_name;
        if (!is_file($full_file_name)) {
            $full_file_name = $file_dir.'controllers/index.php';
            if (!is_file($full_file_name)) {
                $file_dir = $controller->appPath.'default/controllers/index.php';
            }
        }
        $controller->modulePath = $file_dir;
        return $full_file_name;
    }
        
    
}