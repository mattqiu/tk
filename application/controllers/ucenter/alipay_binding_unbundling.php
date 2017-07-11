<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class alipay_binding_unbundling extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
    }

    public function index() {
        $this->_viewData['title'] =$this->_userInfo['alipay_account']&&$this->_userInfo['alipay_name']?lang('alipay_unbundling'):lang('alipay_binding');
        $this->_viewData['user']=$this->_userInfo;
        parent::index();
    }

    public function submit() {
        $postData = $this->input->post();
        $this->load->model('m_user');
        //参数初步验证过滤 m by brady.wang
        $password = trim($this->input->post('password',true));
        $vcode = trim($this->input->post('vcode',true));

        if ($postData['bd_type'] == 4) {
            $count = $this->m_user->alipay_select($postData['alipay_account']);
            if (isset($count['id'])) {
                die(json_encode(array('success' => 0, 'msg' => lang("alipay_account_exists"))));
            }
        }

        if (empty($vcode)) {
            die(json_encode(array('success' => 0, 'msg' => lang('please_input_code'))));
        }

        if(empty($password)) {
            die(json_encode(array('success' => 0, 'msg' => lang('please_input_cash_passwd'))));
        }

        //------------这里做资金密码验证
        $this->load->model('tb_users');
        $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$postData['password']);
        if(!$error_num['is']){
            $msg=5-$error_num['num']?lang('pls_input_correct_take_out_pwd'):lang('pls_pwd_retry');
            die(json_encode(array('success' => 0, 'msg' => $msg)));
        }
        $vcode = trim($postData['vcode']);
        $alipay_account = trim($postData['alipay_account']);
        $this->Verification_Code($vcode,$alipay_account);//验证

        //----------------------------
        $data['alipay_account'] = $this->_userInfo['alipay_account']&&$this->_userInfo['alipay_name']?'':$postData['alipay_account'];
        $data['alipay_name'] = $this->_userInfo['alipay_account']&&$this->_userInfo['alipay_name']?'':$postData['alipay_name'];
       
        $this->m_user->alipay_binding($this->_userInfo['id'], $data);
        if ($this->db->affected_rows()) {
            die(json_encode(array('success' => 1)));
        } else {
            die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
        }
    }

    /**
     * 添加验证码
     */
//    public function add_captcha() {
//        if ($this->input->is_ajax_request()) {
//            $alipay_account = $this->input->post('alipay_account');
//            $action_id = $this->input->post('bd_type');
//            $cur_language_id = get_cookie('curLan_id', true);
//            if (empty($cur_language_id)) {
//                $cur_language_id = 1;
//            }
//            $code = generate_code(4);
//            $this->load->model('tb_users_register_captcha');
//            $count = $this->tb_users_register_captcha->add_captcha($alipay_account, $cur_language_id, $action_id, $code);
//            if ($count) {
//                die(json_encode(array('success' => 1)));
//            } else {
//                die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
//            }
//        }
//    }
    
    /**
     * 支付宝帐号去重ajax
     */
    public function alipay_account_ajax() {
        if ($this->input->is_ajax_request()) {
            $alipay_account = $this->input->post('alipay_account');
            $this->load->model('m_user');
            $count = $this->m_user->alipay_select($alipay_account);
            if (isset($count['id'])) {
                die(json_encode(array('success' => 0)));
            } else {
                die(json_encode(array('success' => 1)));
            }
        }
    }
}
