<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class policy_procedures extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->lang->load('resource_library');
        $this->_viewData['title'] = lang('policy_procedures');


        if($this->_curLanguage=='kr'){
        	$lanFileKey = array_search("resource_library_lang.php",$this->lang->is_loaded);
	        if($lanFileKey!==false){
	            unset($this->lang->is_loaded[$lanFileKey]);
	        }
        	$this->lang->load('resource_library','english');
        }
        parent::index();
    }

}