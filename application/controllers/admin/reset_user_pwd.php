<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class reset_user_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_forced_matrix');
    }
    public function index() {
        $this->_viewData['title'] = lang('reset_user_pwd');
        parent::index('admin/');
    }

    public function check_data(){
        $data=$this->input->post();
        $user_id=$data['user_id'];
        $confirm_user_id=$data['confirm_user_id'];
        if(!preg_match("/^[0-9]*$/",$user_id)){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }

        if(trim($user_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('id_not_null')));
            exit();
        }

        $userInfo=$this->m_forced_matrix->userInfo($user_id);
        if($userInfo==false){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }


        if(trim($user_id)!=trim($confirm_user_id)){
            echo json_encode(array('success'=>false,'msg'=>lang('id_not_identical')));
            exit();
        }
        $this->reset_user_pwd($user_id);

    }

    /*重置密码*/
    public function reset_user_pwd($user_id){
        $this->load->model('m_user');
        $newPwd = strtolower(rangePassword(6,'NUMBER_AND_LETTER'));
        $oldUserInfo=$this->m_forced_matrix->userInfo($user_id);
        $pwd=$this->m_user->pwdEncryption($newPwd,$oldUserInfo->token);

        $sql="update users set pwd='$pwd' where id=$user_id";
        if($this->db->query($sql)){
            $this->m_user->addInfoToWohaoSyncQueue($user_id,array(2));
			$this->m_log->adminActionLog($this->_adminInfo['id'],'admin_reset_user_pwd','users',$user_id,
				'pwd',$oldUserInfo->pwd,$pwd.$newPwd);
            echo json_encode(array('success'=>true,'msg'=>lang('reset_pwd_success_admin').$newPwd));
        }

    }
}