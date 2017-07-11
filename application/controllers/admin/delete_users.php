<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Delete_users extends MY_Controller {
    const TIME_NODE_OLD = '201606';
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
        //$this->m_global->checkPermission('delete_users',$this->_adminInfo);
    }

    public function index($id = NULL) {
        $this->_viewData['title'] = lang('delete_free_user');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->getDeleteUsersList($searchData);

        $this->load->library('pagination');
        $url = 'admin/delete_users';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getDeleteUsersRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    public function do_delete(){

        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            $user_id=$data['user_id'];
            $confirm_user_id=$data['confirm_user_id'];
//            if(!preg_match("/^[0-9]*$/",$user_id) || trim($user_id)==''){
            if(!preg_match("/^138\d{7}$/",$user_id) || trim($user_id)==''){
                echo json_encode(array('success'=>false,'msg'=>lang('pls_t_correct_ID')));
                exit();
            }

            if(trim($user_id)!=trim($confirm_user_id)){
                echo json_encode(array('success'=>false,'msg'=>lang('id_not_identical')));
                exit();
            }

            $this->load->model('m_user');
            $userInfo=$this->m_user->getUserByIdOrEmail($user_id);
            if(!$userInfo){
                echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
                exit();
            }
         //   $child_counts = $this->db->from('users')->where('parent_id',$user_id)->count_all_results();
            if($userInfo['user_rank'] != 4 || $userInfo['month_fee_rank'] != 4){
                echo json_encode(array('success'=>false,'msg'=>lang('user_not_free')));
                exit();
            }

            //查询是否有过资金变动记录
            
            $is_comm= 1 ;

            
            //echo "<pre>";print_r($is_comm);exit;
            $this->load->model('m_admin_helper');
            $this->db->trans_start();
            $this->m_admin_helper->deleterUserLogs($user_id,$userInfo['parent_id'],$this->_adminInfo['id']);
            $this->m_admin_helper->deleteUserById($user_id,$userInfo,$is_comm);
			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_delete_free_user','users',$user_id,
				'',serialize($userInfo),'');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                echo json_encode(array('success'=>false,'msg'=>lang('delete_failure')));
                exit();
            }
            echo json_encode(array('success'=>TRUE,'msg'=>lang('update_success')));
            exit();
        }
    }
}