<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Monthly_fee_report extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }

    public function index() {
        $this->_viewData['title'] = lang('monthly_fee_detail');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['idEmail'] = isset($searchData['idEmail'])?$searchData['idEmail']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->getMonthlyFeeList($searchData);

        $this->load->library('pagination');
        $url = 'admin/monthly_fee_report';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getMonthlyFeeListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }
    
}