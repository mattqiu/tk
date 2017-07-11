<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Withdraw_table_batch_detail extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_cash_take_out_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('batch_xq');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData[$this->uri->segments[4]] = $this->uri->segments[5];
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $logs = $this->tb_cash_take_out_logs->get_batch_detail($searchData,$searchData['batch_num']);
        $this->_viewData['rate'] = $logs['rate']['exchange_rate'];
        $this->_viewData['status'] = $logs['rate']['status'];
        $tongji = array('zshuliang'=>0,'zjine'=>0,'sxf'=>0,'sjje'=>0);
        foreach ($logs['list'] as $log) {
//            if ($log['status'] != 3) {
                $tongji['zshuliang']+=1;
                $tongji['zjine']+=$log['amount'];
                $tongji['sxf']+=$log['handle_fee'];
                $tongji['sjje']+=$log['actual_amount'];
//            }
        }
        $tongji['format_sjje'] = '￥'.sprintf("%.2f",$tongji['sjje']*$this->_viewData['rate']);
        $this->_viewData['uid'] = $this->_adminInfo['id'];
        $this->_viewData['tongji'] = $tongji; //统计
        $this->_viewData['list'] = $logs['list'];
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['batch_num'] = $this->uri->segments[5]; //批次号
        parent::index('admin/');
    }

    //批次取消
    public function generate_batch() {
        $post_array = $this->input->post();
        $data['where']['batch_num'] = $post_array['id'];
        $data['data']['batch_num'] = '';
        $data['data']['status'] = 0;
        $is = $this->tb_cash_take_out_logs->up_batch_num($post_array['id'], $data);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }

    //一审单项操作
    public function alone_check_one() {
        $post_array = $this->input->post();
        $where['id'] = $post_array['id'];
        $batch['status'] = $post_array['status'];
        $batch['batch_num'] = '';
        if ($post_array['cause']) {
            $batch['check_info'] = $post_array['cause'];
        }
        $is = $this->tb_cash_take_out_logs->up_log_num($where, $batch);
        if ($is) {
            echo json_encode(array('success' => TRUE));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }

    //提交到支付宝
    public function batch_Alipay() {
        $post_array = $this->input->post();
        $data = $this->tb_cash_take_out_logs->alipay_api_data_info(trim($post_array['id']));
        //修改批次的状态为处理中
        $this->tb_cash_take_out_logs->update_batch_pending(trim($post_array['id']));
        $this->load->model('m_alipay');
        die(json_encode(array('success' => 1, 'data' => $this->m_alipay->batch_trans_get_code($data))));
    }

}
