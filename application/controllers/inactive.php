<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Inactive extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->_viewData['title']=lang('Active_account').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        parent::index('mall/','','header');
    }
    
    public function sendEnableMail(){
        $email = $this->input->post('email');
        $this->load->model('m_user');
        $emailCheckRes = $this->m_user->checkUserEmail($email,'active');

        if($emailCheckRes['userInfo'] && $emailCheckRes['userInfo']['send_email_time'] > time() - 60){
            $emailCheckRes['msg'] = lang('send_again');
            $emailCheckRes['isRight'] = FALSE;
        }
        if($emailCheckRes['isRight']){

            $this->m_user->updateCreateTime($emailCheckRes['userInfo']['id']);
            $this->m_user->sendAccountActivationEmail($emailCheckRes['userInfo']);
            $success = TRUE;
            $msg = lang('re_send_mail_success');
        }else{
            $success = FALSE;
            $msg = $emailCheckRes['msg'];
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
    }

}
