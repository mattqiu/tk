<?php
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/28
 * Time: 15:11
 */

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class daily_bonus_pre extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("tb_grant_pre_users_daily_bonus");
	}

	/**
	 * 日分红预发奖列表
	 */
	public function index()
	{
		$this->_viewData['title'] = "日分红预发奖";
		$uid = $this->input->get('uid');
		$uid = isset($uid) ? $uid : '';
		$total_rows = $this->tb_grant_pre_users_daily_bonus->get_total_rows($uid);
		$page_size = 10;
		$page = intval($this->input->get('page',true));
		$page = (empty($page) || $page) <= 0 ? $page = 1 : $page;
		//获取发奖的总金额
		$total_money = $this->tb_grant_pre_users_daily_bonus->get_total_money();




		if ($total_rows <=0) {
			$list = [];
		} else {
			$total_page = ceil($total_rows/$page_size);
			$page = ($page > $total_page) ? $total_page : $page;
			$list = $this->tb_grant_pre_users_daily_bonus->get_by_list($page,$page_size,$uid);
			foreach($list as &$v)
			{
				$v['rate'] = tps_money_format($v['amount'] / $total_money * 100);
			}

		}
		if (!empty($uid)) {
			$pager = $this->tb_grant_pre_users_daily_bonus->get_pager("admin/daily_bonus_pre", ['page' => $page, 'page_size' => $page_size,'uid'=>$uid], $total_rows, true);
		} else {
			$pager = $this->tb_grant_pre_users_daily_bonus->get_pager("admin/daily_bonus_pre", ['page' => $page, 'page_size' => $page_size], $total_rows, true);
		}

		$this->_viewData['pager']  = $pager;
		$this->_viewData['list']  = $list;
		$this->_viewData['uid']  = $uid;
		parent::index("admin/");
	}
}