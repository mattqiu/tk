<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Monthfee_pool_admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('monthfee_pool_admin',$this->_adminInfo);
    }

    public function index() {
        
        $this->_viewData['title'] = lang('monthfee_pool_admin');
        parent::index('admin/');
    }

    public function monthFeePoolChangeSub(){
        $postData = $this->input->post();
        $this->load->model('m_admin_user');
        $errorMsg = $this->m_admin_user->checkMonthfeePoolChangeSubData($this->_adminInfo['id'],$postData);
        if($errorMsg){
            $success = FALSE;
            $msg = $errorMsg;
        }else{
            $success = TRUE;
            $msg = lang('submit_success');
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
}
