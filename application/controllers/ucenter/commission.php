<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission extends MY_Controller {
    
    static $arrProportionType = array('sale_commissions_proportion' => 1,'forced_matrix_proportion'=>2,'profit_sharing_proportion'=>3);
    
    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
        $this->lang->load('level');
        $this->load->model('m_profit_sharing');
        $this->load->model('m_user');
        $this->load->model('tb_users');
        $this->load->model('m_coupons');

    }

    public function index() {
        
        //韩国会员隐藏转账功能文字描述 JacksonZheng
        $country_id = $this->_userInfo['country_id'];
        $this->_viewData['korea_hide'] = false;
        if($country_id == 3) {
           $this->_viewData['korea_hide'] = true;
        }

        $this->_viewData['title'] = lang('dashboard');
        $this->_viewData['transfer_cash_sum']=$this->transfer_cash_sum();
        $this->load->model('m_user');
        $this->_viewData['join_time'] = date('Y-m-d H:i:s',$this->_userInfo['create_time']);
        $this->_viewData['level'] = $this->_userInfo['user_rank'];
        $this->_viewData['uid'] = $this->_userInfo['id'];
        $this->_viewData['ewallet_name'] = $this->_userInfo['ewallet_name'];
        $this->_viewData['enable_time'] = $this->_userInfo['enable_time']!='0000-00-00 00:00:00'?$this->_userInfo['enable_time']:lang('inactive');
        $this->_viewData['store_rating_text'] = lang('level_'.$this->_userInfo['user_rank']);
        $this->_viewData['cur_title_text'] = lang('title_level_'.$this->_userInfo['sale_rank']);
        $this->_viewData['proportion'] = array(
            'sale_commissions_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'sale_commissions_proportion'),
            'forced_matrix_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'forced_matrix_proportion'),
            'profit_sharing_proportion'=>$this->m_profit_sharing->getProportion($this->_userInfo['id'],'profit_sharing_proportion'),
        );
        $this->_viewData['totalSharingPoint'] = $this->m_user->getTotalSharingPoint($this->_userInfo['id']);
        $this->_viewData['rewardSharingPointList'] = $this->m_user->getRewardSharingPointData($this->_userInfo['id']);

        switch ($this->_userInfo['user_rank']) {
            case 1:
                $levelInfo['level'] = lang('diamond');
                break;
            case 2:
                $levelInfo['level'] = lang('gold');
                break;
            case 3:
                $levelInfo['level'] = lang('silver');
                break;
            case 5:
                $levelInfo['level'] = lang('bronze');
                break;
            default:
                $levelInfo['level'] = lang('free');
                break;
        }
        $msg = $this->input->get('msg');
        $this->_viewData['msg'] = $msg=='payok'?lang('payment_success_delay_notice'):'';
        $this->_viewData['note_type'] = $msg;

        /*月费券信息*/
        $monthly_fee_coupon_num = $this->m_coupons->getMonthlyFeeCouponNum($this->_userInfo['id']);
        $this->_viewData['monthly_fee_coupon_note']['content'] = $monthly_fee_coupon_num?'('.sprintf(lang('monthly_fee_coupon_note'),$monthly_fee_coupon_num).')':'';
       // $this->_viewData['monthly_fee_coupon_note']['noteForLimit'] = ($monthly_fee_coupon_num && $this->_userInfo['country_id']==3)?lang('monthly_fee_coupon_note_limit'):'';
        $this->_viewData['monthly_fee_coupon_note']['noteForLimit'] = '';
        
        $this->_viewData['levelInfo'] = $levelInfo;
        $this->_viewData['payments'] = $this->m_global->getPayments(NULL,'','m_amount');
        $this->_viewData['transfer_point'] = $this->m_profit_sharing->getMonthPointLimit($this->_userInfo['id']);

        $this->_viewData['is_auto'] = $this->_userInfo['is_auto'];
        //$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
        $this->load->model('m_user');
        $this->_viewData['user']= $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        $this->_viewData['country_id']=$this->_userInfo['country_id'];//当前国家id
        parent::index();
    }
    
    public function saveSharingPointProportion() {

        $this->load->model('tb_profit_sharing_point_proportion');
        $dataPost = $this->input->post();

        $error_code = $this->tb_profit_sharing_point_proportion->saveUserProportion($this->_userInfo['id'],self::$arrProportionType[$dataPost['proportionType']],trim($dataPost['proportion']));
        if($error_code==0){

            $success = TRUE;
            $msg = '√ ' . lang('save_success');
        }else{

            $success = false;
            $msg = '× ' . lang(config_item('error_code')[$error_code]);
        }

        echo json_encode(array('success' => $success, 'msg' => $msg));
    }
    
    public function manuallyAddSharingPoint(){
        $money = trim($this->input->post('money'));
        if (!is_numeric($money) || $money <= 0 ||  get_decimal_places($money)>2) {
            $success = FALSE;
            $msg = '× ' .lang('positive_num_error');
        }elseif($money>$this->_userInfo['amount']){
            $success = FALSE;
            $msg = '× ' .lang('cur_commission_lack');
        }else{
            $data = $this->m_profit_sharing->manuallyAddSharingPoint($this->_userInfo,$money);
            $success = TRUE;
            $msg = '√ ' .lang('shift_success');
        }
        
        echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>isset($data)?$data:array()));
    }
    
    public function cashToMonthFee(){
        $money = trim($this->input->post('money'));
        if (!is_numeric($money) || $money <= 0 ||  get_decimal_places($money)>2) {
            $success = FALSE;
            $msg = '× ' .lang('positive_num_error');
        }elseif($money>$this->_userInfo['amount']){
            $success = FALSE;
            $msg = '× ' .lang('cur_commission_lack');
        }else{
            $this->db->trans_start();
            $data = $this->m_profit_sharing->cashToMonthFeePool($this->_userInfo,$money);//现金池转月费池
            $this->m_user->checkMonthFeeGap($this->_userInfo['id']);//检查是否欠月费。
            $this->db->trans_complete();
            $success = TRUE;
            $msg = '√ ' .lang('shift_success');
        }
        
        echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>isset($data)?$data:array()));
    }

    public function checkTakeOutPwd($pwd){
        if($this->m_user->encyTakeCashPwd($pwd,$this->_userInfo['token']) !==$this->_userInfo['pwd_take_out_cash']){
            return false;
        }
        return TRUE;
    }

    public function tranToMem(){
        $dataPost = $this->input->post();
        $tranToMemAmount = isset($dataPost['tranToMemAmount'])?trim($dataPost['tranToMemAmount']):0;
        $tranToMemId = isset($dataPost['tranToMemId'])?trim($dataPost['tranToMemId']):'';
        $tranToMemFundsPwd = isset($dataPost['tranToMemFundsPwd'])?trim($dataPost['tranToMemFundsPwd']):'';
        $ischeck = isset($dataPost['ischeck'])?$dataPost['ischeck']:false;
        // 重新检查转账金额是否超出了用户的总金额,直接从数据库中获取，绕开redis获取，避免不同步的情况发生
        $userAmout = $this->db->select('amount,status')->where('id', $this->_userInfo['id'])->force_master()->get('users')->row_array();
        $userAmout['amount'] = isset($userAmout['amount']) && $userAmout['amount'] ? $userAmout['amount'] : 0;
        $this->load->model('tb_users');
        $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$tranToMemFundsPwd);
        if (!is_numeric($tranToMemAmount) || $tranToMemAmount <= 0 ||  get_decimal_places($tranToMemAmount)>2) {
            $success = FALSE;
            $msg = '× ' .lang('positive_num_error');
        }elseif($tranToMemAmount > $userAmout['amount'] || $tranToMemAmount>$this->_userInfo['amount']){
            $success = FALSE;
            $msg = '× ' .lang('cur_commission_lack');
        }elseif(!$tranToMemId){
            $success = false;
            $msg = '× ' .lang('user_id_list_requied');
        }elseif(!$this->m_user->checkMemIdExist($tranToMemId)){
            $success = false;
            $msg = '× ' .lang('no_exist');
        }elseif($this->_userInfo['id']==$tranToMemId){
            $success = false;
            $msg = '× ' .lang('no_need_tran_to_self');
        }elseif(!$error_num['is']){
            $success = false;
            $msg = 5-$error_num['num']?'× ' .lang('pls_input_correct_take_out_pwd'):'× ' .lang('pls_pwd_retry');
        }elseif($userAmout['status'] == 6){
            //添加一个会员状态 退会中: 6 ；User:Ckf,改状态下不给会员转账
            $success = false;
            $msg = '× ' .lang('signouting_not_accounts');
        }else{
        	if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'Commission_tranToMem', $dataPost)) {
        		exit;
        	}        	
            if($ischeck){
                $tranToMemInfo = current($this->m_user->getInfo($tranToMemId));
                $success = TRUE;
                $msg = '';
                $data = array('alertMsg'=>sprintf(lang('tran_to_mem_alert'),$tranToMemAmount,$tranToMemId.'('.$tranToMemInfo['name'].')',$tranToMemId.'('.$tranToMemInfo['name'].')'));
            }else{
                $res = $this->m_profit_sharing->tranMoneyToMem($this->_userInfo['id'],$tranToMemId,$tranToMemAmount);
                $success = TRUE;
                $msg = '√ ' .lang('shift_success');
                $data = array('newAmount'=>$this->_userInfo['amount']-$tranToMemAmount);
            }
        }
        echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>isset($data)?$data:array()));
    }
    
    public function sharingPointToMoney(){
        $point = trim($this->input->post('point'));
        $res = $this->m_profit_sharing->sharingPointToMoney($this->_userInfo,$point);
        if(!$res['error_code']){
            $success=TRUE;
            $msg = '√ ' .lang('shift_success');
        }else{
            $success=FALSE;
            $error = config_item('error_code');
            $msg = '× ' .lang($error[$res['error_code']]);
        }
        
        echo json_encode(array('success' => $success, 'msg' => $msg,'data'=>$res));
    }

    public function getMonthCash(){
        if($this->input->post()){
            $month = $this->input->post('month');
            $payment_id = $this->input->post('payment');
            $payment = $this->m_global->getPaymentById($payment_id);

            $result['success'] = 1;
            if($month=='' || $payment_id== ''){
                $result['success'] = 0;
                echo json_encode($result);
                exit;
            }
            $this->load->model('M_user','m_user');
            $month_fee = $this->m_user->getJoinFeeAndMonthFee();

            $this->load->model('M_currency','m_currency');

            $total_money = $month_fee[$this->_userInfo['user_rank']]['month_fee'] * $month ;

            $result['month_fee'] = $this->m_currency->price_format_array($total_money,$payment['payment_currency']);

            echo json_encode($result);
            exit;
        }
    }

    public function transfer_cash_sum(){

        $this->load->model('o_cash_account');
        $user_id=$this->_userInfo['id'];
        return $this->o_cash_account->getUserTransferAmount($user_id)/100;
    }

    //现金池自动转月费池
    public function auto_to_month_fee_pool(){
        $checked = $this->input->post('checked');
        $user_id = $this->_userInfo['id'];
        if($checked == '1'){
            $this->tb_users->setCashPoolAutoSupplyMonthlyFeePool($user_id,1);
        }


        echo json_encode(array('success'=>true));
        exit();

    }

    //取消现金池自动转月费池
    public function cancel_auto_to_month_fee_pool(){
        $checked = $this->input->post('checked');
        $user_id = $this->_userInfo['id'];
        if($checked == '0'){
            $this->tb_users->setCashPoolAutoSupplyMonthlyFeePool($user_id,0);
        }

        echo json_encode(array('success'=>true));
        exit();
    }


    // 获取转账会员的名称
    public function getnamebyucid() {
        if ($this->input->is_ajax_request()) {
            $ucid = trim($this->input->get('ucid'));
            $this->load->model('tb_users', 'ucusers');
            $ucusers = $this->ucusers->get_one('name', array('id' => $ucid));
            $name    = isset($ucusers['name']) && $ucusers['name'] ? trim($ucusers['name']) : '';
            exit(json_encode(array('success' => 1, 'msg' => lang('result_ok'), 'name' => $name)));
        }
        exit(json_encode(array('success' => 0, 'msg' => 'System Error!')));
    }

}