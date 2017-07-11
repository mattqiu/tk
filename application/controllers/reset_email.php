<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reset_email extends MY_Controller {

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
        if($dataGet['time']<(time() - 1800)){
            $flag =  1012;
        }

        if(!$flag && $this->input->get('ajax') == 'resetEmail'){
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
            if($data['email'] != $data['email_new_re']){
                $success = FALSE;
                $msg = $msg ? $msg.lang('email_match').'<br>' : lang('email_match').'<br>';
            }
            if ($success) {
                $user = $this->m_user->getUserByIdOrEmail($dataGet['id']);
                if($user['send_email_time'] > time()-60){
                    echo json_encode(array('success' => false, 'msg' => lang('send_again')));
                    exit;
                }
                $user['new_email'] = $data['email'];
                $this->m_user->updateCreateTime($user['id']);
                $this->m_user->sendValidateNewEmail($user);
                echo json_encode(array('success' => $success, 'msg' => lang('validate_new_email_tip')));
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
            $this->m_user->addInfoToWohaoSyncQueue($userInfo['id'],array(1));
            delete_cookie("userInfo",  get_public_domain());
            $this->_viewData['msg'] = lang('email_login_again');
        }else {
            $this->_viewData['msg'] = lang('account_active_false');
        }
        $this->_viewData['disabled_status'] = TRUE;
        parent::index('new/','','header_2');
    }
    
}
