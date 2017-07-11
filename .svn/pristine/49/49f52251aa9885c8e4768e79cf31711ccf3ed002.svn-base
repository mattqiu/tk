<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Upgrade_user_manually extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
        $this->m_global->checkPermission('upgrade_user_manually',$this->_adminInfo);
    }

    public function index() {
        
        $this->_viewData['title'] = lang('upgrade_user_manually');
        
        parent::index('admin/');
    }
    
    public function submit(){
        $postData = $this->input->post();
        $this->load->model('m_admin_user');
        $errorMsg = $this->m_admin_user->checkUpgradeUserManuallyData($postData);
        if($errorMsg){
            $success = FALSE;
            $msg = $errorMsg;
        }else{
            $res = $this->m_admin_user->upgradeUserManually($postData,$this->_adminInfo['id']);
            $success = TRUE;
            $msg = lang('submit_success') . ',' . sprintf(lang('upgrade_success_num'), $res);
        }

        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
}
