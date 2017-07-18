<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission_special_check extends MY_Controller {

    public static $COMM_TYPE_IDS = array(6,2,24,25,7,8,1,23,26);
    
    public function __construct() {
        parent::__construct();        
    }

    public function index() {         
        $this->load->model('o_fix_commission');        
       
        $this->_viewData['title'] = lang('commission_special_check');
        $this->_viewData['commList'] = self::$COMM_TYPE_IDS;//佣金奖项
        parent::index('admin/');
    }
    
    public function fix_user_commission(){
        
        $this->load->model('tb_users');
        $this->load->model('o_fix_commission');
        $this->load->model('o_fix_138_elite_commission');        
        $this->load->model('o_fix_commission');      
        $this->load->model('tb_users_store_sale_info_monthly');
        
        $this->_viewData['title'] = lang('commission_special_check');
        $this->_viewData['commList'] = self::$COMM_TYPE_IDS;//佣金奖项
        
        $postData = $this->input->post();
       
        $uid = isset($postData['uid'])?(int)$postData['uid']:0;
        $item_type = isset($postData['item_type'])?(int)$postData['item_type']:0;
        $start = isset($postData['start'])?$postData['start']:'';
        $end = isset($postData['end'])?$postData['end']:'';
        
        $userInfo = $this->tb_users->getUserInfo($uid);
       
        if(!$userInfo){
            parent::index('admin/');
            exit;
        }
        
        if(!in_array($item_type, self::$COMM_TYPE_IDS)){
            parent::index('admin/');
            exit;
        }
       
        if(!$start || !$end){
           parent::index('admin/');
           exit;
        }

        if(strtotime($start) > strtotime($end)){
            parent::index('admin/');
            exit;
        }
        
        if(strtotime($end) > strtotime(date('Y-m-d'))){
            parent::index('admin/');
            exit;
        }
        
        if($item_type==8 || $item_type==1 || $item_type==23)
        {
            $month_day = $this->o_fix_commission->everyMonthFixedDay($start,$end);
            if(empty($month_day))
            {
                parent::index('admin/');
                exit;
            }
        }
        
        if ($item_type == 6) {
            $month_time = date("Ym",strtotime($start)-30*24*3600);
            $query = $this->tb_users_store_sale_info_monthly->get_user_monthly_by_time($uid,$month_time);
            if(empty($query))
            {
                echo json_dump(array('success'=>false,'msg'=>"业绩为空"));exit;
            }
            if (date("Ym",strtotime($start)) !== date("Ym",strtotime($end)) ) {
                parent::index('admin/');
                exit;
            }
        }
        
       
       
        $amount_data =  $this->o_fix_commission->fixUserComm_check($uid,$item_type,$start,$end);    
        
        echo json_dump(array('success'=>true,'msg'=>$amount_data));exit;
    }

    /**
     * 获取用户信息和用户业绩
     */
    public function fix_user_commission_check(){    
        
        $this->load->model('m_user');
        $this->load->model('tb_new_member_bonus');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_group_share_list');
        $this->load->model('tb_users_store_sale_info_monthly');   
        
        $day_bonus_member = array();
        $week_bonus_member = array();
        $month_bonus_member = array();
        $postData = $this->input->post();    
        
        $uid = isset($postData['user_id'])?(int)$postData['user_id']:0;          
        $bonus_type = $postData['bonus_type'];       
        $user_info = $this->m_user->get_user_info_all($uid);        
        $monthly = $this->tb_users_store_sale_info_monthly->get_user_monthly($uid,3);
        //用户队列中的信息      
        if(!empty($bonus_type))
        {
            for($idx=0;$idx < count($bonus_type);$idx++)
            {
                switch($bonus_type[$idx])
                {
                    case 6:
                        $day_bonus_member = $this->tb_new_member_bonus->get_user_new_member_bonus_info($uid);                        
                        break;
                    case 25:
                        $week_bonus_member = $this->tb_week_leader_members->get_user_week_bonus_info($uid);
                        break;
                    case 1:
                        $month_bonus_member = $this->tb_month_group_share_list->get_user_month_bonus_info($uid);
                        break;
                }
                
            }
            
        }
        else 
        {
            $new_member = array();
            $week_bonus_member = array();
            $month_bonus_member = array();
        }
        $user_data = array
        (
            'user_info' => $user_info,
            'monthly'   => $monthly,
            'day_bonus' => $day_bonus_member,
            'week_bonus_member' => $week_bonus_member,
            'month_bonus_member' => $month_bonus_member,
        );
        
        echo json_dump(array('success'=>true,'msg'=>$user_data));exit;
    }
    
    
    
}
