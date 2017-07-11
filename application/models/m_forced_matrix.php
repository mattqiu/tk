<?php

class m_forced_matrix extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* 2x5矩阵第一个节点 */
    public function root_tree(){
        $sql="select * from user_sort_2x5 where user_id=".config_item('mem_root_id');
        $result=$this->db->query($sql)->result();
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    /* 初始化 */
    public function init_2x5(){
        $this->db->insert('user_sort_2x5', array(
                'user_id' =>config_item('mem_root_id'),
                'name' => 'root',
            )
        );
    }

    /***
     * 2x5排序
     * @param $user_id
     */
    public function userSort2x5($user_id){
        //如果用户未排序
        if(!$this->CheckUserExistFor2x5($user_id)){
            $pay_parent_id = $this->findParentIDByRank($this->userInfo($user_id)->parent_id);
            $arr = array($pay_parent_id);
            if($this->CheckUserExistFor2x5($pay_parent_id)){
                //挂靠节点
                $this->locateNodeById($arr,$user_id);
            }
        }
    }

    /***
     * 寻找空位-->排序
     * @param $arr  ID数组
     * @param $user_id  要排序的ID
     * @return bool     成功返回true
     */
    public function locateNodeById($arr,$user_id){
        $users=array();
        foreach($arr as $value) {
            $sql = "select * from user_sort_2x5 where user_id=$value";
            $result = $this->db->query($sql)->result();
            if (!empty($result)) {
                $left_id = $result[0]->left_id;
                $right_id = $result[0]->right_id;
                array_push($users,$left_id);
                array_push($users,$right_id);

                if ($left_id == null) {
                    $leader_id = $value;
                    $level = $result[0]->level + 1;
                    $left_id = $user_id;
                    $this->addNode($user_id, $leader_id, $level);
                    $this->db->where('user_id', $leader_id)->update('user_sort_2x5', array('left_id' => $left_id));
                    return true;
                }
                if ($right_id == null) {
                    $leader_id = $value;
                    $level = $result[0]->level + 1;
                    $right_id = $user_id;
                    $this->addNode($user_id, $leader_id,$level);
                    $this->db->where('user_id', $leader_id)->update('user_sort_2x5', array('right_id' => $right_id));
                    return true;
                }
            }
        }
        $this->locateNodeById($users,$user_id);
    }

    /* 插入2x5数据 */
    public function addNode($user_id,$leader_id,$level){
		$name = $this->userInfo($user_id)->name;
		$pay_parent_id = $this->findParentIDByRank($this->userInfo($user_id)->parent_id);
        $this->db->insert('user_sort_2x5', array(
                'user_id' =>$user_id,
                'name' => $name,
                'pay_parent_id'=>$pay_parent_id,
                'leader_id' => $leader_id,
                'level'=>$level,
                'create_time'=>date("Y-m-d H:i:s", time())
            )
        );

        //每个leader总人数+1
        $leaders = $this->findUserAllLeader($user_id);
        if(count($leaders) != 0){
            foreach($leaders as $leader){
                $leader=(int)$leader;
                $sql="update user_sort_2x5 set child_count=child_count+1 where user_id=$leader";
                $this->db->query($sql);
            }
        }
    }

    /***获取用户升级时间***/
    public function getUserUpgradeTime($user_id){
        $sql="select pay_time from user_upgrade_month_order where uid=$user_id and status=2";
        $result=$this->db->query($sql)->result();
        if(!empty($result)){
            return $result[0]->pay_time;
        }else{
            $sql="select pay_time from user_upgrade_order where uid=$user_id and status=2";
            $result=$this->db->query($sql)->result();
            if(!empty($result)){
                return $result[0]->pay_time;
            }else{
                return "0000-00-00 00:00:00";
            }
        }
    }


    /* 138初始化 */
    public function init_138(){
        $date = date("Y-m-d H:i:s",time());
        $data = array('user_id'=>  config_item('mem_root_id'),'name'=>'root', 'x'=>1,'y'=>1,'create_time'=>$date);
        $this->db->insert('user_coordinates',$data);
    }

    /* 查询138 最大id的坐标 */
    public function getMaxId(){
        $maxId = $this->db->query("select * from user_coordinates where id=(select max(id) from user_coordinates)")->row_array();
    }

    /* 获取用户所有信息 */
    public function userInfo($user_id){
        $sql="select * from users where id=$user_id";
        $result=$this->db_slave->query($sql)->result();
        if(!empty($result)){
            return $result[0];
        }else{
            return false;
        }
    }


    /* 查看用户是否进入138排序 */
    public function CheckUserExistFor138($user_id){
        $sql="select * from user_coordinates where user_id=".$user_id;
        $user=$this->db->query($sql)->result();
        if(!empty($user)){
            return true;
        }else{
            return false;
        }
    }

    /* 查看用户是否进入2x5排序 */
    public function CheckUserExistFor2x5($user_id){
        $sql = "select * from user_sort_2x5 where user_id=".$user_id;
        $user = $this->db->query($sql)->result();
        if(empty($user)){
            return false;
        }else{
            return true;
        }
    }

    /* 138用户排序 */
    public function userSort138(){

        //如果该任务还在处理,直接返回
        $cron = $this->db->query("select * from cron_doing where cron_name='userSort138'")->row_array();
		if($cron){
			if($cron['false_count'] > 99){
				$this->db->delete('cron_doing', array('cron_name' => 'userSort138'));
				return false;
			}
			$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
			return false;
		}

        //插入任务计划名称
        $this->db->insert('cron_doing',array('cron_name'=>'userSort138'));

        $res = $this->db->query("select * from save_user_for_138")->result_array();
		foreach ($res as $v)
		{
			$user_id = $v['user_id'];
			$user = $this->db->query("select * from user_coordinates where user_id = {$user_id}")->row_array();
			if (empty($user))
			{
				$last_record = $this->db->query("select * from user_coordinates where id = (select max(id) from user_coordinates)")->row_array();
				$x = $last_record['x'];
				$y = $last_record['y'];

				//如果X轴数据等于138，则x重置，y轴+1
				if ($x == MAX_X)
				{
					$x = 0;
					$y = ++$y;
				}

				$x = ++$x;
				$name = $this->userInfo($user_id)->name;
				$month_fee_rank = $this->userInfo($user_id)->user_rank;
				$create_time = date("Y-m-d H:i:s", time());
				$data = array('user_id' => $user_id, 'name' => $name, 'x' => $x, 'y' => $y, 'month_fee_rank' => $month_fee_rank, 'create_time' => $create_time);
				if ($this->db->insert('user_coordinates', $data))
				{
					$this->db->query("delete from save_user_for_138 where user_id = $user_id");
				}
			}
			else
			{
				$this->db->query("delete from save_user_for_138 where user_id = {$user_id}");
			}
		}
		//结束计划任务
		$this->db->query("delete from cron_doing where cron_name='userSort138'");
    }


    /* 保存138排序的用户 */
    public function save_user_for_138($user_id){
        $res = $this->db->query("select * from save_user_for_138 where user_id = {$user_id}")->result();
        $user = $this->db->query("select * from user_coordinates where user_id = {$user_id}")->row_array();
        if(empty($res) && empty($user)) {
			$this->db->insert('save_user_for_138',array(
				'user_id'=>$user_id,
				'create_time'=>date("Y-m-d H:i:s",time())
			));
        }
    }

    /* 获取138排序下级总人数 */
    public function getChildrenFor138($user_id){
        $sql="select x,y from user_coordinates where user_id=".$user_id;
        $result=$this->db_slave->query($sql)->row_array();
        $children_id=array();
        $children_rank=array();
        if(!empty($result)){
            $x=$result['x'];
            $y=$result['y'];
            $sql="select * from user_coordinates where x=$x and y>$y";
            $children=$this->db_slave->query($sql)->result_array();
            if(!empty($children)){
                foreach($children as $child){
                    if(count($children_id)>=3){
                        break;
                    }
                    
                   //修正当138点阵的某个用户已经删除不存在的情况下出现的Bug 
                    $userInfo = $this->userInfo($child['user_id']);
                    if(!empty($userInfo)) {   //存在此用户才压栈
                        array_push($children_id,$child['user_id']);
                        array_push($children_rank,$userInfo->month_fee_rank);
                    }
                }
                $children_num=count($children);
            }else{
                $children_num=0;
            }

			//如果月费等级为4，则选择店铺等级
			$month_fee_rank = $this->userInfo($user_id)->month_fee_rank;
			if($month_fee_rank == 4)
			{
				$month_fee_rank = $this->userInfo($user_id)->user_rank;
			}

            $data=array(
                'x'=>$x,
                'y'=>$y,
                'month_fee_rank'=>$month_fee_rank,
                'user_id'=>$user_id,
                'children_id'=>$children_id,
                'children_rank'=>$children_rank,
                'children_num'=>$children_num
            );
            return $data;
        }
    }

    /* 获得合格直推人 */
    public function getQSO($user_id){
        $QSO_count=0;       //合格直推人
        $sql="select id,user_rank,month_fee_rank from users where parent_id=".$user_id;
        $children=$this->db->query($sql)->result();
        if(!empty($children)){
            foreach($children as $value){
                //不包括免费直推
                if(in_array($value->user_rank,config_item('pay_rank'))){
                    ++$QSO_count;
                }
            }
        }
        return $QSO_count;
    }

	/* 会员订单销售情况 */
	public function get_qualified_order($user_id){
		if(config_item('2x5_order_rule'))
		{
			$res = $this->db->query("select * from users_store_sale_info WHERE uid = {$user_id}")->row_array();
			$order_info = count($res)>0?$res:array('orders_num'=>0,'sale_amount'=>0);
			return $order_info;
		}
		else
		{
			return array('orders_num'=>99,'sale_amount'=>9999);
		}
	}

    /* 判断是否公司号 */
    public function isCompanyId($id){
        $sql="select parent_id from users where id=".$id;
        $result=$this->db->query($sql)->result();
        if(!empty($result)){
            $parent_id=$result[0]->parent_id;
            if($parent_id==0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /* 递归付费的parent_id */
    public function findParentIDByRank($parent_id){

        //付费的等级
        $pay_rank = array(1,2,3,5);

        /**如果是公司号,直接返回**/
        if($this->isCompanyId($parent_id)){
            return $parent_id;
        }
        $sql="select id,user_rank,month_fee_rank,parent_id from users where id=".$parent_id;
        $result=$this->db->query($sql)->result();
        $id=$result[0]->id;
        $month_fee_rank=$result[0]->month_fee_rank;
		$user_rank = $result[0]->user_rank;
        $parent_id=$result[0]->parent_id;
        //如果是付费的parent_id,则返回
        if(in_array($month_fee_rank,$pay_rank) || in_array($user_rank,$pay_rank)){
            return $id;
        }
        //否则一直递归寻找
        return $this->findParentIDByRank($parent_id);
    }

    /* 寻找号码比UserID小的ParentID */
    public function findSmallerNumForParentId($parent_id,$user_id){
        /**如果是公司号,直接返回**/
        if($this->isCompanyId($parent_id)){
            return $parent_id;
        }
        $sql="select id,month_fee_rank,parent_id from users where id=".$parent_id;
        $result=$this->db->query($sql)->result();
        $id=$result[0]->id;
        $month_fee_rank=$result[0]->month_fee_rank;
        $parent_id=$result[0]->parent_id;
        //如果是付费的parent_id
        if(($parent_id<$user_id) && (in_array($month_fee_rank,config_item('pay_rank')))){
            return $parent_id;
        }
        //否则一直递归寻找
        return $this->findParentIDByRank($parent_id);
    }


    /***
     * @param $user_id      当前升级用户
     * @param $parent_id    当前升级用户的直推人
     * @param $fee_num      月费数
     * @return bool         执行成功返回true
     */
//    public function  payCommissionToParent($user_id,$parent_id,$fee_num,$first_month_fee){
//
//        $this->load->model('o_cash_account');
//
//        //1.查询parent_id 的月费等级
//        $parent_month_fee_rank = $this->userInfo($parent_id)->month_fee_rank;
//		$user_month_fee_rank = $this->userInfo($user_id)->month_fee_rank;
//
//		/** 如果月费为免费，取店铺等级 */
//		if($parent_month_fee_rank == 4){
//			$parent_month_fee_rank = $this->userInfo($parent_id)->user_rank;
//		}
//
//		/** 如果月费为免费，取店铺等级  */
//		if($user_month_fee_rank == 4){
//			$user_month_fee_rank = $this->userInfo($user_id)->user_rank;
//		}
//
//		//当前$parent_id的状态（1=>合格状态 by john）
//		$parent_status = $this->userInfo($parent_id)->store_qualified;
//
//		//根据$fee_num的数量,发放几次佣金
//		for($i=0;$i<$fee_num;$i++)
//		{
//			//发放第一个月的佣金
//			if($i == 0)
//			{
//				//如果first_month_fee不等于0，说明是首次扣月费，否则按照现在的月费等级走流程
//				$user_month_fee_rank = $first_month_fee != 0 ? $first_month_fee : $user_month_fee_rank;
//			}
//
//			if(in_array($parent_month_fee_rank,config_item('pay_rank')) && $parent_status == 1)
//			{
//				switch ($parent_month_fee_rank) {
//                    case 1:
//                        if($user_month_fee_rank==LEVEL_DIAMOND)$commission=DIAMOND_CASH;
//                        if($user_month_fee_rank==LEVEL_GOLD)$commission=GOLD_CASH;
//                        if($user_month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//                        if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                        if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//                        break;
//                    case 2:
//                        if($user_month_fee_rank<=LEVEL_GOLD)$commission=GOLD_CASH;
//                        if($user_month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//                        if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                        if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//                        break;
//                    case 3:
//                        if($user_month_fee_rank<=LEVEL_SILVER)$commission=SILVER_CASH;
//                        if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                        if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//                        break;
//                    case 4:
//                        $commission=FREE_CASH;
//                        break;
//                    case 5:
//                        if($user_month_fee_rank<=LEVEL_SILVER)$commission=COPPER_CASH;
//                        if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                        if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//                        break;
//				}
//
//                if(config_item('month_half_price')){
//                    $commission = $commission/2;
//                }
//				//$commission = tps_money_format($commission);
//                $commission = sprintf("%0.2f",$commission);
//				$date = date("Y-m-d H:i:s", time());
//
//				/**记录到commission_log表**/
//				if($commission>0)
//				{
//                    $this->o_cash_account->createCashAccountLog(array(
//                        'uid'=>$parent_id,
//                        'item_type'=>REWARD_1,
//                        'amount'=>tps_int_format($commission*100),
//                        'create_time'=>$date,
//                        'related_uid'=>$user_id,
//                    ));
//
//					/* 佣金自动转分红点 */
//					$this->load->model('m_profit_sharing');
//					$rate = $this->m_profit_sharing->getProportion($parent_id, 'sale_commissions_proportion') / 100;
//					$commissionToPoint = 0;
//					if ($rate > 0) {
//						$commissionToPoint = tps_money_format($commission * $rate);
//						if($commissionToPoint>=0.01){
//							$this->db->where('id', $parent_id)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_force', 'profit_sharing_point_from_force+' . $commissionToPoint, FALSE)->update('users');
//
//							$this->load->model('m_commission');
//							$comm_id = $this->m_commission->commissionLogs($parent_id, 17, -1 * $commissionToPoint); //佣金轉分紅點
//							$this->m_profit_sharing->createPointAddLog(array(
//								'uid' => $parent_id,
//								'commission_id' => $comm_id,
//								'add_source' => 2,
//								'money' => $commissionToPoint,
//								'point' => $commissionToPoint
//							));
//						}
//					}
//					/***累加amount、 personal_commission字段值***/
//					$this->db->where('id', $parent_id)
//						->set('amount', 'amount+' . ($commission - $commissionToPoint), FALSE)
//						->set('personal_commission', 'personal_commission+' . $commission, FALSE)->update('users');
//				}
//			}
//		}
//		return true;
//    }

    /*
     * @param $user_id      当前升级用户
     * @param $leader_id    当前升级用户的其中一个上级
     * @param $fee_num      要缴纳的月费数
     */
//    public function getCommission($user_id,$leader_id,$fee_num,$first_month_fee){
//
//        $this->load->model('o_cash_account');
//        $commission = 0.0;      //所得佣金
//        $QSO_count = $this->getQSO($leader_id);       //合格直推人
//		$qualified_order = $this->get_qualified_order($leader_id);
//
//		/**月费等级**/
//        $leader_month_fee_rank = $this->userInfo($leader_id)->month_fee_rank;
//		$user_month_fee_rank = $this->userInfo($user_id)->month_fee_rank;
//
//		/** leader_id如果月费为免费，取店铺等级 by john */
//		if($leader_month_fee_rank == 4){
//			$leader_month_fee_rank = $this->userInfo($leader_id)->user_rank;
//		}
//
//		/** user如果月费为免费，取店铺等级 */
//		if($user_month_fee_rank == 4){
//			$user_month_fee_rank = $this->userInfo($user_id)->user_rank;
//		}
//
//		//是否合格
//        $leader_status = $this->userInfo($leader_id)->store_qualified;   //当前leader的状态(1=>合格状态 by john)
//
//		//根据$fee_num的数量,发放几次佣金
//		for($i=0;$i<$fee_num;$i++)
//		{
//			//发放第一个月的佣金
//			if($i == 0)
//			{
//				//如果first_month_fee不等于0，说明是首次扣月费，否则按照现在的月费等级走流程
//				$user_month_fee_rank = $first_month_fee != 0 ? $first_month_fee : $user_month_fee_rank;
//			}
//
//			/**合格直推人达到2个或以上，且状态为1，且2个或以上销售订单,且销售金额大于等于50美金**/
//			if($QSO_count>=2 && $leader_status==1 && in_array($leader_month_fee_rank,config_item('pay_rank')))
//			{
//				//用户能拿的层数
//				$levelNum = $this->get_level_num($QSO_count,$qualified_order['orders_num'],$qualified_order['sale_amount']);
//
//				$user_level = $this->userInfo2x5($user_id)->level;            //当前升级用户的所在层数
//				$leader_level = $this->userInfo2x5($leader_id)->level;        //上级所在层数
//
//				//如果升级ID在leader的层级内，则拿升级用户的佣金
//				if($user_level - $leader_level <= $levelNum){
//					switch($leader_month_fee_rank){
//						//钻石级
//						case 1:
//							if($user_month_fee_rank==LEVEL_DIAMOND)$commission=DIAMOND_CASH;
//							if($user_month_fee_rank==LEVEL_GOLD)$commission=GOLD_CASH;
//							if($user_month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//							if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//							if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//							break;
//						//黄金级
//						case 2:
//							if($user_month_fee_rank<=LEVEL_GOLD)$commission=GOLD_CASH;
//							if($user_month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//							if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                            if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//							break;
//						//白银级
//						case 3:
//							if($user_month_fee_rank<=LEVEL_SILVER)$commission=SILVER_CASH;
//							if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                            if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//							break;
//						//免费用户
//						case 4:
//							$commission=FREE_CASH;
//							break;
//
//                        //铜级
//                        case 5:
//                            if($user_month_fee_rank<=LEVEL_SILVER)$commission=COPPER_CASH;
//                            if($user_month_fee_rank==LEVEL_FREE)$commission=FREE_CASH;
//                            if($user_month_fee_rank==LEVEL_COPPER)$commission=COPPER_CASH;
//                            break;
//					}
//                    if(config_item('month_half_price')){
//                        $commission = $commission/2;
//                    }
//
//                    $commission = sprintf("%0.2f",$commission);
//					$date = date("Y-m-d H:i:s", time());
//
//					/**记录到commission_log表**/
//					if($commission>0)
//					{
//                        $this->o_cash_account->createCashAccountLog(array(
//                            'uid'=>$leader_id,
//                            'item_type'=>REWARD_1,
//                            'amount'=>tps_int_format($commission*100),
//                            'create_time'=>$date,
//                            'related_uid'=>$user_id,
//                        ));
//
//						/* 佣金自动转分红点 */
//						$this->load->model('m_profit_sharing');
//						$rate = $this->m_profit_sharing->getProportion($leader_id, 'sale_commissions_proportion') / 100;
//						$commissionToPoint = 0;
//						if ($rate > 0) {
//							$commissionToPoint = tps_money_format($commission * $rate);
//							if($commissionToPoint>=0.01){
//								$this->db->where('id', $leader_id)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_force', 'profit_sharing_point_from_force+' . $commissionToPoint, FALSE)->update('users');
//
//								$this->load->model('m_commission');
//								$comm_id = $this->m_commission->commissionLogs($leader_id, 17, -1 * $commissionToPoint); //佣金轉分紅點
//								$this->m_profit_sharing->createPointAddLog(array(
//									'uid' => $leader_id,
//									'commission_id' => $comm_id,
//									'add_source' => 2,
//									'money' => $commissionToPoint,
//									'point' => $commissionToPoint
//								));
//							}
//						}
//						/***累加amount、 personal_commission字段值***/
//						$this->db->where('id', $leader_id)
//							->set('amount', 'amount+' . ($commission - $commissionToPoint), FALSE)
//							->set('personal_commission', 'personal_commission+' . $commission, FALSE)->update('users');
//					}
//				}
//			}
//		}
//        return true;
//    }

    /*
     * @param $date 日期
     * 获取指定日期的商城订单利润
     */
    public function get_total_profit($date){
        $this->load->model("tb_trade_orders");
//        $sql="select sum(order_profit_usd) as total_profit from mall_orders where create_time LIKE '$date%' ";
//        $result=$this->db->query($sql)->row_object();
//        $profitWohao = $result?$result->total_profit:0;

        $result = $this->tb_trade_orders->get_sum_auto([
            "column"=> "order_profit_usd",
            "where"=>["create_time like"=>["$date"=>"after"]]
        ]);
        $profitWohao = isset($result['order_profit_usd'])?$result['order_profit_usd']:0;

//        $sql="select sum(order_profit_usd) as total_profit from trade_orders where pay_time LIKE '$date%' ";
//        $result=$this->db->query($sql)->row_object();
//        $profitTpsMall = $result?$result->total_profit/100:0;
        $result = $this->tb_trade_orders->get_sum_auto([
            "column"=> "order_profit_usd",
            "where"=>["pay_time like"=>["$date"=>"after"]]
        ]);
        $profitTpsMall =isset($result['order_profit_usd'])?$result['order_profit_usd']/100:0;

        return $profitWohao+$profitTpsMall;
    }

    /* 查找用户2x5信息 */
    public  function userInfo2x5($user_id){
        $sql="select * from user_sort_2x5 where user_id=$user_id";
        $result=$this->db->query($sql)->result();
        if(!empty($result)){
            return $result[0];
        }else{
            return false;
        }
    }

	/***
	 * @param $user_id	用户ID
	 * @param $fee_num	月费数量
	 * @param $first_month_fee	判断是否第一次交月费，为0按照正常逻辑走，为1,2,3时则对应的等级发放佣金
	 */
    public function memberFromFreeToPaid($user_id,$fee_num,$first_month_fee){

        return true;
//        if($fee_num == 0){
//            return;
//        }
//
//        $this->userSort2x5($user_id);
//        $leaders = $this->findUserAllLeader($user_id);            //查找当前升级用户的所有leader
//        $parent_id = $this->userInfo($user_id)->parent_id;        //当前升级用户的parent_id
//        if(!empty($leaders)){
//            /***循环发放佣金***/
//            foreach($leaders as $leader_id){
//                //如果是leader,则拿leader佣金，如果是parent，则拿parent佣金
//                if($leader_id !== $parent_id){
//                    $this->getCommission($user_id,$leader_id,$fee_num,$first_month_fee);
//                } else {
//                    $this->payCommissionToParent($user_id,$parent_id,$fee_num,$first_month_fee);
//                }
//            }
//            unset($leaders);//Todo@terry
//        }
    }


    /* 寻找用户的全部上级 */
    public function findUserAllLeader($user_id){
        $leaders_id = array();
        $sql = "select leader_id from user_sort_2x5 where user_id=$user_id";
        $result = $this->db->query($sql)->result();
        if(!empty($result)){
            $leader_id = $result[0]->leader_id;       //第一个上级
            while($leader_id != 0){
                array_push($leaders_id,$leader_id);
                $user_id = $leader_id;
                $sql = "select leader_id from user_sort_2x5 where user_id=$user_id";
                $result = $this->db->query($sql)->result();
                $leader_id = $result[0]->leader_id;
                unset($result);//Todo@terry
            }
        }
        return $leaders_id;
    }

    /* 获取每个用户tree下面的人数 */
    public function getSumMember($user_id){
        $users=array();
        $children=array();
        array_push($users,$user_id);
        $this->getChildrenFor2x5($users,$children);
        return $children;
    }

   /* 计算每个用户tree下面的人数 */
    public function getChildrenFor2x5($users,&$children){
        //总人数
        $rowNum=array();
        if(count($users)==0){
            return $children;
        }
        foreach ($users as $value) {
            $sql = "select * from user_sort_2x5 where user_id=$value";
            $result = $this->db->query($sql)->result();
            if (!empty($result)) {
                $left_id = $result[0]->left_id;
                $right_id = $result[0]->right_id;
                if($left_id!=null){
                    array_push($rowNum,$left_id);
                    array_push($children,$left_id);
                }
                if($right_id!=null){
                    array_push($rowNum,$right_id);
                    array_push($children,$right_id);
                }
            }
        }
        $this->getChildrenFor2x5($rowNum,$children);
    }

    /* 用户是否通过身份认证 */
    public function is_authentication($user_id){
        $res=$this->db->query('select check_status from user_id_card_info where uid='.$user_id)->result();
        if(!empty($res)){
            if($res[0]->check_status==2){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

	/* 根据直推人数,订单数,订单金额,算出该拿的层数 */
	public function get_level_num($member_count,$order_count,$amount){

		$level_num = 0;

		if ($member_count == 2) {
			if ($order_count >= 2 && $amount >= 50) {
				$level_num = 5;
			}
		}

		if ($member_count == 3) {
			if ($order_count >= 3) {
				if($amount >= 75){
					$level_num = 10;
				}
				if($amount >=50 && $amount<75){
					$level_num = 5;
				}
			}
			if($order_count == 2){
				if($amount>=50){
					$level_num =5;
				}
			}
		}

		if ($member_count == 4) {
			if ($order_count >= 4) {
				if($amount >= 100){
					$level_num = 15;
				}
				if($amount>=75 && $amount<100){
					$level_num = 10;
				}
				if($amount>=50 && $amount<75){
					$level_num = 5;
				}
			}
			if ($order_count == 3) {
				if($amount >= 75){
					$level_num = 10;
				}
				if($amount >= 50 && $amount<75){
					$level_num = 5;
				}
			}
			if($order_count == 2){
				if($amount >= 50){
					$level_num = 5;
				}
			}
		}

		if ($member_count == 5) {
			if ($order_count >= 5) {
				if($amount >= 125){
					$level_num = 20;
				}
				if($amount >= 100 && $amount < 125){
					$level_num = 15;
				}
				if($amount >= 75 && $amount < 100){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 4) {
				if($amount >= 100){
					$level_num = 15;
				}
				if($amount >= 75 && $amount < 100){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 3) {
				if($amount >= 75){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 2) {
				if($amount >= 50){
					$level_num = 5;
				}
			}
		}

		if ($member_count > 5) {
			if ($order_count > 5) {
				if($amount > 150){
					$level_num = 25;
				}
				if($amount >= 125 && $amount < 150){
					$level_num = 20;
				}
				if($amount >= 100 && $amount < 125){
					$level_num = 15;
				}
				if($amount >= 75 && $amount < 100){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 5) {
				if($amount >= 125){
					$level_num = 20;
				}
				if($amount >= 100 && $amount < 125){
					$level_num = 15;
				}
				if($amount >= 75 && $amount < 100){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 4) {
				if($amount >= 100){
					$level_num = 15;
				}
				if($amount >= 75 && $amount < 100){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 3) {
				if($amount >= 75){
					$level_num = 10;
				}
				if($amount >= 50 && $amount < 75){
					$level_num = 5;
				}
			}
			if ($order_count == 2) {
				if($amount >= 50){
					$level_num = 5;
				}
			}
		}
		return $level_num;
	}


    /* 根据读取的138数据,创建矩阵 */
    public function create_html_for_138($data){
        $row = ceil(count($data) / MAX_X );
        $html = "";

        $level_color_map = array(
            0=>'gray',
            1=>'purple',
            2=>'gold',
            3=>'green',
            4=>'white',
            5=>'pink'
        );

        for($i = 0; $i<$row; $i++){
            $html .= '<tr>';
            foreach($data as $k => $v)
            {
                //会员月费等级
                $month_fee_rank = $v['month_fee_rank'] == 4 ? $v['user_rank'] : $v['month_fee_rank'];

                if($v['status']!= 1){
                    $month_fee_rank = 0;
                }

                //会员信息
                $info = "level = $month_fee_rank status = {$v['status']} userId = {$v['user_id']} x = {$v['x']} y = {$v['y']}";
                $style = "background-color:{$level_color_map[$month_fee_rank]};border_color:$level_color_map[$month_fee_rank]";

                $html .= '<td class = "item_c" style="'.$style.'"><span '.$info.'></span>';
                $html .= '</td>';

                unset ($data[$k]);

                //每138个换行
                if(($v['x'])%MAX_X == 0){
                    break;
                }
            }
            $html .= '</tr>';
        }

        return $html;
    }
    



    /* 用户升级或购买普通订单时,即时加入138合格列表 */
    public function join_qualified_for_138($uid){
        $this->load->model('tb_commission_logs');

        /* 判断是否拿过138佣金 */
        $res = $this->tb_commission_logs->is_new_member($uid);

        $sale_amount_arr = $this->db->select("sale_amount")->where('uid',$uid)->where('year_month',date('Ym'))->from('users_store_sale_info_monthly')->get()->row_array();
        $qualified_record = $this->db->select('user_id')->where('user_id',$uid)->from('user_qualified_for_138')->get()->row_array();
        $res_user_rank = $this->db->select('user_rank')->where('id',$uid)->from('users')->get()->row();
        if(!$res_user_rank){
            return false;
        }else{
            $user_rank = $res_user_rank->user_rank;
        }

		$user_rank_list = array(1,2,3,5);

        //如果还没进入qualified 列表， 判断是否拿过138奖金,如果没拿过,则铜级或以上,销售额50或以上将加入138合格列表
        if(empty($qualified_record)) {
            if ($res === false && !empty($sale_amount_arr)) {
                if ((in_array($user_rank,$user_rank_list)) && ($sale_amount_arr['sale_amount'] >= 5000)){
                    $info = $this->db->select('x,y')->where('user_id',$uid)->from('user_coordinates')->get()->row_array();
                    if(!empty($info)){
                        $this->db->insert('user_qualified_for_138',array(
                            'user_id'=>$uid,
                            'user_rank'=>$user_rank,
                            'sale_amount'=>$sale_amount_arr['sale_amount'],
                            'x'=>$info['x'],
                            'y'=>$info['y'],
                            'create_time'=>date('Y-m-d h:i:s')
                        ));
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
