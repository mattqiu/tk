<?php

/**
 * 新会员专享奖
 * Created by PhpStorm.
 * User: brady.wang
 * Date: 2017/3/14
 * Time: 10:31
 */
class tb_new_member_bonus extends MY_Model
{
	protected $table = "new_member_bonus";
	protected $table_name = "new_member_bonus";

	/**
	 * @author brady
	 * @description 满足新会员专享奖
	 * 需满足条件:
	 * 1、用户是从高免费店铺升级到付费用户
	 * 2、用户零售订单为50美金以上
	 * 3、用户之前没有拿过新会员专属奖
	 * 4、用户没有升级记录
	 * 5、用户是否有加入到分红队列历史记录
	 * @param $userInfo array
	 * @return  boolean
	 */
	public function new_member_bonus($userInfo)
	{
		//1、满足用户是从新会员升级
		//2、获取用户的零售订单
		$this->m_log->createCronLog('[success]通过升级进入新会员队列'.json_encode($userInfo));
		$this->load->model('tb_users_store_sale_info');//店铺销售总额
		$this->load->model('m_commission');//用户奖金统计表
		$this->load->model("tb_users_level_change_log");//用户升级记录
		$this->load->model("tb_logs_new_member_bonus");
		$start_time = config_item("new_member_bonus_start_time"); //新会员专属奖生效日期
		$this->load->model("tb_stat_intr_mem_month");//用户套餐统计
		if (time() >= strtotime($start_time)) {
			$uid = $userInfo['id'];
			$user_store_sale_amount = $this->tb_users_store_sale_info->getUserSaleInfo($uid); //用户个人店铺销售金额
			$user_team_sale_amount = $this->tb_stat_intr_mem_month->getProductSaleAmount($uid,date("Ym",strtotime($start_time)));
			$total_amount = $user_store_sale_amount + $user_team_sale_amount;

			$logs_new_member_bonus = $this->tb_logs_new_member_bonus->get([
				'select' => 'uid',
				'where' => [
					'uid' => $uid,
				]
			]);

			if ($total_amount >= 5000 && empty($logs_new_member_bonus)) {
				//满足条件 插入一条记录到 新用户分红队列表 new_member_bonus
				$res = $this->db->from("new_member_bonus")->where(array('uid' => $uid))->get()->row_array();

				if (empty($res)) {
					$this->db->insert('new_member_bonus', array(
						'uid' => $uid,
						'qualified_day' => date('Ymd'),
						'end_day' => date("Ymd", time() + 30 * 24 * 3600)
					));
					$affected_id = $this->db->affected_rows();
					if ($affected_id > 0) {
						$this->tb_logs_new_member_bonus->add_ignore($uid);
					}
				}



			}
		}
	}

	public function new_member_bonus_v2($userInfo)
	{
		//1、满足用户是从新会员升级
		//2、获取用户的零售订单
		$this->load->model('tb_users_store_sale_info');//店铺销售总额
		$this->load->model('m_commission');//用户奖金统计表
		$this->load->model("tb_users_level_change_log");//用户升级记录
		$this->load->model("tb_logs_new_member_bonus");
		$start_time = config_item("new_member_bonus_start_time"); //新会员专属奖生效日期
		$this->load->model("tb_stat_intr_mem_month");//用户套餐统计

		$this->db->trans_begin();
		if (time() >= strtotime($start_time)) {
			$uid = $userInfo['id'];
//			$user_store_sale_amount = $this->tb_users_store_sale_info->getUserSaleInfo($uid); //用户个人店铺销售金额
//			$user_team_sale_amount = $this->tb_stat_intr_mem_month->getProductSaleAmount($uid,date("Ym",strtotime($start_time)));
//			$total_amount = $user_store_sale_amount + $user_team_sale_amount;


			//if ($total_amount >= 5000) {
				//满足条件 插入一条记录到 新用户分红队列表 new_member_bonus
				$res = $this->db->from("new_member_bonus")->where(array('uid' => $uid))->get()->row_array();
				if (empty($res)) {
					$this->db->insert('new_member_bonus', array(
						'uid' => $uid,
						'qualified_day' => date('Ymd',strtotime("-1 day")),
						'end_day' => date("Ymd", strtotime("-1 day") + 30 * 24 * 3600)
					));
					$affected_id = $this->db->affected_rows();
					if ($affected_id > 0) {
						$this->tb_logs_new_member_bonus->add_ignore_v2($uid);
					}
				}

				if ($this->db->trans_status() === TRUE) {
					$this->db->trans_commit();
				$this->m_log->createCronLog('[success]修复 通过升级进入新会员队列'.json_encode($userInfo));
				} else {
					$this->db->trans_rollback();
					$this->m_log->createCronLog('[faile]修复 通过升级进入新会员队列'.json_encode($userInfo));
				}

			//}
		}
	}

