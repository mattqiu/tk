<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reset_funds_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        $param = $this->input->get('param');
        $dataGet = unserialize(base64_decode($param));
        $this->load->model('m_user');
        $userInfo = $this->m_user->getUserByIdOrEmail($dataGet['id']);
        $flag = 0;
        if(!$userInfo){
             $flag = 1008;
        }
        if($dataGet['token']!==$userInfo['token']){
            $flag =  102;
        }
        if($param!==$userInfo['send_email_token']){
            $flag =  102;
        }
        if($dataGet['time']<(time() - 1800)){
            $flag =  1012;
            
        }

        if(!$flag && $this->input->get('ajax') == 'resetFundsPwd'){
            $success = TRUE;
            $data = $this->input->post();
            $data['id'] = $dataGet['id'];

            $checkResult = $this->m_user->checkRegisterItems($data);
            $msg = '';
            foreach ($checkResult as $resultItem) {
                if (!$resultItem['isRight']) {
                    $success = FALSE;
                    $msg = $msg ? $msg.$resultItem['msg'].'<br>' : $resultItem['msg'].'<br>';
                    //break;
                }
            }
            $pwdLen = strlen($data['funds_pwd_new']);

            if (!preg_match('/[A-Z]+/', $data['funds_pwd_new'])) {
                $success = FALSE;
                $msg = $msg ? $msg.lang('funds_pwd_tip').'<br>' : lang('funds_pwd_tip').'<br>';
            } else if(!preg_match('/[a-z]+/', $data['funds_pwd_new'])){
                $success = FALSE;
                $msg = $msg ? $msg.lang('funds_match').'<br>' : lang('funds_match').'<br>';
            } else if(!preg_match('/[0-9]+/', $data['funds_pwd_new'])){
                $success = FALSE;
                $msg = $msg ? $msg.lang('funds_match').'<br>' : lang('funds_match').'<br>';
            } else if(!preg_match('/^[0-9A-Za-z]{8,16}$/', $data['funds_pwd_new'])){
                $success = FALSE;
                $msg = $msg ? $msg.lang('funds_match').'<br>' : lang('funds_match').'<br>';
            } else if($data['funds_pwd_new'] != $data['funds_pwd_new_re']) {
                $success = FALSE;
                $msg = $msg ? $msg.lang('funds_match').'<br>' : lang('funds_match').'<br>';
            }
            if ($success) {

                $this->m_user->saveTakeCashPwd($userInfo['id'],$userInfo['token'],$data['funds_pwd_new']);
                echo json_encode(array('success' => $success, 'msg' => lang('set_take_cash_pwd_success')));
                exit;
            }
            echo json_encode(array('success' => $success, 'msg' => $msg));
            exit;
        }

        $disabled_status = false;
        if($flag){
            $disabled_status = TRUE;
            $this->_viewData['msg'] = lang('account_active_false');
        }
        $this->_viewData['disabled_status'] = $disabled_status;
        parent::index('new/','','header_2');
    }

    public function validate_email(){
        $param = $this->input->get('param');
        $dataGet = unserialize(base64_decode($param));
        $this->load->model('m_user');
        $userInfo = $this->m_user->getUserByIdOrEmail($dataGet['id']);
        $flag = 0;
        if(!$userInfo){
            $flag = 1008;
        }
        if($dataGet['token']!==$userInfo['token']){
            $flag =  102;
        }
        if($dataGet['time']<(time() - 1800)){
            $flag =  1012;
        }
        if(!$flag){

            $data = array('email'=>$dataGet['new_email']);
            $this->m_user->updateUserInfo($data,$userInfo['id']);
            delete_cookie("userInfo",  get_public_domain());
            $this->_viewData['msg'] = lang('email_login_again');
        }else {
            $this->_viewData['msg'] = lang('account_active_false');
        }
        $this->_viewData['disabled_status'] = TRUE;
        parent::index('new/','','header_2');
    }
    
}
