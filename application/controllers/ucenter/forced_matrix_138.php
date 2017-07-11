<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class forced_matrix_138 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('forced_matrix');
        $this->load->model('m_forced_matrix');
        $this->load->model('m_overrides');
        $this->load->model('m_helper');
    }

    public function index() {
        $this->_viewData['title'] = lang('forced_matrix_138');
		
		//如果月费等级为4，则选择店铺等级
		$month_fee_rank = $this->_userInfo['month_fee_rank'];
		if($month_fee_rank == 4)
		{
			$month_fee_rank = $this->_userInfo['user_rank'];
		}
        $status = $this->_userInfo['status'];
        $this->_viewData['month_fee_rank'] = $month_fee_rank;
        $this->_viewData['status'] = $status;
        $this->_viewData['store_level'] = $this->_userInfo['user_rank'];

        /***获取所点击用户的ID***/
        $uid =  $this->input->post('uid');
        $user_id = $uid ? $uid : $this->_userInfo['id'];

        $this->_viewData['month_fee_rank'] = $this->m_forced_matrix->userInfo($user_id)->month_fee_rank;

        /**是否通过身份认证**/
		if(!$this->input->post('uid')){
			$is_authentication=$this->m_forced_matrix->is_authentication($user_id);
			$this->_viewData['is_authentication'] = $is_authentication;
		}else{
			$this->_viewData['is_authentication'] = true;
		}


        //正在排队中
        $res = $this->db_slave->query("select * from save_user_for_138 where user_id=$user_id")->result();
        if(!empty($res)){
            $this->_viewData['is_sorting'] = 1;
        }


        //获取所有下级用户
        $data = $this->m_forced_matrix->getChildrenFor138($user_id);
        if ($data) {
            $this->_viewData['x'] = $data['x'];
            $this->_viewData['y'] = $data['y'];
            $this->_viewData['children_num'] = $data['children_num'];
            $this->_viewData['children_id'] = $data['children_id'];
            $this->_viewData['children_rank'] = $data['children_rank'];
            $this->_viewData['tree'] = $this->m_overrides->preTree138($data);
        }
        $this->_viewData['user_id'] = $user_id;
        parent::index('','forced_matrix_138_2');
    }
}

?>