<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profit_sharing_point_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_profit_sharing');
    }

    public function index() {

        $this->_viewData['title'] = lang('profit_sharing_point_log');

        $getData = $this->input->get();
        $page = max((int)(isset($getData['page']) ? $getData['page'] : 1), 1);
        $add_source = isset($getData['add_source']) ? $getData['add_source'] : '';
        $start = isset($getData['start']) ? $getData['start'] : '';
        $end = isset($getData['end']) ? $getData['end'] : '';
        $perPage = $page_size = 10;
        $uid = $this->_userInfo['id'];
        $filter = array(
            'uid' => $uid,
            'add_source' => $add_source,
            'start' => $start,
            'end' => $end,
        );
        //改为查询从库
        $params = [
            'select' => 'id,commission_id,uid,add_source,money,point,create_time',
            'where' => [
                'uid' => $uid,
                'create_time >=' => strtotime($start),
                'create_time <=' => empty($end) ? time(): strtotime($end)+24*3600-1
            ]
        ];
        if(!empty($add_source)) {
            $params['where']['add_source'] = $add_source;
        }
        $this->load->model("tb_profit_sharing_point_add_log");
        $total_rows = $this->tb_profit_sharing_point_add_log->get($params, true);
        if($total_rows > 0) {
            $total_page = ceil($total_rows / $page_size);
            $page = ($page > $total_page) ? $total_page : $page;

            $params['limit'] = [
                'page' => $page,
                'page_size' => $page_size
            ];
            $params['order'] = 'id desc';
            $list = $this->tb_profit_sharing_point_add_log->get($params);

        } else {
            $list = [];
            $page = 1;
        }

        //获取分页
        $page_param = ['page' => $page, 'page_size' => $page_size,'start'=>$start,'end'=>$end,'add_source'=>$add_source];
        $pager = $this->tb_profit_sharing_point_add_log->get_pager("ucenter/profit_sharing_point_log",$page_param , $total_rows, true);

        //$this->_viewData['addPointLogs'] = $this->m_profit_sharing->getUserSharingPointAddLog($filter,$page,$perPage);
        $this->_viewData['addPointLogs'] = $list;


        $this->_viewData['add_source_txt_arr'] = array(
            1 => lang('sale_commissions_sharing_point'),
            2 => lang('forced_matrix_sharing_point'),
            3 => lang('profit_sharing_sharing_point'),
            4 => lang('manually_sharing_point'),
        );

        $this->_viewData['pager'] = $pager;
        $this->_viewData['add_source'] = $add_source;
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;

        parent::index();
    }
    
    /*todo@terry 模拟创建分红点新增记录*/
    public function test() {
        $this->m_profit_sharing->createPointAddLog();
        echo 1;
        exit;
    }
    
}
