<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class export_orders_back extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		parent::index('admin/');
	}

	public function change_staus()
    {
        $tmp = $this->input->get_post();
        var_dump($tmp);
        parent::index('admin/');
    }
}
?>