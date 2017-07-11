<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class forced_matrix_2x5 extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->lang->load('forced_matrix');
        $this->load->Model('m_forced_matrix');
        $this->load->model('m_overrides');
    }

    /******2x5见点奖*******/
    public function index() {
        $this->_viewData['title'] = lang('forced_matrix_2x5');
        $user_id='';
        $uid = $this->input->post('uid');               //单击节点
        $back_uid = $this->input->post('back_uid');     //双击节点
        $submit_uid = $this->input->post('submit_uid'); //点按钮提交

        /***单击事件,显示下面三层***/
        if($this->input->post('uid')){
            $user_id=$uid;
        }
        /***双击事件回溯三层***/
        if($this->input->post('back_uid')){
            $showLevel=3;                   //要回溯的层数
            $parents=$this->m_forced_matrix->findUserAllLeader($back_uid);
            if(!empty($parents)){
                if(count($parents)>=$showLevel){
                    $user_id=$parents[$showLevel-1];
                }else{
                    $user_id=end($parents);
                }
            }
        }
        /***按钮提交事件***/
        if($this->input->post('submit_uid')){
            $user_id=$submit_uid;
        }

        if ($user_id == '') {
            $user_id = config_item('mem_root_id');
        }

        /***用户存在***/
        if($this->m_forced_matrix->CheckUserExistFor2x5($user_id)){
            $this->_viewData['tree'] = $this->m_overrides->preTree2X5($user_id);
            $this->_viewData['status'] = 1;
        }else{
            $this->_viewData['status']=2;
        }

        parent::index('admin/');
    }
}
