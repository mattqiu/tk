<?php
/**
 * Author: Derrick
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Repair_users_point extends MY_Controller {

    public function __construct() {
        parent::__construct();
//        $this->m_global->checkPermission('repair_users_amount', $this->_adminInfo);
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_users');
        $this->load->model('m_log');
    }

    public function index(){
        $curComType = '';
        $this->_viewData['curComType'] = $curComType;
        $this->_viewData['commission_type'] = $this->config->item('funds_change_report');
        $this->_viewData['title'] = lang('repair_users_point');
        parent::index('admin/');
    }

    /**
     * 统计分红点
     * @author: derrick
     * @date: 2017年4月26日
     * @param: 
     * @reurn: return_type
     */
    public function checkData_query(){
        $user_id=$this->input->post('uid');
        //不能为空
        if(trim($user_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }

        //检查用户id是否存在
        $this->load->model('tb_users');
        $user_exists = $this->tb_users->get_user_info($user_id, 'amount, profit_sharing_point');
        if(empty($user_exists)) {
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }

        echo json_encode(array('success'=>true,'sys_point'=>$this->_calculate_user_point($user_id),'user_point'=>$user_exists['profit_sharing_point'], 'msg'=>''));
    }
    
    /**
     * 修复分红点
     * @author: derrick
     * @date: 2017年4月26日
     * @param: 
     * @reurn: return_type
     */
    public function repair(){
        $user_id =$this->input->post('uid_txt');
        //不能为空
        if(trim($user_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('uid_not_null')));
            exit();
        }

   		//检查用户id是否存在
        $this->load->model('tb_users');
        $user_exists = $this->tb_users->get_user_info($user_id, 'amount, profit_sharing_point');
        if(empty($user_exists)) {
            echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
            exit();
        }
        
        $profit_sharing_point = $this->_calculate_user_point($user_id);
        $this->db->query("UPDATE `users` SET `profit_sharing_point` = $profit_sharing_point WHERE `id` = $user_id");
        $this->db->insert('admin_repair_user_point_log',array(
        	'uid' => $user_id,
        	'point_before' => $user_exists['profit_sharing_point'],
        	'point_after' => $profit_sharing_point,
        	'admin_id' => $this->_adminInfo['id'],
        ));
        echo json_encode(array('success'=>true, 'msg'=>lang('update_address_successed')));
    }
    
    /**
     * 计算用户分红点
     * @author: derrick
     * @date: 2017年4月26日
     * @param: @param unknown $user_id
     * @reurn: return_type
     */
    private function _calculate_user_point($user_id) {
    	$this->load->model("tb_cash_account_log_x") ;

    	/* $add_total = $this->tb_cash_account_log_x->get_sum_amount_x_by_uid($user_id,17);
        $before_add = $this->tb_cash_account_log_x->get_sum_amount_old_by_uid($user_id,17);
    	$add_total = $add_total / 100 + $before_add;
    	$add_total = $add_total / 100 + $before_add; */
    	$this->load->model(array('tb_profit_sharing_point_add_log', 'tb_profit_sharing_point_reduce_log'));
    	$add_total = $this->tb_profit_sharing_point_add_log->count_by_uid('point', $user_id);
    	
    	$reduct_total = $this->tb_profit_sharing_point_reduce_log->count_by_uid('point', $user_id);
    	return $add_total - $reduct_total;
    }
}