<?php
/**
	直推人统计情况
 */
class m_referrals_count extends CI_Model
{

    public function __construct(){
        parent::__construct();
    }

	/** 统计直推的人数 */
	public function join_referrals_count($uid,$before_level,$after_level,$id=null){

		$rank_arr = array(
			1=>'member_diamond_num',
			2=>'member_platinum_num',
			3=>'member_silver_num',
			4=>'member_free_num',
            5=>'member_bronze_num'
		);
		$update_fields_minus = $rank_arr[$before_level];
		$update_fields_add = $rank_arr[$after_level];


		/** 统计会员每月推荐的人 */
//		$count_monthly = $this->db->from('users_referrals_count_info_monthly')->where('year_month',date('Ym'))->where('uid',$uid)->get()->row_array();
//		if($count_monthly){
//			$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$uid)
//				->set($update_fields_add,"$update_fields_add+1",FALSE)->update('users_referrals_count_info_monthly');
//			if($after_level < 4){ /** 如果不是注册而是升级，把 $update_fields_minus 的的数量 - 1 */
//				if($before_level == 4){ /** 查看上次的激活時間或者升級時間是否是同一個月 */
//					$enable = $this->db->select('enable_time')->where('id',$id)->get('users')->row_array();
//				}else{
//					$enable = $this->db->query("select create_time enable_time from users_level_change_log where level_type='2' and new_level<old_level and uid={$id} and new_level={$before_level} order by create_time asc")->row_array();
//				}
//				if(date('Ym',strtotime($enable['enable_time'])) == date('Ym')){ //同一個月才把$update_fields_minus 的的数量 - 1
//					$newCount = $count_monthly[$update_fields_minus] > 0 ? $count_monthly[$update_fields_minus] - 1 : 0 ;
//					$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$uid)->set($update_fields_minus, $newCount)->update('users_referrals_count_info_monthly');
//				}
//			}
//		}else{
//			$this->db->insert('users_referrals_count_info_monthly', array(
//				'year_month' => date('Ym'),
//				'uid' => $uid,
//				$update_fields_add => 1
//			));
//		}

		/** 统计会员总共的推荐的人 */
		$count_all = $this->db->from('users_referrals_count_info')->where('uid',$uid)->get()->row_array();

		if($count_all){
			$this->db->where('uid',$uid)->set($update_fields_add,"$update_fields_add+1",FALSE)->update('users_referrals_count_info');
			if(in_array($after_level,array(1,2,3,5))){ /** 如果不是注册而是升级，把 $update_fields_minus 的的数量 - 1 */
				$newCount = $count_all[$update_fields_minus] > 0 ? $count_all[$update_fields_minus] - 1 : 0 ;
				$this->db->where('uid',$uid)->set($update_fields_minus, $newCount)->update('users_referrals_count_info');
			}
		}else{
			$this->db->insert('users_referrals_count_info', array(
				'uid' => $uid,
				$update_fields_add => 1
			));
		}
	}

