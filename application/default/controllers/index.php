<?php
/* 
 * This is an example of a controller
 *  
 */   

class example extends  ControllerModel
{
    public function index ()
    {
        $this->viewHTML('hello_world.html');
    }

    // Call by url as domain.name/file
    public function file ()
    {
        $this->viewFILE('hello_world.html','myFile.html');
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