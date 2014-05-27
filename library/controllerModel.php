<?php
/**    APPLICATION:    Framework
*    FILE:            model.php
*    DESCRIPTION:    library - Model class to use as starter of any class
*                    all classes should be named as NameSome and the file as nameSome.php casesensitive
*    CREATED:        1 May 2013 by Diana De vargas
*    UPDATED:                                    
*/

abstract class ControllerModel extends Model
{
    protected $_View;
    public    $pageTitle;

    /**
     * constructor : adds the view object 
     * @return void
     */
    protected function __construct()
    {
        parent::__construct();
        $this->_View  = View::getInstance();
    }

    /**
    * Abstarct functions
    *   
    */
    abstract public function index();

    /**
    * function to call methods by URL
    *
    * @return mixed
    */
    final private function call ($method_name, $parameter = array())
    {
        $class = get_class($this); 
        if (!empty($method_name) && method_exists($class,$method_name))
        {
            return call_user_func_array(array($this, $method_name), $parameter);
        }
        else
        {
            // Dont call index again avoid loops
            $callers=debug_backtrace(false);
            $trace = array();
            foreach($callers as $call) {
                if (isset($call['class']) && ($call['class'] == $class) && ($call['function'] == 'index'))
                {
                    $this->viewHTML('404.html');
                    return false;
                }
            }
            return call_user_func_array(array($this, 'index'), $parameter);
        }
    }

    /**
    * function to call methods by URL
    *
    * @return mixed
    */
    final public function callBasename ($parameter = array())
    {
        $controller = Controller::getInstance();
        if (isset($controller->basename))
            $this->call($controller->basename,$parameter);
        else
            $this->call('',$parameter);
    }

    /**
    * functions to manage views
    *
    */
    final private function view ($view,$type='HTML',$filename='')
    {
        if (isset($this->_View) && is_object($this->_View))
        {
            $this->_View->displayView($view,$type,$this,$filename);
        }
    }

    final public function viewHTML ($view)
    {
        $this->view($view,'html');
    }

    final public function viewJSON ($view)
    {
        $this->view($view,'json');
    }

    final public function viewFILE ($view,$filename)
    {
        $this->view($view,'file',$filename);
    }
    
    final public function viewDebug ($view)
    {
        $this->view($view,'debug');
    }
}