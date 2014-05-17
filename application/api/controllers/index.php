<?php
/* 
 * API class and easy enable API fuctions responds via JSON
 *  
 */   

class api extends  ControllerModel
{
	public $callback;
	public $json;
    /*
     *  When call from the URL
     */
    public function index ()
    {
    	$this->json = array(false);
    }
    
	/* 
	 * This function calls Bitly API and returns the short URL from the parameters
	 *  
	 * API call 
	 * https://api-ssl.bitly.com/v3/shorten?access_token=$bitlyToken&longUrl=$URL
	 * 
	 * response
	{
	  "data": {
	    "global_hash": "900913",
	    "hash": "ze6poY",
	    "long_url": "http://google.com/",
	    "new_hash": 0,
	    "url": "http://bit.ly/ze6poY"
	  },
	  "status_code": 200,
	  "status_txt": "OK"
	}
	* parameters : 	URL : a valid URL
	*				Callback : a callback id  
 	* this returns a short url
	*/   
    public function shorturl () 
    {
		$controller = Controller::getInstance();    // from bootstrap
		$config		= Config::getInstance();		
		$curlObj    = SingleCurl::getInstance ( '', 5 );     // default values $url=, $timeOut
		
		// Read parameters from URI or POST
		$this->callback = isset($controller->request['callback'])?$controller->request['callback']:'';
		$URL = isset($controller->request['url'])?$controller->request['url']:(isset($controller->request['param_0'])?$controller->request['param_0']:'');
		
		//
		// Call bitly to get short URL
		// 
		// send sitemap to site
		$params = array (
		                'access_token' => $config->bitlyToken,   // from config.ini file
		                'longUrl' => $URL
		);
		
		
		// connect
		$curlObj->createCurl ( 'post', "https://api-ssl.bitly.com/v3/shorten", $params );
		$err = $curlObj->getHttpErr ();
		$status = $curlObj->getHttpStatus ();
		if ($config->debug) {   // from bootstrap
		        $config->curlObj->displayResponce ();
		        echo '<br>';
		}
		$resp = json_decode ( $curlObj->__toString () );
		$code = isset ( $resp->status_code ) ? $resp->status_code : 0;
		$msg  = isset ( $resp->status_txt ) ? $resp->status_txt : 'unknown error';
		
		if (($err != 0) || ($status != 200) || ($code != 200))
		    $shortURL = $URL; 
		else    
		    $shortURL = $resp->data->url;
		
		$this->json = array(
		  "url"         => $shortURL,
		  "status_code" => $code,
		  "status_txt"  => $msg
		);
    }
}

/*
 *  Run if called with bootstrap
 */
if (isset($controller))
{
    /* call the method in the URL */
    $app = api::getInstance();
    $app->callBasename();
	// display all response as json
	$app->viewJSON('json.php');
}

?>
