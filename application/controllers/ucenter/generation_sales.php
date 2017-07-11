<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generation_sales extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_generation_sales', 'm_generation_sales');
        $this->load->model('M_overrides', 'm_overrides');
        $this->load->model('M_order','m_order');
        $this->lang->load('level');
    }

    public function index(){

        $data =  $this->input->get();
        $page = (int)$this->input->get('page')?(int)$this->input->get('page'):1;
        $filter['start_time'] = strtotime(trim($data['start']));
        $filter['end_time'] = strtotime(trim($data['end']));
        $perPage = 10;
        $uid = $this->_userInfo['id'];

        $algebra = $this->m_overrides->generation($uid,$this->_userInfo);
        $this->_viewData['QRCs'] = $this->m_order->getOrderCount($uid);
        $this->_viewData['QSOs'] = $this->m_overrides->generalMemberCount($uid);
        $this->_viewData['algebra'] = $algebra;
        if($algebra == LEVEL_GOLD_GENERATION && $this->_userInfo['user_rank'] <= LEVEL_GOLD){
            $this->_viewData['algebra'] = lang('enjoy_gold');
        }
        if($algebra == LEVEL_DIAMOND_GENERATION && $this->_userInfo['user_rank'] == LEVEL_DIAMOND){
            $this->_viewData['algebra'] = lang('enjoy_diamond');;
        }

        $this->_viewData['level'] = $this->m_user->getUserRank($uid);

        $this->_viewData['title'] = lang('generation_sales');
        $this->_viewData['start_time'] = $data['start'];
        $this->_viewData['end_time'] = $data['end'];
        $this->_viewData['generationLogs'] = $this->m_generation_sales->getSalesLogs($uid,$page,$perPage,$filter);

        $this->load->library('pagination');
        $config['base_url'] = base_url('ucenter/generation_sales?start='. $data['start'].'&end='. $data['end']);
        $config['total_rows'] = $this->m_generation_sales->getSalesTotalRows($uid,$filter);
        $config['per_page'] = $perPage;
        $config['cur_page'] = $page;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index();
    }

}

