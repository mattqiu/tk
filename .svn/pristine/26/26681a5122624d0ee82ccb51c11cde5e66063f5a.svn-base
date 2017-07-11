<?php
/**
 * Author: Derrick
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order_achievement_repair extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_users');
        $this->load->model('m_log');
    }

    public function index(){       
        
        $this->_viewData['title'] = lang('user_order_achievement_repair');
        parent::index('admin/');
    }

    
    /**
     * 修复用户业绩
     * @author: derrick
     * @date: 2017年4月26日
     * @param: 
     * @reurn: return_type
     */
    public function repair()
    {
        $this->load->model('o_trade');
        $this->load->model('m_user');
        $this->load->model('o_fix_commission');
        $this->load->model('tb_users_store_sale_info_monthly');
        
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
        
        //修复用户业绩
        $this->o_trade->order_achievement_repair($user_id);                
       
        $this->tb_users_store_sale_info_monthly->statistics_user_monthly($user_id,0);
        
        $last_bonus_data = array(1,7,8,23,25); //缺6 每天全球利润分红
        
        $bonus_data = array(7,25);
        
        for($i=0;$i < count($last_bonus_data); $i++)
        {
            $this->o_fix_commission->addUserToCommQualifyList($user_id,$last_bonus_data[$i],'');
        }
        
        for($i=0;$i < count($bonus_data); $i++)
        {
            $this->o_fix_commission->fixUserComm($user_id,$bonus_data[$i],'2017-05-01 00:00:00','2017-05-01 00:00:00');
        } 
        echo json_encode(array('success'=>true, 'msg'=>lang('update_address_successed')));
    }
    
   
}