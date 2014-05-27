<?php
/**	APPLICATION:	Framework
*	FILE:			view.php
*	DESCRIPTION:	library - methods to display views
*	CREATED:        1 May 2013 by Diana De vargas
*	UPDATED:									
*/

class View extends Model
{
    protected $_Path;
	
    /**
     * constructor : init the class
     *
     * @return void
     */
    public function init($modulePath='')
    {
        if (empty($modulePath))
        {
            $controller = Controller::getInstance();
            if (!isset($this->_Path))
            {
                $this->_Path = $controller->modulePath.'views/';
            }
        }
        else
        {
            $this->_Path = $modulePath;
        }
    }
    
    /**
     * Display a view
     *
     * @return void
     */
    public function displayView($view,$type,$obj,$filename='')
    {
        $controller = Controller::getInstance();
        $config = Config::getInstance();
        $objName = get_class($obj);
        $$objName = $obj;
        $this->init();
        //echo "file: ".$this->_Path.$view;
        
        if (!headers_sent()) 
        {
            switch (strtolower($type))
            {
                case 'json': header("Content-Type: application/json;charset=iso-8859-1");
                             break;
                case 'file': header ( 'Content-Type: text/plain; charset=ISO-8859-1' );
                             header ( "Content-type: application/octet-stream" );
                             header ( "Content-Disposition: attachment; filename=\"$filename\"" );
                             break;
                case 'debug' :   echo '<pre>';
                             break;
                default :
                             break;
            }
        }
        if (is_file($this->_Path.$view))
        {
            include ($this->_Path.$view);
        }
    }
}