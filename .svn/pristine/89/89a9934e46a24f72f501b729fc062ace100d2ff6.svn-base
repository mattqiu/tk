<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class choose_goods_for_upgrade
 * author leon
 * 店铺升级选择产品 控制器
 */
class choose_goods_for_upgrade extends MY_Controller
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

    public function index(){

        //cookie 存储的升级信息
        $product_set = isset($_COOKIE['product_set'])?unserialize($_COOKIE['product_set']):array();

        //未登录重定向
        if(empty($this->_userInfo)) {
            redirect(base_url('login') . '?redirect = choose_goods_for_upgrade');
        }

        //只能通过后台访问
        if(empty($product_set)) {
            redirect('ucenter/welcome_new');
        }

        //刷新页面,删除cookie
        //$this->m_group->del_cookie();   //2017-06-30  leon  注释掉这个内容

        $this->_viewData['title'] = lang('m_title') . ' - ' . lang('choose_goods');
        $this->_viewData['keywords'] = lang('m_keywords');
        $this->_viewData['description'] = lang('m_description');
        $this->_viewData['canonical'] = base_url();

        $user_id = $this->_userInfo['id'];

        //显示个人信息
        $this->_viewData['data'] = $this->m_group->show_info_upgrade($user_id,$product_set);

        //所选区域ID
        $country_id = $this->session->userdata('location_id');

        //新增搜索内容 --- leon
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

        parent::index('mall/', 'choose_goods_for_upgrade', $header = 'header');
    }


    /***点击确认换购***/
    public function confirm_choose()
    {
        $all_product = $this->m_group->get_all_product();

        //必须选择一种套装
//        if ($all_product['group'] == array()) {
//            echo json_encode(array('success' => false, 'msg' => lang('must_choose_a_product')));
//            exit();
//        }

        //升级的费用
        $pay_money = $this->m_group->get_upgrade_money($this->_userInfo['user_rank'], $this->_userInfo['id']);
        //所选购商品的总价
        $total_money = $this->m_group->get_product_total_money($all_product);

        //2017-03-01 00:00:00 后升级订单不在发放代品券
        if (date('Y-m-d H:i:s', time()) >= config_item('upgrade_not_coupon')) {
            //算更高一级的费用
            $joinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
            $product_set = isset($_COOKIE['product_set']) ? unserialize($_COOKIE['product_set']) : array();
            if (empty($product_set)) {
                redirect(base_url('/'));
                exit;
            }

            if ($product_set['level'] == 5) {
                $product_set_mon = config_item('join_fee_and_month_fee')[3]['join_fee'];
            } elseif ($product_set['level'] == 3) {
                $product_set_mon = config_item('join_fee_and_month_fee')[2]['join_fee'];
            } elseif ($product_set['level'] == 2) {
                $product_set_mon = config_item('join_fee_and_month_fee')[1]['join_fee'];
            } elseif ($product_set['level'] == 1) {
                $product_set_mon = 10000;
            }
            $pay_money_1 = $product_set_mon - $joinFeeAndMonthFee[$this->_userInfo['user_rank']]['join_fee'];

            //验证金额是否一致,支付金额小于升级费用，不给提交
            if ($total_money < $pay_money) {
                echo json_encode(array('success' => false, 'msg' => lang('user_upgrade_not_coupon_1')));
                exit();
            }

            //达到更高等级的费用，不给提交
            if ($total_money >= $pay_money_1) {
                echo json_encode(array('success' => false, 'msg' => lang('user_upgrade_not_coupon_2')));
                exit();
            }
        } else {
            //验证金额是否一致
            if ($total_money != $pay_money) {
                echo json_encode(array('success' => false, 'msg' => lang('price_not_same')));
                exit();
            }
        }
        $redirect_url = "/choose_goods_for_upgrade_checkout";

        //2017-03-01 00:00:00 后升级订单不在发放代品券
        if (date('Y-m-d H:i:s', time()) >= config_item('upgrade_not_coupon')) {
            //大于升级费用，小于更高一级的费用，给出提示，确认后允许提交
            if ($total_money > $pay_money && $total_money < $pay_money_1) {
                echo json_encode(array('success' => true, 'code' => '101','redirect_url' => $redirect_url));
                exit();
            }else{
                echo json_encode(array('success' => true, 'code' => '', 'redirect_url' => $redirect_url));
                exit();
            }
        } else {
            echo json_encode(array('success' => true, 'code' => '', 'redirect_url' => $redirect_url));
            exit();
        }
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


