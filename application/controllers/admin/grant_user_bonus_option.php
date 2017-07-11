<?php
/**
 * User: Able
 * Date: 2017/3/20
 * Time: 9:54
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class grant_user_bonus_option extends MY_Controller{


    public function __construct(){
        parent::__construct();        
    }

    public function index(){
        $this->_viewData['title'] = lang('grant_user_hand_bonus_option');   
        parent::index('admin/');
    }

    /**
     * 用户发奖
     */
    public function pre_user_bonus()
    {
        $item_type = $this->input->post('item_type');
        $pre_type = $this->input->post('pre_type');
        $this->load->model('tb_system_grant_bonus_queue_list');
        $this->tb_system_grant_bonus_queue_list->add($item_type,$pre_type);
        echo json_encode(array('success' =>'success'));    
    }

    /***
     * 获取预发奖状态
     */
    public function get_pre_bonus_state()
    {
    
        $state = 0;
        $data = $this->input->post();
        $this->load->model('tb_grant_pre_bonus_state');
        $ret_value = $this->tb_grant_pre_bonus_state->get_state($data['item_type']);
        if(!empty($ret_value))
        {
            $state = $ret_value['state'];
        }
         
        echo json_encode(array('success' =>'success','state'=>$state));
    }
    
    
    
}