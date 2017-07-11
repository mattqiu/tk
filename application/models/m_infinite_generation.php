<?php

class M_infinite_generation extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('M_generation_sales','m_generation_sales');
        $this->load->model('m_commission');
    }

    //是否在 上個月最後一天之前 是 合格鑽石會員
    public function isDiamond($uid){

        //必须是钻石会员 //升級鑽石級別的時間必須在上月最近一天之前
        $user_count = $this->db->from('users u')
            ->join('user_upgrade_log uul','uul.uid = u.id and uul.upgrade_rank = u.user_rank')
            ->where('u.id',$uid)->where('uul.upgrade_rank',LEVEL_DIAMOND)
            ->where('uul.create_time <=',get_last_timestamp())
            ->count_all_results();

        return $user_count ? TRUE : FALSE;
    }

    /** 无限代奖 满足有3000个银级会员 至少2个分支 每个分支最多计数1500银级会员 */
    public function infinityCondition($uid){

        $referrer =$this->db->select('id,user_rank')->from('users')->where('parent_id',$uid)->get()->result_array();

        if(count($referrer) < TEAM_COUNT_LIMIT ){ //至少有2个分支以上
            return FALSE;
        }

        $all_count = 0 ;
        foreach($referrer as $refer){

             $add = 0; //如果第一代会员满足银级以上，统计就要加上第一代会员
             $count = $this->m_generation_sales->getChildCount($refer['id']);

             if($refer['user_rank'] != 4){ //不是免費的，加上本身
                 $add = 1;
             }
		     unset($refer);
             $count = $count + $add > TEAM_MEMBER_LIMIT ? TEAM_MEMBER_LIMIT : $count + $add; //加上本身（直推會員）

             $all_count = $all_count + $count;
        }
		unset($referrer);
        if ($all_count >= TEAM_SILVER_LIMIT){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**  15號 定时执行  统计合格人数 */
    public function countInfinity(){

        $this->db->trans_begin();

		$this->load->model('M_order','m_order');

        //上個月最後一天之前 是 合格鑽石會員
        $users = $this->db->select('u.id')->from('users u')
            ->join('user_upgrade_log uul','uul.uid = u.id and uul.upgrade_rank = u.user_rank','left')
            ->where('u.sale_rank',5)
            ->where('uul.upgrade_rank',LEVEL_DIAMOND)
            ->where('uul.create_time <=',get_last_timestamp())
            ->get()->result_array();
        foreach($users as $k => $user){

            //必须有30个合格客户
            $is_qualified_30 = $this->m_order->getPassOrderCount($user['id']);
            if(!$is_qualified_30){ //如果不满足30个合格客户
                unset($users[$k]);
                continue;
            }
            /** 必须有3000以上的银级会员 */
            $is_3000 = $this->m_generation_sales->getChildCount($user['id']);
            if($is_3000 < TEAM_SILVER_LIMIT){  //如果不满足3000 去除
                unset($users[$k]);
                continue;
            }
        }

        if($users)foreach($users as $user){
            /** 无限代奖 满足有3000个银级会员 至少2个分支 每个分支最多计数1500银级会员 */
            if($this->infinityCondition($user['id'])){
				$this->db->replace('infinity_generation_count',array('uid'=>$user['id']));
            }
        }


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
    }

	/**
	 * 15号 统计金额
	 */
	public function countInfinity2(){

		ini_set('memory_limit', '256M');

		$this->db->trans_begin();

		$time = time();
		$month = date('Y-m',get_last_timestamp());

		$data = $this->db->get('infinity_generation_count')->result_array();
		foreach($data as $user){
			/** @var  $child_ids  得到領導人11層以下的所有人數和销售利润总额*/
			 $child_ids = $this->m_generation_sales->getChildIdsAndMoney($user['uid']);
			//检测 不能重复插入
               $is_duplicate = $this->db->from('infinity_generation_log')
                    ->where('qualified_time',$month)
                    ->where('uid',$user['uid'])->count_all_results();
                if(!$is_duplicate){
			  $log = array('uid'=>$user['uid'],'money'=>$child_ids['all_cash'] * INFINITY_RATIO ,
			     'create_time'=>$time,'qualified_time'=>$month,'child_count'=>$child_ids['child_count'],
			     'total_money'=>$child_ids['all_cash']
			 );
			 $this->db->replace('infinity_generation_log',$log);
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}

    //15号  从infinity_generation_log上个月取得符合的用户
    public function grantInfinityCash($month = false){

		if($month === false){
			$month = date('Y-m',get_last_timestamp());
		}
        //从infinity_generation_log取得上个月符合的用户
        $logs = $this->db->from('infinity_generation_log')->where('qualified_time',$month)->where('grant',0)->get()->result_array();
        //循环发放
        if($logs){
            foreach($logs as $log){

                //插入佣金记录
                $this->m_commission->commissionLogs($log['uid'],REWARD_4,$log['money']);

                /* 佣金自动转分红点 */
                $this->load->model('m_profit_sharing');
                $rate = $this->m_profit_sharing->getProportion($log['uid'], 'sale_commissions_proportion') / 100;
                $commissionToPoint = 0;
                if ($rate > 0) {
                    $commissionToPoint = tps_money_format($log['money'] * $rate);
                    if($commissionToPoint>=0.01){
                        $this->db->where('id', $log['uid'])->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+' . $commissionToPoint, FALSE)->update('users');

                        $comm_id = $this->m_commission->commissionLogs($log['uid'],17,-1*$commissionToPoint); //佣金轉分紅點

                        $this->m_profit_sharing->createPointAddLog(array(
                            'uid' => $log['uid'],
                            'commission_id' => $comm_id,
                            'add_source' => 1,
                            'money' => $commissionToPoint,
                            'point' => $commissionToPoint
                        ));
                    }
                }
                $real_cash = $log['money'] - $commissionToPoint;
                $this->db->where('id', $log['uid'])->set('amount','amount+'.$real_cash,FALSE)->set('infinite_commission','infinite_commission+'.$log['money'],FALSE)->update('users');
                $this->db->where('id',$log['id'])->set('grant',1,FALSE)->update('infinity_generation_log');
            }
        }
    }



}
