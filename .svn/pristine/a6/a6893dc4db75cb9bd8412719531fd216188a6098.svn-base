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
            if (date("Ym",strtotime($start)) !== date("Ym",strtotime($end)) ) {
                parent::index('admin/');
                exit;
            }
        }

        $amount_data =  $this->o_fix_commission->fixUserComm_check($uid,$item_type,$start,$end);    
        
        $this->load->model('o_fix_commission');         
       
        $this->_viewData['item_type'] = $item_type;  
        $this->_viewData['amount'] = $amount_data;
        parent::index('admin/');
    }

}