	/**
	 * @authro 批量添加合格用户
	 * @param $page
	 * @param $page_size
	 */
	public function new_member_bonus_v3($page, $page_size)
	{
		//1、满足用户是从新会员升级
		//2、获取用户的零售订单
		$this->load->model("tb_users_level_change_log");//用户升级记录


		$yesterday = date("Y-m-d", strtotime("-1 day"));
		$today = date("Y-m-d", time());

		$level_change_logs = $this->db->from("users_level_change_log")
				->select("uid")
				->where("old_level = 4 and level_type = 2 and create_time >= '".$yesterday ."' and create_time <  '".$today."'")
				//->where("old_level = 4 and level_type = 2 and create_time >= '2017-01-01' and create_time <  '".$today."'")
				->group_by("uid")
				->order_by('id desc')
				->limit($page_size, ($page - 1) * $page_size)
				->get()
				->result_array();

		$uids = [];
		foreach ($level_change_logs as $v) {
			$uids[] = $v['uid'];
		}
		unset($level_change_logs);


		//满足条件 插入一条记录到 新用户分红队列表 new_member_bonus
		$res = $this->db->from("logs_new_member_bonus")->select("uid")->where_in("uid", $uids)->get()->result_array();

		$uids_exists = [];
		if (!empty($res)) {
			foreach ($res as $vv) {
				$uids_exists[] = $vv['uid'];
			}
		}

		if (!empty($uids_exists)) {
			foreach ($uids as $k => &$v) {
				if (in_array($v, $uids_exists)) {
					unset($uids[$k]);
				}
			}
		}

		echo "满足人数".count($uids);
		if (!empty($uids)) {
			$sql1 = "insert ignore into new_member_bonus(uid,qualified_day,end_day) values";
			foreach ($uids as $v) {
				$sql1 .= "(" . $v . "," . date('Ymd', strtotime("-1 day")) . "," . date("Ymd", strtotime("-1 day") + 30 * 24 * 3600) . "),";
			}
			$sql1 = substr($sql1, 0, strlen($sql1) - 1);

			$sql2 = "insert ignore into logs_new_member_bonus(uid,create_time) values";
			foreach ($uids as $v) {
				$sql2 .= "(" . $v .  ",'" . date("Y-m-d H:i:s", strtotime("-1 day")) . "'),";
			}
			$sql2 = substr($sql2, 0, strlen($sql2) - 1);

			$this->db->query($sql1);
			$this->db->query($sql2);

			if ($this->db->trans_status() === TRUE) {
				$this->m_log->createCronLog('[success]修复 通过升级进入新会员队列:page' . json_encode($uids));
			}

		}
	}

