<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_admin extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
		$this->m_global->checkPermission('add_admin',$this->_adminInfo);
        $this->load->model('m_admin_user');
    }

    public function index() {
        $this->_viewData['title'] = lang('add_admin');
		$this->_viewData['admin_role'] = config_item('admin_role');
        parent::index('admin/');
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
        if(!$data['role'] || !in_array($data['role'],array(1,2,3,4,5,6,7,8))){
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

            $admin_id = $this->m_admin_user->createAdmin($data);

			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_manage_add','admin_users',$admin_id,
				'','','');
        }
        echo json_encode(array('success' => $success, 'checkResult' => $checkResult));
        exit;
    }

    public function checkJobNumber(){
        $data   = $this->input->post();
        $number = $this->m_admin_user->get_new_job_number_by_area($data['area']);
        $job_number = $number['job_number']+1;
        die(json_encode(array('number'=>$job_number)));
    }

}