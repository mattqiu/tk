<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class commission_month extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('commission_month',$this->_adminInfo);
    }

  
    public function index() {
        $this->_viewData['title'] = lang('commission_month');
        $this->load->model("tb_cash_account_log_x");
        $getData = $this->input->get();
        $search_arr = Array();
        $search_arr["id"] = $id = isset($getData['id']) ? trim($getData['id']) : '';
        $search_arr["year"] = $year = isset($getData['year']) ? (int)$getData['year'] : (int)date('Y');
        $search_arr["month"] = $month = isset($getData['month']) ? (int)$getData['month'] : (int)date('m');
        $this->_viewData['search_arr'] = $search_arr;
        //用户不存在 不允许查询
        if(!$_GET){
           $this->_viewData['code'] = 1002;
        }else{
            if(empty($id)){
                $this->_viewData['code'] = 1003;
            }else{
                $this->load->model('m_user');
                $user = $this->m_user->getUserByIdOrEmail($id);
                if (!isset($user["id"])) {
                    $this->_viewData['code'] = 1004;
                } else {
                    $time = date("Ym",strtotime($year."-".$month));
                    if($time <= date("Ym")){
                        $start_time = date("Y-m-01 00:00:00",strtotime($year."-".$month));
                        $end_time = date("Y-m-31 23:59:59",strtotime($year."-".$month));
                       //需要统计的类型
                        $item_str = '5,3,6,1,8,2,7,23,4,24,25,26';
                        $item_arr = explode(',',$item_str);
                        //m by brady.wang 2017/05/11 START
                        if($time > 201606){
                            $comm_logs = $this->tb_cash_account_log_x-> get_sum_bonus_by_item($user["id"],"item_type,SUM(amount)/100 as sum_amount",$time,$item_arr );
                        }else{
                            $comm_logs = $this->tb_cash_account_log_x-> get_sum_bonus_by_item_201606($user["id"],"type as item_type,SUM(amount) as sum_amount",$time,$item_arr );
                        }
                        //m by brady.wang 2017/05/11 END
                        if(isset($comm_logs) && $comm_logs){
                            foreach($comm_logs as $commlog){
                                $commission_item_arr[$commlog["item_type"]] =  tps_money_format($commlog['sum_amount']);
                            }
                        }
                    }
                }
            }
        }
        $this->_viewData['commission_item'] = isset($commission_item_arr) ? $commission_item_arr : '';
        parent::index('admin/');
    }
}
