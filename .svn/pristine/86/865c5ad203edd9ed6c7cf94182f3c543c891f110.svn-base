<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class forced_matrix_2x5 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('forced_matrix');
        $this->load->Model('m_forced_matrix');
        $this->load->Model('m_helper');
    }

    /******2x5见点奖*******/
    public function index() {

        $this->_viewData['title'] = lang('forced_matrix_2x5');
        $this->load->model('m_overrides');
        $month_fee_rank = $this->_userInfo['month_fee_rank'];   //会员等级
        $user_status = $this->_userInfo['status'];              //会员状态
        $store_level = $this->_userInfo['user_rank'];              //店铺状态

        $this->_viewData['month_fee_rank'] = $month_fee_rank;
        $this->_viewData['user_status'] = $user_status;
        $this->_viewData['store_level'] = $store_level;

        /***获取所点击用户的ID***/
        $uid =  $this->input->post('uid');
        $user_id = $uid ? $uid : $this->_userInfo['id'];

        /**银级以上会员**/
        if(in_array($month_fee_rank,config_item('pay_rank')) || in_array($store_level,config_item('pay_rank'))){

            //且账号没被冻结
            if ($user_status == 1 || $user_status==2){
                /**打印矩阵图***/
                $this->_viewData['tree'] = $this->m_overrides->preTree2X5($user_id);
            }
        }
        parent::index();
    }
}
