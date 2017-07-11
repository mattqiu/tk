<?php
/**
 *　供应商管理
 * @date: 2015-9-2
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Supplier extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('m_goods');
	}

	/* 增加或编辑商品 */
	public function index($supplier_id = NULL) {

		// 禁止新增供应商
		redirect(base_url('admin'));

		$this->_viewData['title'] = lang('label_supplier_add');
		$this->_viewData['is_edit'] = 0;

		$data=array();
		if($supplier_id){
			$data = $this->m_goods->get_supplier($supplier_id);

			$this->_viewData['title'] = lang('label_supplier_edit');
			$this->_viewData['is_edit'] = 1;
		}
		$this->_viewData['data'] = $data;

		parent::index('admin/','supplier_form');
	}

	/* 增加或编辑AJAX提交 */
	public function do_add(){

		echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
		exit;

		$is_edit = $this->input->post('is_edit');
		$data = $this->input->post();

		foreach($data as $k=>$v) {
		    $data[$k]=htmlspecialchars($v);
		}

		if(!empty($data['supplier_password'])) {
		    $data['supplier_password']=md5($data['supplier_password']);
		}else {
		    unset($data['supplier_password']);
		}

		//检查该供应商是否已经存在
		if(!$is_edit && $this->m_goods->supplier_check($data['supplier_name'])) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_supplier_exist')));
		    exit;
		}

		//检查该供应商用户名是否已经存在
		if(!$is_edit && !empty($data['supplier_username']) && $this->m_goods->get_supplier_username($data['supplier_username'])) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_supplier_username_exist')));
		    exit;
		}

		$status=1;
		if(empty($data['supplier_name']) || empty($data['supplier_user']) || empty($data['supplier_phone']) || empty($data['supplier_address'])) {
			$status=0;
		}

		if($status) {

			if(empty($is_edit)) {
				$status=$this->m_goods->add_supplier($data);
			}else {

				$status=$this->m_goods->update_supplier($data);
			}

		}

		if($status) {
			echo json_encode(array('error'=>0,'msg'=>lang('info_success')));
		}else {
			echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
		}

		exit;
	}

	/* 供应商列表  */
	public function supplier_list() {

		// 禁止修改供应商
		redirect(base_url('admin'));

		$this->load->library('pagination');

		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';

		$this->_viewData['searchData'] = $searchData;

		/* 分页数据 */
		$this->_viewData['list'] = $this->m_goods->get_supplier_list_page($searchData,50);
		$this->_viewData['title'] = lang('label_supplier');

		$url = 'admin/supplier/supplier_list';
		add_params_to_url($url, $searchData);

		/* Pager */
		$config['per_page'] = 50;
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['total_rows'] = $this->m_goods->get_supplier_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();

		parent::index('admin/','supplier_list');
	}

}

