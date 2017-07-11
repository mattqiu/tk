<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class paypal_binding extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
    }

    public function index() {
        $this->load->model('m_paypal_log');
        $paypal_email = $this->m_paypal_log->get_paypal($this->_userInfo['id']);
        $this->_viewData['title'] = isset($paypal_email['paypal_email']) ? lang('paypal_unbundling') : lang('paypal_binding');
        $this->_viewData['userInfo']['paypal_email'] = isset($paypal_email['paypal_email']) ? $paypal_email['paypal_email'] : '';
        parent::index();
    }

    public function submit() {
        $postData = $this->input->post();
        //-----------验证邮箱
        $pattern = "/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/";
        if (!preg_match( $pattern, $postData['paypal_email'] ) )
        {
            die(json_encode(array('success' => 0, 'msg' => lang('regi_errormsg_mail'))));
        }
        //--------------------
        $this->load->model('m_user');
        //------------这里做资金密码验证
        $this->load->model('tb_users');
        $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$postData['password']);
        if(!$error_num['is']){
            $msg=5-$error_num['num']?lang('pls_input_correct_take_out_pwd'):lang('pls_pwd_retry');
            die(json_encode(array('success' => 0, 'msg' => $msg)));
        }
        $vcode = trim($postData['vcode']);
        $alipay_account = trim($postData['paypal_email']);
        $this->Verification_Code($vcode,$alipay_account);//验证
        //----------------------------
        $this->load->model('m_paypal_log');
        if(!$postData['type']){
            $is=$this->m_paypal_log->get_paypal($this->_userInfo['id']);
            if(!isset($is['id'])){
                $this->m_paypal_log->add_payapl($this->_userInfo['id'], trim($postData['paypal_email']));
            }  else {
                die(json_encode(array('success' => 0, 'msg' => lang('alipay_account_exists'))));
            }
//            $this->m_paypal_log->add_payapl($this->_userInfo['id'], $postData['paypal_email']);
        }  else {
            $this->m_paypal_log->del_payapl($this->_userInfo['id'], $postData['paypal_email']);
        }
        if ($this->db->affected_rows()) {
            die(json_encode(array('success' => 1)));
        } else {
            die(json_encode(array('success' => 0, 'msg' => lang('try_again'))));
        }
    }
}
