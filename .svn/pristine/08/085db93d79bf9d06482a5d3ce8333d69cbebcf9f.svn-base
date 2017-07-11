<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class admin_after_sale_batch extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_after_sale_batch');
    }

    public function index() {
        $this->_viewData['title'] = lang('view_batch');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';//批次号
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $logs = $this->tb_admin_after_sale_batch->get_after_sale_batch($searchData);
        $this->_viewData['list'] = $logs;
        $this->load->library('pagination');

        $url = 'admin/admin_after_sale_batch';
        add_params_to_url($url, $searchData);

        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_admin_after_sale_batch->get_after_sale_batch_rows($searchData);
        //$config['per_page'] = $searchData['page_num'];
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['start_time'] = $searchData['start'];
        $this->_viewData['end_time'] = $searchData['end'];
        parent::index('admin/');
    }

    //批次取消
    public function cancel_generate_batch() {
        $post_array = $this->input->post();
        $is = $this->tb_admin_after_sale_batch->cancel_batch_num($post_array['id']);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }


    //提交到支付宝
    public function submit_batch_Alipay() {
        $post_array = $this->input->post();
        $data = $this->tb_admin_after_sale_batch->alipay_data_info(trim($post_array['id']));
        //修改批次的状态为处理中
        $this->tb_admin_after_sale_batch->update_batch_pending(trim($post_array['id']));
        $this->load->model('m_alipay');
        die(json_encode(array('success' => 1, 'data' => $this->m_alipay->batch_trans_get_code_after($data))));
    }


}
