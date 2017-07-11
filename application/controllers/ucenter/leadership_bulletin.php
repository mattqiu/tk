<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @description 精英排行榜
 * Class leadership_bulletin
 */
class leadership_bulletin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_elite_rankings');
        //$this->load->model('m_forced_matrix');
    }

    public function index() {
        $this->_viewData['title'] = lang('leadership_bulletin');
        $result=$this->m_elite_rankings->store_ranking();
        $this->_viewData['result']=$result;
        parent::index();
    }
}