	/** 降级或退会 触发的直推统计 */
	public function demote_referrals_count($child_id,$uid,$before_level,$after_level,$is_all = TRUE){

		$rank_arr = array(
			1=>'member_diamond_num',
			2=>'member_platinum_num',
			3=>'member_silver_num',
			4=>'member_free_num',
			5=>'member_bronze_num'
		);

		$update_fields_minus = $rank_arr[$before_level];
		$update_fields_add = $rank_arr[$after_level];

		/** 兼容阶段性升级 依次减去 每个月每一次升级的等级数量 */
//		$sql = "select * from users_level_change_log where level_type='2' and new_level<old_level and uid={$child_id} and new_level<{$after_level} order by create_time asc";
//		$level_changes = $this->db->query($sql)->result_array();
//
//		if($level_changes)foreach($level_changes as $key=>$item){
//
//			/** 减去会员每月推荐的人 */
//			$count_monthly = $this->db->where('year_month',date('Ym',strtotime($item['create_time'])))
//				->where('uid',$uid)->get('users_referrals_count_info_monthly')->row_array();
//			if($count_monthly){
//				$count_fields = $count_monthly[$rank_arr[$item['new_level']]];
//				$newCount = $count_fields > 0 ? $count_fields - 1 : 0 ;
//
//				$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$uid)
//					->set($rank_arr[$item['new_level']],$newCount)->update('users_referrals_count_info_monthly');
//
//				$is_exist = $this->db->query("select * from users_level_change_log where level_type='2' and new_level<old_level and uid={$child_id} and new_level={$after_level} order by create_time asc")->row_array();
//				if($key == 0 && !$is_exist){ /** 如果是当月升级，并且降级。把最初升级的月份记录降级的等级+1 不然$uid所有等级的统计都是0 */
//					if($after_level == 4){ //如果是退会,且注册时间和升级时间不是同一个，就不用把最初升级的月份记录降级的等级+1
//						$enable = $this->db->select('enable_time')->where('id',$child_id)->get('users')->row_array();
//						if(date('Ym',strtotime($enable['enable_time'])) != date('Ym',strtotime($item['create_time']))){
//							continue;
//						}
//					}
//					$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$uid)
//						->set($update_fields_add,"$update_fields_add+1",FALSE)->update('users_referrals_count_info_monthly');
//				}
//			}
//		}

		if($is_all){

			/** 统计会员总共的推荐的人 */
			$count_all = $this->db->from('users_referrals_count_info')->where('uid',$uid)->get()->row_array();
			if($count_all){
				$new_minus = $count_all[$update_fields_minus] > 0 ? $count_all[$update_fields_minus] - 1 : 0 ;
				if($after_level == 4){ //退会，不做免费会员统计
					$this->db->where('uid',$uid)
						->set($update_fields_minus,$new_minus)->update('users_referrals_count_info');
				}else{
					$this->db->where('uid',$uid)->set($update_fields_add,"$update_fields_add+1",FALSE)
						->set($update_fields_minus,$new_minus)->update('users_referrals_count_info');
				}
			}
		}
	}

	/** 删除免费会员 触发的直推人统计 */
	public function delete_referrals_count($uid,$enable_time){

		/** 减去会员每月推荐的人 */
//		$count_monthly = $this->db->where('year_month',date('Ym',strtotime($enable_time)))
//			->where('uid',$uid)->get('users_referrals_count_info_monthly')->row_array();
//
//		if($count_monthly){
//			$count_fields = $count_monthly['member_free_num'];
//			$newCount = $count_fields > 0 ? $count_fields - 1 : 0 ;
//			$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$uid)
//				->set('member_free_num',$newCount)->update('users_referrals_count_info_monthly');
//		}

		/** 统计会员总共的推荐的人 */
		$count_all = $this->db->from('users_referrals_count_info')->where('uid',$uid)->get()->row_array();
		if($count_all){
			$new_minus = $count_all['member_free_num'] > 0 ? $count_all['member_free_num'] - 1 : 0 ;
			$this->db->where('uid',$uid)->set('member_free_num',$new_minus)->update('users_referrals_count_info');
		}

	}

