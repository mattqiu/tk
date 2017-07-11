<?php
/**
 * author:andy
 */
class mvp_live_order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_mvp_list');
    }

    public function index(){
        $this->_viewData['title'] = lang('mvp_live_list');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['luyan_account'] = isset($searchData['luyan_account'])?$searchData['luyan_account']:'';
        $searchData['payment_type'] = isset($searchData['payment_type'])?$searchData['payment_type']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        $this->_viewData['list'] =$this->tb_mvp_list->get_mvp_live_list($searchData);
        $url = 'admin/mvp_live_order';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_mvp_list->get_mvp_live_list_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['payment_map'] = array(
            0 => lang('admin_order_payment_unpay'),
            1 => lang('admin_order_payment_group'),
            2 => lang('admin_order_payment_coupon'),
            105 => lang('admin_order_payment_alipay'),
            106 => lang('admin_order_payment_unionpay'),
            107 => lang('admin_order_payment_paypal'),
            108 => lang('admin_order_payment_ewallet'),
            109 => lang('admin_order_payment_yspay'),
            110 => lang('admin_order_payment_amount'),
            104 => 'WxPay(微信支付)',
            111 => lang('payment_111'),
        );

        parent::index('admin/','mvp_live_list');
    }
}