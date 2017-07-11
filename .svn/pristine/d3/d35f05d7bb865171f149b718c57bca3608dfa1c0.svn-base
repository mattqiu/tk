<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class paypal_pending_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title'] = lang('paypal_pending_log');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 10;
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $this->load->model("tb_cash_take_out_logs");
        $logs = $this->tb_cash_take_out_logs->get_paypal_pending_log($searchData, $searchData['page'], $searchData['page_num']);
//        fout($logs);exit;
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');
        $url = 'admin/paypal_pending_log';
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
    //备注操作
    public function alone_check_one() {
        $post_array = $this->input->post();
        if ($post_array['cause']) {
            $batch['order_id'] = $post_array['id'];
            $batch['remark'] = $post_array['cause'];
            $batch['admin_user'] = $this->_adminInfo['email'];
        }
        if ($post_array['status']) {
            $where['order_id'] = $post_array['id'];
            $where['status'] = $post_array['status'];
        }
        $this->load->model("tb_cash_take_out_logs");
        $is = $this->tb_cash_take_out_logs->up_paypal_pending_log($batch,$where);
        if ($is) {
            echo json_encode(array('success' => TRUE));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }
    
    public function get_remark_list() {
        $post_array = $this->input->post();
        if ($post_array['id']) {
            $batch['order_id'] = $post_array['id'];
        }
        $this->load->model("tb_cash_take_out_logs");
        $is = $this->tb_cash_take_out_logs->get_remark_list($batch);
//        fout($is);exit;
        if ($is) {
            echo json_encode(array('success' => TRUE,'data'=>$is));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }
}
