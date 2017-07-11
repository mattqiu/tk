<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class my_coupons extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->_viewData['title'] = lang('my_coupons');

        $this->load->model('m_suite_exchange_coupon');
        $allCoupons = $this->m_suite_exchange_coupon->getAll($this->_userInfo['id']);
        $coupon_total_num = 0;
        $coupon_total_amount = 0;
        foreach($allCoupons as $k=>$itemNum){
        	$coupon_total_num += $itemNum;
        	$coupon_total_amount += substr($k,1)*$itemNum;
        }
        $this->_viewData['allCoupons'] = $allCoupons;
        $this->_viewData['coupons_total_num_text'] = str_replace(array(':total_num:'), array($coupon_total_num), lang('coupons_total_num'));
        $this->_viewData['coupon_total_amount'] = $coupon_total_amount;
        parent::index();
    }

}