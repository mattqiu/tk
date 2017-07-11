<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class change_2x5_position extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_overrides');
        $this->lang->load('forced_matrix');
        $this->load->model('m_forced_matrix');
    }

    public function index(){
        $this->_viewData['title'] = lang('change_2x5_position');
        $pay_parent_id=$this->input->post('pay_parent_id');
            /**如果ID为空，或者ID未进入排序**/
        if($pay_parent_id!=""){
            if($this->m_forced_matrix->CheckUserExistFor2x5($pay_parent_id)){
                $tree=$this->m_overrides->preTree2X5($pay_parent_id);
                $this->_viewData['tree']=$tree;
            }
        }
        parent::index('admin/');
    }
}