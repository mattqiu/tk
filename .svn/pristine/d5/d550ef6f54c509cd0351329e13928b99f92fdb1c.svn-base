<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Upgrade_level extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('level');
        $this->load->model('M_upgrade_level','m_upgrade_level');
        $this->load->model('M_user','m_user');
    }

    public function index(){

        $this->_viewData['title'] =  lang('upgrade_level');
        $this->_viewData['user_rank'] =  $this->m_user->getUserRank($this->_userInfo['id']);
        $this->_viewData['validity'] =  date('Y-m-d',strtotime("+1 year"));
        $this->_viewData['levels'] =  $this->m_upgrade_level->getLevel();
        parent::index();
    }


}
