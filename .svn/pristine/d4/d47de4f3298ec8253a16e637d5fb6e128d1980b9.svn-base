<?php
/** 
 *　商品套餐管理
 * @date: 2015-8-3 
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Goods_group extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('m_goods');
	}

	public function index($id = NULL) {
		$this->_viewData['title'] = lang('label_goods_group_add');
		$this->_viewData['is_edit'] = 0;
		
		
		$data=array();
		if($id){
			$data = $this->m_goods->get_goods_group($id);			
			
			$this->_viewData['title'] = lang('label_goods_group_edit');			
			$this->_viewData['is_edit'] = 1;
		}
		$this->_viewData['data'] = $data;		
		
		parent::index('admin/','goods_gorup_form');
	}
	
	/* 搜索商品  */
	function search_goods() {
        $keywords=trim($this->input->get('keyword'));

        $rs=$this->m_goods->get_goods_list_page(array('keywords'=>$keywords,'page'=>1,'is_alone_sale'=>1,'is_on_sale'=>1));

        exit(json_encode($rs));
	}
	
	public function do_add(){
		$is_edit = $this->input->get('is_edit');
		$id = $this->input->get('id');
		$ids = $this->input->get('ids');
		$ids=trim($ids,'|');		
        
		if($this->input->is_ajax_request() && $ids) {
    		if(empty($is_edit)) {
    			$status=$this->m_goods->add_goods_group($ids);
    		}else {
    			
    			$status=$this->m_goods->update_goods_group($ids,$id);
    		}
    		
    		if($status) {
    			exit('ok');
    		}
		}
		echo $this->db->last_query();
		exit('failed');
	}
	
	/* 套餐列表 */
	public function goods_group_list() {
		$this->load->library('pagination');	
		
		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?intval($searchData['keywords']):'';

		$this->_viewData['searchData'] = $searchData;
		
		/* 分页数据 */
		$rs=$this->m_goods->get_goods_group_list_page($searchData);

		$list=array();
		foreach($rs as $k=>$v) {
			$list[$k]['group_id']=$v['group_id'];
			$list[$k]['goods']=$this->m_goods->get_goods_group_info_admin($v['group_id']);
		}
		
		$this->_viewData['list'] = $list;
		$this->_viewData['title'] = lang('goods_group_list');
		
		$url = '/admin/goods_group/goods_group_list';
		add_params_to_url($url, $searchData);
		
		/* Pager */
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['total_rows'] = $this->m_goods->get_goods_group_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);
		
		parent::index('admin/','goods_group_list');
	}
}

