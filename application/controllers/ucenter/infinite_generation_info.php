<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Infinite_generation_info extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_infinite_generation', 'm_infinite_generation');
        $this->load->model('M_generation_sales', 'm_generation_sales');
    }

    public function index(){

        $uid = $this->_userInfo['id'];

        //必须是钻石会员
        $is_diamond = $this->m_infinite_generation->isDiamond($uid);

        //必须有3000以上的银级会员
        $is_3000 = $this->m_infinite_generation->getChildCount($uid);

        //必须有30个合格客户
        $this->load->model('M_order','m_order');
        $is_qualified_30 = $this->m_order->getPassOrderCount($uid);

        $this->_viewData['title'] =  lang('infinity');

        $firstday = date("Y-m-01", time());
        $this->_viewData['leftSeconds'] = strtotime("$firstday".(date('d')>10?" +1 month":"")." +10 day")-time();
        $this->_viewData['con1'] = $is_diamond ;
        $this->_viewData['con2'] =  $is_3000;
        $this->_viewData['con3'] =  $is_qualified_30 ;

        parent::index();
    }

    public function test1(){
        $uid = 1381234601;
        //$all_cash = 0;
        //var_dump($this->m_infinite_generation->recursiveTotalSales($uid ,$all_cash));
        $this->m_infinite_generation->infinityCrontab();
    }
    public function test2(){

        $this->m_infinite_generation->grantInfinityCash();
    }
}
