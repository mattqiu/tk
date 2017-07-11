<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clear_member_account_info extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('clear_member_account_info',$this->_adminInfo);
    }

    public function index() {
        
        $this->_viewData['title'] = lang('clear_member_account_info');
		$this->load->model('m_admin_helper');
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$this->_viewData['list'] = $this->m_admin_helper->getTransferUsersList($searchData);

		$this->load->library('pagination');
		$url = 'admin/clear_member_account_info';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_admin_helper->getTransferUsersRows($searchData);
		$config['cur_page'] = $searchData['page'];
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);
		$this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    public function submit(){
    	$postData = $this->input->post();
        $this->load->model('m_user');
        $this->load->model('m_admin_helper');
        $user = $this->m_admin_helper->getCardOne($postData['uid']);
		if(!trim($postData['uid']) || !trim($postData['new_email']) || !trim($postData['new_card_number']) || !trim($postData['check_info']) ){
			echo json_encode(array('success'=>0,'msg'=>'*'.lang('required')));exit;
		}
        if(!$user){
            $user['id_card_num'] = '';
        }
        $res = $this->m_user->resetMemberAccount(trim($postData['uid']),trim($postData['new_email']),trim($postData['new_card_number']),
			trim($user['id_card_num']),'',$this->_adminInfo['id'],trim($postData['check_info']),1,1);
        if(is_array($res)){
            $this->m_user->addInfoToWohaoSyncQueue($postData['uid'],array(1,2,3,4,5,6,7,8,9,10,11));
            $success = TRUE;
            $msg = lang('submit_success') . ',' . sprintf(lang('new_password_note'), $res['newPwd']);
        }else{
            $success = FALSE;
            $msg = lang(config_item('error_code')[$res]);
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
    }
}
