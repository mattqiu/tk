<?php

/**
 * public controller.
 * @author Terry Lu
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function check_ssl(){
    $CI =& get_instance();
    $subMenu = $CI->uri->segment(1);
    $class = $CI->router->fetch_class();
    $method = $CI->router->fetch_method();
    $ssl = $CI->config->item('ssl_class_method')?$CI->config->item('ssl_class_method'):array();   
    if(in_array($class.'/'.$method,$ssl) || in_array($subMenu,$ssl)){
        //force_ssl();
        $CI =&get_instance();
        $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] != 443) redirect( base_url($CI->uri->uri_string()) );
    }else{
        //unforce_ssl
        $CI =&get_instance();
        $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] == 443) redirect($CI->uri->uri_string());
    }
}

// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */