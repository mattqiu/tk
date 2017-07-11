<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class News_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_news');
    }

    public function index() {

        $this->_viewData['title'] = lang('news_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['cate_id'] = isset($searchData['cate_id'])?$searchData['cate_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_news->getNewsList($searchData);

        $this->load->library('pagination');
        $url = 'admin/news_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_news->getNewsListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

		//获取新闻种类
		$this->_viewData['type_all']=$this->m_news->get_news_type();

        parent::index('admin/');
    }

}