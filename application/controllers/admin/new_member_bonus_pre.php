<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/3/28
 * Time: 14:12
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class new_member_bonus_pre extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_admin_user');
		$this->load->model("tb_grant_pre_users_new_member_bonus");
		$this->load->model("o_company_money_today_total");
	}

	/**
	 * 新会员专享奖列表
	 */
	public function index()
	{
		$this->_viewData['title'] = "新会员专享奖";
		$uid = $this->input->get('uid');
		$uid = isset($uid) ? $uid : '';

		$total_rows = $this->tb_grant_pre_users_new_member_bonus->get_total_rows($uid);
		$page_size = 20;
		$page = intval($this->input->get('page', true));
		$page = (empty($page) || $page) <= 0 ? $page = 1 : $page;
		//获取发奖的总金额
		$total_money = $this->tb_grant_pre_users_new_member_bonus->get_total_money();

		if ($total_rows <= 0) {
			$list = [];
		} else {
			$total_page = ceil($total_rows / $page_size);
			$page = ($page > $total_page) ? $total_page : $page;
			$list = $this->tb_grant_pre_users_new_member_bonus->get_by_list($page, $page_size, $uid);
			foreach ($list as &$v) {
				$v['rate'] = tps_money_format($v['amount'] / $total_money * 100);
			}

		}
		$param = ['page' => $page, 'page_size' => $page_size];
		if (!empty($uid)) {
			$param['uid'] = $uid;
		}
		$pager = $this->tb_grant_pre_users_new_member_bonus->get_pager("admin/new_member_bonus_pre", $param, $total_rows, true);
		$this->_viewData['pager'] = $pager;
		$this->_viewData['list'] = $list;
		parent::index("admin/");
	}
}