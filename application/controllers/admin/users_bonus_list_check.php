<?php
/**
 * User: ckf
 * Date: 2016/09/06
 * Time: 12:50
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class users_bonus_list_check extends MY_Controller {

    public static $COMM_TYPE_IDS = array(6,2,24,25,7,8,1,23,26);
    
    public function __construct() {
        parent::__construct();
        $this->load->model('tb_users_status_log');
    }

    public function index(){
        $this->load->model('o_fix_commission');
        $this->load->model('tb_user_transfer_account_waring');
        $this->_viewData['commList'] = self::$COMM_TYPE_IDS;//佣金奖项
        $this->_viewData['title'] = lang('users_bonus_list_check');
        parent::index('admin/');
    }

    /**
     * 用户佣金队列异常数据处理
     */
    public function user_bonus_list_checks()
    {               
        $this->load->model('tb_users');
        $searchData = $this->input->post()?$this->input->post():array();
        $searchData['user_id'] = $searchData['user_id'];
        $searchData['item_type'] = $searchData['item_type'];
        $searchData['user_monthly'] = $searchData['user_monthly'];
        $searchData['sale_rank'] = $searchData['sale_rank'];       
        $this->tb_users->users_bonus_list_edit($searchData['user_id'],$searchData['item_type'],$searchData['user_monthly'],$searchData['sale_rank']);
        echo json_encode(array('success' =>'success'));
    }
    
}