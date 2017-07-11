<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Upgrade_order_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }

    public function index() {

        $this->_viewData['title'] = lang('order_search');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['idEmail'] = isset($searchData['idEmail'])?$searchData['idEmail']:'';
        $searchData['order_sn'] = isset($searchData['order_sn'])?$searchData['order_sn']:'';
        $searchData['txn_id'] = isset($searchData['txn_id'])?$searchData['txn_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $searchData['table'] = isset($searchData['table'])?$searchData['table']:'user_month_fee_order';
        $searchData['status'] = isset($searchData['status'])?$searchData['status']:'';
        $searchData['payment_method'] = isset($searchData['payment_method'])?$searchData['payment_method']:'';

        $lists = $this->m_admin_helper->getUpgradeList($searchData);
        $this->_viewData['list'] = $lists;

        $this->load->library('pagination');

        $url = 'admin/upgrade_order_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getUpgradeListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

}