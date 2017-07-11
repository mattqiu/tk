<?php
/** 
 *　问题反馈
 * @date: 2015-9-6
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Feedback extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('m_goods');
	}
	
	/* 供应商列表  */
	public function feedback_list() {
	
		$this->load->library('pagination');
	
		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		
		$this->_viewData['searchData'] = $searchData;
	
		/* 分页数据 */
		$this->_viewData['list'] = $this->m_goods->get_feedback_list_page($searchData);
		$this->_viewData['title'] = lang('label_feedback');
	
		$url = 'admin/feedback/feedback_list';
		add_params_to_url($url, $searchData);
	
		/* Pager */
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['total_rows'] = $this->m_goods->get_feedback_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);
	
		parent::index('admin/','feedback_list');
	}
	
	/* 改变状态 */
	function chang_state() {
	    $id=intval($this->input->get('id'));
	    
	    if($id && $this->input->is_ajax_request()) {
	        if($this->m_goods->chang_feedback_state($id)) {
	            exit('ok');
	        }
	    }
	    
	    exit('failed');
	}
	
}


