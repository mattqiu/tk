<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Enroll extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->_viewData['title']=lang('nav_register').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();

        $this->_viewData['memberDomainInfo'] = $this->m_global->getMemberDomainInfo();
        $this->_viewData['is_register'] = $this->m_global->checkDomainIsWWW();
        parent::index('mall/',config_item('website_stop_join')?'announce':'store_register','header');
    }

}
