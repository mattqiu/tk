<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/21
 * Time: 16:06
 */
class o_bonus extends MY_Model
{
	protected $table = "new_member_bonus";
	protected $table_name = "new_member_bonus";


	private $create_time = '';

	public function __construct()
	{
		parent::__construct();
		$this->config->load('config_bonus');
		$this->load->model("tb_cash_account_log_x");
	}

	public function assign_bonus_batch_fix($list, $item_type, $create_time = '')
	{
		$res = true;
		if (empty($create_time)) {
			$create_time = date('Y-m-d H:i:s');
		}
		$this->create_time = $create_time;
		if (empty($list)) {
			$this->m_log->createCronLog("[failed] 发放分红的时候 传递过来的为空数组" . __FUNCTION__);
		} else {
			$new_list = [];
			foreach($list as $v) {
				$left_4 = substr($v['uid'],0,4);
				$new_list[$left_4][] = $v;
			}

			foreach($new_list as $k=>$v) {
				$res = $this->assign_bonus_batch_fix_branch($v, $item_type,$k,$create_time);
			}
			return $res;
		}	
	}
	/**
	 * @author brady.wang 批量补发奖
	 * @param array $list [uid=>,money=>,[uid=>,money=>],[uid=>,money=>]]
	 * @param int $item_type 分红类型
	 * @param string $create_time
	 * @return bool
	 */
	public function assign_bonus_batch_fix_branch($list, $item_type,$table_suffix, $create_time = '')
	{

		if (empty($create_time)) {
			$create_time = date('Y-m-d H:i:s');
		}

		$list = $this->check_bonus_exists($list,$item_type,$table_suffix,strtotime($create_time));
		$uids = $lists = [];

		$this->create_time = $create_time;

		if (empty($list)) {
			$this->m_log->createCronLog("[failed] 发放分红的时候 传递过来的为空数组" . __FUNCTION__);
			return false;
		} else {
			//找到所有的id
			foreach ($list as $v) {
				$v['type'] = $item_type;
				$lists[$v['uid']] = $v;
				$uids[] = $v['uid'];
			}


			//获取用户信息

			$users_data = $this->get_users_data($uids);

			foreach ($lists as $k => $v) {
				$lists[$k]['old_amount'] = $users_data[$v['uid']]['amount'];
				$lists[$k]['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金
				$lists[$k]['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
				$lists[$k]['proportion'] = 0;//单位美金
				$lists[$k]['commissionToPoint'] = 0;//单位美金

				$lists[$k]['amount_monthly_leader_comm'] = $users_data[$v['uid']]['amount_monthly_leader_comm'];//单位美金
				$lists[$k]['amount_profit_sharing_comm'] = $users_data[$v['uid']]['amount_profit_sharing_comm'];//单位美金
				$lists[$k]['amount_weekly_Leader_comm'] = $users_data[$v['uid']]['amount_weekly_Leader_comm'];//单位美金

			}

			//事物开始
			$this->db->trans_begin();

			//第一步  批量发放到 cash_account_log_ 表
			$sql = $this->get_sql_fix("cash_account_log", $lists, $item_type);
			$this->db->query($sql);

			//第二步 统计用户奖金
			$sql = $this->get_sql_fix("user_comm_stat_insert", $lists, $item_type);
			$this->db->query($sql);

			$sql = $this->get_sql_fix("user_comm_stat_update", $lists, $item_type);
			$this->db->query($sql);




			//第四步 统计用户转分红点 开始
			$user_proportion = $this->get_user_proportion($uids);
			$proportion_arr = [];

			foreach ($user_proportion as $v) {
				if ($v['proportion'] > 0) {
					if ($users_data[$v['uid']]['amount'] < 0){
						if(abs($users_data[$v['uid']]['amount'])*100 >= $lists[$v['uid']]['money']){
							//资金还不够扣为正数 不能转分红点
						} else {
							$trans_money = $lists[$v['uid']]['money'] - abs($users_data[$v['uid']]['amount'])*100 ;
							//$v['commissionToPoint'] = tps_int_format($v['proportion'] * $lists[$v['uid']]['money'] / 100); //单位美分
							$v['commissionToPoint'] = tps_int_format($v['proportion'] * $trans_money / 100); //单位美分
							if (tps_money_format($v['commissionToPoint'] / 100) >= 0.01) {
								$lists[$v['uid']]['remain_money'] = $lists[$v['uid']]['money'] - $v['commissionToPoint'];

								$lists[$v['uid']]['commissionToPoint'] = $v['commissionToPoint'];
								$lists[$v['uid']]['proportion'] = 1;

								$v['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金

								$v['money'] = $lists[$v['uid']]['money'];
								$v['old_amount'] = $users_data[$v['uid']]['amount'] * 100 + $v['money'];
								$v['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
								$proportion_arr[$v['uid']] = $v;
							}

						}

					} else {
						$v['commissionToPoint'] = tps_int_format($v['proportion'] * $lists[$v['uid']]['money'] / 100); //单位美分
						if (tps_money_format($v['commissionToPoint'] / 100) >= 0.01) {
							$lists[$v['uid']]['remain_money'] = $lists[$v['uid']]['money'] - $v['commissionToPoint'];

							$lists[$v['uid']]['commissionToPoint'] = $v['commissionToPoint'];
							$lists[$v['uid']]['proportion'] = 1;

							$v['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金

							$v['money'] = $lists[$v['uid']]['money'];
							$v['old_amount'] = $users_data[$v['uid']]['amount'] * 100  + $v['money'];
							$v['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
							$proportion_arr[$v['uid']] = $v;
						}
					}

				}
			}
//			echo "转分红点";
			if (!empty($proportion_arr)) {
				//批量更新 用户表 profit_sharing_point 总分红点 profit_sharing_point_from_sharing 分红佣金转入分红点
				$sql = $this->get_sql_fix("profit_sharing_point", $proportion_arr, $item_type);
				$this->db->query($sql);
				$sql = $this->get_sql_fix("profit_sharing_point_from_sharing", $proportion_arr, $item_type);
				$this->db->query($sql);

				//插入佣金转分红点记录
				$sql = $this->get_sql_fix("transfer_point", $proportion_arr, 17);
				$this->db->query($sql);
				$to_point_uids = array_keys($proportion_arr);

				//分红点新增记录
				$sql = $this->get_sql_fix("profit_sharing_point_add_log", $proportion_arr, 3);
				$this->db->query($sql);
				//第四步 统计用户转分红点 结束

			}


			//第五步 更新用户
			if ($item_type == 7) {
				$userAmount = 'amount_weekly_Leader_comm';//月分红总额
			} elseif ($item_type == 8) {
				$userAmount = 'amount_monthly_leader_comm'; //月分红总额
			} else {
				$userAmount = 'amount_profit_sharing_comm'; //个人分红总额
			}
			//更新用户amount
			$sql = $this->get_sql_fix("amount", $lists, $item_type);
			$this->db->query($sql);
			//更新用户 其他几个字段
			$sql = $this->get_sql_fix("other_amount", $lists, $item_type, $userAmount);
			$this->db->query($sql);
			//第六步 写入 发放记录
			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
//                 $sql = $this->get_sql_fix("grant_bonus_user_logs", $lists, $item_type, '', 1);
//                 $this->db->query($sql);
				return true;
				//$this->m_log->createCronLog('[Success] '.json_encode(array($list,$item_type)));
				//$this->m_log->createCronLog('[Success] 分红类型：'.$item_type.'发放成功');
			} else {
				$this->db->trans_rollback();
//                 $sql = $this->get_sql_fix("grant_bonus_user_logs", $lists, $item_type, '', 0);
//                 $this->db->query($sql);
				$this->m_log->createCronLog('[fail] ' . json_encode(array($lists, $item_type)));
				return false;
			}

		}

	}

	/**
	 * 获取批量sql语句
	 * @param $type
	 * @return bool
	 */
	public function get_sql_fix($sql_type, $lists, $item_type, $field = '', $status = 1)
	{
		if (empty($sql_type)) {
			return false;
		}
		$suffix_uid = '';
		foreach($lists as $k=>$v) {
			$suffix_uid = $k;
			break;
		}

		$create_time = $this->create_time;
		$year_month = date("Ym", strtotime($create_time));
		switch ($sql_type) {

			//资金变动报表
			case "cash_account_log" : {
				$table = get_cash_account_log_table_write($suffix_uid,$year_month);
				//如果是小于201706的补发奖，那么直写旧表
				if($year_month <= config_item("cash_account_log_cut_table_end")) {
					$sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time,true);
				} else {
					$sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time);
					if (config_item("cash_account_log_write_both") == true) {
						$table = "cash_account_log_".$year_month;
						$sqls = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time,true);
						$this->db->query($sqls);
					}
				}
			}
				break;

			//分红表统计 插入
			case "user_comm_stat_insert": //用户分红统计
			{
				$table = "user_comm_stat";
				$sql = "insert ignore into " . $table . " (uid) values ";
				foreach ($lists as $v) {
					$sql .= "(" . $v['uid'] . "),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);

			}
				break;

			//用户各类分红统计
			case "user_comm_stat_update" ://用户分红统计
			{
				$table = "user_comm_stat";
				$field = config_item("bonus")["bonus_type"][$item_type];

				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE user_comm_stat SET  " . $field . " = CASE uid  ";
				foreach ($lists as $uid => $v) {
					//$sql .= sprintf(" WHEN %d THEN %d", $uid, $field+$v['money']);
					$sql .= " WHEN $uid THEN $field + " . $v['money'] . " ";
				}
				$sql .= " END WHERE uid IN ($ids)";


			}
				break;



			//用户总分红点统计
			case "profit_sharing_point" : {
				$table = "users";
				$field = "profit_sharing_point";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN profit_sharing_point+" . tps_money_format($v['commissionToPoint'] / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//用户表转入分红总额统计
			case "profit_sharing_point_from_sharing" : {
				$table = "users";
				$field = "profit_sharing_point_from_sharing";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN  profit_sharing_point_from_sharing+" . tps_money_format($v['commissionToPoint'] / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//分红点转入记录
			case "transfer_point": {
//				$table = get_cash_account_log_table_write($suffix_uid,$year_month);
//				echo $sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time);
//				if (config_item("cash_account_log_write_both") == true) {
//					$table = "cash_account_log_".$year_month;
//					$sqls = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time,true);
//					$this->db->query($sqls);
//				}

				$table = get_cash_account_log_table_write($suffix_uid,$year_month);
				//如果是小于201706的补发奖，那么直写旧表
				if($year_month <= config_item("cash_account_log_cut_table_end")) {
					$sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time,true);
				} else {
					$sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time);
					if (config_item("cash_account_log_write_both") == true) {
						$table = "cash_account_log_".$year_month;
						$sqls = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,$create_time,true);
						$this->db->query($sqls);
					}
				}
			}
				break;

			//佣金转分红点记录
			case "profit_sharing_point_add_log": {
				$table = "profit_sharing_point_add_log";
				$sql = "insert into " . $table . "(uid,add_source,money,point,create_time) values";
				foreach ($lists as $v) {
					$create_time = time();
					$v['commissionToPoint'] = $v['commissionToPoint'] / 100;
					$sql .= "(" . $v['uid'] . ",3," . $v['commissionToPoint'] . "," . $v['commissionToPoint'] . ",'" . $create_time . "'),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);

			}
				break;

			//用户表转入分红总额统计
			case "amount" : {
				$table = "users";
				$field = "amount";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					//$remain_amount = (!empty($v['remain_money'])) ? $v['remain_money'] : 0;//佣金转分红后剩下的 用来加入到总金额上
					if (isset($v['remain_money'])) {
						$remain_amount = $v['remain_money'];
					} else {
						$remain_amount = $v['money'];
					}
					// var_dump($remain_amount);
					$sql .= " WHEN " . $uid . " THEN amount+" . tps_money_format($remain_amount / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//用户表转入分红总额统计
			case "other_amount" : {
				$table = "users";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN " . $field . "+" . tps_money_format($v['money'] / 100);
				}
				$sql .= " END WHERE id IN ($ids)";

			}
				break;

			case  "grant_bonus_user_logs" : {
				$table = "grant_bonus_user_logs";
				$sql = "insert ignore into grant_bonus_user_logs(uid,proportion,share_point,amount,bonus,type,item_type,create_time) VALUES ";

				foreach ($lists as $v) {
					$v['old_amount'] = 100 * $v['old_amount'];
					$sql .= "(" . $v['uid'] . "," . $v['proportion'] . "," . $v['commissionToPoint'] . "," . $v['old_amount'] . "," . $v['money'] . "," . $status . "," . $v['type'] . ",'" . $create_time . "'),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);


			}
				break;

		}
		return $sql;
	}

	public function check_bonus_exists($lists,$item_type,$table_suffix,$time)
	{

		$table = get_cash_account_log_table_write($table_suffix,date("Ym",$time));

		$uids = [];
		foreach($lists as $v) {
			$uids[] = $v['uid'];
		}
		$exists = $this->db->select("uid")->from($table)->where(array('item_type'=>$item_type,'DATE_FORMAT(create_time,"%Y%m%d")'=>date("Ymd",$time)))->where_in('uid',$uids)
				->group_by("uid")->order_by('id asc')->get()->result_array();
		if (!empty($exists)) {
			$exist_uids = [];
			foreach($exists as $v) {
				$exist_uids[] = $v['uid'];
			}
			//echo "[failed] 发放分红的时候 已经发放过的用户" .json_encode($exist_uids);
			$this->m_log->createCronLog("[failed] 发放分红的时候 已经发放过的用户" .json_encode($exist_uids));
			foreach($lists as $k=>$list) {
				if(in_array($list['uid'],$exist_uids)) {
					unset($lists[$k]);
				}
			}
		}
		return $lists;
	}

	/***********/

	public function assign_bonus_batch($list, $item_type)
	{

		$res = true;
		if (empty($list)) {
			$this->m_log->createCronLog("[failed] 发放分红的时候 传递过来的为空数组" . __FUNCTION__);
		} else {
			$new_list = [];
			foreach($list as $v) {
				$left_4 = substr($v['uid'],0,4);
				$new_list[$left_4][] = $v;
			}
			foreach($new_list as $k=>$v) {
				$res = $this->assign_bonus_batch_branch($v, $item_type,$k);
			}
			return $res;
		}

	}
	/**
	 * @author brady.wang 批量发奖
	 * @param $list [[uid=>,money=>],[uid=>,money=>],[uid=>,money=>]]
	 * $item_type int  分红类型
	 * 金额 money  美分
	 */
	public function assign_bonus_batch_branch($list, $item_type,$table_suffix)
	{
		$list = $this->check_bonus_exists($list,$item_type,$table_suffix,time());
		$uids = $lists = [];
		if (empty($list)) {
			//echo "[failed] 发放分红的时候 传递过来的为空数组";
			$this->m_log->createCronLog("[failed] ".$table_suffix."发放分红的时候 传递过来的为空数组" . __FUNCTION__);
			return false;
		} else {
			//找到所有的id
			foreach ($list as $v) {
				$v['type'] = $item_type;
				$lists[$v['uid']] = $v;
				$uids[] = $v['uid'];
			}

			//获取用户信息

			$users_data = $this->get_users_data($uids);
			foreach ($lists as $k => $v) {
				$lists[$k]['old_amount'] = $users_data[$v['uid']]['amount'];
				$lists[$k]['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金
				$lists[$k]['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
				$lists[$k]['proportion'] = 0;//单位美金
				$lists[$k]['commissionToPoint'] = 0;//单位美金

				$lists[$k]['amount_monthly_leader_comm'] = $users_data[$v['uid']]['amount_monthly_leader_comm'];//单位美金
				$lists[$k]['amount_profit_sharing_comm'] = $users_data[$v['uid']]['amount_profit_sharing_comm'];//单位美金
				$lists[$k]['amount_weekly_Leader_comm'] = $users_data[$v['uid']]['amount_weekly_Leader_comm'];//单位美金

			}


			//事物开始
			$this->db->trans_begin();

			//第一步  批量发放到 cash_account_log_ 表
			$sql = $this->get_sql("cash_account_log", $lists, $item_type);
			$this->db->query($sql);

			//第二步 统计用户奖金
			$sql = $this->get_sql("user_comm_stat_insert", $lists, $item_type);
			$this->db->query($sql);
			$sql = $this->get_sql("user_comm_stat_update", $lists, $item_type);
			$this->db->query($sql);



			//第四步 统计用户转分红点 开始
			$user_proportion = $this->get_user_proportion($uids);
			$proportion_arr = [];
			foreach ($user_proportion as $v) {
				if ($v['proportion'] > 0) {
					if ($users_data[$v['uid']]['amount'] < 0){
						if(abs($users_data[$v['uid']]['amount'])*100 >= $lists[$v['uid']]['money']){
							//资金还不够扣为正数 不能转分红点
						} else {
							$trans_money = $lists[$v['uid']]['money'] - abs($users_data[$v['uid']]['amount'])*100 ;
							//$v['commissionToPoint'] = tps_int_format($v['proportion'] * $lists[$v['uid']]['money'] / 100); //单位美分
							$v['commissionToPoint'] = tps_int_format($v['proportion'] * $trans_money / 100); //单位美分
							if (tps_money_format($v['commissionToPoint'] / 100) >= 0.01) {
								$lists[$v['uid']]['remain_money'] = $lists[$v['uid']]['money'] - $v['commissionToPoint'];

								$lists[$v['uid']]['commissionToPoint'] = $v['commissionToPoint'];
								$lists[$v['uid']]['proportion'] = 1;

								$v['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金

								$v['money'] = $lists[$v['uid']]['money'];
								$v['old_amount'] = $users_data[$v['uid']]['amount'] * 100  + $v['money']; //余额为原来的加上发奖的减去转分红的
								$v['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
								$proportion_arr[$v['uid']] = $v;
							}

						}

					} else {
						$v['commissionToPoint'] = tps_int_format($v['proportion'] * $lists[$v['uid']]['money'] / 100); //单位美分
						if (tps_money_format($v['commissionToPoint'] / 100) >= 0.01) {
							$lists[$v['uid']]['remain_money'] = $lists[$v['uid']]['money'] - $v['commissionToPoint'];

							$lists[$v['uid']]['commissionToPoint'] = $v['commissionToPoint'];
							$lists[$v['uid']]['proportion'] = 1;

							$v['old_profit_sharing_point'] = $users_data[$v['uid']]['profit_sharing_point'];//单位美金

							$v['money'] = $lists[$v['uid']]['money'];
							$v['old_amount'] = $users_data[$v['uid']]['amount'] * 100 +$v['money'];
							$v['old_profit_sharing_point_from_sharing'] = $users_data[$v['uid']]['profit_sharing_point_from_sharing'];//单位美金
							$proportion_arr[$v['uid']] = $v;
						}
					}

				}
			}


//			echo "转分红点";
			if (!empty($proportion_arr)) {
				//批量更新 用户表 profit_sharing_point 总分红点 profit_sharing_point_from_sharing 分红佣金转入分红点
				$sql = $this->get_sql("profit_sharing_point", $proportion_arr, $item_type);
				$this->db->query($sql);
				$sql = $this->get_sql("profit_sharing_point_from_sharing", $proportion_arr, $item_type);
				$this->db->query($sql);

				//插入佣金转分红点记录
				$sql = $this->get_sql("transfer_point", $proportion_arr, 17);
				$this->db->query($sql);
				$to_point_uids = array_keys($proportion_arr);

				//分红点新增记录
				$sql = $this->get_sql("profit_sharing_point_add_log", $proportion_arr, 3);
				$this->db->query($sql);
				//第四步 统计用户转分红点 结束

			}

			//第五步 更新用户
			if ($item_type == 7) {
				$userAmount = 'amount_weekly_Leader_comm';//月分红总额
			} elseif ($item_type == 8) {
				$userAmount = 'amount_monthly_leader_comm'; //月分红总额
			} else {
				$userAmount = 'amount_profit_sharing_comm'; //个人分红总额
			}



			//更新用户amount
			$sql = $this->get_sql("amount", $lists, $item_type);
			$this->db->query($sql);
			//更新用户 其他几个字段
			$sql = $this->get_sql("other_amount", $lists, $item_type, $userAmount);
			$this->db->query($sql);
			//第六步 写入 发放记录

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
// 				$sql = $this->get_sql("grant_bonus_user_logs", $lists, $item_type, '', 1);
// 				$this->db->query($sql);
				//$this->m_log->createCronLog('[Success] '.json_encode(array($list,$item_type)));
				//$this->m_log->createCronLog('[Success] 分红类型：'.$item_type.'发放成功');
				return true;
			} else {
				$this->db->trans_rollback();
// 				$sql = $this->get_sql("grant_bonus_user_logs", $lists, $item_type, '', 0);
// 				$this->db->query($sql);
				$this->m_log->createCronLog('[fail] ' . json_encode(array($lists, $item_type)));
				return false;
			}


		}

	}

	/**
	 * 获取批量sql语句
	 * @param $type
	 * @return bool
	 */
	public function get_sql($sql_type, $lists, $item_type, $field = '', $status = 1)
	{
		if (empty($sql_type)) {
			return false;
		}
		$suffix_uid = '';
		foreach($lists as $k=>$v) {
			$suffix_uid = $k;
			break;
		}

		switch ($sql_type) {

			//资金变动报表
			case "cash_account_log" : {
				$year_month = date("Ym", time());
				$table = get_cash_account_log_table_write($suffix_uid,$year_month);
				 $sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,date("Y-m-d H:i:s", time()));
				if (config_item("cash_account_log_write_both") == true) {

					$table = "cash_account_log_".$year_month;
					 $sqls = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,date("Y-m-d H:i:s", time()),true);
					$this->db->query($sqls);
				}

			}
				break;

			//分红表统计 插入
			case "user_comm_stat_insert": //用户分红统计
			{
				$table = "user_comm_stat";
				$sql = "insert ignore into " . $table . " (uid) values ";
				foreach ($lists as $v) {
					$sql .= "(" . $v['uid'] . "),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);

			}
				break;

			//用户各类分红统计
			case "user_comm_stat_update" ://用户分红统计
			{
				$table = "user_comm_stat";
				$field = config_item("bonus")["bonus_type"][$item_type];

				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE user_comm_stat SET  " . $field . " = CASE uid  ";
				foreach ($lists as $uid => $v) {
					//$sql .= sprintf(" WHEN %d THEN %d", $uid, $field+$v['money']);
					$sql .= " WHEN $uid THEN $field + " . $v['money'] . " ";
				}
				$sql .= " END WHERE uid IN ($ids)";


			}
				break;



			//用户总分红点统计
			case "profit_sharing_point" : {
				$table = "users";
				$field = "profit_sharing_point";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN profit_sharing_point+" . tps_money_format($v['commissionToPoint'] / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//用户表转入分红总额统计
			case "profit_sharing_point_from_sharing" : {
				$table = "users";
				$field = "profit_sharing_point_from_sharing";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN  profit_sharing_point_from_sharing+" . tps_money_format($v['commissionToPoint'] / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//分红点转入记录
			case "transfer_point": {

				$year_month = date("Ym", time());
				$year_month = date("Ym", time());
				$table = get_cash_account_log_table_write($suffix_uid,$year_month);
				$sql = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,date("Y-m-d H:i:s", time()));
				if (config_item("cash_account_log_write_both") == true) {
					$table = "cash_account_log_".$year_month;
					$sqls = $this->tb_cash_account_log_x->get_sql_batch($lists,$item_type,$table,date("Y-m-d H:i:s", time()),true);
					$this->db->query($sqls);
				}

			}
				break;

			//佣金转分红点记录
			case "profit_sharing_point_add_log": {
				$table = "profit_sharing_point_add_log";
				$sql = "insert into " . $table . "(uid,add_source,money,point,create_time) values";
				foreach ($lists as $v) {
					$create_time = time();
					$v['commissionToPoint'] = $v['commissionToPoint'] / 100;
					$sql .= "(" . $v['uid'] . ",3," . $v['commissionToPoint'] . "," . $v['commissionToPoint'] . "," . $create_time . "),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);

			}
				break;

			//用户表转入分红总额统计
			case "amount" : {
				$table = "users";
				$field = "amount";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					//$remain_amount = (!empty($v['remain_money'])) ? $v['remain_money'] : 0;//佣金转分红后剩下的 用来加入到总金额上
					if (isset($v['remain_money'])) {
						$remain_amount = $v['remain_money'];
					} else {
						$remain_amount = $v['money'];
					}
					// var_dump($remain_amount);
					$sql .= " WHEN " . $uid . " THEN amount+" . tps_money_format($remain_amount / 100) . " ";
				}
				$sql .= " END WHERE id IN ($ids)";


			}
				break;

			//用户表转入分红总额统计
			case "other_amount" : {
				$table = "users";
				$ids = implode(',', array_keys($lists));
				$sql = "UPDATE " . $table . " SET  " . $field . " = CASE id  ";
				foreach ($lists as $uid => $v) {
					$sql .= " WHEN " . $uid . " THEN " . $field . "+" . tps_money_format($v['money'] / 100);
				}
				$sql .= " END WHERE id IN ($ids)";

			}
				break;

			case  "grant_bonus_user_logs" : {
				$table = "grant_bonus_user_logs";
				$sql = "insert ignore into grant_bonus_user_logs(uid,proportion,share_point,amount,bonus,type,item_type,create_time) VALUES ";
				$create_time = date("Y-m-d H:i:s", time());
				foreach ($lists as $v) {
					$v['old_amount'] = 100 * $v['old_amount'];
					$sql .= "(" . $v['uid'] . "," . $v['proportion'] . "," . $v['commissionToPoint'] . "," . $v['old_amount'] . "," . $v['money'] . "," . $status . "," . $v['type'] . ",'" . $create_time . "'),";
				}
				$sql = substr($sql, 0, strlen($sql) - 1);


			}
				break;

		}
		return $sql;
	}

	/**
	 * 批量获取用户分红点比例
	 */
	public function get_user_proportion($uids)
	{
		$res = $this->db->from("profit_sharing_point_proportion")
				->select("uid,proportion")
				->where_in('uid', $uids)
				->get()
				->result_array();
		return $res;
	}

	/**
	 * 批量获取用户信息
	 *
	 */
	public function get_users_data($uids)
	{
		$res = $this->db->from("users")
				->select("id,profit_sharing_point,profit_sharing_point_from_sharing,amount,amount_weekly_Leader_comm,amount_monthly_leader_comm,amount_profit_sharing_comm")
				->where_in('id', $uids)
				->get()
				->result_array();
		$new_res = [];

		if (!empty($res)) {
			foreach ($res as $v) {
				$new_res[$v['id']] = $v;
			}
		}
		return $new_res;
	}


	/**
	 * 批量获取用户的订单总金额权重
	 * @author brady
	 */
	public function getUsersTotalWeight($user_list)
	{
		$end = date("Y-m-d");
		$tab_name = "trade_orders";
		$tab_names = "trade_orders";
		$ext = $this->get_table_ext();//取分表后缀
		if($ext)
		{
		    $tab_names = $tab_name.$ext;//检测数据表，如果没有就创建
		}
		$mallProfit = $this->db->select('sum(a.order_amount_usd) as totalProfit')->from('mall_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->get()->row_object()->totalProfit;
		$mallProfit = $mallProfit ? $mallProfit : 0;

		$mallProfitOnederect = $this->db->select('sum(a.order_amount_usd) as totalProfit')->from('one_direct_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->get()->row_object()->totalProfit;
		$mallProfitOnederect = $mallProfitOnederect ? $mallProfitOnederect : 0;

		$mallProfitTps = $this->db->select('sum(a.goods_amount_usd) as totalProfit')->from('trade_orders as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->get()->row_object()->totalProfit;
		$mallProfitTps_new = $this->db->select('sum(a.goods_amount_usd) as totalProfit')->from($tab_names.' as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->get()->row_object()->totalProfit;
//		$mallProfitTps = $mallProfitTps ? $mallProfitTps / 100 : 0;
//         $mallProfitTps = $this->tb_trade_orders->get_sum("goods_amount_usd",[
//             'customer_id'=>$user_list,
//             'pay_time <'=>$end,
//             'order_prop'=>array('0', '1'),
//             'status'=>array('90','100','111','1','3', '4', '5', '6')
//         ]);
        $mallProfitTps = isset($mallProfitTps['totalProfit']) ? $mallProfitTps['totalProfit'] / 100 : 0;
        $mallProfitTps_new = isset($mallProfitTps_new['totalProfit']) ? $mallProfitTps_new['totalProfit'] / 100 : 0;
		$tpsProductMoney = $this->db->select("sum(a.order_amount_usd) as totalProfit")->from("walmart_orders as a")->where_in('customer_id', $user_list)->where('a.create_time <', $end)->where(['a.status' => 1])->get()->row_object()->totalProfit;
		$tpsProductMoney = $tpsProductMoney ? $tpsProductMoney : 0;
		return 100 * ($mallProfit + $mallProfitOnederect + $mallProfitTps + $tpsProductMoney+$mallProfitTps_new);
	}

	/**
	 * @author brady
	 * @description 批量获取用户的权重
	 * @param $user_list 用户列表
	 * @return array
	 * Array
	 *   (
	 *   [1381017677] => 625720
	 *   [1384616027] => 48770
	 *   [1384616029] => 43393
	 *   )
	 */
	public function getUsersTotalWeightArr($user_list)
	{
		$end = date("Y-m-d", time());
		
		
		$tab_name = "trade_orders";
		$tab_names = "trade_orders";
		$ext = $this->get_table_ext();//取分表后缀
		if($ext)
		{
		    $tab_names = $tab_name.$ext;//检测数据表，如果没有就创建
		}
		
		
		$mall_orders = $trade_orders = $user_upgrade_order = $one_direct_orders = [];

		$mallProfit = $this->db->select('sum(a.order_amount_usd) as totalProfit,customer_id as uid')->from('mall_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->group_by('customer_id')->get()->result_array();
		$mallProfitOnederect = $this->db->select('sum(a.order_amount_usd) as totalProfit,customer_id as uid')->from('one_direct_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->group_by('customer_id')->get()->result_array();

		$mallProfitTps_old = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from('trade_orders as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();

		$mallProfitTps = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from($tab_names.' as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
        
		$mallProfitTps_level_old = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from('trade_orders as a')->where_in('customer_id', $user_list)->where('shopkeeper_id', 0)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
		$mallProfitTps_level = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from($tab_names.' as a')->where_in('customer_id', $user_list)->where('shopkeeper_id', 0)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
		$tpsProductMoney = $this->db->select("sum(a.order_amount_usd) as totalProfit,customer_id as uid")->from("walmart_orders as a")->where_in('customer_id', $user_list)->where('a.create_time <', $end)->where(['a.status' => 1])->group_by('customer_id')->get()->result_array();

		if (!empty($mallProfit)) {
			foreach ($mallProfit as $v) {
				$mall_orders[$v['uid']] = $v['totalProfit'];
			}
			unset($mallProfit);
		}


		if (!empty($mallProfitTps_level)) {
			foreach ($mallProfitTps_level as $v) {
				$mallProfitTps_level_order[$v['uid']] = $v['totalProfit'];
			}
			unset($mallProfitTps_level);
		}

		if (!empty($mallProfitTps_level_old)) {
		    foreach ($mallProfitTps_level_old as $v) {
		        $mallProfitTps_level_order[$v['uid']] += $v['totalProfit'];
		    }
		    unset($mallProfitTps_level_old);
		}
		
		if (!empty($mallProfitOnederect)) {
			foreach ($mallProfitOnederect as $v) {
				$one_direct_orders[$v['uid']] = $v['totalProfit'];
			}
			unset($mallProfitOnederect);
		}

		if (!empty($mallProfitTps)) {
			foreach ($mallProfitTps as $v) {
				$trade_orders[$v['uid']] = $v['totalProfit'];
			}
			unset($mallProfitTps);
		}
		
		if (!empty($mallProfitTps_old)) {
		    foreach ($mallProfitTps_old as $v) {
		        $trade_orders[$v['uid']] += $v['totalProfit'];
		    }
		    unset($mallProfitTps_old);
		}
		
		if (!empty($tpsProductMoney)) {
			foreach ($tpsProductMoney as $v) {
				$user_upgrade_order[$v['uid']] = $v['totalProfit'];
			}
			unset($tpsProductMoney);
		}

		$total = [];

		foreach ($user_list as $uid) {
			$mall_orders_temp = empty($mall_orders[$uid]) ? 0 : $mall_orders[$uid];
			$one_direct_orders_temp = empty($one_direct_orders[$uid]) ? 0 : $one_direct_orders[$uid];
			$trade_orders_temp = empty($trade_orders[$uid]) ? 0 : $trade_orders[$uid];
			$user_upgrade_order_temp = empty($user_upgrade_order[$uid]) ? 0 : $user_upgrade_order[$uid];
			$mallProfitTps_level_temp = empty($mallProfitTps_level_order[$uid]) ? 0 : $mallProfitTps_level_order[$uid];
			$total_temp = $mall_orders_temp * 100 + $one_direct_orders_temp * 100 + $trade_orders_temp + $user_upgrade_order_temp * 100;
			$total[$uid] =  array("total_money"=>$total_temp,"level_money"=>$mallProfitTps_level_temp);
		}
		return $total;
	}

	
	/**
	 * @author brady
	 * @description 批量获取用户的权重
	 * @param $user_list 用户列表
	 * @return array
	 * Array
	 *   (
	 *   [1381017677] => 625720
	 *   [1384616027] => 48770
	 *   [1384616029] => 43393
	 *   )
	 */
	public function getUsersTotalWeightArr_new($user_list)
	{
        error_reporting( E_ALL&~E_NOTICE );
	    $end = date("Y-m-d", time());
	
	
	    $tab_name = "trade_orders";	    
	    $ext = $this->get_table_ext();//取分表后缀
	    
	
	    $mall_orders = $trade_orders = $user_upgrade_order = $one_direct_orders = [];
	
	    $mallProfit = $this->db->select('sum(a.order_amount_usd) as totalProfit,customer_id as uid')->from('mall_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->group_by('customer_id')->get()->result_array();
	    $mallProfitOnederect = $this->db->select('sum(a.order_amount_usd) as totalProfit,customer_id as uid')->from('one_direct_orders as a')->where_in('customer_id', $user_list)->where('a.create_time <', $end)->group_by('customer_id')->get()->result_array();
	    
	    $mallProfitTps_old = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from('trade_orders as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
	    
	    //订单分表
	    if($ext)
	    {
	        $end_time = (int)(substr(date("Y"),-2).date("m"));
	        for($index = 1706;$index <= $end_time;$index++)
	        {
	            $tab_names = "trade_orders_".$index;
	            $mallProfitTps = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from($tab_names.' as a')->where_in('customer_id', $user_list)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
	            if (!empty($mallProfitTps)) {
	                foreach ($mallProfitTps as $v) {
	                    if(isset($trade_orders[$v['uid']]))
	                    {
	                        $trade_orders[$v['uid']] += $v['totalProfit'];
	                    }
	                    else
	                    {
	                        $trade_orders[$v['uid']] = $v['totalProfit'];
	                    }
	                }
	                unset($mallProfitTps);
	            }
	            
	            $mallProfitTps_level = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from($tab_names.' as a')->where_in('customer_id', $user_list)->where('shopkeeper_id', 0)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();

	            if (!empty($mallProfitTps_level)) {
	                foreach ($mallProfitTps_level as $v) {
	                    if(isset($mallProfitTps_level_order[$v['uid']]))
	                    {
	                        $mallProfitTps_level_order[$v['uid']] += $v['totalProfit'];
	                    }
	                    else 
	                    {
	                        $mallProfitTps_level_order[$v['uid']] = $v['totalProfit'];
	                    }	                    
	                }
	                unset($mallProfitTps_level);
	            }
	        }	         
	    }
	    
	    $mallProfitTps_level_old = $this->db->select('sum(a.goods_amount_usd) as totalProfit,customer_id as uid')->from('trade_orders as a')->where_in('customer_id', $user_list)->where('shopkeeper_id', 0)->where('a.pay_time <', $end)->where_in('order_prop', array('0', '1'))->where_in('status', array('90','100','111','1','3', '4', '5', '6'))->group_by('customer_id')->get()->result_array();
	
	    
	    $tpsProductMoney = $this->db->select("sum(a.order_amount_usd) as totalProfit,customer_id as uid")->from("walmart_orders as a")->where_in('customer_id', $user_list)->where('a.create_time <', $end)->where(['a.status' => 1])->group_by('customer_id')->get()->result_array();
	
	    if (!empty($mallProfit)) {
	        foreach ($mallProfit as $v) {
	            $mall_orders[$v['uid']] = $v['totalProfit'];
	        }
	        unset($mallProfit);
	    }
	
	    if (!empty($mallProfitTps_level_old)) {
	        foreach ($mallProfitTps_level_old as $v) {
	            if(isset($mallProfitTps_level_order[$v['uid']]))
	            {
	                $mallProfitTps_level_order[$v['uid']] += $v['totalProfit'];
	            }
	            else
	            {
	                $mallProfitTps_level_order[$v['uid']] = $v['totalProfit'];
	            }
	        }
	        unset($mallProfitTps_level_old);
	    }
	
	    if (!empty($mallProfitOnederect)) {
	        foreach ($mallProfitOnederect as $v) {
	            $one_direct_orders[$v['uid']] = $v['totalProfit'];
	        }
	        unset($mallProfitOnederect);
	    }
 	
	    if (!empty($mallProfitTps_old)) {
	        foreach ($mallProfitTps_old as $v) {
	            if(isset($trade_orders[$v['uid']]))
	            {
	                $trade_orders[$v['uid']] += $v['totalProfit'];
	            }
	            else
	            {
	                $trade_orders[$v['uid']] =  $v['totalProfit'];
	            }
	            
	        }
	        unset($mallProfitTps_old);
	    }
	
	    if (!empty($tpsProductMoney)) {
	        foreach ($tpsProductMoney as $v) {
	            $user_upgrade_order[$v['uid']] = $v['totalProfit'];
	        }
	        unset($tpsProductMoney);
	    }
	
	    $total = [];
	    foreach ($user_list as $uid) {
	        $mall_orders_temp = empty($mall_orders[$uid]) ? 0 : $mall_orders[$uid];
	        $one_direct_orders_temp = empty($one_direct_orders[$uid]) ? 0 : $one_direct_orders[$uid];
	        $trade_orders_temp = empty($trade_orders[$uid]) ? 0 : $trade_orders[$uid];
	        $user_upgrade_order_temp = empty($user_upgrade_order[$uid]) ? 0 : $user_upgrade_order[$uid];
	        $mallProfitTps_level_temp = empty($mallProfitTps_level_order[$uid]) ? 0 : $mallProfitTps_level_order[$uid];
	        $total_temp = $mall_orders_temp * 100 + $one_direct_orders_temp * 100 + $trade_orders_temp + $user_upgrade_order_temp * 100;
	        $total[$uid] =  array("total_money"=>$total_temp,"level_money"=>$mallProfitTps_level_temp);
	    }
	    return $total;
	}


	/**
	 * @author brady
	 * @description 给用户发放新用户分红
	 * @param $uid 用户id
	 * @param $money 新用户得到的分红金额
	 */
//	public function assign_bonus($uid, $total_money, $commission_type)
//	{
//		//echo "<br>";
//		//echo $uid . "---" . $total_money . "<br>";
//		$this->load->model('o_cash_account');
//		$this->load->model('m_profit_sharing');
//
//		$commission_type_name = config_item("commission_type")[$commission_type];
//
//		$commission_amount_int = tps_int_format($total_money * 100);
//		$create_time = date("Y-m-d H:i:s", time());
//		$this->o_cash_account->createCashAccountLog(array(
//				'uid' => $uid,
//				'item_type' => $commission_type,
//				'amount' => $commission_amount_int,
//				'create_time' => $create_time,
//		));
//		/*统计用户奖金*/
//		$this->load->model('tb_user_comm_stat');
//		$this->tb_user_comm_stat->updateUserCommStat($uid, $commission_type, $commission_amount_int);
//		//echo $this->db->last_query();
//		/*佣金自动转分红点*/
//		$rate = $this->m_profit_sharing->getProportion($uid, 'sale_commissions_proportion') / 100;
//		//echo $this->db->last_query();
//		$commissionToPoint = 0;
//		if ($rate > 0) {
//			//佣金转分红点数量
//			$commissionToPoint = tps_money_format($total_money * $rate);
//			if ($commissionToPoint >= 0.01) {
//				$this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sharing', 'profit_sharing_point_from_sharing+' . $commissionToPoint, FALSE)->update('users');
//				//		echo "<br>";
//				///		echo $this->db->last_query();
//				$comm_id = $this->o_cash_account->createCashAccountLog(array(
//						'uid' => $uid,
//						'item_type' => 17,
//						'amount' => tps_int_format(-1 * $commissionToPoint * 100),
//						'create_time' => $create_time,
//				));
//				//	echo "<br>";
//				//	echo $this->db->last_query();
//				$dataPointLog = array(
//						'uid' => $uid,
//						'commission_id' => $comm_id,
//						'add_source' => 3,
//						'money' => $commissionToPoint,
//						'point' => $commissionToPoint
//				);
//				if ($create_time) {
//					$dataPointLog['create_time'] = strtotime($create_time);
//				}
//				$this->m_profit_sharing->createPointAddLog($dataPointLog);
//				//	echo "<br>";
//				//	echo $this->db->last_query();
//			} else {
//				$commissionToPoint = 0;
//			}
//		}
//
//
//		$this->db->where('id', $uid)->set('amount', 'amount+' . ($total_money - $commissionToPoint), FALSE)->update('users');
//		//echo "<br>";
//		//echo $this->db->last_query();
//	}

	/*
	 * 过滤掉店铺不合格的用户
	 */
	public function filter_store_qualified_users($uids)
	{
		$res = $this->db->from('users')
				->select("id")
				->where_in('id', $uids)
				->where(['store_qualified !=' => 1])
				->get()
				->result_array();
		return $res;
	}

	/**
	 * 发放个人销售提成奖
	 * @author: derrick
	 * @date: 2017年5月2日
	 * @param: @param int $uid 用户ID
	 * @param: @param string $order_id 订单ID
	 * @param: @param int $order_profit_usd 订单利润,美分单位
	 * @param: @param date $date 发放月份, 对应业绩字段
	 * @reurn: return_type
	 */
	public function personal_prize($uid, $order_id, $order_profit_usd, $date = '') {
		$profit = round($order_profit_usd * 0.2);
		if ($profit > 0) {
			$this->load->model(array('tb_users'));
			//todo: amount拆表之后要从分表查询
			$user_info = $this->tb_users->get_user_info($uid, 'status, amount');
			if (empty($user_info)) {
				return false;
			}
			if ($user_info['status'] == 4) {
				return true;
			}
			
			$this->load->model(array('tb_profit_sharing_point_proportion', 'tb_users',"o_cash_account"));
			//生成资金变动报表记录（个人店铺提成）
			//todo: before_amount, after_amount, amount拆表之后不需要在*100转换成美分操作
			$this->o_cash_account->createCashAccountLog(array(
					'uid' => $uid,
					'item_type' => 5,
					'amount' =>$profit,
					'order_id'=>$order_id,
					'before_amount' => $user_info['amount'] * 100,
					'after_amount' => $user_info['amount'] * 100 + $profit
			));
			//判断是否设置了自动转分红点比例

			$point = $proportion = 0;
			$proportion = $this->tb_profit_sharing_point_proportion->getUserCommToSharingpointProportion($uid);
			$proportion = $proportion / 100;
			if ($proportion > 0) {
				//有自动转分红比例，执行佣金自动转分红
				$point = round($profit * $proportion);
				if ($point > 0) {
					//更新用户表分红点
					$this->tb_users->udpate_user_sharing_point($uid, $point);

					//生成相应资金变动报表记录（佣金转分红点）
					
					$this->o_cash_account->createCashAccountLog(array(
							'uid' => $uid,
							'item_type' => 17,
							'amount' => -1 * $point,
							'order_id'=>$order_id,
							'before_amount' => $user_info['amount'] * 100 + $profit,
							'after_amount' => $user_info['amount'] * 100 + $profit - $point
					));
					//生成分红变动记录
					$this->load->model('tb_profit_sharing_point_add_log');
					$this->tb_profit_sharing_point_add_log->add_sharing_point_log(0, $uid, 1, $point / 100, $point / 100, time());
				}
			}

			//更新用户现金池，个人店铺销售提成统计
			$this->tb_users->update_cash_and_store_stat_data($uid, $profit, $point);
		}
		return true;
	}

	/**
	 * 发放团队销售提成奖
	 * @author: derrick
	 * @date: 2017年5月2日
	 * @param: @param string or array $parent_ids 父ID集合, 数据格式为 aaa,bbb,ccc,ddd
	 * @param: @param string $order_id 订单ID
	 * @param: @param int $order_profit_usd 订单利润,美分单位
	 * @param: @param int $related_uid 关联用户ID
	 * @param: @param number $level
	 * @param: @param date $date 发放月份, 对应业绩字段
	 * @reurn: return_type
	 */
	public function group_prize($parent_ids, $order_id, $order_profit_usd, $related_uid, $level = 2, $date = '') {
		$ids = array();
		switch (gettype($parent_ids)) {
			case 'string':
				$ids = explode(',', $parent_ids);
				break;
			case 'array':
				$ids = $parent_ids;
				break;
		}
		if (empty($ids)) {
			return true;
		}
		$this->load->model(array('tb_users', 'tb_profit_sharing_point_proportion','o_cash_account'));
		$proportion = config_item('team_profit_proportion');
		$cur_pro = 0;
		foreach ($ids as $i => $uid) {
			if ($i >= $level) {
				break;
			}
			if ($uid == '1380100217' || empty($uid)) {
				continue;
			}
			//todo: amount拆表从子表查询
			$parent_user_info = $this->tb_users->get_user_info($uid, 'user_rank,store_qualified,status,amount');
			if (empty($parent_user_info)) {
				continue;
			}
			if ($parent_user_info['status'] == 4) {
				continue;
			}
			$cur_pro = isset($proportion[$i]) ? $proportion[$i] : $cur_pro;
			$cur_pro = isset($cur_pro[$parent_user_info['user_rank']]) ? $cur_pro[$parent_user_info['user_rank']] : $cur_pro[4];
			$profit = round($order_profit_usd * $cur_pro);
			if ($profit < 0) {
				continue;
			}
			//#生成资金变动报表记录
			$this->o_cash_account->createCashAccountLog(array(
					'uid' => $uid,
					'item_type' => 3,
					'amount' => $profit,
					'order_id'=>$order_id,
					'related_uid'=>$related_uid,
					'before_amount' => $parent_user_info['amount'] * 100,
					'after_amount' => $parent_user_info['amount'] * 100 + $profit
			));
			//判断是否设置了自动转分红点比例
			$comm_to_point = $u_proportion = 0;
			$u_proportion = $this->tb_profit_sharing_point_proportion->getUserCommToSharingpointProportion($uid);
			$u_proportion = $u_proportion / 100;
			if ($u_proportion > 0) {
				//有自动转分红比例，执行佣金自动转分红
				$comm_to_point = round($profit * $u_proportion);
				if ($comm_to_point > 0) {
					//更新用户表分红点
					$this->tb_users->udpate_user_sharing_point($uid, $comm_to_point);

					//生成相应资金变动报表记录（佣金转分红点）
					$this->o_cash_account->createCashAccountLog(array(
						'uid' => $uid,
						'item_type' => 17,
						'amount' => -1 * $comm_to_point,
						'before_amount' => $parent_user_info['amount'] * 100 + $profit,
						'after_amount' => $parent_user_info['amount'] * 100 + $profit - $comm_to_point
					));
					$this->load->model('tb_profit_sharing_point_add_log');
					$this->tb_profit_sharing_point_add_log->add_sharing_point_log(0, $uid, 1, $comm_to_point / 100, $comm_to_point / 100, time());
				}
			}
			//更新用户现金池，提成统计
			$this->tb_users->update_parent_cash_and_store_stat_data($uid, $profit, $comm_to_point);
		}
		return true;
	}
}