<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class use_coupons extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_group');
	}

	public function index()
	{
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect=use_coupons');
		}

		$this->m_group->del_cookie();

		$this->load->model('m_coupons');

		$this->_viewData['title'] = lang('coupons_redemption');
		$this->_viewData['keywords'] = lang('m_keywords');
		$this->_viewData['description'] = lang('m_description');
		$this->_viewData['canonical'] = base_url();

		$userInfo['id'] = $this->_userInfo['id'];
		$userInfo['user_rank'] = $this->_userInfo['user_rank'];
		$userInfo['name'] = $this->_userInfo['name'];

		// 选择收货国家
		$country_id = '';
		if (isset($_GET['param']) && !empty($_GET['param']))
		{
			$country_id = unserialize(base64_decode($_GET['param']));
			if ($this->m_goods->is_exist_country($country_id))
			{
				$is_show = FALSE;
			}
			else
			{
				$is_show = TRUE;
			}
		}
		else
		{
			$is_show = TRUE;
		}

		// 把选择的商品地址存入cookie
		set_cookie('sel_country', serialize($country_id), 0, get_public_domain());

		$this->_viewData['user_info'] = $userInfo;
		$this->_viewData['coupons_list'] = $this->m_coupons->get_coupons_list($userInfo['id']);
		$this->_viewData['coupons_info'] = $this->m_coupons->get_coupons_info($userInfo['id']);

		// 套装
		//$this->_viewData['group_list'] = $this->m_coupons->get_group_list($country_id);
		//leon 修改方法的调用
		$this->_viewData['group_list'] = $this->m_coupons->get_group_list_new($country_id);

		// 套装单品
		$this->_viewData['goods_list'] = $this->m_coupons->get_goods_list($country_id);

		// 获得國家數據
		$this->_viewData['countrys'] = $this->m_goods->get_sale_country();

		// 是否显示弹层 选择国家
		$this->_viewData['is_show'] = $is_show;

		// 选择国家
		$this->_viewData['country_id'] = $country_id;

		parent::index('mall/', 'use_coupons');
	}

	/**
	 * 点击确认换购
	 */
	public function confirm_choose_coupons()
	{
		// 提交时检查库存
		$all_product = $this->m_group->get_all_product();
		$goods_list = $this->m_group->get_goods_list($all_product);
		$this->m_group->check_stock($goods_list);

		$redirect_url = "/use_coupons_submit";
		echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
		exit();
	}
}
