<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Change_funds_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
    }

    public function index(){
        $this->_viewData['title'] = lang('funds_pwd_reset');
        $this->_viewData['email'] = $this->_userInfo['email'];
        $this->_viewData['reset_email_tip'] = sprintf(lang('reset_email_tip'),$this->_userInfo['email']);
        $this->_viewData['is_verified_email'] = $this->_userInfo['is_verified_email'];
        //如果还未验证邮箱,强制验证邮箱

        //sm by brady.wang 增加手机重置密码 只有大陆地区可以 156
        $mobile_encrypt = mobile_encrypy($this->_userInfo['mobile']);
        $email_encrypt = email_encrypy($this->_userInfo['email']);
        $this->_viewData['email_encrypt'] = $email_encrypt;
        $this->_viewData['mobile_encrypt'] = $mobile_encrypt;
        $this->_viewData['mobile'] = $this->_userInfo['mobile'];
        $this->_viewData['is_verified_mobile'] = $this->_userInfo['is_verified_mobile'];
        parent::index();
    }

    public function changeFundsPwd(){
        
        $data = $this->input->post();
        $data['id'] = $this->_userInfo['id'];
        $checkResult = $this->m_user->checkRegisterItems($data);
        $success = TRUE;
        foreach ($checkResult as $resultItem) {
            if (!$resultItem['isRight']) {
                $success = FALSE;
                break;
            }
        }
        if ($success) {
            $user = $this->m_user->getUserByIdOrEmail($data['id']);
            if($user['send_email_time'] > time()-60){
                $checkResult = array('pwdOld'=>array('isRight' => false, 'msg' => lang('send_again')));
                echo json_encode(array('success'=>  false,'checkResult' => $checkResult));exit;
            }
            $this->m_user->updateCreateTime($user['id']);
            $this->m_user->sendChangeFundsPwd($user);
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }



    /**
     * 手机号重置资金密码
     */
    public function resetPassword()
    {
     
        $new_pwd = trim($this->input->post('new_pwd', true));
        $new_pwd_re = trim($this->input->post('new_pwd_re', true));
        $tps_pwd = trim($this->input->post('tps_pwd', true));
        $phone_code = trim($this->input->post("phone_code", true));
        //数据验证
        try {
            $this->load->model("m_user");
            //必须登录才能操作
            if (empty($this->_userInfo)) {
                throw new Exception(lang("please_login_first"));
            }

            if (empty($new_pwd)) {
                throw new Exception(lang("new_passwd_not_null"));
            }

            if (!preg_match('/[A-Z]+/', $new_pwd)) {    //匹配至少一个大写字母
                
                throw new Exception(lang("funds_pwd_tip"));
                
            } elseif (!preg_match('/[a-z]+/', $new_pwd)) {   //匹配至少一个小写字母
                
                throw new Exception(lang("funds_pwd_tip"));
                
            } elseif (!preg_match('/[0-9]+/', $new_pwd)) {    //匹配至少一个数字
                
                throw new Exception(lang("funds_pwd_tip"));
                
            } elseif (!preg_match('/^[0-9A-Za-z]{8,16}$/', $new_pwd)) {    //匹配大小写字母数字8-16位
                
                throw new Exception(lang("funds_pwd_tip"));
            }
            
            if (empty($new_pwd_re)) {
                throw new Exception(lang("enter_re_passwd"));
            }

            if ($new_pwd !== $new_pwd_re) {
                throw new Exception(lang("passwd_not_same"));
            }

            if (empty($tps_pwd)) {
                throw new Exception(lang("enter_tps_passwd"));
            }

            if (empty($phone_code)) {
                throw new Exception(lang("phone_code_not_null"));
            }

            
            //验证原密码是否正确
            $old_pwd = $this->m_user->pwdEncryption($tps_pwd, $this->_userInfo['token']);
            if ($old_pwd !== $this->_userInfo['pwd']) {
                throw new Exception(lang("old_pwd_error"));
            }
            
            //验证短信验证码是否正确
            $this->load->model("tb_mobile_message_log");
            $this->tb_mobile_message_log->verify_mobile_code($this->_userInfo['mobile'], $phone_code);

            $affected_rows = $this->m_user->updateTakeCashPwd($this->_userInfo['id'], $this->_userInfo['token'], $new_pwd);
            if($affected_rows > 0){//重置密码时，清空密码错误次数
                    $this->db->delete('user_pwd_error_num', array('uid' => $this->_userInfo['id']));
                }
            if ($affected_rows <= 0) {
                throw new Exception(lang("update_take_cash_pwd_error"));
            }

            $error = false;
            $msg  = lang('phone_reset_passwd_success');
            echo json_encode(array('error' => $error, 'msg' => $msg));

        } catch (Exception $e) {
            $error = true;
            $msg = $e->getMessage();
            if (is_numeric($msg)) {
               $this->load->model("service_message_model");
                $this->service_message_model->error_response($e->getMessage());
            } else {
                echo json_encode(array('error' => $error, 'msg' => $msg));
            }

        }


    }

    /**
     * @author  brady.wang
     * @descripton reset user's take cash password by email
     * @param
     */
    public function resetPasswordByEmail()
    {
        $uid = $this->_userInfo['id'];
        $this->load->model('service_message_model');
        $this->load->model("m_user");
        $tps_pwd = trim($this->input->get_post('tps_pwd', true)); //tps密码
        $new_pwd = trim($this->input->get_post('new_pwd', true)); //新密码
        $new_pwd_re = trim($this->input->get_post('new_pwd_re', true));//再次输入新密码
        $email_code = trim($this->input->get_post('email_code', true));//验证码
        $user = $this->m_user->getUserByIdOrEmail($uid);

        $data = [
            "uid"     =>$uid,
            'userInfo'=>$this->_userInfo,
            "tps_pwd" =>$tps_pwd,
            "new_pwd" =>$new_pwd,
            "new_pwd_re" =>$new_pwd_re,
            "email_code" =>$email_code
        ];

        try {
            $this->params_verify_email($data);

            $affected_rows = $this->m_user->updateTakeCashPwd($this->_userInfo['id'], $this->_userInfo['token'], $new_pwd);
            if($affected_rows > 0){//重置密码时，清空密码错误次数
                    $this->db->delete('user_pwd_error_num', array('uid' => $user['id']));
                }
            if($affected_rows <= 0) {
                throw new Exception("10501038");
            }
            $this->m_user->updateCreateTime($user['id']);
            $error = false;
            $msg = lang('phone_reset_passwd_success');
            $this->session->unset_userdata($this->_userInfo['email']);        //删除记录的验证码数据
            $output = array('message'=>$msg,'msg'=>$msg);
            $this->service_message_model->success_response($output);

        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }

    }

    /**
     * @author brady.wang
     * @description 邮箱重置密码参数验证
     * @param $data
     * @throws Exception
     */
    private function params_verify_email($data)
    {
        $session = $this->session->userdata($data['userInfo']['email']);

        if($data['userInfo']['send_email_time'] > time() - 10) {
            throw new Exception("10501034");//操作频繁
        }
        if(empty($data['uid'])) {
            throw new Exception("10501010");//未登陆
        }
        //新密码不能够为空
        if(empty($data['new_pwd'])) {
            throw new Exception("10501025");
        }


        if (!preg_match('/[A-Z]+/', $data['new_pwd'])) {    //匹配至少一个大写字母

            throw new Exception("10501040");

        } elseif (!preg_match('/[a-z]+/', $data['new_pwd'])) {   //匹配至少一个小写字母

            throw new Exception("10501040");

        } elseif (!preg_match('/[0-9]+/', $data['new_pwd'])) {    //匹配至少一个数字

            throw new Exception("10501040");

        } elseif (!preg_match('/^[0-9A-Za-z]{8,16}$/', $data['new_pwd'])) {    //匹配大小写字母数字8-16位

            throw new Exception("10501040");
        }

        //再次输入资金密码
        if (empty($data['new_pwd_re'])) {
            throw new Exception("10501027");
        }
        //两次输入密码不一致
        if ($data['new_pwd'] !== $data['new_pwd_re']) {
            throw new Exception("10501028");
        }
        //tps密码
        if (empty($data['tps_pwd'])) {
            throw new Exception("10501029");
        }
        //验证码不能为空
        if (empty($data['email_code'])) {
            throw new Exception("10501032");
        }
        //验证码不符合规则
        if (!preg_match('/^\d{3,6}$/',$data['email_code'])) {
            throw new Exception("10501031");
        }
        //验证原密码是否正确
        $old_pwd = $this->m_user->pwdEncryption($data['tps_pwd'], $data['userInfo']['token']);
        if($old_pwd !== $this->_userInfo['pwd']) {
            throw new Exception("10501041");
        }
        //验证验证码是否正确
        if (!empty($session['email_or_phone'])) {
            if ($user['email'] = $session['email_or_phone'] && $session['code'] == $data['email_code']) {
                if (time()> $session['expire_time']) {
                    throw new Exception("10501006");
                }
            } else {
                throw new Exception("10501005");//验证码错误
            }
        } else {
            throw new Exception("10501037");
        }


    }



}

