<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class choose_goods_for_coupons
 * author leon
 * 代品卷换购 控制器
 */
class choose_goods_for_coupons extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_group');
        $this->load->model('m_coupons');

		$this->load->model('tb_mall_goods_main');
	}

	public function index()
	{
        //未登录重定向
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect = choose_goods_for_coupons');
		}

        //刷新页面,删除cookie
		//$this->m_group->del_cookie();

		$this->_viewData['title'] = lang('coupons_redemption');
		$this->_viewData['keywords'] = lang('m_keywords');
		$this->_viewData['description'] = lang('m_description');
		$this->_viewData['canonical'] = base_url();

		$userInfo['id'] = $this->_userInfo['id'];
		$userInfo['user_rank'] = $this->_userInfo['user_rank'];
		$userInfo['name'] = $this->_userInfo['name'];

        //显示代品券信息
		$coupons_list = $this->m_coupons->get_coupons_list($userInfo['id']);
        $this->_viewData['user_info'] = $userInfo;
        $this->_viewData['coupons_list'] = $coupons_list;
        $this->_viewData['coupons_info'] = $this->m_coupons->get_coupons_info($userInfo['id']);


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
			$this->_viewData['group_list'] = $this->tb_mall_goods_main->get_useable_goods_group_data($package_data);
			//单品数据
			$this->_viewData['goods_list'] = $this->tb_mall_goods_main->get_useable_group_goods_data($package_data,$condition_data);
		}else{
			$this->_viewData['group_search_hint'] = 2;
			$this->_viewData['group_search'] = $data_get['search'];//要搜索的内容
			//套装数据
			$this->_viewData['group_list'] = array();
			//单品数据
			$this->_viewData['goods_list'] = array();
		}

		// 套装数据
		//$this->_viewData['group_list'] = array_values($this->m_coupons->get_group_list($country_id));
		//leon 修改方法的调用
        //$this->_viewData['group_list'] = array_values($this->m_coupons->get_group_list_new($country_id));
		// 单品数据
		//$this->_viewData['goods_list'] = array_values($this->m_coupons->get_goods_list($country_id));

		parent::index('mall/', 'choose_goods_for_coupons',$header = 'header');
	}

	/**
	 * 点击确认换购
	 */
	public function confirm_choose_coupons()
	{
		// 提交时检查商品
		$all_product = $this->m_group->get_all_product();

        if($all_product['group'] == array() && $all_product['goods'] == array())
        {
            echo json_encode(array('success' => false, 'msg'=>lang('you_not_choose_product')));
            exit;
        }


        //显示代品券信息
        $coupons_list = $this->m_coupons->get_coupons_list($this->_userInfo['id']);
        //会员没有代品券
        if($coupons_list['total_money'] <= 0){
//            echo '您的账户中代品劵为0，不允许提交代品劵订单。请您重新确认您需要提交的订单类型。';
            echo json_encode(array('success' => false, 'msg'=>lang('you_not_coupons')));
            exit;
        }


		$redirect_url = "/choose_goods_for_coupons_checkout";
		echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
		exit();
	}
}
