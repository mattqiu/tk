<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class mobile_enroll extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->_viewData['title']=lang('nav_register').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');

        $this->_viewData['memberDomainInfo'] = $this->m_global->getMemberDomainInfo();
        $this->_viewData['is_register'] = $this->m_global->checkDomainIsWWW();
    	$this->load->view('mall/mobile_enroll.php',$this->_viewData);
	}

}
