<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reg_ok extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->_viewData['title']=lang('reg_success').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();

        parent::index('mall/','','header');
    }

}
