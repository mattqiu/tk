<?php
/**
 * User: ckf
 * Date: 2016/09/06
 * Time: 12:50
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class users_amount_check extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_users_status_log');
    }

    public function index(){
        
        $this->load->model('tb_user_transfer_account_waring');
        
        $this->_viewData['title'] = lang('users_amount_check');
        // 设定程序永不超时
        ignore_user_abort(TRUE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $useStart = memory_get_usage();
        
        //会员状态映射
        $sta_arr = array(
            '1' => lang('enabled'),
            '2' => lang('sleep')
        );

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['t_stime'] = isset($searchData['t_stime'])?$searchData['t_stime']:'';
        $searchData['t_end'] = isset($searchData['t_end'])?$searchData['t_end']:'';
        $searchData['fx'] = isset($searchData['fx'])?$searchData['fx']:0;
        
        //$this->tb_user_transfer_account_waring->user_transfer_account_check($searchData['t_stime'],$searchData['t_end'],$searchData['fx']);
        //$this->tb_user_transfer_account_waring->check_user_transfer_account($searchData['t_stime'],$searchData['t_end']);


        $list = $this->tb_user_transfer_account_waring->getUserTransferAccountWarningList($searchData);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/users_amount_check';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->tb_user_transfer_account_waring->getUserTransferAccountWarningNum($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['sta_arr'] = $sta_arr;

        $this->_viewData['page_data'] = array(
            'uid' => $searchData['uid'],
        );

        parent::index('admin/');
    }

    /**
     * 检测
     */
    public function transfer_check()
    {
               
        $this->load->model('tb_grant_pre_bonus_state');
        $this->load->model('tb_user_transfer_account_waring');
        $this->_viewData['title'] = lang('users_amount_check');
        ignore_user_abort(TRUE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $useStart = memory_get_usage();
        $ret_value = $this->tb_grant_pre_bonus_state->edit_state(99,0,"");
        $searchData = $this->input->post()?$this->input->post():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
        $searchData['t_stime'] = isset($searchData['t_stime'])?$searchData['t_stime']:'';
        $searchData['t_end'] = isset($searchData['t_end'])?$searchData['t_end']:'';
        $searchData['fx'] = isset($searchData['fx'])?$searchData['fx']:'1';
        $this->tb_user_transfer_account_waring->user_transfer_account_check($searchData['t_stime'],$searchData['t_end'],$searchData['fx']);
        $ret_value = $this->tb_grant_pre_bonus_state->edit_state(99,1,"");
        echo json_encode(array('success' =>'success'));
    }
    
    /**
     * 删除转账记录
     */
    public function del_user_account_option()
    {
         
        $this->load->model('tb_user_transfer_account_waring');
        $searchData = $this->input->post()?$this->input->post():array();
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:0;
        if($searchData['user_id']!=0)
        {
            $this->tb_user_transfer_account_waring->del_user_account($searchData['user_id']);
        }        
        echo json_encode(array('success' =>'success'));
    }
    
    /**
     * 删除多条转账记录
     */
    public function del_user_account_option_all()
    {
        $this->load->model('tb_user_transfer_account_waring');
        $searchData = $this->input->post()?$this->input->post():array();
        $user_id_list = $searchData['user_id'];
        if(!empty($user_id_list))
        {
            for($idx=0;$idx < count($user_id_list);$idx++)
            {
                $this->tb_user_transfer_account_waring->del_user_account($user_id_list[$idx]);
            }
        }  
        echo json_encode(array('success' =>'success'));
    }
    
    
    /**
     * 添加缺失转账记录
     */
    public function add_user_account_log()
    {
         
        $this->load->model('tb_user_transfer_account_waring');
        $searchData = $this->input->post()?$this->input->post():array();
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:1;
        
        if($searchData['user_id']!=0)
        {
            $this->tb_user_transfer_account_waring->add_user_account_log($searchData['user_id']);
            
        }        
        echo json_encode(array('success' =>'success'));
       
    }
    
    /**
     * 批量添加转账记录
     */
    public function add_user_account_all_log()
    {
         
        $this->load->model('tb_user_transfer_account_waring');
        $searchData = $this->input->post()?$this->input->post():array();
        $user_id_list = $searchData['user_id'];
        if(!empty($user_id_list))
        {
            for($idx=0;$idx < count($user_id_list);$idx++)
            {
                $this->tb_user_transfer_account_waring->add_user_account_log($user_id_list[$idx]);
            }
        }      
        echo json_encode(array('success' =>'success'));         
    }
    
    /**
     * 获取检测状态
     */
    public function get_check_status()
    {
        $state = 0;
        $this->load->model('tb_grant_pre_bonus_state');
        $ret_value = $this->tb_grant_pre_bonus_state->get_state(99);
        if(!empty($ret_value))
        {
            $state = $ret_value['state'];
        }
         
        echo json_encode(array('success' =>'success','state'=>$state));
    }
    
    
    
    
    
    
    
}