<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission_report extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_commission');
        $this->load->model('m_user');
    }

    public function index()
    {
        $this->load->model('o_cash_account');
        $this->_viewData['title'] = lang('funds_change_report');

        $getData = $this->input->get();
        $page = max((int)(isset($getData['page']) ? $getData['page'] : 1), 1);
        $perPage = 10;
        $curComType = isset($getData['curComType']) ? $getData['curComType'] : '';
        $start = isset($getData['start']) ? $getData['start'] : date('Y-m-01', strtotime(date("Y-m-d")));
        $end = isset($getData['end']) ? $getData['end'] : '';
        $idEmail = isset($getData['idEmail']) ? trim($getData['idEmail']) : '';
        $pay_user_id = isset($getData['pay_user_id']) ? trim($getData['pay_user_id']) : '';
        $order_id = isset($getData['order_id']) ? trim($getData['order_id']) : '';

        $filter = array();
        $filter['start_time'] = $start;
        isset($email) ? $url_data = $email : $url_data = $idEmail;
        //开始时间不能为空
        if(empty($start)){
            $this->_viewData['pager'] = '';
            $this->_viewData['list'] = array();
            $this->_viewData['code'] = 1004;
        }else {
                //没有提交不查询
                if (!$_GET) {
                    $this->_viewData['pager'] = '';
                    $this->_viewData['list'] = array();
                    $this->_viewData['code'] = 1003;
                } else {
                    //用户ID不能为空
                    if(empty($idEmail)){
                        $this->_viewData['pager'] = '';
                        $this->_viewData['list'] = array();
                        $this->_viewData['code'] = 1005;
                    }else {
                    //判断时间，开始时间和结束时间不能跨月份查询
                    if (!empty($end) && (date("m", strtotime($start)) != date("m", strtotime($end)))) {
                        $this->_viewData['pager'] = '';
                        $this->_viewData['list'] = array();
                        $this->_viewData['code'] = 1002;
                    } else {
                        //如果结束时间不输入，判断开始时间是不是本月，如果是本月，则结束时间为本月的当天；如果开始时间不是本月，则结束时间赋值为开始时间月份的最后一天
                        if (empty($end)) {
                            if (date("m", strtotime($start)) != date("m")) {
                                $firstday = date('Y-m-01', strtotime($start));
                                $end = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
                            }
                        }
                        if ($idEmail && !is_numeric($idEmail)) {
                            $user = $this->m_user->getUserByIdOrEmail($idEmail);
                            $email = $idEmail;
                            $idEmail = $user['id'];
                        }

                        if ($curComType) {
                            $filter['item_type'] = $curComType;
                        }
                        if ($start) {
                            $filter['start_time'] = $start;
                        }
                        if ($end) {
                            $filter['end_time'] = $end;
                        }
                        if ($idEmail) {
                            $filter['uid'] = $idEmail;
                        }
                        if ($pay_user_id) {
                            $filter['related_uid'] = $pay_user_id;
                        }
                        if ($order_id) {
                            $filter['order_id'] = $order_id;
                        }

                        $list = $this->o_cash_account->getCashAccountLogByPageNew($filter, $page, 'funds_change_report');
                        $this->_viewData['code'] = 1001;
                        $this->_viewData['list'] = $list;

                        $this->load->library('pagination');
                        $config['base_url'] = base_url('admin/commission_report?curComType=' . $curComType . '&start=' . $start . '&end=' . $end . '&idEmail=' . $url_data . '&pay_user_id=' . $pay_user_id . '&order_id=' . $order_id);
                        $config['total_rows'] = $this->o_cash_account->getCashAccountLogNumNew($filter);
                        $config['per_page'] = $perPage;
                        $config['cur_page'] = $page;
                        $this->pagination->initialize_ucenter($config);
                        $this->_viewData['pager'] = $this->pagination->create_links(true);
                    }
                }
            }
        }
        $this->_viewData['curComType'] = $curComType;
        $this->_viewData['start_time'] = $start;
        $this->_viewData['end_time'] = $end;
        $this->_viewData['idEmail'] = $url_data;
        $this->_viewData['pay_user_id'] = $pay_user_id;
        $this->_viewData['order_id'] = $order_id;
        $this->_viewData['commission_type'] = $this->config->item('funds_change_report');

        parent::index('admin/');
    }
    
}