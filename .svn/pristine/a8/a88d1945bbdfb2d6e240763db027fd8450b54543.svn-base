<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission_special_do extends MY_Controller {

    public static $COMM_TYPE_IDS = array(6,2,24,25,7,8,1,23,26);
    
    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('commission_special_do',$this->_adminInfo);
    }

    public function index() {         
        $this->load->model('o_fix_commission');        
       
        $this->_viewData['title'] = lang('commission_special_do');
        $this->_viewData['commList'] = self::$COMM_TYPE_IDS;//佣金奖项
        parent::index('admin/');
    }
    
    public function add_comm_qualified(){
        
        $this->load->model('tb_users');
        $this->load->model('o_fix_commission');
        
        $postData = $this->input->post();
        $uid = isset($postData['uid'])?(int)$postData['uid']:0;
        $item_type = isset($postData['item_type'])?(int)$postData['item_type']:0;

        //记录日志
        $this->load->model("tb_logs_bonus_special");
        $this->tb_logs_bonus_special->add_log($this->_adminInfo,$postData,__FUNCTION__."(".lang("add_to_cur_month_queue").")");

        $userInfo = $this->tb_users->getUserInfo($uid);

        if(!$userInfo){
            echo json_dump(array('success'=>false,'msg'=>lang('pls_input_right_uid')));
        }
        
        if(!in_array($item_type, self::$COMM_TYPE_IDS)){
            echo json_dump(array('success'=>false,'msg'=>lang('pls_sel_comm_item')));
        }

        //全球日分红加入本月队列，需要验证是否满足条件
        if ($item_type == 6) {
            $this->load->model('tb_daily_bonus_qualified_list');
            $exists = $this->tb_daily_bonus_qualified_list->get([
                'select'=>'uid',
                'where'=>[
                    'uid'=>$uid
                ]
            ],false,true);
            if (!empty($exists)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_has_in_queue")));exit;
            }


            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) ) as user_rank  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid=".$uid;
            $result = $this->db->query($sql)->row_array();
            if (empty($result)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_not_match_daily_bonus")));exit;
            }
        }

        //新会员奖 验证是否满足条件
        $new_member_start = $this->input->post("new_member_start");
        
        if ($item_type == 26) {
            $this->load->model("o_bonus");
            $uids = array($uid);

            if(empty($new_member_start)) {
                echo json_dump(array('success'=>false,'msg'=>lang("please_select_queue_time")));exit;
            }
            $this->load->model('tb_new_member_bonus');
            $exists = $this->tb_new_member_bonus->get([
                'select'=>'uid',
                'where'=>[
                    'uid'=>$uid
                ]
            ],false,true);
            if (!empty($exists)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_has_in_queue")));exit;
            }

            $weight = $this->o_bonus->getUsersTotalWeightArr_new($uids);
            $desc_money =  $weight[$uid]['total_money'] - $weight[$uid]['level_money'];
            if (!( $desc_money >=  5000 && $weight[$uid]['level_money'] >= 25000)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_order_not_match_new_bonus")));exit;
            }

        }


        $this->o_fix_commission->addUserToCommQualifyList($uid,$item_type,$new_member_start);


        echo json_dump(array('success'=>true,'msg'=>lang('result_ok')));
    }

    public function fix_user_commission(){
        
        $this->load->model('tb_users');
        $this->load->model('o_fix_commission');
        $this->load->model('o_fix_138_elite_commission');
        
        
        $postData = $this->input->post();

        //记录日志
        $this->load->model("tb_logs_bonus_special");
        $this->tb_logs_bonus_special->add_log($this->_adminInfo,$postData,__FUNCTION__."(".lang("").")");

        $uid = isset($postData['uid'])?(int)$postData['uid']:0;
        $item_type = isset($postData['item_type'])?(int)$postData['item_type']:0;
        $start = isset($postData['start'])?$postData['start']:'';
        $end = isset($postData['end'])?$postData['end']:'';
        
        $userInfo = $this->tb_users->getUserInfo($uid);
        if(!$userInfo){
            echo json_dump(array('success'=>false,'msg'=>lang('pls_input_right_uid')));
        }
        
        if(!in_array($item_type, self::$COMM_TYPE_IDS)){
            echo json_dump(array('success'=>false,'msg'=>lang('pls_sel_comm_item')));
        }

        if(!$start || !$end){
            echo json_dump(array('success'=>false,'msg'=>lang('pls_sel_date')));
        }

        if(strtotime($start) > strtotime($end)){
            echo json_dump(array('success'=>false,'msg'=>lang('input_date_error')));
        }

        if(strtotime($end) > strtotime(date('Y-m-d'))){
            echo json_dump(array('success'=>false,'msg'=>lang('date_error_over_today')));
        }
                
        if($item_type==8 || $item_type==1 || $item_type==23)
        {
            $month_day = $this->o_fix_commission->everyMonthFixedDay($start,$end);
            if(empty($month_day))
            {
                echo json_dump(array('success'=>false,'msg'=>lang('month_date_error_over_today')));
            }
        }

        if ($item_type == 6) {
            if (date("Ym",strtotime($start)) !== date("Ym",strtotime($end)) ) {
                echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_month_error")));
            }

        }

        if($item_type == 24 || $item_type == 2) {
            $this->o_fix_138_elite_commission->common_138_elite_interface($uid,$item_type,$start,$end);
        }else{
            $this->o_fix_commission->fixUserComm($uid,$item_type,$start,$end);
        }


        echo json_dump(array('success'=>true,'msg'=>lang('result_ok')));
    }

}
