<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class submit_order extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_group');
	}

	public function index()
	{
		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect=submit_order');
		}
		$uid = $this->_userInfo['id'];

		// 生成提交 token
		set_cookie("submit_token_upgrade", md5("tpssubmittoken{$uid}"), 86400 * 30, get_public_domain(), '/');

		// 头部信息
		$this->_viewData['title'] = lang('m_title');
		$this->_viewData['keywords'] = lang('m_keywords');
		$this->_viewData['description'] = lang('m_description');
		$this->_viewData['canonical'] = base_url();

		// 获取选购的商品
		$all_product = $this->m_group->get_all_product();
		$this->_viewData['all_product'] = $all_product;

		// 获取收货地址
        $this->load->model("tb_trade_user_address");
		$address_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);
		if (empty($address_list))
		{
			$page = "submit_order_new_address";
		}
		else
		{
			$page = "submit_order_address";
			$this->_viewData['address_list'] = $address_list;
		}

		// 获得选购商品的总金额
		$total_money = $this->m_group->get_product_total_money($all_product);
		$this->_viewData['total_money'] = $total_money;

		// 实际支付金额
		$this->_viewData['real_pay_money'] = $this->m_group->get_upgrade_money($this->_userInfo['user_rank'],$this->_userInfo['id']);

		// 配送方式与费用（单位分）
		$deliver_info = array(
			'type' => "checkout_order_deliver_type_express",
			'fee' => 0,
		);
		$this->_viewData['deliver_info'] = $deliver_info;
		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');

		parent::index('mall/', $page, 'header1', 'footer1');
	}

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
			'customer_id' => "required|in:{$uid}",
			'addr_id' => "required|integer",
			'deliver_time_type' => "required|in:1,2,3",
			'remark' => "max:128",
			'need_receipt' => "required|boolean",
			'order_amount_usd' => "required|integer",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		$ret = $this->m_group->make_order($attr);
		if (0 != $ret['code'])
		{
			$ret_data['code'] = $ret['code'];
			if ($ret['code'] == 1042)
			{
				$ret_data['msg'] = lang('default_country_not_ok');
			}
			else
			{
				$ret_data['msg'] = lang('try_again');
			}
			echo json_encode($ret_data);
			exit;
		}
		$ret_data['id'] = $ret['id'];

		echo json_encode($ret_data);
		exit;
	}
}
