<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Withdraw_table_batch extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_cash_take_out_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('view_batch');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 10;
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';//批次号
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $logs = $this->tb_cash_take_out_logs->get_all_batch($searchData, $searchData['page'], $searchData['page_num']);
//        fout($logs);exit;
        $this->_viewData["count_money"] = $logs["count"];
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');

        $url = 'admin/withdraw_table_batch';
        add_params_to_url($url, $searchData);

        $config['base_url'] = base_url($url);
        $config['total_rows'] = $logs['num'];
        $config['per_page'] = $searchData['page_num'];
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['start_time'] = $searchData['start'];
        $this->_viewData['end_time'] = $searchData['end'];
        parent::index('admin/');
    }

}
