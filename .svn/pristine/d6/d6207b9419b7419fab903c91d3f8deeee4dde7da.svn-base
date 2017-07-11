<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Change_email extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
        $this->load->model('m_user');
    }

    public function index(){
        $this->_viewData['title'] = lang('email_reset');
        $this->_viewData['email'] = $this->_userInfo['email'];
        $email_encrypt = email_encrypy($this->_userInfo['email']);
        $this->_viewData['reset_email_tip'] = sprintf(lang('reset_email_tip'),$email_encrypt);
        $this->_viewData['email_encrypt'] = $email_encrypt;

        //如果还未验证邮箱,强制验证邮箱
        $this->_viewData['is_verified_email'] = $this->_userInfo['is_verified_email'];
        parent::index();
    }

    public function changeEmail(){


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
            $this->m_user->sendChangeEmail($user);
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }

}

