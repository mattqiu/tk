<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Paypal_failure_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }

    public function index($id = NULL) {
        $this->_viewData['title'] = lang('admin_paypal_failure_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['txn_id'] = isset($searchData['txn_id'])?$searchData['txn_id']:'';
        $searchData['email'] = isset($searchData['email'])?$searchData['email']:'';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->getPayPalList($searchData);

        $this->load->library('pagination');
        $url = 'admin/paypal_failure_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getPayPalRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }


}