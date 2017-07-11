<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin_account_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('admin_account_list',$this->_adminInfo);
        $this->load->model('m_user');
    }

    public function index() {
        
        $this->_viewData['title'] = lang('admin_account_list');
        
        $searchData              = $this->input->get()?$this->input->get():array();
        $searchData['page']      = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['email']     = isset($searchData['email'])?$searchData['email']:'';
        $searchData['role']      = isset($searchData['role'])?$searchData['role']:'';
        $searchData['status']    = isset($searchData['status'])?$searchData['status']:'';
        $this->_viewData['list'] = $this->m_user->getAdminUserList($searchData,$this->_adminInfo['role']);

        $this->load->library('pagination');
        $url = 'admin/admin_account_list';
        add_params_to_url($url, $searchData);
        $config['base_url']   = base_url($url);
        $config['total_rows'] = $this->m_user->getAdminUserListRows($searchData,$this->_adminInfo['role']);
        $config['cur_page']   = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }
    
    public function changeAdminAccountStatus(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $this->m_user->changeStatusAdmin($id,$status);
		$action_name = $status==1 ? 'admin_manage_enable' : 'admin_manage_inactive';
		$before_status = $status==1 ? '0' : '1';
		$this->m_log->adminActionLog($this->_adminInfo['id'],$action_name,'admin_users',$id,
			'status',$before_status,$status);
        echo json_encode(array('success'=>TRUE,'msg'=>''));
    }

    public function deleteAdminAccount(){
        $id = $this->input->post('id');
		$admin_user = $this->db->where('id',$id)->get('admin_users')->row_array();
		$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_manage_delete','admin_users',$id,
			'',serialize($admin_user),'');
		$this->m_user->deleteAdminAccount($id);
        echo json_encode(array('success'=>TRUE,'msg'=>''));
    }
    /**
     * 重置密码
     */
    public function resetAdminAccountPw(){
        $id = $this->input->post('id');
        $admin_user = $this->db->where('id',$id)->get('admin_users')->row_array();
        $this->m_log->adminActionLog($this->_adminInfo['id'],'admin_manage_reset_password','admin_users',$id,'',serialize($admin_user),'');
        $new_psw = $this->m_user->resetAdminAccountPw($id);
        echo json_encode(array('success'=>TRUE,'msg'=>$new_psw,'title'=>lang("pwd_reset"),'button'=>lang('confirm')));
    }
}
