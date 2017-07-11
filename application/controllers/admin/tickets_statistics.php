<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class tickets_statistics extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('tb_admin_tickets_record');
        $this->load->model('tb_admin_tickets_daily_count');
        $this->load->model('tb_admin_tickets');
    }

    public function index(){
        if($this->_viewData['curLanguage']=='zh' && check_right('tickets_statistics_right')){
            $this->_viewData['title']       = lang('tickets_statistics');
            $searchData                     = $this->input->post() ? $this->input->post() : array();
            $searchData['cus_id']           = isset($searchData['cus_id']) ? $searchData['cus_id'] : '';
            $searchData['start']            = isset($searchData['start']) ? $searchData['start'] : '';
            $searchData['end']              = isset($searchData['end']) ? $searchData['end'] : '';
            $this->_viewData['cus']         = $this->m_admin_user->get_customers();
            if($searchData['start'] || $searchData['end']){
                $this->_viewData['all_data']    = $this->get_daily_data($searchData);
                $this->_viewData['sign']        = 1;
                $this->_viewData['searchData']  = $searchData;
            }else{
                $this->_viewData['all_data']    = $this->tb_admin_tickets->get_tickets_statistics($searchData['cus_id'],'all',date('Y-m-d'));
                $this->_viewData['sign']        = 2;
                $this->_viewData['searchData']  = $searchData;
            }
            parent::index('admin/');
        }else{
            redirect('admin/my_tickets');
        }
    }

    private function get_daily_data($searchData){
        $today  = array();
        $date   = date('Y-m-d');
        $old    = $this->tb_admin_tickets_daily_count->get_daily_count_list($searchData);
        if($searchData['start']==$date || $searchData['end']==$date){
            $today  = $this->tb_admin_tickets->get_tickets_statistics($searchData['cus_id'],'',date('Y-m-d'));
        }
        if($old && $today){
            foreach($today as $item){
                foreach($old as &$v){
                    if($v['admin_id']==$item['admin_id']){
                        $v['d_in'] = $v['d_in']+$item['today_in'];
                        $v['d_out'] = $v['d_out']+$item['today_out'];
                        $v['d_count'] = $v['d_count']+$item['today_assign'];
                    }
                }
            }
        }
        return $old;
    }
}