<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class month_fee_to_amount extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_month_fee_change');
        $this->load->model('m_forced_matrix');
    }

    public function index(){
        $this->_viewData['title'] = lang('month_fee_to_amount');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:'';
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->tb_month_fee_change->alllist($searchData);



        $this->load->library('pagination');
        $url = 'admin/month_fee_to_amount';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_month_fee_change->getMonthLogListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    /***获取可转入的月费***/
    public function get_month_fee_pool($user_id){
        $userInfo=$this->m_forced_matrix->userinfo($user_id);
        if(!empty($userInfo)){
            $month_fee_pool=$userInfo->month_fee_pool;  //月费池
            $sql="select * from users_coupon_monthfee where uid=$user_id";
            $result=$this->db->query($sql)->result();
            if(!empty($result)){
                $coupon_level=$result[0]->coupon_level; //券等级
                $status=$result[0]->status;             //券使用状态,0=>未使用，1=>已使用,2=>券的金额已经用完
                if($status==1){
                    if($coupon_level==1){$month_fee_pool=$month_fee_pool-60;}
                    if($coupon_level==2){$month_fee_pool=$month_fee_pool-40;}
                    if($coupon_level==3){$month_fee_pool=$month_fee_pool-20;}
                }
            }
            return $month_fee_pool;
        }
        else
        {
            return false;
        }
    }


    public function checkUserId(){
        $user_id=$this->input->post('user_id');
        if(trim($user_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('id_not_null')));
            exit();
        }
        $userInfo=$this->m_forced_matrix->userinfo($user_id);
        if(empty($userInfo)){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }
        $month_fee_pool=$this->get_month_fee_pool($user_id);
        $amount=$userInfo->amount;
        echo json_encode(array('success'=>true,'msg'=>lang('max_cash').$month_fee_pool,'amount'=>$amount));
    }

    public function checkData(){
        $user_id=$this->input->post('user_id');
        $month_fee_pool=$this->input->post('month_fee_pool');
        if(trim($user_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('id_not_null')));
            exit();
        }
        $userInfo=$this->m_forced_matrix->userInfo($user_id);
        if(empty($userInfo)){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }

        if(trim($month_fee_pool)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('cash_not_null')));
            exit();
        }

        $reg='/^\d+[.]?\d{0,2}$/';
        $res=preg_match($reg,$month_fee_pool,$result);
        if ($res==0) {
            echo json_encode(array('success'=>false,'msg'=>lang('month_fee_error')));
            exit();
        }

        $limit_month_fee_pool=$this->get_month_fee_pool($user_id);
        if($month_fee_pool>$limit_month_fee_pool){
            echo json_encode(array('success'=>false,'msg'=>lang('not_bigger')));
            exit();
        }

        if($this->saveData($user_id,$month_fee_pool)){
            echo json_encode(array('success'=>true,'msg'=>lang('transfer_to_success')));
        }
    }


    public function saveData($user_id,$month_fee_pool){

        $this->load->model('o_cash_account');
        $userInfo=$this->m_forced_matrix->userInfo($user_id);
        $old_month_fee_pool=$userInfo->month_fee_pool;                      //没转钱之前的月费池
        $new_month_fee_pool=$old_month_fee_pool-$month_fee_pool;            //转钱之后的月费池
        //1.修改users表
        $date=date('Y-m-d H:i:s',time());
        $sql = "update users set amount=amount+$month_fee_pool, month_fee_pool=month_fee_pool-$month_fee_pool WHERE id=$user_id";
        if ($this->db->query($sql)) {
            //2.新增资金变动记录
            if ($this->o_cash_account->createCashAccountLog(array('uid' => $user_id, 'item_type' => 15, 'amount' => $month_fee_pool*100, 'create_time' => $date, 'related_uid' => $user_id))) {
                //3.新增月费变动记录
                $data = array(
                    'user_id' => $user_id,
                    'old_month_fee_pool' => $old_month_fee_pool,
                    'month_fee_pool' => $new_month_fee_pool,
                    'cash' => -$month_fee_pool,
                    'admin_id' => $this->_adminInfo['id'],
                    'type' => 5,
                    'create_time' => $date
                );
                if ($this->db->insert('month_fee_change', $data)) {
                    $this->m_log->adminActionLog($this->_adminInfo['id'],'admin_month_fee_to_amount','users',$user_id,
                        'month_fee_pool',$old_month_fee_pool,$new_month_fee_pool,-1*$month_fee_pool);
                    return true;
                }
            }
        }else{
            return false;
        }
    }
}

