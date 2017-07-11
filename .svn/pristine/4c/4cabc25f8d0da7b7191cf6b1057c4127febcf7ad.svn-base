<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Bulletin_board_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('m_news');
        $this->_viewData['title'] = lang('bulletin_board_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['idEmail'] = isset($searchData['idEmail'])?$searchData['idEmail']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $lists = $this->m_news->getBoardList($searchData,0,10,'all');
        /*
        foreach($lists as &$list){
            $list['hk'] = mb_substr(htmlspecialchars($list['hk']), 0, 50, 'utf-8').'...';
            $list['zh'] = mb_substr(htmlspecialchars($list['zh']), 0, 50, 'utf-8').'...';
            $list['kr'] = mb_substr(htmlspecialchars($list['kr']), 0, 50, 'utf-8').'...';
            $list['english'] = mb_substr(htmlspecialchars($list['english']), 0, 80, 'utf-8').'...';
        }
        */
        
        foreach($lists as &$list){
            $list['hk'] = mb_substr(($list['title_hk']), 0, 80, 'utf-8').'...';
            $list['zh'] = mb_substr(($list['title_zh']), 0, 80, 'utf-8').'...';
            $list['kr'] = mb_substr(($list['title_kr']), 0, 80, 'utf-8').'...';
            $list['english'] = mb_substr(($list['title_english']), 0, 80, 'utf-8').'...';
        }
        
        $this->_viewData['list'] = $lists;

        $this->load->library('pagination');
        $url = 'admin/bulletin_board_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_news->getBoardListRows($searchData,0);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

}