<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reset_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){

        $this->_viewData['title'] = lang('reset_pwd');
        parent::index('admin/');
    }

    public function changePwd(){

        $data = $this->input->post();
        $data['id'] = $this->_adminInfo['id'];
        $this->load->model('m_admin_user');
        $checkResult = $this->m_admin_user->checkRegisterItems($data);
        $success = TRUE;
        foreach ($checkResult as $resultItem) {
            if (!$resultItem['isRight']) {
                $success = FALSE;
                break;
            }
        }
        if ($success) {
            unset($data['pwdOriginal_re']);

            $this->m_admin_user->updatePwd($data,$this->_adminInfo['token']);

			$this->m_log->adminActionLog($data['id'],'admin_update_admin_pwd','admin_users',$data['id'],
				'pwd','','');
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }

}

