<?php
/* 
 * Tool class and esay way to test small code and display database data
 *  
 */   

class Tools extends  ControllerModel
{
    private $splitReg  = '/# Match position between camelCase "words".
                            (?<=[a-z]|\s)  # Position is after a lowercase,
                            (?=[A-Z])   # and before an uppercase letter.
                            /x';     
    public $tables = array();
    /*
     *  When call from the URL
     */
    public function index ()
    {
    }
    
    public function showDatabase () 
    {
	    // Connect to database
	    $db = Db::getInstance();
	    $config = Config::getInstance();
        $controller = Controller::getInstance();
	    if (!$db->connect($config->dbserver, $config->dbuser, $config->dbpass, $config->dbname, true))  
	    {
	        $db->print_last_error(false);
	        $db->printMe();
	    }
    	$this->tables = array();
        if (isset($controller->request['db']))
        {
            $db->select_db($controller->request['db']);
        }
        $aResults = $db->select("SHOW TABLES");
        while ($aRow = $db->get_row($aResults, 'MYSQL_ASSOC')) {
            $tableName = $aRow['Tables_in_'.$db->selected_db()];
            $this->tables[$tableName] = array('fields' => array(), 'rows' => 0);
            $bResults = $db->select("DESCRIBE $tableName");
            while ($bRow = $db->get_row($bResults, 'MYSQL_ASSOC')) {
                $this->tables[$tableName]['fields'][] = $bRow;
            }
            $cResults = $db->select_one("select count(*) num from $tableName");
            $this->tables[$tableName]['rows'] = $cResults;
        }
        $this->viewHTML('showTables.php');
    }
    
    public function showRows () 
    {
	    // Connect to database
	    $db = Db::getInstance();
	    $config = Config::getInstance();
        $controller = Controller::getInstance();
	    if (!$db->connect($config->dbserver, $config->dbuser, $config->dbpass, $config->dbname, true))  
	    {
	        $db->print_last_error(false);
	        $db->printMe();
	    }
        if (isset($controller->request['param_0']))
        {
            $this->tables = array();
            $db = Db::getInstance();
            $tableName = $controller->request['param_0'];
            $where = isset($controller->request['where'])?" where ".$controller->request['where']:'';
            $this->tables[$tableName] = array('fields' => array(), 'rows' => 0);
            $bResults = $db->select("select * from  $tableName $where limit 200");
            while ($bRow = $db->get_row($bResults, 'MYSQL_ASSOC')) {
                $this->tables[$tableName]['fields'][] = $bRow;
            }
            $cResults = $db->select_one("select count(*) num from $tableName");
            $this->tables[$tableName]['rows'] = $cResults;
        }
        $this->viewHTML('showTables.php');
    }
    
    public function splitReg() {
        $a = preg_split($this->splitReg, 'ccWordThisIs Some');
        print_r($a);
        $a = preg_split($this->splitReg, 'New Some');
        print_r($a);
    }

    public function displayUnix () 
    {
        $controller = Controller::getInstance();
        if (isset($controller->request['time']))
        {
            echo $controller->request['time']." = ".strtotime($controller->request['time']);
        }
    }

    public function displayDate () 
    {
        $controller = Controller::getInstance();
        if (isset($controller->request['param_0']))
        {
            echo date('Y-m-d H:i:s',$controller->request['param_0']);
        }
    }

    public function showPost () 
    {
    	setcookie("send-c", "cookie", time()+3600);
    	$this->viewHTML('form.php');
    }

    public function testPost () 
    {
    	$controller = Controller::getInstance();
    	print_r($controller->request);
    	print_r($_REQUEST);
    	print_r($_POST);
    	print_r($_GET);
    	print_r($_COOKIE);
    }
    
}

/*
 *  Run if called with bootstrap
 */
if (isset($controller))
{
    /* call the method in the URL */
    $app = Tools::getInstance();
    $app->callBasename();
}

?>
