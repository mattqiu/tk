<?php
/**
 * 月费队列类
 * @author john
 */
class o_month_fee extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	public function deduct_month_fee($uid)
	{
		//找到用户相关信息
		$this->load->model("tb_users");
		$this->load->model("tb_monthly_fee_coupon");
		$this->load->model("tb_users_month_fee_log");
		$this->load->model("tb_month_fee_change");

		$user_info = $this->tb_users->getUserInfo($uid,['id','name','amount',"is_auto","month_fee_pool"]);
		$amount = 1000;//要扣的月费 美分
		//统计该用户，上个月的获取的佣金总金额


		$amount_yuan = tps_money_format($amount/100);
		//找到用户的月费券数量
		$couples  = $this->tb_monthly_fee_coupon->get(['where'=>[
			'uid'=>$uid
		],
			'order'=>'id asc',
		]);

		if (count($couples) > 0 ) {
			//使用月费券
			$this->tb_monthly_fee_coupon->delete_coupon_one($couples[0]['id']);

			//月费变动记录
			$month_fee_change_data = [
				"user_id"=>$uid,
				'old_month_fee_pool'=>$user_info["month_fee_pool"],
				'month_fee_pool'=>$user_info['month_fee_pool'],
				'cash'=>0,
				'admin_id'=>0,
				'type'=> 4,
				'old_coupon_num'=>count($couples),
				'coupon_num'=>count($couples) -1,
				'coupon_num_change'=> -1,
				'create_time'=>date("Y-m-d H:i:s")
			];

			$this->tb_month_fee_change->add($month_fee_change_data);

		} else {
			$this->db->where('id', $uid)->set('amount', 'amount-' . $amount_yuan, FALSE)->update('users');
			$this->load->model("o_cash_account");
			//插入资金明细(现金池转月费池)
			$this->o_cash_account->createCashAccountLog(array(
				'uid'=>$uid,
				'item_type'=>14,
				'amount'=>-$amount,
				'create_time'=>date('Y-m-d H:i:s',time())
			));


		}

		/* 记录月费扣取日志 */
		//用户月费扣取记录
		$users_month_fee_log_data = array('uid'=>$uid,"year_and_month"=>date("Ym"),'amount'=>$amount,"create_time"=>date("Y-m-d H:i:s"));
		$this->tb_users_month_fee_log->add_ignore($users_month_fee_log_data);
		echo $this->db->last_query();

		$this->db->query("delete from users_month_fee_fail_info where uid=$uid");

		$is = $this->db->from('users')->where('id', $uid)->where('status', 2)->get()->result_array();
		$this->db->where('id', $uid)->update('users', array('status' => 1));
		$this->db->where('id',$uid)->update('users', array('store_qualified'=>1));
		if(!empty($is)) {
			//扣取用户月费成功，用户从休眠变成正常状态，记录日志
			$this->db->insert('users_status_log', array(
				'uid' => $uid,
				'front_status' => 2,
				'back_status' => 1,
				'type' => 1,
				'create_time' => time()
			));
		}

	}

    /**
	 * 首先统计欠费的会员，正常扣取月费的会员。放进sync_charge_month_fee表中,
	 **/
	function count_charge_month_fee($curDay = '',$curMonthLastDay = ''){

		$curDay = $curDay?$curDay:date('d');
		$curMonthLastDay = $curMonthLastDay?$curMonthLastDay:date('d', strtotime(date('Y-m-01')." +1 month -1 day"));

		$this->db->select('u.id')->from('users u')->where('status',1)->where('upgrade_month_fee_time <',date('Y-m-').$curDay);

		if($curDay==$curMonthLastDay){
			$this->db->where('u.month_fee_date >=',$curDay);
		}else{
			$this->db->where('u.month_fee_date',$curDay);
		}

		$monthFeeMembers = $this->db->get()->result_array();

		$failMonthFeeMem = $this->db->query("select a.create_time fail_time,b.id id from users_month_fee_fail_info a left join users b on a.uid=b.id")->result_array();

		$this->db->trans_begin();

		if($monthFeeMembers)foreach($monthFeeMembers as $item){
			$insert_arr = array(
				'uid' => $item['id'],
			);
			$this->db->replace('sync_charge_month_fee',$insert_arr);
		}

		/** 不敢用insert_batch,生怕有重复的ID统计，使用了replace，效率很低。暂时这样 */

		if($failMonthFeeMem)foreach($failMonthFeeMem as $fail_item){
			$fail_insert_arr = array(
				'uid' => $fail_item['id'],
				'fail_time' => $fail_item['fail_time'],
			);
			$this->db->replace('sync_charge_month_fee',$fail_insert_arr);
		}

		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			$this->m_log->createCronLog('统计交月费，欠月费会员.[执行完成]');
		} else {
			$this->db->trans_rollback();
			$this->m_log->createCronLog('统计交月费,欠月费会员.[执行失败]');
		}

	}

	/**
	 * 	每一分钟扫描扣月费队列 john
	 */
	public function charge_month_fee(){

		$cronName = 'charge_month_fee';
		$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
		if($cron){
			if($cron['false_count'] > 29){
				$this->db->delete('cron_doing', array('cron_name' => $cronName));
				return false;
			}
			$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
			return false;
		}

		$logs = $this->db->from('sync_charge_month_fee')->limit(500)->get()->result_array();

		if($logs){

			$this->db->insert('cron_doing',array(
				'cron_name'=>$cronName
			));

			$this->load->model('m_coupons');
			$this->load->model('m_commission');
			$this->load->model('m_forced_matrix');
			$this->load->model('m_user');

			$join_fee_and_month_fee = $this->m_user->getJoinFeeAndMonthFee();

			foreach($logs as $log){

				$this->db->trans_begin();

				$userInfo = $this->db->select('id,month_fee_rank,month_fee_pool,user_rank,first_monthly_fee_level,country_id')
					->where('id',$log['uid'])->get('users')->row_array();
				if($log['fail_time']){ //欠缴交月费的人
					$userInfo['fail_time'] = $log['fail_time'];
				}

				$this->m_user->do_charge_month_fee($userInfo,$join_fee_and_month_fee);

				if ($this->db->trans_status() === TRUE) {
					$this->db->where('id',$log['id'])->delete('sync_charge_month_fee');
					$this->db->trans_commit();
				} else {
					$this->db->trans_rollback();
					//$this->m_log->createCronLog($log['uid'].'扣取月费.[error]');
				}
			}
		}

		$this->db->delete('cron_doing', array('cron_name' => $cronName));

	}

}
