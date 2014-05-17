<?php

/**	APPLICATION:	Framework
*	FILE:			controller.php
*	DESCRIPTION:	library - Class_Controller read the controls from the URL and Paths
*	CREATED:		1 May 2013 by Diana De vargas
*	UPDATED:									
*/

class Controller extends  Model
{
	protected $server;
	protected $path;
	protected $basename;
	protected $query;
	protected $system;
	protected $address;
	protected $request;
	protected $post;
	protected $get;
	protected $cookies;
	protected $server_name;
    protected $modulePath;
    protected $basePath;
	protected $baseURL;
	protected $appPath;
	protected $appURL;
	protected $self;
	protected $ori_kwd		= '';
	protected $orign_keyword	= '';
	protected $keyword		= '';	
	private static $_Controller; 
	
    /**
     * constructor : reads the $_SERVER variables and set up the server http
     *
     * @return void
     */
	protected function __construct($basePath)
	{
		$this->server = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
				$this->server .= "s";
		}
		$this->server .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$this->server_name = $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		} else {
			$this->server_name = $_SERVER["SERVER_NAME"];
		}
		$this->server .= $this->server_name;
		$this->system = (stripos(strtolower(php_uname ("s")), 'windows') !== false)?'WINDOWS':'LINUX';
		$this->address = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'';
		
		// Get the get, post and request variables and clean them
		$this->request = array();
		foreach($_REQUEST as $key => $val) {
			$key = strtolower($key);
			$this->request[$key] = is_string($val)?urldecode($val):$val;
		}
		$this->post = array();
		foreach($_POST as $key => $val) {
			$key = strtolower($key);
			$this->post[$key] = $val;
		}
		
		$this->get = array();
		foreach($_GET as $key => $val) {
			$key = strtolower($key);
			$this->get[$key] = is_string($val)?urldecode($val):$val;
		}
		$get = array_keys($_GET);
       	foreach($get as $key => $val) {
        	$this->request["param_$key"] = (empty($this->get[strtolower($val)]))?urldecode($val):$this->get[strtolower($val)];
        }

		$this->cookies = array();
		foreach($_COOKIE as $key => $val) {
			$key = strtolower($key);
			$this->cookies[$key] = $val;
		}
        
		// Get base the path and url
		$this->self     = $_SERVER['PHP_SELF'];
		$this->appPath  = self::cleanPath($basePath).'/application/';
		$this->appURL   = $this->server.self::getAppURL();
		$this->baseURL  = $this->server.self::getBaseURL();
		$this->basePath = self::cleanPath($basePath).'/';

		$this->__getController();
                
		self::$_Controller = $this;
		return self::$_Controller;
	}

	public static function getAppPath ()
	{
		return  self::cleanPath ($_SERVER['DOCUMENT_ROOT'].pathinfo($_SERVER['SCRIPT_NAME'],PATHINFO_DIRNAME)).'/';
	}
	
	public static function getAppURL ()
	{
		$baseURL = self::cleanPath (pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME));
		$baseURL .= ($baseURL != '/')?'/':'';
		return $baseURL;
	}

	public static function getBaseURL ()
	{
		$baseURL = self::getAppURL();
        $pos     = strrpos($baseURL,'application');
        $baseURL = $pos?substr($baseURL,0,$pos):$baseURL;
		return $baseURL;
	}
        
	public static function cleanPath ($str) 
	{
		$str = str_replace('\\','/',$str);
		$parts = explode('/',$str);
		$clean = array();
		foreach ($parts as $val) {
			$val = trim($val);
			if (!empty($val)) {
				$clean[] = 	$val;
			}
		}
		if (count($clean) > 0 && $clean[count($clean)-1] == '/') {
			array_pop($clean);
		}
		$str = implode('/',$clean);
		$str = !preg_match('|^[a-z,A-Z]+:|', $str)?'/'.$str:$str;
		return $str;
	}

    /**
     * Get the controller static object
     *
     * @return self
     */
    public static function getInstance($appPath='') 
    {
    	$class = __CLASS__;
    	if (!isset(self::$_Controller)) {
    		return new $class($appPath);
    	}
    	return self::$_Controller;
    }

    /** 
     * getController : get the route from the url to extract the module name and the url parts
     *
     * @return void
     */
    private function __getController() {
	        /*** get the route from the url ***/
	        $route = (empty($_SERVER["REQUEST_URI"])) ? '' : $_SERVER["REQUEST_URI"];
	        $base = self::getBaseURL();
	        $route = ($base != '\\' && $base != '/')?str_ireplace(self::getBaseURL(), '', $route):$route;
            $this->basename = 'index';
			$this->query =array();
            $this->path = array();
	        if (!empty($route))
	        {
	                /*** get the parts of the route ***/
	                $parse = @parse_url ( trim($route) );
                        if (preg_match('|/$|', $parse['path']) && !isset($parse['query']))
                        {
                            $parse['path'] .= 'index';
                        }

                        $query = isset($parse['query'])?$parse['query']:'';
                        $path_parts = isset($parse['path'])?pathinfo($parse['path']):array();
	                	$dir = (isset($path_parts['dirname']) && $path_parts['dirname'] != '.')?explode('/', str_replace('\\','/',$path_parts['dirname'])):array();
                        $basename = isset($path_parts['filename'])?$path_parts['filename']:'';
                        $this->basename = !empty($basename)?str_replace($basename[0], strtolower($basename[0]), $basename):$this->basename;
                        //$path_parts['extension'];
                        //$path_parts['basename'];                  
                        
                        /* put the query in an array */
	                	parse_str($query,$this->query);
                        /* get variables from query */
                        if (empty($this->request) && !empty($this->query))
                        {
                            foreach($this->query as $key => $val) {
                                    $key = strtolower($key);
                                    $this->request[$key] = is_string($val)?urldecode($val):$val;
                            }
                            $get = array_keys($this->query);
                            foreach($get as $key => $val) {
                                $this->request["param_$key"] = (empty($this->request[strtolower($val)]))?urldecode($val):$this->request[strtolower($val)];
                            }
                        }
	                
                        /* clean the path from empty //  */
	                foreach ($dir as $key => $val) {
	                	if (empty($val)) {
	                		unset($dir[$key]);
	                	}
	                }
	                
	                $this->path = (count($dir) > 0)?$dir:array();
	        }
	}
        
        protected function __setValModulePath ($value)
        {
            $this->modulePath = $value;
            return $value;
        }

}