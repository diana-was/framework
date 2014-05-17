<?php
/**	APPLICATION:	Framework
*	FILE:			model.php
*	DESCRIPTION:	library - Model class to use as starter of any class
*					all classes should be named as NameSome and the file as nameSome.php casesensitive
*	CREATED:		1 May 2013 by Diana De vargas
*	UPDATED:									
*/

abstract class Model
{
    private static $microtime_start = null;
    protected static $inCron = false;
    protected static $log = '';

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
    }

    public static function getInstance() 
    {
    	$class = get_called_class();
        return new $class();
    }
    
    /**
     * Magic Get
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    final public function __get($property)
    {
        return $this->__getProperty($property);
    }

    /**
     * Magic Set
     *
     * @param string $property Property name
     * @param mixed $value New value
     *
     * @return self
     */
    final public function __set($property, $value)
    {
        return $this->__setProperty($property, $value);
    }

    /**
     * Magic Isset
     *
     * @param string $property Property name
     *
     * @return boolean
     */
    public function __isset($property)
    {
       if (isset($this->$property)) {
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
    protected function __getProperty($property)
    {
        $value = null;

        $methodName = '__getVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            $value = call_user_func(array($this, $methodName));
        } else {
        	if (isset($this->$property)) {
        		$value = $this->$property;
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
    protected function __setProperty($property, $value)
    {
        $methodName = '__setVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            return call_user_func(array($this, $methodName), $value);
        } else {
            if ($property[0] !== '_') {
                return ($this->$property = $value);
            }
        }
    }

    static public function inCron()
    {
        self::$inCron = true;
    }
    
    static public function setLog($file)
    {
        self::$log = $file;
    }
    
    static public function printTime($text='',$logit=false)
    {
        if (is_array($text))
            $text = json_encode ($text);
        $logit = $logit || self::$inCron;
        if(self::$microtime_start === null)
        {
            self::$microtime_start = microtime(true);
            if ($logit)
            {
                $class = isset($this)?get_class($this):'Model'; 
                if (empty(self::$log))
                    syslog (LOG_INFO, "$class : $text (0.0)");
                else
                    file_put_contents(self::$log, "$text (0.0)".PHP_EOL, FILE_APPEND);
            }
            else 
            {
                 echo "$text (0.0)<br>".PHP_EOL;
            }
        }
        else
        {   
            $microtime_end = microtime(true);
            if ($logit)
            {
                if (empty(self::$log))
                    syslog (LOG_INFO, "$text (".number_format($microtime_end - self::$microtime_start,4).')');
                else
                    file_put_contents(self::$log, "$text (".number_format($microtime_end - self::$microtime_start,4).")".PHP_EOL, FILE_APPEND);
            }
            else 
            {
                echo  "$text (".number_format($microtime_end - self::$microtime_start,4).')<br>'.PHP_EOL;
            }
            self::$microtime_start = $microtime_end;
        }
    }
	
    public static function resetCssCounter($i=1)
    {
        self::$css_count = is_numeric($i)?$i:1;
    }

    public function resetTime()
    {
        self::$microtime_start = microtime(true);
    }

    /**
     * Display the object 
     *
     * @return void
     */
    public function printMe() {
		echo '<br />';
		echo '<pre>';
		print_r ($this);
		echo '</pre>';
    }
}