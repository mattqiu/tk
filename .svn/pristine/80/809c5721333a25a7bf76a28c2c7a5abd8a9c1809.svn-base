<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class use_coupons_submit extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_coupons');
		$this->load->model('m_group');
	}

	public function index()
	{
		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect=use_coupons');
		}
		$uid=$this->_userInfo['id'];

		// 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();

		// 获取收货地址
        $this->load->model("tb_trade_user_address");
		$address_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);
		if (empty($address_list))
		{
			$page = "submit_coupons_new_address";
		}
		else
		{
			$page = "submit_coupons_address";
			$this->_viewData['address_list'] = $address_list;
		}

		// 获取商品列表
		$all_product = $this->m_group->get_all_product();
		$this->_viewData['all_product'] = $all_product;

		// 商品总价，固定美金结算
		$total_money = $this->m_coupons->get_product_total_money($all_product);
		$this->_viewData['total_money'] = $total_money;

		// 用户代品券信息：张数、总额
		$coupons_list = $this->m_coupons->get_coupons_list($uid);
		$this->_viewData['coupons_list'] = $coupons_list;

		// 实付金额 = 商品总价 - 代品券总额，固定美金结算
		$pay_amount = $total_money - $coupons_list['total_money'];
		if ($pay_amount > 0)
		{
			$this->_viewData['real_pay_money'] = $pay_amount;
		}
		else
		{
			$this->_viewData['real_pay_money'] = 0;
		}

		// 配送方式与费用（单位分）
		$deliver_info = array(
			'type' => "checkout_order_deliver_type_express",
			'fee' => 0,
		);
		$this->_viewData['deliver_info'] = $deliver_info;
		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');

		parent::index('mall/', $page, 'header1', 'footer1');
	}

	/**
	 * 验证提交订单
	 */
	public function do_checkout_order()
	{
		$ret_data = array(
			'code' => 0,
			'msg' => "",
			'id' => null,
		);

		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 103;
			$ret_data['msg'] = "user logout";
			echo json_encode($ret_data);
			exit;
		}
		$uid = $this->_userInfo['id'];

			$attr = $this->input->post();
		$rules = array(
			'customer_id' => "required|integer",
			'addr_id' => "required|integer",
			'deliver_time_type' => "required|in:1,2,3",
			'remark' => "max:128",
			'need_receipt' => "required|boolean",
			'goods_amount_usd' => "required|integer",
			'order_amount_usd' => "required|integer",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		$ret = $this->m_coupons->make_order($attr, $uid);
		if ($ret['code'] == 5)
		{
			$ret_data['code'] = 5;
			$ret_data['msg'] = lang('default_country_not_ok');
			echo json_encode($ret_data);
			exit;
		}
		else if ($ret['code'] == 10)
		{
			$ret_data['code'] = 10;
			$ret_data['msg'] = lang('order_submit_success');
			echo json_encode($ret_data);
			exit;
		}
		else if ($ret['code'] == 0)
		{
			$ret_data['id'] = $ret['id'];
			echo json_encode($ret_data);
			exit;
		}
		else
		{
			$ret_data['code'] = 103;
			$ret_data['msg'] = lang('try_again');
			echo json_encode($ret_data);
			exit;
		}
	}
}
