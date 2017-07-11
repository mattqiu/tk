<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commission_report extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_commission');
    }

    public function index() {
        
        //韩国会员隐藏历史月份各项佣金统计  JacksonZheng 2017-05-19
        $country_id = $this->_userInfo['country_id'];
        $this->_viewData['korea_hide'] = false;
        if($country_id == 3) {
           $this->_viewData['korea_hide'] = true;
        }
         
        $this->_viewData['title'] = lang('commission_report');
        $getData = $this->input->get();
        
        $search_arr = Array();
        $search_arr["id"] = $uid = $this->_userInfo['id'];
        $search_arr["year"] = $year = isset($getData['year']) ? (int)$getData['year'] : "";
        $search_arr["month"] = $month = isset($getData['month']) ? (int)$getData['month'] : "";
        $this->_viewData['search_arr'] = $search_arr;
        
        if(isset($getData['year'])) {
            $commission_item_arr = array();
            $search = array();
             $time = date("Ym",strtotime($year."-".$month));
             
             if($time <= date("Ym")){
                        $start_time = date("Y-m-01 00:00:00",strtotime($year."-".$month));
                        $end_time = date("Y-m-31 23:59:59",strtotime($year."-".$month));
                       //需要统计的类型
                        $item_str = '1,2,3,4,5,6,7,8,23,24,25,26,27';
                        $search['item_str'] = $item_str;
                        $search['uid'] = $uid;
                        $search['search_month'] = $time;
                        $search['start_time'] = $start_time;
                        $search['end_time'] = $end_time;
                        if($time > 201606){
                            if($time == date("Ym")) {   //查询当月
                                $comm_logs = $this->m_commission->getCurMonthEachComm($this->_userInfo['id']);
                            } else {
                                $comm_logs = $this->m_commission->getHistoryMonthEachComm($search);
                            }

                        }else{
                            $search['commission_logs'] = true;  //查询commission_logs旧表
                            $comm_logs = $this->m_commission->getHistoryMonthEachComm($search);
                        }
                        
                        if(!empty($comm_logs)) {
                            
                            if(isset($search['commission_logs'])) {   
                                foreach($comm_logs as $key=>$val){
                                    $commission_item_arr[$key] = $val;  //amount字段是decimal类型
                                }
                                
                            } else {
                                foreach($comm_logs as $key=>$val){
                                    $commission_item_arr[$key] = tps_money_format($val/100);  //amount字段是int类型
                                }
                            }
                        }
            }
            $this->_viewData['commission_item'] = $commission_item_arr;     
            
        }

         /*会员当月各项佣金*/
        $commData = $this->m_commission->getCurMonthEachComm($this->_userInfo['id']);
        $this->_viewData['commData']  = $commData;
        
        /*当日，当月佣金 */
        $this->_viewData['today_commission'] =  $this->m_commission->getTodayComm($this->_userInfo['id']);
        $this->_viewData['current_month_commission']  = !empty($commData)?tps_money_format(array_sum($commData)/100):0;

        parent::index();
      
    }

}