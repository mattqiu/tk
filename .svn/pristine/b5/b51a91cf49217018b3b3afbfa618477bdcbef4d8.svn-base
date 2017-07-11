<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Storehouse_to_supplier extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
        //$this->m_global->checkPermission('delete_users',$this->_adminInfo);
    }

    public function index($id = NULL) {
        $this->_viewData['title'] = lang('admin_supplier_store_code');
		/* 获取仓库  */
		$this->_viewData['store_all']=$this->m_global->getStoreList();

		//所有供应商
		$this->_viewData['supplier_all']=$this->m_goods->get_supplier_list();

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['store_code'] = isset($searchData['store_code'])?$searchData['store_code']:"";
		$searchData['supplier_id'] = isset($searchData['supplier_id'])?$searchData['supplier_id']:'';
        $this->_viewData['list'] = $this->m_admin_helper->getStorehouseList($searchData);

        $this->load->library('pagination');
        $url = 'admin/storehouse_to_supplier';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->geStorehouseRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

	//更改对应的供应商
	public function update_supplier(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			$this->db->where('store_code',$data['store_code'])->update('mall_goods_storehouse',array('supplier_id'=>$data['supplier_id']));
			die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
		}
	}

}