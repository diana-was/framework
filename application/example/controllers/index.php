<?php
/* 
 * Tool class and esay way to test small code and display database data
 *  
 */   

class Example extends  ControllerModel
{
    private $splitReg  = '/# Match position between camelCase "words".
                            (?<=[a-z]|\s)  # Position is after a lowercase,
                            (?=[A-Z])   # and before an uppercase letter.
                            /x';     
    public $tables = array();
    public $form = array(
                        'title' => '',
                        'action' => '',
                        'method' => 'post',
                        'name'  => 'test-form',
                        'input' => array(),  
                        'output' => '',     
                    );
    
    /*
     *  When call from the URL
     */
    public function index ()
    {
        $this->viewHTML('menu.php');
    }
    
    public function showDatabase () 
    {
        // Connect to database
        $config = Config::getInstance();
        $controller = Controller::getInstance();
        $db = Db::getInstance();
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
        $this->viewHTML('tables.php');
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
        $this->viewHTML('tables.php');
    }
    
    public function splitReg() {
        $controller = Controller::getInstance();
        $this->form['title'] = 'Split Camel Case String';
        $this->form['action'] = 'splitReg';
        $text = isset($controller->request['text'])?$controller->request['text']:'';
        $this->form['input'] = array('name' => 'text', 'label' => 'Text', 'type' => 'text',  'value' => $text);
        $this->form['output'] = preg_split($this->splitReg, $text);
        $this->viewHTML('form.php');
    }

    public function displayUnix () 
    {
        $controller = Controller::getInstance();
        $this->form['title'] = 'Convert Date and Time to Unix time';
        $this->form['action'] = 'displayUnix';
        $time = isset($controller->request['date'])?$controller->request['date']:'now';
        $this->form['input'] = array('name' => 'date', 'label' => 'Date (YYYY-MM-DD hh:mm:ss)', 'type' => 'text',  'value'  => $time);
        $this->form['output'] = strtotime($time);
        $this->viewHTML('form.php');
    }

    public function displayDate () 
    {
        // can use this also $controller->request['param_0']
        $controller = Controller::getInstance();
        $this->form['title'] = 'Convert Unix time to Date and Time';
        $this->form['action'] = 'displayDate';
        $date = isset($controller->request['unix'])?(int)$controller->request['unix']:strtotime('now');
        $this->form['input'] = array('name' => 'unix', 'label' => 'Unix time', 'type' => 'text',  'value'  => $date);
        $this->form['output'] = date('Y-m-d H:i:s',$date);
        $this->viewHTML('form.php');
    }

    public function convertJson ()
    {
        $controller = Controller::getInstance();
        $this->form['title'] = 'Convert Json to Array';
        $this->form['action'] = 'convertJson';
        $json = isset($controller->request['json'])?$controller->request['json']:'';
        $this->form['input'] = array('name' => 'json', 'label' => 'Json', 'type' => 'textarea',  'value'  => $json);
        $this->form['output'] = json_decode($json,true);
        $this->viewHTML('form.php');
    }
    
    
    public function unserializeText ()
    {
        $controller = Controller::getInstance();
        $this->form['title'] = 'Unserialize Text';
        $this->form['action'] = 'unserializeText';
        $text = isset($controller->request['text'])?$controller->request['text']:'';
        $this->form['input'] = array('name' => 'text', 'label' => 'Serialized text', 'type' => 'textarea',  'value'  => $text);
        $this->form['output'] = unserialize($text);
        $this->viewHTML('form.php');
    }
    
    public function showRequest () 
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
    $app = Example::getInstance();
    $app->callBasename();
}

?>
