<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


/**
 * User: Able
 * Date: 2017/6/2
 * Time: 11:25
 */
class bonus_plan_control extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("tb_bonus_plan_control");
    }

    public function index(){
        $this->load->model("o_cron");
        $this->_viewData['user_modify_total'] = $this->o_cron->get_modify_user_monthly_total();
        $this->_viewData['user_account_err'] = $this->o_cron->get_user_account_err_total();
        $result = $this->tb_bonus_plan_control->getAllDate();
        $this->_viewData['title'] = lang('bonus_plan_control');
        $this->_viewData['list'] = $result;
        parent::index('admin/');
    }

}