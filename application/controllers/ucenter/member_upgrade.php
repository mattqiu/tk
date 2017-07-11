<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member_upgrade extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
        $this->load->model('m_user');

    }

    public function index() {
        $this->_viewData['title'] = lang('member_upgrade');
        
        $this->_viewData['allLevels'] = array(
            1=>lang('member_diamond'),
            2=>lang('member_platinum'),
            3=>lang('member_silver'),
            5=>lang('member_bronze')
        );


        //如果還有未選購的商品，则阻止提交
        $this->_viewData['is_can_upgrade'] = 0;
//        if($this->_userInfo['is_choose']==0 && $this->_userInfo['user_rank']<4){
//            $this->_viewData['is_can_upgrade']=1;
//        }

        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        $this->_viewData['level'] = $user['user_rank'];
        $this->_viewData['month_rank'] = $user['month_fee_rank'];
        $this->_viewData['parent_id'] = $user['parent_id'];
        $this->_viewData['ewallet_name'] = $user['ewallet_name'];
        $this->_viewData['uid'] = $user['id'];
        //$this->_viewData['levelInfo'] = $levelInfo;
        $this->_viewData['join_fee_and_month_fee'] = $this->m_user->getJoinFeeAndMonthFee();
        $this->_viewData['info_contrys'] = config_item('countrys_and_areas');
        $msg = $this->input->get('msg');
        $this->_viewData['msg'] = $msg=='payok'?lang('payment_success_delay_notice'):$msg;
        $type = $this->input->get('type');
        $this->_viewData['msg_type'] = $type;
        $this->_viewData['month_fee_level_change_note'] = $this->m_user->getMonthFeeLevelChangeNote($this->_userInfo['id']);
        $this->_viewData['payments'] = $this->m_global->getPayments('USD',$this->_userInfo['id']);

        parent::index();
    }
    
    public function getUpgradeMonth(){
        
        $levelId = trim($this->input->post('levelId'));
        if($levelId){
            $pay_id = trim($this->input->post('payment_method'));
            $payment = $this->m_global->getPaymentById($pay_id);

            $needMoney = $this->m_user->getJoinFeeAndMonthFee();
                
            $this->load->model('M_currency','m_currency');

            $total_money = $needMoney[$levelId]['month_fee'] ;

            $result = $this->m_currency->price_format_array($total_money,$payment['payment_currency']);

            if($payment['pay_code'] === 'amount'){
                $this->load->model('m_order');
                $error_code = $this->m_order->upgradeMonthlyLevelByAmountCheck($this->_userInfo['id'],$levelId);
                if($error_code){
                    echo json_encode(array('success'=>FALSE,'msg'=>lang(config_item('error_code')[$error_code])));exit;
                }
            }

            echo json_encode(array('success' => isset($success)?$success:TRUE, 'msg' => $result));
        }
    }

    public function getUpgradeMoney(){

        $levelId = trim($this->input->post('levelId'));

        $needMoney = $this->m_user->computeUserUpgradeMoney($levelId,$this->_userInfo['user_rank'],$this->_userInfo['id']);

        $this->load->model('M_currency','m_currency');
        $needMoney = $this->m_currency->price_format_array($needMoney,'USD');

        if($needMoney['money']==0){
            $success = FALSE;
            $msg = lang('no_need_upgrade');
        }

        echo json_encode(array('success' => isset($success)?$success:TRUE, 'msg' => isset($msg)?$msg:'','data'=>array('needMoney'=>$needMoney)));
    }
    
    public function getUpgradeAllMoney(){

        $levelId = trim($this->input->post('levelId'));
        $payment_method = trim($this->input->post('payment_method'));

        if($payment_method == "eWallet" || $payment_method == "tps_amount"){
            $payment_method = "USD";
        }

        if ($payment_method == 'UP' || $payment_method == 'yspay') {
            $payment_method = 'CNY';
        }

        $needMoney = $this->m_user->computerUserUpallMoney($levelId,$this->_userInfo['user_rank'],$this->_userInfo['month_fee_rank']);
        
        $this->load->model('M_currency','m_currency');
        $needMoney = $this->m_currency->price_format($needMoney,$payment_method);

        if($needMoney['money']==0){
            $success = FALSE;
            $msg = lang('no_need_upgrade');
        }

        echo json_encode(array('success' => isset($success)?$success:TRUE, 'msg' => isset($msg)?$msg:'','data'=>array('needMoney'=>$needMoney)));
    }
    
    public function go_pay(){

        $dataPost = $this->input->post();

		/*$user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
		if($er['month_fee_rank'] == 4){
			$success = FALSE;
			echo json_encode(array('success' => $success, 'msg' => lang('month_fee_rank_empty'), 'data' => array('error' => array('month_fee_user_rank'=>TRUE))));exit;
		}
		if($user['month_fee_rank'] > $dataPost['level']){
			$success = FALSE;
			echo json_encode(array('success' => $success, 'msg' => lang('month_user_rank'), 'data' => array('error' => array('month_user_rank'=>TRUE))));exit;
		}*/

		$month_fail_count = $this->db->from('users_month_fee_fail_info')->where('uid',$this->_userInfo['id'])->count_all_results();
		if($month_fail_count){
			$success = FALSE;
			echo json_encode(array('success' => $success, 'msg' => lang('month_fee_arrears'), 'data' => array('error' => array('month_user_rank'=>TRUE))));exit;
		}

        /** 身份證審核开关 */
        if(config_item('check_card_switch')){
            $this->load->model('m_admin_helper');
            $status = $this->m_admin_helper->getCardOne($this->_userInfo['id']);
            if($status['check_status'] != 2){
                echo json_encode(array('success' => 0, 'msg' =>lang('pls_complete_auth_info'),'data' => array('error' => array())));
                exit;
            }
        }

        /*if(isset($dataPost['payment_method']) && $dataPost['payment_method']=='tps_amount'){
            $this->load->model('m_order');
            $error_code = $this->m_order->upgradeStoreLevelByAmountCheck($this->_userInfo['id'],$dataPost['level']);
            if($error_code){
                echo json_encode(array('success'=>FALSE,'msg'=>lang(config_item('error_code')[$error_code])));exit;
            }
        }*/

        $res = $this->m_user->checkUserUpgradeData($dataPost,$this->_userInfo['user_rank'],$this->_userInfo['id']);
        if($res['error_code']==0){

			/** 记录cookie 产品套装的等级和金额 */
			$publicDomain = get_public_domain();
			$product_set = array('level'=>$dataPost['level'],'amount'=>$dataPost['amount']);
			set_cookie("product_set", serialize($product_set), 0, $publicDomain);
            $success = TRUE;
            $msg = lang('pay_success');
        }else{
            $success = FALSE;
            $msg = '';
        }
        echo json_encode(array('success' => $success, 'msg' => $msg, 'data' => array('error' => $res['error'])));
    }

    public function pay(){
        $this->load->model('m_order');
        
        $data = $this->input->post();
        if ($data) {
            $error_code = $this->m_order->uptradeStoreLevelByAmount($this->_userInfo['id'],$data['level']);
            if($error_code){
                $msg = lang(config_item('error_code')[$error_code]);
            }else{
                $msg = lang('upgrade_success');
            }
            redirect(base_url("ucenter/member_upgrade?msg=".$msg."&type=upgrade"));
        }
    }

    public function tps_amount_pay_monthly_up(){
        $this->load->model('m_order');
        
        $data = $this->input->post();
        if ($data) {
            $error_code = $this->m_order->uptradeMonthlyLevelByAmount($this->_userInfo['id'],$data['level']);
            if($error_code){
                $msg = lang(config_item('error_code')[$error_code]);
            }else{
                $msg = lang('upgrade_success');
            }
            redirect(base_url("ucenter/?msg=".$msg."&type=upgrade_month_fee"));
        }
    }

    public function tps_amount_pay_all_up(){
        $this->load->model('m_order');
        
        $data = $this->input->post();
        if ($data) {
            $error_code = $this->m_order->uptradeAllLevelByAmount($this->_userInfo['id'],$data['upall_level']);
            if($error_code){
                $msg = lang(config_item('error_code')[$error_code]);
            }else{
                $msg = lang('upgrade_success');
            }
            redirect(base_url("ucenter/member_upgrade?msg=".$msg."&type=upall"));
        }
    }
    
    public function upall_go_pay(){

        /** 身份證審核开关 */
        if(config_item('check_card_switch')){
            $this->load->model('m_admin_helper');
            $status = $this->m_admin_helper->getCardOne($this->_userInfo['id']);
            if($status['check_status'] != 2){
                echo json_encode(array('success' => 0, 'msg' =>lang('pls_complete_auth_info'),'data' => array('error' => array())));
                exit;
            }
        }

        $dataPost = $this->input->post();
        $res = $this->m_user->checkUserUpallData($dataPost,$this->_userInfo['user_rank'],$this->_userInfo['month_fee_rank']);
        if($res['error_code']==0){
            $success = TRUE;
            $msg = lang('pay_success');
        }else{
            $success = FALSE;
            $msg = '';
        }

        if($dataPost['upall_payment_method']=='tps_amount'){
            $this->load->model('m_order');
            $error_code = $this->m_order->upgradeAllLevelByAmountCheck($this->_userInfo['id'],$dataPost['upall_level']);
            if($error_code){
                echo json_encode(array('success'=>FALSE,'msg'=>lang(config_item('error_code')[$error_code])));exit;
            }
        }

        echo json_encode(array('success' => $success, 'msg' => $msg, 'data' => array('error' => $res['error'])));
    }

    public function go_enable(){

        $dataPost = $this->input->post();

        $res = $this->m_user->checkUserUpgradeData($dataPost,$this->_userInfo['user_rank'],'enable');
        if($res['error_code']==0 || in_array($this->_userInfo['id'],config_item('leader_list'))){
            $success = TRUE;
        }else{
            $success = FALSE;
        }
        echo json_encode(array('success' => $success, 'msg' =>'', 'data' => array('error' => $res['error'])));
    }

    public function go_month(){

        $dataPost = $this->input->post();

        $res = $this->m_user->checkUserMonthData($dataPost,$this->_userInfo['user_rank']);
        if($res['error_code']==0){
            $success = TRUE;
        }else{
            $success = FALSE;
        }
        echo json_encode(array('success' => $success, 'msg' =>'', 'data' => array('error' => $res['error'])));
    }

    public function go_upgrade_month(){

        $dataPost = $this->input->post();
        if($dataPost['level'] > $this->_userInfo['user_rank']){
            $res['error_code'] = 1;
            $msg = lang('month_fee_user_rank');
            echo json_encode(array('success' => 0, 'msg' =>$msg,'data' => array('error' => array())));
            exit;
        }


        /** 身份證審核开关 */
        if(config_item('check_card_switch')){
            $this->load->model('m_admin_helper');
            $status = $this->m_admin_helper->getCardOne($this->_userInfo['id']);
            if($status['check_status'] != 2){
                echo json_encode(array('success' => 0, 'msg' =>lang('pls_complete_auth_info'),'data' => array('error' => array())));
                exit;
            }
        }

        $res = $this->m_user->checkUpgradeMonthData($dataPost);
        if($res['error_code']==0){
            $success = TRUE;
        }else{
            $success = FALSE;
        }

        if($dataPost['month_payment_method']=='110'){
            $this->load->model('m_order');
            $error_code = $this->m_order->upgradeMonthlyLevelByAmountCheck($this->_userInfo['id'],$dataPost['level']);
            if($error_code){
                echo json_encode(array('success'=>FALSE,'msg'=>lang(config_item('error_code')[$error_code])));exit;
            }
        }

        echo json_encode(array('success' => $success, 'msg' =>'', 'data' => array('error' => $res['error'])));
        exit;
    }

    public function sub_info(){
        $dataPost = $this->input->post();
        $res = $this->m_user->checkUserInfoData($dataPost);
        if($res['error_code']==0){
            /*用户资料更新*/
            $this->m_user->updateUserOtherInfo($this->_userInfo['id'],$dataPost);
            $success = TRUE;
            $msg = lang('submit_success');
        }else{
            $success = FALSE;
            $msg = '';
        }
        echo json_encode(array('success' => $success, 'msg' => $msg, 'data' => array('error' => $res['error'])));
    }

    /*修改月费等级弹出层*/
    public function changeMonthlyLevelPop(){
        if($this->_userInfo['month_fee_rank']<3){
            $success = TRUE;
            $msg = '';
            $optionHtml = '';
            foreach(config_item('levels') as $levelId=>$levelname){
                if($levelId>=$this->_userInfo['month_fee_rank'] && $levelId<4){
                    $optionHtml.='<option value="'.$levelId.'">'.lang($levelname).'</option>';
                }
            }
        }else{
            $success = FALSE;
            $msg = lang('cannot_change_monthly_fee_level');
            $optionHtml = '';
        }
        echo json_encode(array('success' => $success, 'msg' => $msg,'optionHtml'=>$optionHtml));
    }

    public function changeMonthliFeeLevelFormSub(){
        $dataPost = $this->input->post();
        $newMonthlyFeeLevel = $dataPost['monthlyFeeLevel'];
        if($newMonthlyFeeLevel>=$this->_userInfo['month_fee_rank'] && $newMonthlyFeeLevel<4){
            if($newMonthlyFeeLevel==$this->_userInfo['month_fee_rank']){
                $this->m_user->removeMonthFeeLevelChange($this->_userInfo['id']);
            }else{
                $this->m_user->changeMonthFeeLevel($this->_userInfo['id'],$newMonthlyFeeLevel);    
            }
            $success = TRUE;
            $msg = lang('submit_success');
            $monthFeeLevelChangeNote = $this->m_user->getMonthFeeLevelChangeNote($this->_userInfo['id']);
            $monthFeeLevelChangeNote = $monthFeeLevelChangeNote?'('.$monthFeeLevelChangeNote.')':'';
        }else{
            $success = FALSE;
            $msg = lang('no_change');
            $monthFeeLevelChangeNote = '';
        }
        echo json_encode(array('success' => $success, 'msg' => $msg,'monthFeeLevelChangeNote'=>$monthFeeLevelChangeNote));
    }

}
