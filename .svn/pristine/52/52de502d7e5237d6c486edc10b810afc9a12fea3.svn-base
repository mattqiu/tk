<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generation_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_overrides','m_overrides');
        $this->lang->load('level');
    }

    public function index() {

        $uid = $this->input->post('uid');
        $type = $this->input->post('type');
        $uid = $uid ? $uid : config_item('mem_root_id');
        if($type != 'father'){
            $tree_str = $this->m_overrides->getTreeStr($uid);
        }else{
            $tree_str = $this->m_overrides->getFatherTreeStr($uid);
        }
        $this->_viewData['tree_str'] = $tree_str;
        $this->_viewData['title'] = lang('gene_tree_list');
        parent::index('admin/');
    }

    public function checkUid(){
        if($this->input->post()){
            $uid = $this->input->post('uid');
            $this->load->model('m_user');
            $exist = $this->m_user->checkUserExist($uid);
            if($exist){
                $result['success'] = 1;
            }else{
                $result['success'] = 0;
                $result['msg'] = lang('no_exist');
            }
            echo json_encode($result);
        }
    }

}
