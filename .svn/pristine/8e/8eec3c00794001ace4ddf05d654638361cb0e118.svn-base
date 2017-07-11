<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Payment_list extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('payment_list',$this->_adminInfo);
        $this->load->model('m_admin_helper');
    }

    public function index() {

        $this->_viewData['title'] = lang('payment_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->getPaymentList($searchData);

        $this->load->library('pagination');
        $url = 'admin/payment_list';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getPaymentRows();
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }
    /*
     * 获取新的密钥对支付配置重新加密
     * 2017/0512   RongXia
     */
    public function Reappear_encryption() {
        $this->load->model('tb_users');
        $data=$this->db->get('mall_payment_new')->result_array();
        foreach ($data as $key => $value) {
            $data[$key]['pay_config']=$this->tb_users->AES_encryption($value['pay_config']);
            $data[$key]['pay_desc']=$this->tb_users->AES_encryption($value['pay_desc']);
        }
        $is=$this->db->update_batch('mall_payment_new',$data,'pay_id');
        echo json_encode(array('success' => TRUE));
    }

}