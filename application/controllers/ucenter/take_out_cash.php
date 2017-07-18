<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Take_out_cash extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
    }

    public function index() {
        error_reporting(E_ALL); 
        ini_set('display_errors', '1'); 
        $this->_viewData['title'] = lang('take_out_cash');
        $take_out_type = config_item('take_out_type');
        if ($this->_userInfo['country_id'] == 1) {
            unset($take_out_type[4]);
            unset($take_out_type[3]);
            unset($take_out_type[5]);
        } else if ($this->_userInfo['country_id'] == 2) {
            unset($take_out_type[3]);
           // unset($take_out_type[2]);
            unset($take_out_type[4]);
            unset($take_out_type[6]);
        } else if ($this->_userInfo['country_id'] == 3) {
            unset($take_out_type[4]);
            unset($take_out_type[3]);
            unset($take_out_type[6]);
            if ($this->_userInfo['id'] !== '1380100266') {
            //    unset($take_out_type[2]);
                unset($take_out_type[6]);
            }

        } else if ($this->_userInfo['country_id'] == 4) {
            unset($take_out_type[4]);
            unset($take_out_type[3]);
            unset($take_out_type[6]);
        } else {
            unset($take_out_type[4]);
            unset($take_out_type[3]);
           // unset($take_out_type[2]);
            unset($take_out_type[6]);
        }
        $this->load->model('m_paypal_log');
        $paypal = $this->m_paypal_log->get_paypal($this->_userInfo['id']);
        $this->_viewData['userInfo']['paypal_email'] = isset($paypal['paypal_email']) ? $paypal['paypal_email'] : '';
        $this->_viewData['take_out_type'] = $take_out_type;
        $this->_viewData['display_set_pwd_button'] = $this->_userInfo['pwd_take_out_cash'] ? FALSE : TRUE;
        $this->_viewData['take_out_cash_sum'] = $this->take_out_cash_sum();

        /** 查看是否已经申请银联预付卡 */
        $info = $this->db->where('uid', $this->_userInfo['id'])->get('users_prepaid_card_info')->row_array();
        $this->_viewData['pre_card'] = $info;

        $country_arr = array(
            'China' => lang('con_china'),
            'Hong Kong' => lang('con_hongkong'),
            'USA' => lang('con_usa'),
            'Korea' => lang('con_korea'),
            'Macao' => 'Macao',
        );
        $this->_viewData['country_arr'] = $country_arr;
        //$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
        $this->load->model('m_user');
        $user_info = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);

        $this->_viewData['user'] = $user_info;
        $this->_viewData['country_id'] = $this->_userInfo['country_id']; //当前国家id
        //获取用户绑定的银行卡
        $param = [
            'where'=>[
                'uid'=>$this->_userInfo['id']
            ],
            'limit'=>1
        ];
        $this->load->model("tb_users_bank_card");
        $user_card = $this->tb_users_bank_card->get($param,false,true,true);
        $this->_viewData['user_card'] = $user_card;

        if ($user_info['country_id'] == '1') {
            parent::index("ucenter/",'china_withdrow');
        } else {
            parent::index();
        }

    }

    public function submit(){
        
        $postData = $this->input->post();
        $this->load->model('m_admin_helper');
        $status = $this->m_admin_helper->getCardOne($this->_userInfo['id']);
        $this->load->model('m_paypal_log');
        $paypal_email=  $this->m_paypal_log->get_paypal($this->_userInfo['id']);
        $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$postData['take_out_pwd']);

        //查询会员的状态
        $userAmout = $this->db->select('status')->where('id', $this->_userInfo['id'])->force_master()->get('users')->row_array();

        //查询用户是否有补单的记录
        $this->load->model("tb_withdraw_task");

        $withdrow_list = $this->tb_withdraw_task->get([
            'where' => [
                'uid' => $this->_userInfo['id'],
            ],
            'limit'=>1

        ],false,true);
        if (!empty($withdrow_list)) {
            $success=false;
            $msg= lang("withdorw_list_not_null");
            echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
        }

        //银行卡提现判断
       if($postData['take_cash_type'] == 6) {
           if((!$postData['bank_name'] || !$postData['bank_branch_name'] || !$postData['bank_number'] ||!$postData['bank_user_name'])){
               $success=false;
               $msg= lang("bank_card_infomation_lose");
               echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
           }

           if($postData['take_out_amount'] > 12000) {
               $success=false;
               $msg= lang("beyond_amount_fee");
               echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
           }
       }

        if (!$status || $status['check_status'] != 2) {
            $success = FALSE;
            $msg = lang('pls_complete_auth_info');
        }elseif(!$postData['take_cash_type']){
            $success = FALSE;
            $msg = lang('pls_sel_take_out_type');
        }elseif($postData['take_out_amount'] < 100){
            $success=false;
            $msg=lang('pls_input_correct_amount2');
        }elseif($postData['take_out_amount'] > 7000 && $postData['take_cash_type'] == 2){
            $success=false;
            $msg=lang('withdrawal_alipay_tip');
        }elseif(!$this->checkTakeOutAmount($postData['take_out_amount'])){
            $success=false;
            $msg=lang('pls_input_correct_amount');
        }elseif(!$error_num['is']){
            $success=false;
            $msg=5-$error_num['num']?lang('pls_input_correct_take_out_pwd'):lang('pls_pwd_retry');
        }elseif((!$this->_userInfo['alipay_account'] || !$this->_userInfo['alipay_name'])  && $postData['take_cash_type'] == 2){
            $success=false;
            $msg=lang('not_fill_alipay_account');
        }elseif((!$postData['account_bank'] || !$postData['account_name'] || !$postData['card_number'] ||!$postData['c_card_number'] || !$postData['subbranch_bank']) && $postData['take_cash_type'] == 3){
            $success=false;
            $msg=lang('payee_info_incomplete');
        }else if($postData['card_number'] !== $postData['c_card_number'] && $postData['take_cash_type'] == 3){
            $success=false;
            $msg=lang('card_number_match');
        }else if((!$postData['maxie_card_number'] ||!$postData['c_maxie_card_number']) && $postData['take_cash_type'] == 4){
			$success=false;
			$msg=lang('payee_info_incomplete');
        }else if($postData['maxie_card_number'] !== $postData['c_maxie_card_number'] && $postData['take_cash_type'] == 4){
                $success=false;
                $msg=lang('card_number_match');
        }else if($postData['take_cash_type'] == 5 && count($paypal_email) == 0){
                $success=false;
                $msg=lang('payee_info_incomplete');                           
        }elseif($userAmout['status'] == 6){
            //添加一个会员状态 退会中: 6 ；User:Ckf,改状态下不能提现
            $success=false;
            $msg=lang('signouting_not_withdrawals');
        }else{
            $this->db->trans_begin();

            $this->load->model('m_user');
            $this->load->model('m_commission');
			if($postData['take_cash_type'] == 4){
				$postData['card_number'] = $postData['maxie_card_number'];
			}else if($postData['take_cash_type'] == 2){
				$postData['card_number'] = $this->_userInfo['alipay_account'];
				$postData['account_name'] = $this->_userInfo['alipay_name'];
			}else if($postData['take_cash_type'] == 5){
                $postData['card_number'] =$paypal_email['paypal_email'];
            }
            $this->m_user->takeOutCash($this->_userInfo['id'],$postData);
            $this->m_commission->commissionLogs($this->_userInfo['id'],10,-1 * $postData['take_out_amount']);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->load->model("tb_cash_take_out_logs");
                $this->tb_cash_take_out_logs->del_redis_page($this->_userInfo['id'],"cash_take_out_logs");
                $this->db->trans_commit();
            }
            $success=TRUE;
            $msg=lang('take_out_success');
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }

    public function validate_pwd() {
        if ($this->input->is_ajax_request()) {
            $pwd = $this->input->post('pwd');
            if (!$this->checkTakeOutPwd($pwd)) {
                echo lang('pls_input_correct_take_out_pwd');
                exit;
            }
            echo '';
            exit;
        }
    }

    public function checkTakeOutAmount($takeOutAmount) {
        if (!is_numeric($takeOutAmount) || $takeOutAmount < 100 || get_decimal_places($takeOutAmount) > 2) {
            return FALSE;
        } elseif ($takeOutAmount > $this->_userInfo['amount']) {
            return FALSE;
        }
        return TRUE;
    }

    public function checkTakeOutPwd($pwd) {
        $this->load->model('m_user');
        if ($this->m_user->encyTakeCashPwd($pwd, $this->_userInfo['token']) !== $this->_userInfo['pwd_take_out_cash']) {
            return false;
        }
        return TRUE;
    }

    public function set_take_cash_pwd() {
        if (!$this->_userInfo['pwd_take_out_cash']) {
            $success = TRUE;
            $msg = '';
        } else {
            $success = false;
            $msg = lang('take_cash_pwd_exit');
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

    public function modify_take_cash_pwd() {
        if ($this->_userInfo['pwd_take_out_cash']) {
            $success = TRUE;
            $msg = '';
        } else {
            $success = false;
            $msg = lang('take_cash_pwd_not_exit');
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

    /**
     * 設置提現密碼（提交）。
     */
    public function set_take_cash_pwd_submit() {
        $postData = $this->input->post();
        $pwd = trim($postData['take_cash_pwd']);
        $pwdRe = trim($postData['take_cash_pwd_re']);
        $pwdLen = strlen($pwd);

        if ( !preg_match('/[A-Z]+/', $pwd)) {     //匹配至少一个大写字母
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
           
        } elseif (!preg_match('/[a-z]+/', $pwd)) {   //匹配至少一个小写字母
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
           
        } elseif (!preg_match('/[0-9]+/', $pwd)) {    //匹配至少一个数字
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
           
        } elseif (!preg_match('/^[0-9A-Za-z]{8,16}$/', $pwd)) {    //匹配大小写字母数字8-16位
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
            
        } elseif ($pwd !== $pwdRe) {
            $success = FALSE;
            $msg = lang('regi_errormsg_repwd');
          
        } else {
            $this->load->model('m_user');
            $this->m_user->saveTakeCashPwd($this->_userInfo['id'], $this->_userInfo['token'], $pwd);
            $success = TRUE;
            $msg = lang('set_take_cash_pwd_success');
        }
        
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

    /**
     * 修改提现密码（提交）。
     */
    public function modify_take_cash_pwd_submit() {
        $postData = $this->input->post();
        $oldPwd = trim($postData['old_take_out_pwd']);
        $pwd = trim($postData['take_cash_pwd']);
        $pwdRe = trim($postData['take_cash_pwd_re']);
        $pwdLen = strlen($pwd);
        $this->load->model('tb_users');
        $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$oldPwd);
        $this->load->model('m_user');
        if (!$error_num['is']) {
            $success = FALSE;
            $msg=5-$error_num['num']?lang('pls_input_correct_take_out_pwd'):lang('pls_pwd_retry');
        } elseif (!preg_match('/[A-Z]+/', $pwd)) {  //匹配至少一个大写字母
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
        } elseif (!preg_match('/[a-z]+/', $pwd)) {   //匹配至少一个小写字母
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
        } elseif (!preg_match('/[0-9]+/', $pwd)) {    //匹配至少一个数字
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
        } elseif (!preg_match('/^[0-9A-Za-z]{8,16}$/', $pwd)) {    //匹配大小写字母数字8-16位
            $success = FALSE;
            $msg = lang('funds_pwd_tip');
        } elseif ($pwd !== $pwdRe) {
            $success = FALSE;
            $msg = lang('regi_errormsg_repwd');
        } else {
            $this->m_user->saveTakeCashPwd($this->_userInfo['id'], $this->_userInfo['token'], $pwd);
            $success = TRUE;
            $msg = lang('modify_take_cash_pwd_success');
        }

        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

    function get_withdrawal_fee() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $cash = trim($data['cash']);
            if ($cash < 100 || !is_numeric($cash)) {
                die(json_encode(array('success' => 0)));
            }
            $data = aliapy_withdrawal_fee($cash);

            die(json_encode(array('success' => 1, 'data' => array('actual_fee' => $data['actual_fee'], 'withdrawal_fee' => $data['withdrawal_fee']))));
        }
    }

    public function visitor() {
        $cookie = unserialize($_COOKIE['userInfo']);
        if ($cookie['readOnly']) {
            if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                echo array('success' => false);
            }
        }
    }

    public function take_out_cash_sum() {
        $user_id = $this->_userInfo['id'];
        $this->db->select_sum('amount');
        $this->db->where('uid', $user_id);
        $this->db->where('status', 1);
        $query = $this->db->from('cash_take_out_logs')->get()->row();
        if (!empty($query)) {
            $take_out_cash_sum = $query->amount;
            if ($take_out_cash_sum !== null) {
                return abs($take_out_cash_sum);
            } else {
                return 0.00;
            }
        }
    }

    //发送手机验证码
    public function send_phone_yzm() {
        //$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
        $this->load->model('m_user');
        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        if (isset($user['mobile'])) {
            if (is_numeric($user['mobile']) && preg_match('/^1[34578]{1}\d{9}$/', $user['mobile'])) { //手机注册
                $count = $this->phone_yzm($user['mobile'], 5);
                if (isset($count)) {
                    die(json_encode(array('success' => 1)));
                } else {
                    die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
                }
            } else {
                die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
            }
        }
    }

    //发送手机验证码
    public function send_sms_mssage() {
        $action_id = $this->input->post('reg_type') ? $this->input->post('reg_type') : 1;
        $email_or_phone = trim($this->input->post('email_or_phone'));

        if(!$email_or_phone){
            die(json_encode(array('success'=>0,'msg'=>lang("not_bind_mobile"))));
        }
        //手机号是否验证
        $this->load->model("tb_users");
        $param = [
            'select'=>'id,mobile,is_verified_mobile',
            'where'=>[
                'mobile'=>$email_or_phone
            ],
            'limit'=>1
        ];
        $user_info = $this->tb_users->get($param,false,true,true);
        if ($user_info['is_verified_mobile'] != 1){
            die(json_encode(array('success' => 0, 'msg' => lang("mobile_verify_not"))));
        }
        if (is_numeric(preg_match('/^1[34578]{1}\d{9}$/', $email_or_phone))) { //手机注册
            //$boolean = $this->publicSMSMssage($email_or_phone,$action_id);
            $this->load->model("tb_mobile_message_log");
            $send_res = $this->tb_mobile_message_log->send_mobile_code($email_or_phone,$action_id);
            if($send_res['error'] == true) {
                die(json_encode(array('success' => 0, 'msg' => $send_res['msg'])));
            }
            if ($send_res['error'] == false) {
                die(json_encode(array('success' => 1)));
            } else {
                die(json_encode(array('success' => 0, 'msg' => $send_res['msg'])));
            }
        }else{
            die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
        }
    }
}
