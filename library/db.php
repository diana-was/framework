<?php
/**
 * DB Access class
 * Author: Diana De Vargas
 * 
 */

Class Db  {

    private static $_dbType;
    
    private function __construct() {}

    /**
     * Get the db static object
     *
     * @return self
     */
    public static function getInstance($host='', $db='',$type='')
    {
        self::$_dbType = !empty($type)?strtolower(trim($type)):(isset(self::$_dbType)?self::$_dbType:'mysql');
        
        switch (self::$_dbType) {
            case 'mysql': return DbMysql::getInstance($host, $db);
                          break;
            default     : return DbMysql::getInstance($host, $db);
                          break;
        }
    }

} 

?>