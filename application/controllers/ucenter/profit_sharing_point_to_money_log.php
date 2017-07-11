<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profit_sharing_point_to_money_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_profit_sharing');
    }

    public function index()
    {

        $getData = $this->input->get();
        $page = max((int)(!empty($getData['page']) ? $getData['page'] : 1), 1);
        $page_size = 10;
        $uid = $this->_userInfo['id'];
        $start = isset($getData['start']) ? $getData['start'] : '';
        $end = isset($getData['end']) ? $getData['end'] : '';

        $this->_viewData['title'] = lang('profit_sharing_point_to_money_log');

        $this->load->model("tb_profit_sharing_point_reduce_log");
        $params = [
            'select' => 'id,uid,point,money,create_time',
            'where' => [
                'uid' => $uid,
                'FROM_UNIXTIME(create_time, "%Y-%m-%d") >=' => $start,
                'FROM_UNIXTIME(create_time, "%Y-%m-%d") <=' => empty($end) ? date("Y-m-d",time()) : $end,
            ]
        ];
        $total_rows = $this->tb_profit_sharing_point_reduce_log->get($params, true);
        if($total_rows > 0) {
            $total_page = ceil($total_rows / $page_size);
            $page = ($page > $total_page) ? $total_page : $page;

            //分页查询
            $params['limit'] = [
                'page' => $page,
                'page_size' => $page_size
            ];
            $params['order'] = 'id desc';
            $list = $this->tb_profit_sharing_point_reduce_log->get($params);
        } else {
            $list = [];
            $page = 1;
        }

        $this->_viewData['pointToMoneyLogs'] = $list;
        $page_param = ['page' => $page, 'page_size' => $page_size,'start'=>$start,'end'=>$end];
        $this->_viewData['pager'] = $this->tb_profit_sharing_point_reduce_log->get_pager("ucenter/profit_sharing_point_to_money_log", $page_param, $total_rows);
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;

        parent::index();
    }
    
    /*todo@terry 模拟创建分红记录*/
    public function test() {
        $this->m_profit_sharing->createLog();
        echo 1;
        exit;
    }

}
