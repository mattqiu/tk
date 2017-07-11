<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cash_to_month_fee_pool_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_user');
    }

    public function index() {
        
        $this->_viewData['title'] = lang('cash_to_month_fee_pool_log');
        
        $getData = $this->input->get();
        $page = max((int)(isset($getData['page'])?$getData['page']:1),1);
        $perPage = 10;
        $uid = $this->_userInfo['id'];
        $start = isset($getData['start'])?$getData['start']:'';
        $end = isset($getData['end'])?$getData['end']:'';
        $filter = array(
            'uid'=>$uid,
            'start'=>$start,
            'end'=>$end,
        );
        $this->_viewData['pointToMoneyLogs'] = $this->m_user->getCashToMonthFeeLogs($filter,$page,$perPage);

        $this->load->library('pagination');
        $config['base_url'] = base_url('ucenter/cash_to_month_fee_pool_log?start='.$start.'&end='.$end);
        $config['total_rows'] = $this->m_user->getCashToMonthFeeLogsTotalRows($filter);
        $config['per_page'] = $perPage;
        $config['cur_page'] = $page;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;

        parent::index();
    }
    
}
