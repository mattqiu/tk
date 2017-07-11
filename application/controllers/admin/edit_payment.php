<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Edit_payment extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
        $this->load->model('m_global');
    }

    public function index($id = NULL) {
        if($id !== NULL ){
            $payment = $this->m_global->getPaymentById($id);
            if(!$payment){
                redirect('admin/payment_list/');
            }
            if($this->m_global->table_name=='mall_payment_new'){
                $this->load->model('tb_users');
                $payment['pay_config']=$this->tb_users->AES_decryption($payment['pay_config']);
                $payment['pay_desc']=$this->tb_users->AES_decryption($payment['pay_desc']);
            }
            $this->_viewData['title'] = lang('edit_payment');
            $this->_viewData['payment'] = $payment;
            parent::index('admin/');
        }else{
            redirect('admin/payment_list/');
        }

    }

    public function do_edit(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            $is_enabled = $data['is_enabled'];
            $pay_id = $data['pay_id'];
            $modules['config'] =array();
            if($this->m_global->table_name=='mall_payment_new'){
                $pay_desc=$data['pay_desc'];
                unset($data['pay_desc']);
            }
            unset($data['is_enabled']);
            unset($data['pay_id']);
            foreach($data as $key=>$item){
                $config = array('name' =>$key,'type' =>'text','value' => trim($item));
                $modules['config'][] = $config;
            }
            if($this->m_global->table_name=='mall_payment_new'){
                $this->load->model('tb_users');
                $modules_config=$this->tb_users->AES_encryption(serialize($modules['config']));
                $pay_desc=$this->tb_users->AES_encryption($pay_desc);
            }
            $this->db->where('pay_id',$pay_id)->update($this->m_global->table_name,array('pay_desc'=>$pay_desc,'pay_config'=>$modules_config,'is_enabled'=>$is_enabled));
            echo json_encode(array('success'=>TRUE,'msg'=>lang('update_success')));exit;
        }
    }

}