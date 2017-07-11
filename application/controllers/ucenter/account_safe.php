<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_safe extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->_viewData['title'] = lang('account_safe');
        parent::index();
    }
}

