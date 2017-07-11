<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Reprocess_order extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index() {
		$this->_viewData['title'] = lang('order_sn');
        parent::index('admin/');
    }


    
    public function do_reprocess_order(){

        $data = $this->input->post();

		if(trim($data['order_id'])==''){
			die(json_encode(array('success'=>false,'msg'=>lang('order_id_not_null'))));
		}
		if(trim($data['order_id'])!=trim($data['confirm_order_id'])){
			die(json_encode(array('success'=>false,'msg'=>'no match')));
		}
		$this->load->model('m_helper');
		$success = $this->m_helper->processOrder(trim($data['order_id']));
		if($success){
			die(json_encode(array('success' => $success, 'msg' => 'Process success')));
		}else{
			die(json_encode(array('success' => $success, 'msg' => 'Process failure')));
		}

    }

}