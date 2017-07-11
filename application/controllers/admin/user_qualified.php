<?php
/**
 * User: ckf
 * Date: 2016/8/3
 * Time: 17:05
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class user_qualified extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_cash_account_log_x');
        $this->load->model('tb_users');
        $this->load->model('tb_user_qualified_for_138');
    }

    /* 138合格人数列表 */
    public function index(){

        $this->_viewData['title'] = lang('add_user_qualified');

        parent::index('admin/');
    }


}