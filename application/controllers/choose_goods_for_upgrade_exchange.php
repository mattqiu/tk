<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class choose_goods_for_upgrade_exchange extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $this->load->model('m_goods');
        $this->load->model('m_coupons');
        $this->load->model('m_user');

        $this->load->model('tb_mall_goods_main');
    }

    public function index()
    {
        //cookie 存储的升级信息
//        $product_set = isset($_COOKIE['product_set'])?unserialize($_COOKIE['product_set']):array();
        $exchange = isset($_COOKIE['exchange'])?unserialize($_COOKIE['exchange']):array();
        //未登录重定向
        if(empty($this->_userInfo))
        {
            redirect(base_url('login') . '?redirect = choose_goods_for_upgrade');
        }

        //只能通过后台访问
        if(empty($exchange))
        {
            redirect('ucenter/my_other_orders');
        }

        //刷新页面,删除cookie
        $this->m_group->del_cookie();

        $this->_viewData['title'] = lang('m_title') . ' - ' . lang('choose_goods');
        $this->_viewData['keywords'] = lang('m_keywords');
        $this->_viewData['description'] = lang('m_description');
        $this->_viewData['canonical'] = base_url();

        $user_id = $this->_userInfo['id'];

//        $this->_viewData['data'] = $this->m_group->show_info_upgrade($user_id,$product_set);
        $this->_viewData['data'] = $exchange;

        //所选区域ID
        $country_id = $this->session->userdata('location_id');

        /**
         * leon
         * 新增搜索内容
         */
        $data_get = $this->input->get();
        $search   = trim($data_get['search']);//去空
        $search   = htmlspecialchars_detection($search);//转换html内容为字符串
        $condition_data=array();
        $str_info=string_detection($search);//过滤字符串内容
        if(isset($search)){
            $condition_data=array('search'=>$search);
        }
        $package_data = $this->tb_mall_goods_main->get_all_goods_group_data($country_id,$condition_data);//套餐数据

        if($str_info['code'] && !empty($package_data)){
            $this->_viewData['group_search_hint'] = 1;
            $this->_viewData['group_search'] = $data_get['search'];//要搜索的内容
            //套装数据
            $this->_viewData['group_info'] = $this->tb_mall_goods_main->get_useable_goods_group_data($package_data);
            //单品数据
            $this->_viewData['goods_info'] = $this->tb_mall_goods_main->get_useable_group_goods_data($package_data,$condition_data);
        }else{
            $this->_viewData['group_search_hint'] = 2;
            $this->_viewData['group_search'] = $data_get['search'];//要搜索的内容
            //套装数据
            $this->_viewData['group_info'] = array();
            //单品数据
            $this->_viewData['goods_info'] = array();
        }

        parent::index('mall/', 'choose_goods_for_upgrade_exchange', $header = 'header');
    }


    /***点击确认换购***/
    public function confirm_choose()
    {
        //要换的订单信息
        $exchange = isset($_COOKIE['exchange'])?unserialize($_COOKIE['exchange']):array();

        $all_product = $this->m_group->get_all_product();

        //所选购商品的总价
        $total_money = $this->m_group->get_product_total_money($all_product);

        //所选商品金额小于原来的金额，不能提交
        if ($total_money < $exchange['order_money']) {
            echo json_encode(array('success' => false, 'msg' => lang('user_upgrade_not_lower')));
            exit();
        }

        $redirect_url = "/choose_goods_for_exchange_checkout";

        echo json_encode(array('success' => true, 'code' => '', 'redirect_url' => $redirect_url));
        exit();
    }

	/** 得到产品的介绍图 */
	public function get_goods_info(){
		if($this->input->is_ajax_request()){
			$goods_id = $this->input->post('goods_id');
			$goods_sn_main = $this->input->post('goods_sn_main');

			$data = $this->m_goods->get_goods_info_by_id($goods_id,$goods_sn_main,'https://img.tps138.net/');
			//if($data['goods_desc']){
				die(json_encode(array('success'=>1,'data'=>$data)));
			//}else{
				//die(json_encode(array('success'=>0)));
			//}
		}
	}
}


