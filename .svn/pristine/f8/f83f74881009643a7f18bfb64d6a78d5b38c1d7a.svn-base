<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rank extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
    }

    public function index() {
        $this->_viewData['title'] = lang('my_rank');
        
        parent::index();
    }

}
