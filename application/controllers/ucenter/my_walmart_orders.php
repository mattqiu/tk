<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_walmart_orders extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_walmart_orders');
    }

    public function index() {
        $this->_viewData['title'] = lang('my_affiliate');
        
        $getData = $this->input->get();
        $page = max((int)(isset($getData['page'])?$getData['page']:1),1);
        $perPage = 10;
        $uid = $this->_userInfo['id'];
        $start = isset($getData['start'])?$getData['start']:'';
        $end = isset($getData['end'])?$getData['end']:'';
        $filter = array(
            'shopkeeper_id'=>$uid,
            'start'=>$start,
            'end'=>$end,
        );
        $orders = $this->tb_walmart_orders->getWalmartOrders($filter,$page,$perPage);
        $customer_ids = array();
        foreach($orders as $order){
            if($order['customer_id']){
                $customer_ids[] = $order['customer_id'];
            }
        }
        $this->load->model('m_user');
        $this->_viewData['customer_info_list'] = $this->m_user->getUserByIds($customer_ids);
        $this->_viewData['list'] = $orders;

        $this->load->library('pagination');
        $config['base_url'] = base_url('ucenter/my_walmart_orders?start='.$start.'&end='.$end);
        $config['total_rows'] = $this->tb_walmart_orders->getWalmartTotalRows($filter);
        $config['per_page'] = $perPage;
        $config['cur_page'] = $page;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;

        parent::index();
    }
    

}
