<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Genealogy_tree extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_overrides', 'm_overrides');
        $this->lang->load('level');
    }

    public function index(){
        //$uid =  $this->input->post('uid');
        $uid = $this->_userInfo['id'];
        $uid = $uid ?  $uid : $this->_userInfo['id'];
        if($uid != $this->_userInfo['id']){
            $user = $this->m_user->getUserByIdOrEmail($uid);
            if(!$user || !in_array($this->_userInfo['id'],explode(',',$user['parent_ids']))){
                $uid = $this->_userInfo['id'];
            }
        }
        $tree_str = $this->m_overrides->getTreeStr($uid);
        $this->_viewData['tree_str'] = $tree_str;
        $this->_viewData['title'] = lang('gene_tree_list');
        parent::index();
    }



}

