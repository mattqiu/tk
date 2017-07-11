<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_store_commission extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_generation_sales');
    }

    public function index() {
        
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
        
        $this->_viewData['title'] = lang('individual_store_sales_commission');
        $this->_viewData['list'] = $this->M_generation_sales->getStoreCommissionLogs($filter,$page,$perPage);

        $this->load->library('pagination');
        $config['base_url'] = base_url('ucenter/my_store_commission?start='.$start.'&end='.$end);
        $config['total_rows'] = $this->M_generation_sales->getStoreCommissionLogsTotalRows($filter);
        $config['per_page'] = $perPage;
        $config['cur_page'] = $page;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;
        $this->_viewData['accumulation_commission'] = $this->_userInfo['amount_store_commission'];

        parent::index();
    }
    
}
