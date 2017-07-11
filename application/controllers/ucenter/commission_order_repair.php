<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission_order_repair extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {

        $this->load->model('tb_withdraw_task');
        $this->_viewData['title'] = lang('commission_order_repair');

        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['uid'] = $this->_userInfo['id'];
        $searchData['comm_type'] = isset($searchData['comm_type']) ? $searchData['comm_type'] : '';
        $searchData['comm_year_month'] = isset($searchData['comm_year_month']) ? $searchData['comm_year_month'] : '';

        $this->load->model("tb_withdraw_task");

        $list = $this->tb_withdraw_task->get([
            'where' => [
                'uid' => $this->_userInfo['id'],
            ],
            'order' => [
                [
                    'key' => 'order_year_month',
                    'value' => 'asc'
                ],
                [
                    'key' => 'sale_amount_lack',
                    'value' => 'asc'
                ]
            ]
        ]);
        //$this->_viewData['list'] = $this->tb_withdraw_task->getCommOrderRepariList($searchData);
        $this->_viewData['list'] = $list;

        $this->_viewData['searchData'] = $searchData;

        parent::index('ucenter/');
    }

    public function repair_order(){

        $this->load->model('tb_withdraw_task');
        $order_year_month = $this->input->post('order_year_month');
        $error_code = $this->tb_withdraw_task->markOrderRepair($this->_userInfo['id'],$order_year_month);
        echo json_encode(array('success' => $error_code?FALSE:TRUE, 'msg' => $error_code?lang(config_item('error_code')[$error_code]):''));
    }
    
}