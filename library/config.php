<?php
/**    APPLICATION:    Framework
*    FILE:            config.php
*    DESCRIPTION:    library - Class_Config read the config file
*    CREATED:        1 May 2013 by Diana De vargas
*    UPDATED:                                    
*/

class Config extends  Model
{
    private $_config;
    private $properties = array();
        private $app = array();
    private static $_Config;
    
    /**
     * constructor : reads the config file an set up the variables
     *
     * @param string $file file name
     * @param string $enviroment name of enviroment to read variables
     *
     * @return void
     */
    protected function __construct()
    {
        $controller = Controller::getInstance();
        $file = $controller->appPath.'default/config/config.ini';
        if  (is_file($file)) {
                $this->_config = parse_ini_file($file,1);
                $this->properties = $this->__getVariables($this->_config,0);
        }
        self::$_Config = $this;
        return self::$_Config;
    }

    /**
     * Get the controller static object
     *
     * @return self
     */
    public static function getInstance() 
    {
        $class = __CLASS__;
        if (!isset(self::$_Config)) {
            return new $class();
        }    
        return self::$_Config;
    }
    
    /**
     * reads the array of variables and return them in an array
     *
     * @param array $config variables in the config file
     * @param integer $level level in the array
     *
     * @return array
     */
    static private function __getVariables($config,$level) 
        {
        $properties = array();
        $env = false;
        $search = array('{appPath}','{basePath}','{HOME}');
                if (!self::$inCron)
                {
                    $controller = Controller::getInstance();
                    $replace = array($controller->appPath,$controller->basePath,$controller->publicURL);
                }
                else
                {
                    $replace = $search; // don't replace
                }
        foreach($config as $var => $value)
        {
            if (is_array($value)) {
                if ($level == 0) {
                    if ($var == APPLICATION_ENVIRONMENT) {
                        $properties = array_merge($properties,self::__getVariables($value,$level+1));
                        $env = true;
                    } elseif (!$env) {
                        $properties[$var] = self::replaceArray($search, $replace, $value);
                    }
                } else {
                    $properties[$var] = self::replaceArray($search, $replace, $value);
                }
            } else {
                $properties[$var] = self::replaceArray($search, $replace, $value);
            }
        }
        return $properties;
    }
        
    static public function replaceArray ($search,$replace,$subject)
    {
        if (is_array($subject)) {
            foreach ($subject as $key => $data) {
                $subject[$key] =  self::replaceArray($search, $replace, $data);
            }
        } else {
            $subject = str_ireplace($search, $replace, $subject);
        }
        return $subject;
    }

    public function globalizeProperties () {
        foreach($this->properties as $var => $value) {
            global $$var;
            $$var = $value;
        }
    }

    public function loadConfig($file)
    {
        if  (is_file($file)) {
            $_config = parse_ini_file($file,1);
            $this->app = $this->__getVariables($_config,0);
        }
    }   
    
    static public function getConfig($file)
    {
        if  (is_file($file)) {
            $_config = parse_ini_file($file,1);
            return self::__getVariables($_config,0);
        }
                return false;
    }   
    /**
     * Magic Isset
     *
     * @param string $property Property name
     *
     * @return boolean
     */
    final public function __isset($property)
    {
       if (isset($this->properties[$property])) {
           return true;
       }
    }

    /**
     * Get Property
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    final protected function __getProperty($property)
    {
        $value = null;

        $methodName = '__getVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            $value = call_user_func(array($this, $methodName));
        } else {
            if (isset($this->properties[$property])) {
                return $this->properties[$property];
            }
        }

        return $value;
    }

    /**
     * Set Property
     *
     * @param string $property Property name
     * @param mixed $value Property value
     *
     * @return self
     */
    final protected function __setProperty($property, $value)
    {
        $methodName = '__setVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            call_user_func(array($this, $methodName), $value);
        } else {
            if (isset($this->properties[$property])) {
                $this->properties[$property] = $value;
            }
        }
            
        return $this;
    }

}