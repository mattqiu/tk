<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Bank_withdraw extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('bank_withdraw',$this->_adminInfo);
        $this->load->model('tb_cash_take_out_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('bank_withdraw');
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);

        //当前汇率
        //$this->m_global->checkPermission('cash_withdrawal_list', $this->_adminInfo);
        $this->_viewData['rate'] = $rate ? sprintf("%.2f",$rate) : sprintf("%.2f",$this->m_global->get_rate('CNY'));
        /*         * ** */
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 100;
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';

        $logs = $this->tb_cash_take_out_logs->get_all_bank_logs($searchData, $searchData['page'], $searchData['page_num']);

        $tongji = array('zshuliang'=>0,'zjine'=>0,'sxf'=>0,'sjje'=>0);
        foreach ($logs['list'] as $log) {
//            if($log['status']!=3) {
                $tongji['zshuliang']+=1;
                $tongji['zjine']+=$log['amount'];
                $tongji['sxf']+=$log['handle_fee'];
                $tongji['sjje']+=$log['actual_amount'];
//            }
        }
        $tongji['format_sjje'] = '￥'.sprintf("%.2f",$tongji['sjje']*$this->_viewData['rate']);
        $this->_viewData['tongji'] = $tongji; //统计
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');

        $url = 'admin/bank_withdraw';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = $searchData['page_num'];
        $config['total_rows'] =  $logs['num'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['page_num'] = $searchData['page_num'];
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    /** 提现汇率的自定义 */
    public function withdrawal_rate(){
        if($this->input->is_ajax_request()){
            $rate = $this->input->post('rate');
            if($rate > 0 && $rate < 7 ){
                set_cookie('withdrawal_rate',$rate,0,get_public_domain());
            }
            die(json_encode(array('success'=>1)));
        }
    }

    //生成唯一批次号
    public function only_name() {
        $num = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        echo json_encode(array('success' => TRUE, 'msg' => $num));
    }

    //批次入库
    public function generate_batch() {
        $post_array = $this->input->post();
        $post_array['batch_num']=$post_array['batch_number'];
        unset($post_array['batch_number']);
        //-------------此处查看是否已有批次
        $isy = $this->tb_cash_take_out_logs->select_batch_num($post_array);
        if ($isy['batch_num']) {
            echo json_encode(array('success' => FALSE, 'msg' => 8));
            exit;
        }
        //-------------------------------
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);
        //$rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        //当前汇率
        //$this->m_global->checkPermission('cash_withdrawal_list', $this->_adminInfo);
        $exchange_rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        /*         * ** */
        $batch = array(
            'batch_num' => $post_array['batch_num'],
            'total' => $post_array['total'],
            'lump_sum' => $post_array['lump_sum'],
            'reason' => $post_array['payment_reason'],
            'born_time' => date("Y-m-d H:i:s", time()),
            'exchange_rate'=>$exchange_rate,
        );
        $is = $this->tb_cash_take_out_logs->insert_batch_bank_num($batch, $post_array['checkboxes']);
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
        if ($post_array['cause']) {
            $batch['check_info'] = $post_array['cause'];
        } else {
            echo json_encode(array('success' => FALSE));exit;
        }
        $is = $this->tb_cash_take_out_logs->up_log_num($where, $batch);
        if ($is) {
            echo json_encode(array('success' => TRUE));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }
    /*
     * 查看批次
     */
    public function bank_withdraw_batch() {
        $this->_viewData['title'] = lang('view_batch');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 10;
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';//批次号
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $logs = $this->tb_cash_take_out_logs->get_bank_all_batch($searchData, $searchData['page'], $searchData['page_num']);
//        fout($logs);exit;
        $this->_viewData["count_money"] = $logs["count"];
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');

        $url = 'admin/bank_withdraw/bank_withdraw_batch';
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
        parent::index('admin/','bank_withdraw_batch');
    }
    
    public function bank_withdraw_batch_detail() {
        $this->_viewData['title'] = lang('batch_xq');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData[$this->uri->segments[4]] = $this->uri->segments[5];
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $logs = $this->tb_cash_take_out_logs->get_bank_batch_detail($searchData,$searchData['batch_num']);
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
        parent::index('admin/','bank_withdraw_batch_detail');
    }

    //批次取消
    public function generate_batch_qx() {
        $post_array = $this->input->post();
        $data['where']['batch_num'] = $post_array['id'];
        $data['data']['batch_num'] = '';
        $data['data']['status'] = 0;
        $is = $this->tb_cash_take_out_logs->up_bank_batch_num($post_array['id'], $data);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }

    //一审单项操作
//    public function alone_check_ones() {
//        $post_array = $this->input->post();
//        $where['id'] = $post_array['id'];
//        $batch['status'] = $post_array['status'];
//        $batch['batch_num'] = '';
//        if ($post_array['cause']) {
//            $batch['check_info'] = $post_array['cause'];
//        }
//        $is = $this->tb_cash_take_out_logs->up_log_num($where, $batch);
//        if ($is) {
//            echo json_encode(array('success' => TRUE));
//        } else {
//            echo json_encode(array('success' => FALSE));
//        }
//    }

    //提交到支付宝
    public function batch_Alipay() {
        $post_array = $this->input->post();
        $data = $this->tb_cash_take_out_logs->alipay_api_data_info(trim($post_array['id']));
        //修改批次的状态为处理中
        $this->tb_cash_take_out_logs->update_bank_batch_pending(trim($post_array['id']),trim($post_array['pay_type']));
//        $this->load->model('m_alipay');
//        die(json_encode(array('success' => 1, 'data' => $this->m_alipay->batch_trans_get_code($data))));
    }

}
