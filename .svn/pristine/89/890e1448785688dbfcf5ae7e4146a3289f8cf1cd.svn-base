<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Change_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
        parent::CheckPermission();
        $this->load->model('m_user');
    }

    public function index(){
        $this->_viewData['title'] = lang('reset_pwd');
        parent::index();
    }


    public function changePwd(){
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
            unset($data['pwdOriginal_re']);
            $this->m_user->updatePwd($data,$this->_userInfo['token']);
            $this->m_user->addInfoToWohaoSyncQueue($data['id'],array(2));
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }
}

