<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//Load Active Record manually
require_once(BASEPATH.'database/DB_driver.php');
require_once(BASEPATH.'database/DB_active_rec.php');
//Load my version of Active Record
require_once(APPPATH. 'core/MY_DB_active_record.php');
//Finally initialize the DB class
class CI_DB extends MY_DB_active_record{}

//In order to not break the loader class I will create my dummy loader class
class MY_Loader extends CI_Loader {
    public function redis($params = '', $return = FALSE)
    {
        // Grab the super object
        $CI =& get_instance();

        // Do we even need to load the database class?
        if (class_exists('Redis') AND $return == FALSE AND isset($CI->redis) AND is_object($CI->redis))
        {
            return FALSE;
        }
        var_dump($params);exit(__FILE__.",".__LINE__."<BR>");
    }
}