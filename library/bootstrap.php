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
        
        /* find the directory where the controller sits */
        $parts = $controller->path;
        if (!empty($parts)) 
            $parts[] = '';
        $file_path = implode('/', $parts);
        $file_name = $controller->basename.'.php';

        /* Search for the folder in the application folder */
        $file_dir = $controller->appPath.strtolower($file_path);
        
        if (!is_dir($file_dir) && (count($parts) > 2)) {
            /* if no found try to find one level down */
            $file_name = $parts[count($parts) - 2].'.php';
            unset($parts[count($parts) - 2]);
            $file_path = implode('/', $parts);
            $file_dir = $controller->appPath.strtolower($file_path);
        }
        
        /* Load the config file for this module */
        if (!is_dir($file_dir) || empty($file_path)) { /* we didn't find the module. use the default module. the config is already up */
            $file_dir = $controller->appPath.'default/';
        }
        else { 
            $config->loadConfig($file_dir.'config/config.ini');
        }

        /* Find the directory for the controllers in this module */
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