<?php
/**
 * User: ckf
 * Date: 2016/5/25
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/***
 * 周团队组织分红奖
 * @author tps
 *
 */
class pre_month_team_bonus extends MY_Controller {

    public function __construct() {
        parent::__construct();              
    }

    public function index(){

        $this->load->model('o_month_leader_bonus_option');
        
        $this->_viewData['title'] = lang('pre_month_team_bonus');        
        
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
       
        //每周团队
        $list = $this->o_month_leader_bonus_option->pre_bonus_list($searchData,2);
        $this->_viewData['list'] = $list;


        $this->load->library('pagination');
        $url = 'admin/pre_month_team_bonus';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->o_month_leader_bonus_option->pre_bonus_all($searchData,2);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }


    //重新预发 每周团队销售分红奖
    public function reset_pre_bonus()
    {
        $this->load->model('o_month_leader_bonus_option');
        $this->o_month_leader_bonus_option->grant_pre_every_month_team_dividend();
        echo json_encode(array('success' =>'success'));
        
    }
    
    /***
     * 获取预防将状态
     */
    public function get_pre_bonus_state()
    {
        
         $state = 0;
         $this->load->model('tb_grant_pre_bonus_state');
         $ret_value = $this->tb_grant_pre_bonus_state->get_state(1);
         if(!empty($ret_value))
         {
             $state = $ret_value['state'];
         }
         
         echo json_encode(array('success' =>'success','state'=>$state));        
    }
    
    
    
    
}