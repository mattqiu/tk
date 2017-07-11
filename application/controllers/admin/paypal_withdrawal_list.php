<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class paypal_withdrawal_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('paypal_withdrawal_list',$this->_adminInfo);
        $this->load->model('tb_cash_take_out_logs');
    }

    public function index() {
        $this->_viewData['title'] = lang('paypal_withdrawal_list');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int) (isset($searchData['page']) ? $searchData['page'] : 1), 1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 30;
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['country_id'] = isset($searchData['country_id']) ? $searchData['country_id'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $rate = get_cookie('withdrawal_rate', true);
        //$rate = $rate ? $rate : $this->m_global->get_rate('CNY');
        //当前汇率
        $this->_viewData['rate'] = $rate ? $rate : $this->m_global->get_rate('CNY');
        $logs = $this->tb_cash_take_out_logs->get_all_paypal_take_out_logs($searchData, $searchData['page'], $searchData['page_num']);
        //echo "<pre>";print_r($logs);exit;
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');

        $url = 'admin/paypal_withdrawal_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['per_page'] = $searchData['page_num'];
        $config['total_rows'] = $logs['num'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(1);
        $this->_viewData['page_num'] = $searchData['page_num'];
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    //一审单项操作
    public function alone_check_one() {
        $post_array = $this->input->post();
        $where['id'] = $post_array['id'];
        $batch['status'] = $post_array['status'];
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

    /** 导出 */
    function exportWithdrawal() {
        /*         * *汇率 */
        $rate = get_cookie('withdrawal_rate', true);
        $this->_viewData['rate'] = $rate ? $rate : $this->m_global->get_rate('CNY');
        /*         * ** */
        $searchData = $this->input->get() ? $this->input->get() : array();
        //echo "<pre>";print_r($searchData);exit;
        $page = max((int) (isset($searchData['page']) ? $searchData['page'] : 1), 1);
        $perPage = $searchData['page_num'] ? $searchData['page_num'] : 100;
        $is_export_lock = isset($searchData['is_export_lock']) ? $searchData['is_export_lock'] : '';
        /*         * *要注销的提交项* */
        unset($searchData['is_export_lock']); //分页
        unset($searchData['page']); //分页
        unset($searchData['page_num']); //每页数量
        unset($searchData['uid']);//ID
        /*         * ************** */
       
        $logs = $this->tb_cash_take_out_logs->get_all_paypal_take_out_logs($searchData, $page, $perPage);
        $data = $logs['list'];
        /*         * ***** */
        foreach ($data as $ky => $sd) {
            $update_data[] = array(
                'id' => $sd['id'],
                'status' => 2,
            );
            $nr[0] = $sd['card_number'];
            $nr[1] = $sd['actual_amount'];
            $nr[2] = 'USD';
            $nr[3] = $sd['id'];//提现记录ID
            $nr[4] = $sd['uid'];
            $dota[] = $nr;
        }
        
        if ($is_export_lock) {
            $this->db->update_batch('cash_take_out_logs', $update_data, 'id');
        }
        /*         * **** */
        $filename = 'PayPal_' . date('Y-m-d', time()) . '_' . time();

        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $filename . '.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        foreach ($dota as $v) {
            foreach ($v as $k => $item) {
                $v[$k] = $item;
            }
            fputcsv($fp, $v);
        }
    }
    
    /**
	 *  导入paypal
	 */
	public function import_paypal(){
		if (isset($_FILES['excelfile']['type']) == null)
		{
			$result['msg'] = lang('admin_file_format');
			$result['success'] = 0;
			die(json_encode($result));
		}
               
		$name = $_FILES['excelfile']['name'];
		$type = explode('.',$name); //限制上传格式
		if (!in_array(end($type),array('xls','xlsx','csv'))) {
			$result['msg'] = lang('admin_file_format');
			$result['success'] = 0;
			die(json_encode($result));
		}
		$mime = $_FILES['excelfile']['type'];
		$path = $_FILES['excelfile']['tmp_name'];
                $file = fopen($path,'r'); 
                while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
                $goods_list[] = array_filter($data);
                 }
                 fclose($file);
                 $tou = array_slice($goods_list, 0,18);//结果概述
                 $wei = array_slice($goods_list, 18);//详细信息
                $paypal_error_code = config_item('paypal_error_code');
                $failure = $success = 0;
                foreach ($wei as $k=> $value) {
                    $wh[$k]['id']=$value[2];
                    $del[]=$value[2];
                    $insert[$k]['id']=$value[2];
                    $insert[$k]['trade_no']=$value[0];
                    if($value[5]=='已完成'){
                        $wh[$k]['status']=1;
                        $success ++;
                    }elseif ($value[5]=='被拒絕') {
                        $wh[$k]['status']=3;
                        $failure++;
                    }
                    $wh[$k]['check_time']=date("Y-m-d H:i:s",time());
                    if(isset($value[6])){$wh[$k]['check_info']=lang($paypal_error_code[$value[6]]);}
                }
                $this->db->trans_begin();
                //判断是否是详情页面上传表格，如果是，则改变批次的状态为处理完成（隐藏取消批次按钮）
                if(isset($_POST["batch_num"]) && $_POST["batch_num"]){
                    $this->db->where('id', $_POST["batch_num"])->update('cash_paypal_take_out_batch_tb', array('status' => 3,'process_time'=>date("Y-m-d H:i:s",time()),'success'=>$success,'failure'=>$failure));
                }
                //echo "<pre>";print_r($wh);exit;
                $this->db->update_batch('cash_take_out_logs', $wh, 'id');
                $this->db->where_in('id',$del)->delete('mass_pay_trade_no');
                $this->db->insert_batch('mass_pay_trade_no', $insert);
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    $result['msg'] = 'NO';
                    $result['success'] = FALSE;
                    die(json_encode($result));
                }
                else
                {
                    $this->db->trans_commit();
                    $result['msg'] = 'OK';
                    $result['success'] = 1;
                    die(json_encode($result));
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
        //echo "<pre>";print_r($post_array);exit;
        $batch = array(
            'batch_num' => $post_array['batch_num'],
            'total' => $post_array['total'],
            'lump_sum' => $post_array['lump_sum'],
            //'reason' => '0',//付款理由
            'born_time' => date("Y-m-d H:i:s", time()),
            'exchange_rate'=>$exchange_rate,//汇率
            'handle_fee'=>$post_array['handle_fee'],
            'actual_amount' =>$post_array['actual_amount'],
        );
        $is = $this->tb_cash_take_out_logs->insert_paypal_batch_num($batch, $post_array['checkboxes']);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }
    /**
     * paypal批次列表
     */
    public function paypal_withdraw_table() {
        $this->_viewData['title'] = lang('view_batch');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['page_num'] = isset($searchData['page_num']) ? $searchData['page_num'] : 10;
        $searchData['batch_num'] = isset($searchData['batch_num']) ? $searchData['batch_num'] : '';//批次号
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $logs = $this->tb_cash_take_out_logs->get_paypal_all_batch($searchData, $searchData['page'], $searchData['page_num']);
        $this->_viewData['list'] = $logs['list'];
        $this->load->library('pagination');
       //echo "<pre>";print_r($logs);exit;
        $url = 'admin/paypal_withdrawal_list/paypal_withdraw_table';
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
        parent::index('admin/','paypal_withdraw_table_batch');
    }
    /**
     * 批次详情
     */
    public function paypal_withdraw_detail() {
        $this->_viewData['title'] = lang('batch_xq');
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData[$this->uri->segments[4]] = $this->uri->segments[5];
        $searchData['uid'] = isset($searchData['uid']) ? $searchData['uid'] : '';
        $searchData['card_number'] = isset($searchData['card_number']) ? $searchData['card_number'] : '';
        $searchData['status'] = isset($searchData['status']) ? $searchData['status'] : '';
        //echo "<pre>";print_r($searchData);exit;
        $logs = $this->tb_cash_take_out_logs->get_paypal_batch_detail($searchData,$searchData['batch_num']);
        //echo "<pre>";print_r($logs);exit;
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
        parent::index('admin/','paypal_withdraw_table_batch_detail');
    }
    /**
     * 批次详情页面  批次取消
     */
    public function cancel_batch() {
        $post_array = $this->input->post();
        $data['where']['batch_num'] = $post_array['id'];
        $data['data']['batch_num'] = '';
        $data['data']['status'] = 0;
        $is = $this->tb_cash_take_out_logs->up_paypal_batch_num($post_array['id'], $data);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }
    /**
     * 批次改为线下处理
     */
    public function cancel_batch2() {
        $post_array = $this->input->post();
        $data['where']['batch_num'] = $post_array['id'];
        $data['data']['remark'] = '已通过线下其他方式支付';
        $data['data']['status'] = 1;
        $is = $this->tb_cash_take_out_logs->up_paypal_batch_num2($post_array['id'], $data);
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }
     /** 提交到paypal处理 */
    public function batch_Alipay() {
        $post_array = $this->input->post();
        $this->load->model('m_paypal');
        $is=$this->m_paypal->submit_masspay(trim($post_array['id']));
        $this->db->where('id', trim($post_array['id']))->update('cash_paypal_take_out_batch_tb', array('operator'=>$this->_adminInfo['id']));
        if ($is === FALSE) {
            echo json_encode(array('success' => FALSE, 'msg' => "错误"));
        } else {
            echo json_encode(array('success' => TRUE, 'msg' => "成功"));
        }
    }
}