	/**
	 * @author brady
	 * @description 获取队列表里面满足条件 需要进行分红的用户列表
	 * @return mixed
	 */
	public function get_bonus_users($where = array(),$get_number=false)
	{
		$now = date("Ymd", time());

		$params = [
			'select' => 'new_member_bonus.uid,new_member_bonus.bonus_shar_weight',
			'join'=>[
				[
					'table'=>"users",
					'where'=>'new_member_bonus.uid=users.id',
					'type'=>"inner"
				],
			],
			'where' => [
				'new_member_bonus.end_day >=' => $now,
				'new_member_bonus.qualified_day <' => $now,
				"users.status"=>1
			]

		];




		//当总查询的时候 将因用户不正常而没发到奖的记录下来
		if (!empty($where['limit']) && !empty($where['limit']['page']) && !empty($where['limit']['pageSize'])) {
			$params['limit'] = ['page' => $where['limit']['page'], 'page_size' => $where['limit']['pageSize']];
		} else {
			$params1 = [
				'select' => 'new_member_bonus.uid',
				'join'=>[
					[
						'table'=>"users",
						'where'=>'new_member_bonus.uid=users.id',
						'type'=>"inner"
					],
				],
				'where' => [
					'new_member_bonus.end_day >=' => $now,
					'new_member_bonus.qualified_day <' => $now,
					"users.status !="=>1
				]

			];
			//dump($params);
			$bad_users = $this->get($params1,false,false,true);
			if (!empty($bad_users)) {
				$bad_users_arr = [];
				foreach($bad_users as $v) {
					$bad_users_arr[] = $v['uid'];
				}
				$this->m_log->createCronLog('[false] '.date("Y-m-d H:i:s").'新会员奖因用户状态不正常而未发的用户 '.json_encode($bad_users_arr));
				//释放变量
				unset($bad_users);
				unset($bad_users_arr);
			}

		}
		$res = $this->get($params,false,false,true);
		if($get_number == true) {
			if (!$res || empty($res)) {
				return 0;
			} else {
				$num = count($res);
				unset($res);
				return $num;
			}
		} else {
			if (!$res || empty($res)) {
				return [];
			} else {
				return $res;
			}
		}
	}


	public function get_users_total_order_amount($uids)
	{
		if (!is_array($uids)) {

			return false;
		}
		$this->load->model('tb_trade_orders');
		return $this->tb_trade_orders->getUsersOrderAmount($uids);

	}

	public function get_users_total_order_amount_arr($uids)
	{
		if (!is_array($uids)) {

			return false;
		}
		$this->load->model('tb_trade_orders');
		return $this->tb_trade_orders->getUsersOrderAmountArr($uids);

	}

	/**
	 * 批量设置用户的权重
	 * @param $weight
	 */
	public function set_users_weight($weight)
	{

		$ids = implode(',', array_keys($weight));
		$sql = "UPDATE new_member_bonus SET bonus_shar_weight = CASE uid ";
		foreach ($weight as $uid => $v) {
			//$sql .= sprintf(" WHEN  %s  THEN  %s  ", $uid, $weight['total_money']);
			$sql .= "  WHEN $uid THEN  {$v['total_money']}";
		}
		$sql .= " END WHERE uid IN ($ids)";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	public function get_not_match_users()
	{
		$res = $this->db->from($this->table)
			->select("uid,end_day")
			->where(['end_day < '=>(int)(date("Ymd"))])
			->get()
			->result_array();
		return $res;
	}

	public function del_not_match($uids)
	{
		$this->db->where_in('uid',$uids);
		return $this->db->delete($this->table);
	}

	/**
	 * 新增记录
	 * @author: derrick
	 * @date: 2017年4月5日
	 * @param: @param unknown $uid
	 * @param: @param unknown $qualified_day
	 * @param: @param unknown $end_day
	 * @param: @param unknown $bonus_shar_weight
	 * @reurn: return_type
	 */
	public function add_bonus($uid, $qualified_day, $end_day, $bonus_shar_weight) {
		$this->insert_one(array(
			'uid' => $uid,
			'qualified_day' => $qualified_day,
			'end_day' => $end_day,
			'bonus_shar_weight' => $bonus_shar_weight,
		));
	}

	public function add_new_bonus($uid,$time,$weight)
	{
		$this->db->replace('new_member_bonus', array(
				'uid' => $uid,
				'qualified_day' => date('Ymd',strtotime($time)),
				'end_day' => date("Ymd", strtotime($time) + 30 * 24 * 3600),
				'bonus_shar_weight'=>$weight
		));
	}


}