	/** 統計之前的老數據 */
	function count_old_data(){
		$sql = "select id,parent_id,user_rank,status,enable_time from users where status!=0 and status!=4 and parent_id!=0 and parent_id!=1380100217";
		$users = $this->db->query($sql)->result_array();

		$rank_arr = array(
			1=>'member_diamond_num',
			2=>'member_platinum_num',
			3=>'member_silver_num',
			4=>'member_free_num'
		);
		$this->db->trans_start();
		foreach($users as $k=>$user){

//			if($user['status'] == 4 || $user['user_rank'] == 4){ /** 公司账户、免费账户不需要检测升级后的操作,　*/
//					// 统计会员每月推荐的人
//				$count_monthly = $this->db->from('users_referrals_count_info_monthly')->where('year_month',date('Ym',strtotime($user['enable_time'])))->where('uid',$user['parent_id'])->get()->row_array();
//				if($count_monthly){
//					$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$user['parent_id'])
//						->set('member_free_num',"member_free_num+1",FALSE)->update('users_referrals_count_info_monthly');
//				}else{
//					$this->db->insert('users_referrals_count_info_monthly', array(
//						'year_month' => date('Ym',strtotime($user['enable_time'])),
//						'uid' => $user['parent_id'],
//						'member_free_num' => 1
//					));
//				}
//				$count_all = $this->db->from('users_referrals_count_info')->where('uid',$user['parent_id'])->get()->row_array();
//				if($count_all){
//					$this->db->where('uid',$user['parent_id'])->set('member_free_num',"member_free_num+1",FALSE)->update('users_referrals_count_info');
//				}else{
//					$this->db->insert('users_referrals_count_info', array(
//						'uid' => $user['parent_id'],
//						'member_free_num' => 1
//					));
//				}
//				continue;
//			}

//			$sql = "select old_level,new_level,create_time from users_level_change_log where level_type='2' and new_level!=old_level and uid={$user['id']} order by create_time asc";
//			$level_changes = $this->db->query($sql)->result_array();
//			if( $level_changes)foreach($level_changes as $item){
//
//				if($item['create_time'] == '0000-00-00 00:00:00'){
//					$item['create_time'] = $user['enable_time'];
//				}
//				$update_fields_add = $rank_arr[$item['new_level']];
//				if($item['new_level'] < $item['old_level']){ //升级操作
//
//					// 统计会员每月推荐的人 */
//					$count_monthly = $this->db->from('users_referrals_count_info_monthly')->where('year_month',date('Ym',strtotime($item['create_time'])))->where('uid',$user['parent_id'])->get()->row_array();
//					if($count_monthly){
//						$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$user['parent_id'])
//							->set($update_fields_add,"$update_fields_add+1",FALSE)->update('users_referrals_count_info_monthly');
//
//						if($item['new_level'] < 4){ //如果不是注册而是升级，把 $update_fields_minus 的的数量 - 1 */
//							if($item['old_level'] == 4){ // 查看上次的激活時間或者升級時間是否是同一個月
//								$enable = $this->db->select('enable_time')->where('id',$user['id'])->get('users')->row_array();
//							}else{
//								$enable = $this->db->query("select create_time enable_time from users_level_change_log where level_type='2' and new_level<old_level and uid={$user['id']} and new_level={$item['old_level']} order by create_time asc")->row_array();
//							}
//							if(date('Ym',strtotime($enable['enable_time'])) == date('Ym')){ //同一個月才把$update_fields_minus 的的数量 - 1
//								$update_fields_minus =$rank_arr[$item['old_level']];
//								$newCount = $count_monthly[$update_fields_minus] > 0 ? $count_monthly[$update_fields_minus] - 1 : 0 ;
//								$this->db->where('year_month',$count_monthly['year_month'])->where('uid',$user['parent_id'])->set($update_fields_minus, $newCount)->update('users_referrals_count_info_monthly');
//							}
//						}
//					}else{
//
//						$this->db->insert('users_referrals_count_info_monthly', array(
//							'year_month' => date('Ym',strtotime($item['create_time'])),
//							'uid' =>  $user['parent_id'],
//							$update_fields_add => 1
//						));
//					}
//
//				}else if($item['new_level'] > $item['old_level']){ //降级处理
//					$this->demote_referrals_count($user['id'],$user['parent_id'],$item['old_level'],$item['new_level'],FALSE);
//				}
//			}

			/** 统计会员总共的推荐的人 */
			$update_fields_add = $rank_arr[$user['user_rank']];
			$count_all = $this->db->from('users_referrals_count_info')->where('uid',$user['parent_id'])->get()->row_array();
			if($count_all){
				$this->db->where('uid',$user['parent_id'])
					->set($update_fields_add,"$update_fields_add+1",FALSE)->update('users_referrals_count_info');
			}else{
				$this->db->insert('users_referrals_count_info', array(
					'uid' => $user['parent_id'],
					$update_fields_add => 1
				));
			}
			var_dump($k);
		}
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return 'Failure';
		}else{
			return "Success";
		}
	}

}
