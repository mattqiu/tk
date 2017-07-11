<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class mobile_reg_success extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

    	$this->load->view('mall/mobile_reg_success',$this->_viewData);
	}

}
