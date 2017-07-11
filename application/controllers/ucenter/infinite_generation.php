<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Infinite_generation extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_generation_sales', 'm_generation_sales');
    }

    public function index(){

        $uid = $this->_userInfo['id'];
        $data =  $this->input->get();

        $page = (int)$this->input->get('page')?(int)$this->input->get('page'):1;
        $filter['start_time'] = strtotime(trim($data['start']));
        $filter['end_time'] = strtotime(trim($data['end']));
        $perPage = 10;
        $this->_viewData['title'] =  lang('infinity');
        $this->_viewData['start_time'] = $data['start'];
        $this->_viewData['end_time'] = $data['end'];
        $this->_viewData['logs'] = $this->m_generation_sales->getInfinityLogs($uid,$page,$perPage,$filter);

        $this->load->library('pagination');
        $config['base_url'] = base_url('ucenter/infinite_generation?start='. $data['start'].'&end='. $data['end']);
        $config['total_rows'] = $this->m_generation_sales->getInfinityTotalRows($uid,$filter);
        $config['per_page'] = $perPage;
        $config['cur_page'] = $page;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index();
    }
}
