<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Register extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
		redirect(base_url('admin/sign_in'));exit;
        parent::index('admin/','','header_1','footer_1');
    }

    public function checkRegisterItem() {
        $requestData = $this->input->post();
        $registerData = array($requestData['itemName'] => $requestData['itemVal'], 'pwdVal' => $requestData['pwdVal']);
        if($this->_adminInfo){
            $registerData['id'] = $this->_adminInfo['id'];
        }
        $checkResult = $this->m_admin_user->checkRegisterItems($registerData);
        echo json_encode($checkResult);
        exit;
    }
    
    public function submit(){

        $data = $this->input->post();
        $checkResult = $this->m_admin_user->checkRegisterItems($data);
        if(!$data['role'] || !in_array($data['role'],array(1,2,3,4,5))){
            $data['role'] = 1;//客服
        }
        $success = TRUE;
        foreach ($checkResult as $resultItem) {
            if (!$resultItem['isRight']) {
                $success = FALSE;
                break;
            }
        }
        if ($success) {
            unset($data['pwdOriginal_re']);

            $this->m_admin_user->createAdmin($data);
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }

}