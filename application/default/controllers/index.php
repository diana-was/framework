<?php
/* 
 * This is an example of a controller
 *  
 */   

class example extends  ControllerModel
{
    public function index ()
    {
        $this->viewHTML('menu.html');
    }

    public function helloWorld ()
    {
        $this->viewHTML('hello_world.html');
    }

    public function exampleOutputFile ()
    {
        $this->viewFILE('hello_world.html','myFile.html');
    }
    
    public function exampleOutputJson ()
    {
        $this->viewJSON('json.php');
    }
    
}
/*
 *  Run if called with bootstrap
 */
if (isset($controller))
{
    /* call the method in the URL */
    $app = example::getInstance();
    $app->callBasename();
}
?>