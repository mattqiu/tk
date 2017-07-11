<?php
/** 
 *　品牌管理
 * @date: 2015-8-3 
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Brand extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('m_goods');
	}

	public function index($id = NULL) {
		$this->_viewData['title'] = lang('label_brand_add');
		$this->_viewData['is_edit'] = 0;
		
		$this->_viewData['lang_all']=$this->m_global->getLangList();
		
		//获取所有分类
		$this->_viewData['category_all']=$this->m_goods->get_all_category();		
		
		$data=array();
		if($id){
			$data = $this->m_goods->get_brand($id);			
			
			$this->_viewData['title'] = lang('edit_goods');			
			$this->_viewData['is_edit'] = 1;
		}
		
		$this->_viewData['data'] = $data;
		
		parent::index('admin/','brand_form');
	}
	
	public function do_add(){
		$is_edit = $this->input->post('is_edit');
		$brand_id= intval($this->input->post('brand_id'));
		$language_id = intval($this->input->post('language_id'));
		$cate_id = intval($this->input->post('cate_id'));
		$brand_name = trim($this->input->post('brand_name'));				

		if(empty($language_id) || empty($cate_id) || empty($brand_name) ) {
                   echo json_encode(array('error'=>1,'msg'=>lang('info_failed'),'data'=>'empty'));
		}else {
	           $data_db=array('brand_name'=>$brand_name,'language_id'=>$language_id,'cate_id'=>$cate_id);
		     if(empty($is_edit)) {
		          $status=$this->m_goods->add_brand($data_db);
                  echo json_encode($status);
		      }else{
                 $data_db['brand_id'] = $brand_id;
			     $status=$this->m_goods->update_brand($data_db);
                 echo json_encode($status);
		      }
		}
	}
	
	/* 品牌列表 */
	public function brand_list() {
		//获取所有分类
		$this->_viewData['category_all']=$this->m_goods->get_all_category();
		
		$this->_viewData['lang_all']=$this->m_global->getLangList();
		
		$this->load->library('pagination');		
		
		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		
		$this->_viewData['searchData'] = $searchData;
		
		/* 分页数据 */
		$this->_viewData['list'] = $this->m_goods->get_brand_list_page($searchData,25);
		$this->_viewData['title'] = lang('label_brand_list_m');

		$url = 'admin/brand/brand_list';
		add_params_to_url($url, $searchData);
		
		/* Pager */
		$config['per_page'] = 25;
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['total_rows'] = $this->m_goods->get_brand_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);
	
		parent::index('admin/','brand_list');
	}
}

