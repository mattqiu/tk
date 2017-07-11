<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_monthly_fee_report extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
    }

    public function index()
    {
        $this->_viewData['title'] = lang('monthly_fee_detail');

        $page_size = 10;
        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int)(isset($searchData['page']) ? $searchData['page'] : 1), 1);
        $searchData['type'] = isset($searchData['type']) ? $searchData['type'] : '';
        $searchData['start'] = (isset($searchData['start']) && !empty($searchData['start'])) ? $searchData['start'] : '';
        $searchData['end'] = (isset($searchData['end']) && !empty($searchData['end'])) ?  $searchData['end']  : date("Y-m-d", time());
        //M BY BRADY.WANG 改为查询从库
        //$this->_viewData['list'] = $this->m_admin_helper->getMonthlyFeeList($searchData,$this->_userInfo['id']);

        $params = [
            'select' => "id,cash,type,create_time,month_fee_pool, coupon_num_change,coupon_num",
            'where' => [
                'user_id' => $this->_userInfo['id'],
                'DATE_FORMAT(create_time, "%Y-%m-%d") >=' => $searchData['start'],
                'DATE_FORMAT(create_time, "%Y-%m-%d") <=' => $searchData['end'],
            ]
        ];
        if(!empty($searchData['type'])) {
            $params['where']['type'] = $searchData['type'];
        }
        $this->load->model("tb_month_fee_change");
        $total_rows = $this->tb_month_fee_change->get($params, true);
        if($total_rows > 0) {
            $total_page = ceil($total_rows / $page_size);
            $searchData['page'] = ($searchData['page'] > $total_page) ? $total_page : $searchData['page'];

            $params['limit'] = [
                "page" => $searchData['page'],
                'page_size' => $page_size
            ];
            $params['order'] = 'id desc';
            $list = $this->tb_month_fee_change->get($params);

        } else {
            $list = [];
            $searchData['page'] = 1;
        }
        $pager = $this->tb_month_fee_change->get_pager("ucenter/user_monthly_fee_report", ['page' => $searchData['page'], 'page_size' => $page_size], $total_rows);

        $this->_viewData['pager'] = $pager;
        $this->_viewData['list'] = $list;
        $this->_viewData['searchData'] = $searchData;

        parent::index('ucenter/');
    }
    
}