<?php

Class M_overrides extends CI_Model{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_generation_sales', 'm_generation_sales');
        $this->load->model('m_commission');
        $this->load->model('m_forced_matrix');
    }

    /** 团队销售提成奖 不用递归
     * @param $user_id
     * @param int $sales
     * @param int $order_id
     */
    public function generationSalesOverrides2($user_id , $sales = 0 ,$order_id = 0){
		$this->generationSalesOverridesNew($user_id , $sales, $order_id);
    }

	/** 補發 团队销售提成奖 不用递归
	 * @param $user_id
	 * @param int $sales
	 * @param int $order_id
	 */
	public function generationSalesOverrides($user_id , $sales = 0 ,$order_id = 0){

		$user = $this->db->from('users')->select('parent_ids')->where('id',$user_id)->get()->row_array();
		if(!$user){
			return;
		}
		if($sales <= 0) return;
		$parent_ids = explode(',',$user['parent_ids']);
		//array_pop($parent_ids);//去掉最後的公司號
        $ids_last = array_pop($parent_ids);
        $mem_root_id = config_item('mem_root_id'); 
        if($ids_last !=$mem_root_id) {   
                array_push($parent_ids,$ids_last);
        }
        
         
		$level = 0;
		if($parent_ids)foreach($parent_ids as $uid){

			if($uid != 1380129032){
				continue;
			}

			$row = $this->db->select('id,amount,parent_id,user_rank,status,store_qualified,team_commission')->from('users')->where('id',$uid)->get()->row_array();
			$level = $level + 1;

			if($level <= TEAM_SALES_OVERRIDES_ALGEBRA){

				//计算会员的父类可以获得几代的团队销售提成
				if(config_item('team_sales_order_rule')){
					$reality_generation = $this->new_rule_generation($uid,$row);/** 有订单要求 */
				}else{
					$reality_generation = $this->generation($uid,$row); /** 没有订单要求 */
				}

				//银级会员以上级别可以参加团队销售利润提成奖
				if($reality_generation >= $level || ($level == 1 /*&& $row['store_qualified'] == 1*/) ){//如果父類是他的直推人享受15%，免費10%。

					$percent = config_item('percent_'.$row['user_rank'])[$level]; //团队销售提成

					$cash =  $sales * $percent;

					//佣金记录
					if($cash>=0.01){
						$comm_log_id = $this->m_commission->commissionLogs($uid,REWARD_3,$cash,$user_id,$order_id);
						//團隊銷售提成獎
						$this->m_generation_sales->generationSalesLogs($uid,$user_id,$level,$sales,$percent,$cash,$comm_log_id);
					}

					/* 佣金自动转分红点 */
					$this->load->model('m_profit_sharing');
					$rate = $this->m_profit_sharing->getProportion($uid, 'sale_commissions_proportion') / 100;
					$commissionToPoint = 0;
					if ($rate > 0) {
						$commissionToPoint = tps_money_format($cash * $rate);
						if($commissionToPoint>=0.01){
							$this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+' . $commissionToPoint, FALSE)->update('users');

							$comm_id = $this->m_commission->commissionLogs($uid,17,-1*$commissionToPoint); //佣金轉分紅點
							$this->m_profit_sharing->createPointAddLog(array(
								'uid' => $uid,
								'commission_id' => $comm_id,
								'add_source' => 1,
								'money' => $commissionToPoint,
								'point' => $commissionToPoint
							));
						}
					}

					$real_cash = $cash - $commissionToPoint;
					//添加提成后的金额
					$this->db->where('id', $uid)
						->set('amount', 'amount+' . $real_cash, FALSE)
						->set('team_commission', 'team_commission+' . $cash, FALSE)->update('users');
				}
			}
		}
	}

    /** 团队产品销售代数
     * @param $user_id 用戶ID
     * @param $user 用户信息
     * @return int
     */
    public function generation($user_id,$user){

        $generation = 0;
        if(!$user['store_qualified']){ //如果不是合格店铺，则不能享受团队提成奖 generation = 0
           return $generation;
        }

        //推荐合格會員的數量
        $general = $this->generalMemberCount($user_id);

        $this->load->model('M_order','m_order');
        //有多少合格订单
        $order = $this->m_order->getOrderCount($user_id);

        switch($user['user_rank']){

            case LEVEL_FREE:

                $generation = LEVEL_FREE_GENERATION;
                if($general >=1 && $order >=1){
                    $reality_temp = $generation;
                }else{
                    $reality_temp =  0;
                }
                break;
			case LEVEL_COPPER:
				$generation = LEVEL_COPPER_GENERATION;
				if($general >=1 && $order >=1){
					$reality_temp = $generation;
				}else{
					$reality_temp =  0;
				}
				break;
            case LEVEL_SILVER:

                $generation = LEVEL_SILVER_GENERATION;
                $reality_temp = $general > $order ? $order * 3 : $general * 3;
                break;

            case LEVEL_GOLD:

                $generation = LEVEL_GOLD_GENERATION;
                $reality_temp = $general > $order ? $order * 3 : $general * 3;

                break;

            case LEVEL_DIAMOND:

                $generation = LEVEL_DIAMOND_GENERATION;
                if($general >= 3 && $order >= 3){
                    $reality_temp = $generation;
                }else{
                    $reality_temp = $general > $order ? $order * 3 : $general * 3;
                }
                break;

            default:
				$reality_temp = 0;
                $generation = 0;
                break;
        }

        $reality_generation = $reality_temp > $generation ? $generation : $reality_temp;

        return $reality_generation;
    }

	/** 团队产品销售代数
     * @param $user_id 用戶ID
     * @param $user 用户信息
     * @return int
     */
    public function new_rule_generation($user_id,$user){

        $generation = 0;
        if(!$user['store_qualified']){ //如果不是合格店铺，则不能享受团队提成奖 generation = 0
           return $generation;
        }

        //推荐合格會員的數量
        $general = $this->generalMemberCount($user_id);

        $this->load->model('M_order','m_order');
        //店铺的销售金额 和 合格订单
		$sales_info = $this->m_order->getOrderSalesInfo($user_id);

        switch($user['user_rank']){

			case LEVEL_FREE:

				$reality_temp = 1;
				break;

			case LEVEL_COPPER:
				$generation = LEVEL_COPPER_GENERATION;
				if($general >=1 && $sales_info['sale_amount'] >= 25){
					$reality_temp = $generation;
				}else{
					$reality_temp =  0;
				}
				break;

            case LEVEL_SILVER:

				if($sales_info['sale_amount'] >= 25 && $general >= 1 ){
					$reality_temp = 3;
				}else{
					$reality_temp = 0;
				}
                break;

            case LEVEL_GOLD:

				if($sales_info['sale_amount'] >= 50 && $sales_info['orders_num'] >= 2  && $general >= 2 ){
					$reality_temp = 6;
				}else if($sales_info['sale_amount'] >= 25 && $general >= 1 ){
					$reality_temp = 3;
				}else{
					$reality_temp = 0;
				}
                break;

            case LEVEL_DIAMOND:

				if($sales_info['sale_amount'] >= 75 && $sales_info['orders_num'] >= 3  && $general >= 3 ){
					$reality_temp = 10;
				}else if($sales_info['sale_amount'] >= 50 && $sales_info['orders_num'] >= 2  && $general >= 2 ){
					$reality_temp = 6;
				}else if($sales_info['sale_amount'] >= 25 && $general >= 1 ){
					$reality_temp = 3;
				}else{
					$reality_temp = 0;
				}
                break;

            default:
				$reality_temp = 0;
                break;
        }
        return $reality_temp;
    }

    /** 得到推薦合格會員的數量
     * @param $uid
     * @return int
     */
    public function generalMemberCount($uid){
        $count = $this->db->from('users')->
            where('parent_id',$uid)->
            where('store_qualified',1)->
            where('user_rank <>', 4)->count_all_results();
        return $count ? $count : 0 ;
    }


    /** 递归得到某个会员所有的子类 拼接成字符
     * @param $user_id
     * @param $level
     * @return str
     */
    public function recursiveTreeStr($user_id,$level=1){
        //店铺显示层数 如果为0就显示全部层数
        // m by brady.wang 2017/02/23
        $end_time = strtotime(config_item("team_profit_old_end_time"));
        if (time() > $end_time) {
            $show_level = 2;
        } else {
            $show_level = SHOW_LEVEL;
        }
        if($show_level && $level > $show_level){
            return;
        }
        $tree_str = '';
        $count = $this->db_slave->from('users')->where('parent_id',$user_id)->count_all_results();
        if($count){
            $tree_str =  '<ul>';
            $referrer = $this->db_slave->select('id,user_rank,sale_rank,status,name,child_count')->from('users')->where('parent_id',$user_id)->get()->result_array();
            foreach ($referrer AS $row){
                if($row['status'] != 0){
                    $tree_str .= '<li><span level='.$row['user_rank'].' uid='.$row['id'].' status='.$row['status'].'></span>';
                    $tree_str .= '<strong class="id_color">'.$row['id'].'</strong>';
                    $tree_str .= '<strong class="word_break">['.$row['name'].']</strong>';
                    $tree_str .= config_item('sale_rank')[$row['sale_rank']]/*.'-'.$row['child_count']*/;
                    $tree_str .= $this->recursiveTreeStr($row['id'],$level + 1);
                    $tree_str .= '</li>';
                }
            }
            $tree_str .= '</ul>';
        }
        return $tree_str;
    }

    /** 递归得到某个会员的上3层父類的ID
     * @param $user_id
     * @param $level
     * @return str
     */
    public function fatherToThree($user_id,$level = 1){

       $user = $this->db->select('id,user_rank,status,store_url,parent_id')->from('users')->where('id',$user_id)->get()->row_array();
       if($user['parent_id'] && $level < SHOW_LEVEL){
           return $this->fatherToThree($user['parent_id'],$level + 1);
       }else{
           return $user['parent_id'] ? $user['parent_id'] : $user['id'];
       }

    }

    /** 回溯会员上3层的父类
     * @param $user_id
     * @return str
     */
    public function getFatherTreeStr($user_id){
        $uid = $this->fatherToThree($user_id);
        return $this->getTreeStr($uid);
    }

    //拼接成字符
    public function getTreeStr($uid){
        $user = $this->db_slave->select('id,user_rank,status,name,sale_rank ')->from('users')->where('id',$uid)->get()->row_array();
        $str = '<ul id="org" style="display:none"> <li><span level='.$user['user_rank'] .' uid='.$user['id'].' status='.$user['status'].' ></span>';
        $str .= '<strong class="id_color">'.$user['id'].'</strong>';
        
        $str .= '<strong class="word_break">['.$user['name'].']</strong>';
        $str .= config_item('sale_rank')[$user['sale_rank']];
        $str .= $this->recursiveTreeStr($uid);
        $str .= '</li></ul>';
        return $str;
    }

    /**
     * 打印2x5矩阵子元素
     * @param $user_id  用户ID
     * @param $level    层数
     * @return string   返回组合的html代码
     */
    public function getTree2X5($user_id,$level){
        $tree_str = '';
        $sql = "select * from user_sort_2x5 where user_id=$user_id";
        $result = $this->db->query($sql)->result();
        $left_id = $result[0]->left_id;
        $right_id = $result[0]->right_id;
        $child_level = $result[0]->level;

        if ($child_level - $level < 3) {
            if ($left_id != null || $right_id != null) {
                $tree_str = '<ul>';
                if ($left_id != null) {
                    $month_fee_rank = $this->m_forced_matrix->userInfo($left_id)->month_fee_rank;
					if($month_fee_rank == 4){
						$month_fee_rank = $this->m_forced_matrix->userInfo($left_id)->user_rank;
					}
                    $status=$this->m_forced_matrix->userInfo($left_id)->status;

                    $name = $this->m_forced_matrix->userInfo($left_id)->name;
                    $tree_str .= '<li><span level=' . $month_fee_rank . ' uid=' . $left_id . ' status='.$status.'></span>';
                    $tree_str .= $left_id . "<br>";
                    $tree_str .= '<strong class="word_break">' . $name . '</strong>';
                    $tree_str .= $this->getTree2X5($left_id, $level);
                    $tree_str .= '</li>';
                }
                if ($right_id != null) {
                    $month_fee_rank = $this->m_forced_matrix->userInfo($right_id)->month_fee_rank;
					if($month_fee_rank == 4){
						$month_fee_rank = $this->m_forced_matrix->userInfo($right_id)->user_rank;
					}
                    $status=$this->m_forced_matrix->userInfo($right_id)->status;

                    $name = $this->m_forced_matrix->userInfo($right_id)->name;
                    $tree_str .= '<li><span level=' . $month_fee_rank . ' uid=' . $right_id . ' status='.$status.'></span>';
                    $tree_str .= $right_id . "<br>";
                    $tree_str .= '<strong class="word_break">' . $name . '</strong>';
                    $tree_str .= $this->getTree2X5($right_id, $level);
                    $tree_str .= '</li>';
                }
                $tree_str .= '</ul>';
            }
        }
        return $tree_str;
    }

    /***
     * 打印2x5矩阵第一个元素
     * 2x5第一个元素
     * @param $user_id  用户ID
     * @return string   返回html代码（2x5矩阵）
     */
    public function preTree2X5($user_id){

        $sql = "select * from user_sort_2x5 where user_id=$user_id";
        $result = $this->db->query($sql)->result();
        $month_fee_rank = $this->m_forced_matrix->userInfo($user_id)->month_fee_rank;
		if($month_fee_rank == 4){ //没有月费等级，取店铺等级
			$month_fee_rank = $this->m_forced_matrix->userInfo($user_id)->user_rank;
		}
        $status=$this->m_forced_matrix->userInfo($user_id)->status;
        $name = $this->m_forced_matrix->userInfo($user_id)->name;
        $user_id = $result[0]->user_id;
        $level = $result[0]->level;

        //点击获取树下总人数
        $child_count=$this->m_forced_matrix->userInfo2x5($user_id)->child_count;

        if (!empty($result)) {
            $str = '<ul id="org" style="display:none"><li><span level=' . $month_fee_rank . ' uid=' . $user_id . ' status='.$status.'></span>';
            $str .= $user_id . "<br>";
            $str .= '<strong class="word_break">' . $name . '</strong>';
            $str .= $child_count;
            $str .= $this->getTree2X5($user_id, $level);
            $str .= '</li></ul>';
        }
        return $str;
    }

    //138矩阵图
    public function preTree138($data){
        $children_id = $data['children_id'];  //下级ID
        $children_rank = $data['children_rank'];  //下级等级
        $name = $this->m_forced_matrix->userInfo($data['user_id'])->name;

        $str = '<table id="tab">';
        $str.='<tr><td><span level='.$data['month_fee_rank'].' uid='.$data['user_id'].'>';
        $str.= $data['user_id']."<br>";
        $str.= '<span>'.$name.'</span><br>';
        $str.= '<span>'.$data['children_num'].'</span>';
        $str.='</td></tr>';
        for($i=0;$i<$data['children_num'];$i++){
            if($i<=2){
                $name=$this->m_forced_matrix->userInfo($children_id[$i])->name;
				if($children_rank[$i] == 4){
					$children_rank[$i] = $this->m_forced_matrix->userInfo($children_id[$i])->user_rank;
				}
                $str.='<tr><td><span level='.$children_rank[$i].' uid='.$children_id[$i].'>';
                $str.= $children_id[$i]."<br>";
                $str.= '<span>'.$name.'</span>';
                $str.='</span></td></tr>';
            }
        }
        $str.='</table>';
        return $str;
    }


    //得到升級後 產品套裝的利潤
    public function getUpgradeProfit($current_level,$before_level,$uid){
        $this->load->model('M_user','m_user');

		$is_true = $this->m_user->is_first_upgrade_time_1_1($uid);
		if($is_true){
			$join_fee = config_item('old_join_fee_and_month_fee');
			if($before_level == 2){
				$join_fee['2'] = array('join_fee'=>1000);
			}
			if($before_level == 3){
				$join_fee['3'] = array('join_fee'=>500);
			}
			if($before_level == 4){
				$join_fee['2'] = array('join_fee'=>1000);
				$join_fee['3'] = array('join_fee'=>500);
			}
		}else{
			$join_fee = $this->m_user->getJoinFeeAndMonthFee();

		}

        if($before_level != LEVEL_FREE){
            $profit =  $join_fee[$current_level]['join_fee'] - $join_fee[$before_level]['join_fee'];
        }else{
            $profit = $join_fee[$current_level]['join_fee'];
        }
        return $profit;
    }

    /**  降级操作
     * @param $id 用户ID
     * @param $store_level 新的店铺等级
     * @param $monthly_fee_level  新的月费等级
     */
    public function do_demote($id,$store_level,$monthly_fee_level,$admin_id,$remark){

        $this->load->model('m_user');
        $user = $this->m_user->getUserByIdOrEmail($id);

		$is_true = $this->m_user->is_first_upgrade_time_1_1($id);
		if($is_true){
			$point = config_item('old_join_fee_and_month_fee');
		}else{
			$point = $this->m_user->getJoinFeeAndMonthFee();
		}

        if($store_level > $user['user_rank'] || ($store_level==4 && $user['user_rank'] ==5) ){ /** 降級店鋪等級 抽回團隊銷售獎 */

            $this->reduceTeamSales($id,$store_level,$monthly_fee_level);
			if($store_level != 4 || $monthly_fee_level != 4){   //如果是退出，保留日分红数据
            	/** 本身的日分紅獎 */
            	$this->reduceDailyBonus($id,$store_level,$user,$point);
			}
        }

        if($monthly_fee_level > $user['month_fee_rank'] && $store_level !=4 && $monthly_fee_level!=4){ /** 2X5见点奖 */
			/** 月費降級暫時不抽回2×5見點獎 */
            //$this->load->model('m_helper');
            //$this->m_helper->minus_2x5_commission($id,$monthly_fee_level);
        }

        if($store_level != 4 || $monthly_fee_level != 4){ /** 如果用戶降级到银级，而不是退款到免費級別，执行 */

            /** 店鋪等級變動 */
            if($store_level > $user['user_rank']){
                $this->updateUsersAllStatus($id,$user,$store_level,$point);

				/** 统计直推人数量　*/
				$this->load->model('m_referrals_count');
				$this->m_referrals_count->demote_referrals_count($id,$user['parent_id'],$user['user_rank'],$store_level);
            }
            /** 月費等級變動 */
            if($monthly_fee_level > $user['month_fee_rank']){
                /** 月份券處理 */
                $this->reduceCoupon($user,$monthly_fee_level);
                /** 月費等級變動 */
                $this->m_user->addUserLevelChangeLog($id,$user['month_fee_rank'],$monthly_fee_level,1);
                $this->db->where('id',$id)->set('month_fee_rank',$monthly_fee_level,FALSE)->update('users');
            }

			$this->m_log->adminActionLog($admin_id,'admin_demote_user','users',$id,
				'user_rank|month_fee_rank',$user['user_rank'].'|'.$user['month_fee_rank'],$store_level.'|'.$monthly_fee_level);

        }else{ /** 退款到免費級別 回收为公司账号状态 其他的不動 (郵箱，名字，身份證清掉)*/
            $user_card = $this->m_admin_helper->getCardOne($id);
            if(!$user_card){
                $user_card['id_card_num'] = '';
            }
            $this->m_user->resetMemberAccount($id,$id.'@company.com','','',$user_card['id_card_num'],$admin_id,$remark,2,4);
            $this->m_user->addInfoToWohaoSyncQueue($id,array(1,2,3,4,5,6,7,8,9,10,11));
            /** 清空用户的佣金记录 ##暂时不清空记录 */
            //$this->deleteUserLogs($id);
			$this->db->where('id',$id)->set('status',4,FALSE)->set('update_time',  time(),FALSE)->update('users');

			/** 退会统计　*/
			$this->load->model('m_referrals_count');
			$this->m_referrals_count->demote_referrals_count($id,$user['parent_id'],$user['user_rank'],4);

			/** 如果退出用户的上线拿到了一月份现金红包，并且退出的用户升级时间是一月份，需重新检测现金红包是否可以拿到，否則抽回现金红包 */
			$cash_bonus = $this->db->where('uid',$user['parent_id'])->where('type','1')->get('users_cash_bonus')->row_array();
			if($cash_bonus){
				$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$user['id'])->get('users_level_change_log')->row_array();
				if(!empty($date) &&$date['create_time'] > '2016-01-01 00:00:00' && $date['create_time'] <= '2016-01-31 23:59:59'){
					$child_ids = $this->db->select('id')->where('parent_id',$user['parent_id'])->where('id !=',$user['id'])->where('user_rank <',4)->where('status !=',4)->get('users')->result_array();
					$child_count = count($child_ids);
					$count = 0;
					$amount = 0;
					if($child_count >= 3){
						foreach($child_ids as $child_id){
							$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
							if(!empty($date) &&$date['create_time'] > '2016-01-01 00:00:00' && $date['create_time'] <= '2016-01-31 23:59:59'){
								$count = $count + 1;
							}
						}
						//1月 店铺的销售金额 和 合格订单
						$sales_info = $this->db->where('uid',$user['parent_id'])->where('year_month','201601')->get('users_store_sale_info_monthly')->row_array();
						if(!empty($sales_info) && $sales_info['sale_amount']>=25000 && $sales_info['orders_num']>=10 && $count >= 10){
							$amount = 1000;
						}else if(!empty($sales_info) && $sales_info['sale_amount']>=15000 && $sales_info['orders_num']>=6 && $count >= 6){
							$amount = 500;
						}else if(!empty($sales_info) && $sales_info['sale_amount']>=7500 && $sales_info['orders_num']>=3 && $count >= 3){
							$amount = 200;
						}else{
							$amount = 0;
						}
				}
				// 99 属于1月份现金红包抽回
				$reduce = $this->db->select_sum('amount')->where('type',99)->where('uid',$user['parent_id'])->get('user_reduce_commission_logs')->row_array();
				if($reduce['amount'] == null){
					$reduce_amount = 0;
				}else{
					$reduce_amount = $reduce['amount'];
				}
				$new_amount = $cash_bonus['amount']-$amount-$reduce_amount;
				if($new_amount > 0){
					$this->m_commission->commissionLogs($user['parent_id'],16,-1*$new_amount,$user['id']);
					$this->m_commission->reduceCommissionLogs($user['parent_id'],$new_amount,99,$user['id']);
					$this->db->where('id',$user['parent_id'])->set('amount', 'amount-' . $new_amount, FALSE)->update('users');
				}
			}
       	  }

			/** 如果退出的用户拿到了2月份的现金红包,并且退出的用户升级时间2016年2月29日之前，需重新检测现金红包是否可以拿到，否則抽回现金红包 */
			$cash_bonus_3 = $this->db->where('uid',$user['parent_id'])->where('type','2')->get('users_cash_bonus')->row_array();
			if($cash_bonus_3){
				$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$user['id'])->get('users_level_change_log')->row_array();
				if(!empty($date)&&$date['create_time'] <= '2016-02-29 23:59:59'){
					$child_ids = $this->db->select('id')->where('parent_id',$user['parent_id'])->where('id !=',$user['id'])->where('user_rank <',4)->where_in('status',array(1,2))->get('users')->result_array();
					$child_count = count($child_ids);
					$count = 0;
					$amount = 0;
					if($child_count >= 5){

						foreach($child_ids as $child_id){
							$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
							if(!empty($date)&&$date['create_time'] <= '2016-02-29 23:59:59'){
								$count = $count + 1;
							}
						}/** 剩下的都是2月29日之前升级的，其他退出循环 */

						if($count >= 20){
							$amount = 1000;
						}else if($count >= 15){
							$amount = 750;
						}else if($count >= 10){
							$amount = 500;
						}else if($count >= 5){
							$amount = 250;
						}
					}
					// 100 属于2月份现金红包抽回
					$reduce = $this->db->select_sum('amount')->where('type',100)->where('uid',$user['parent_id'])->get('user_reduce_commission_logs')->row_array();
					if($reduce['amount'] == null){
						$reduce_amount = 0;
					}else{
						$reduce_amount = $reduce['amount'];
					}
					$new_amount = $cash_bonus_3['amount']-$amount-$reduce_amount;
					if($new_amount > 0){
						$this->m_commission->commissionLogs($user['parent_id'],16,-1*$new_amount,$user['id']);
						$this->m_commission->reduceCommissionLogs($user['parent_id'],$new_amount,100,$user['id']);
						$this->db->where('id',$user['parent_id'])->set('amount', 'amount-' . $new_amount, FALSE)->update('users');
					}
				}
			}

        }


    }
    /* 
     * @param type $id @用户id
     * @param type $store_level @目标等级,
     * @param type $monthly_fee_level @目标月费等级
     * @param type $admin_id @后台人员id
     * @param type $remark @备注
     */
    public function new_do_demote($id,$store_level,$monthly_fee_level,$admin_id,$type){
        $this->load->model('m_user');
        $user = $this->m_user->getUserByIdOrEmail($id);

        $is_true = $this->m_user->is_first_upgrade_time_1_1($id);
        if($is_true){
            $point = config_item('old_join_fee_and_month_fee');
        }else{
            $point = $this->m_user->getJoinFeeAndMonthFee();
        }
        if($store_level > $user['user_rank'] || ($store_level==4 && $user['user_rank'] ==5) ){ /** 降級或者退会 */
            $this->new_reduceTeamSales($id,$user,$store_level,$type);//抽回团队销售奖
          //  $this->reduceDailyBonus($id,$store_level,$user,$point);//抽回日分红
            $this->back_bonus($id,$store_level,$user,$type);//抽回分红类奖金
            $this->new_updateUsersAllStatus($id,$user,$store_level,$point,$user['user_rank']);//等级变动
            $this->load->model('m_referrals_count');
            $this->m_referrals_count->demote_referrals_count($id,$user['parent_id'],$user['user_rank'],$store_level);//统计直推人数
            if($store_level == 4){//只有到免费的时候才清空会员的月费券
                $this->new_reduceCoupon($id);//清空用户的月费券
            }
        }
        if($type == 1){ //用戶降级执行 
            $this->m_log->adminActionLog($admin_id,'admin_demote_user','users',$id,'user_rank|month_fee_rank',$user['user_rank'].'|'.$user['month_fee_rank'],$store_level.'|'.$monthly_fee_level);
        }else{ //用戶退会执行
            $this->load->model('o_cash_account');
            $gr_daily_logs = $this->o_cash_account->getCashAccountLog(array('uid'=>$id,'item_type'=>5));//退会的时候清空所有个人店铺提成
            if(!empty($gr_daily_logs)){
                foreach($gr_daily_logs as $daily_log){
                    $amount = $daily_log['amount']/100;
                    $this->m_commission->commissionLogs($daily_log['uid'],16,-1*$amount,0,"","",date("Y-m-d",time())."退会,抽回个人店铺提成佣金");//添加用户资金变动记录日志
                    $this->m_commission->reduceCommissionLogs($daily_log['uid'],$amount,$daily_log['item_type'],$daily_log['related_uid']);//添加用户佣金抽回记录
                    $this->db->where('id', $daily_log['uid'])->set('amount', 'amount-' . $amount, FALSE)->update('users');//扣除用户余额
                }
            }
            
            $user_card = $this->m_admin_helper->getCardOne($id);
            if(!$user_card){
                $user_card['id_card_num'] = '';
            }
            $this->m_user->resetMemberAccount($id,$id.'@company.com','','',$user_card['id_card_num'],$admin_id,'',2,4);//重置用户账户信息，
            $this->m_user->addInfoToWohaoSyncQueue($id,array(1,2,3,4,5,6,7,8,9,10,11));//同步到沃好,具体逻辑不清楚
            //$this->deleteUserLogs($id);//清空用户的所有佣金记录 ##暂时不清空记录
            $this->db->where('id',$id)->set('status',4,FALSE)->set('update_time',  time(),FALSE)->update('users');//变更为公司预留账户

            /** 如果退出用户的上线拿到了一月份现金红包，并且退出的用户升级时间是一月份，需重新检测现金红包是否可以拿到，否則抽回现金红包 */
            $cash_bonus = $this->db->where('uid',$user['parent_id'])->where('type','1')->get('users_cash_bonus')->row_array();
            if($cash_bonus){
                $date = $this->db->select('create_time')->where('level_type',2)->where('uid',$user['id'])->get('users_level_change_log')->row_array();
                if(!empty($date) &&$date['create_time'] > '2016-01-01 00:00:00' && $date['create_time'] <= '2016-01-31 23:59:59'){
                    $child_ids = $this->db->select('id')->where('parent_id',$user['parent_id'])->where('id !=',$user['id'])->where('user_rank <',4)->where('status !=',4)->get('users')->result_array();
                    $child_count = count($child_ids);
                    $count = 0;
                    $amount = 0;
                    if($child_count >= 3){
                        foreach($child_ids as $child_id){
                                $date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
                                if(!empty($date) &&$date['create_time'] > '2016-01-01 00:00:00' && $date['create_time'] <= '2016-01-31 23:59:59'){
                                        $count = $count + 1;
                                }
                        }
                        //1月 店铺的销售金额 和 合格订单
                        $sales_info = $this->db->where('uid',$user['parent_id'])->where('year_month','201601')->get('users_store_sale_info_monthly')->row_array();
                        if(!empty($sales_info) && $sales_info['sale_amount']>=25000 && $sales_info['orders_num']>=10 && $count >= 10){
                                $amount = 1000;
                        }else if(!empty($sales_info) && $sales_info['sale_amount']>=15000 && $sales_info['orders_num']>=6 && $count >= 6){
                                $amount = 500;
                        }else if(!empty($sales_info) && $sales_info['sale_amount']>=7500 && $sales_info['orders_num']>=3 && $count >= 3){
                                $amount = 200;
                        }else{
                                $amount = 0;
                        }
                    }
                    // 99 属于1月份现金红包抽回
                    $reduce = $this->db->select_sum('amount')->where('type',99)->where('uid',$user['parent_id'])->get('user_reduce_commission_logs')->row_array();
                    if($reduce['amount'] == null){
                            $reduce_amount = 0;
                    }else{
                            $reduce_amount = $reduce['amount'];
                    }
                    $new_amount = $cash_bonus['amount']-$amount-$reduce_amount;
                    if($new_amount > 0){
                            $this->m_commission->commissionLogs($user['parent_id'],16,-1*$new_amount,$user['id'],"","","现金红包佣金抽回");
                            $this->m_commission->reduceCommissionLogs($user['parent_id'],$new_amount,99,$user['id']);
                            $this->db->where('id',$user['parent_id'])->set('amount', 'amount-' . $new_amount, FALSE)->update('users');
                    }
                }
            }

            /** 如果退出的用户拿到了2月份的现金红包,并且退出的用户升级时间2016年2月29日之前，需重新检测现金红包是否可以拿到，否則抽回现金红包 */
            $cash_bonus_3 = $this->db->where('uid',$user['parent_id'])->where('type','2')->get('users_cash_bonus')->row_array();
            if($cash_bonus_3){
                $date = $this->db->select('create_time')->where('level_type',2)->where('uid',$user['id'])->get('users_level_change_log')->row_array();
                if(!empty($date)&&$date['create_time'] <= '2016-02-29 23:59:59'){
                    $child_ids = $this->db->select('id')->where('parent_id',$user['parent_id'])->where('id !=',$user['id'])->where('user_rank <',4)->where_in('status',array(1,2))->get('users')->result_array();
                    $child_count = count($child_ids);
                    $count = 0;
                    $amount = 0;
                    if($child_count >= 5){

                            foreach($child_ids as $child_id){
                                    $date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
                                    if(!empty($date)&&$date['create_time'] <= '2016-02-29 23:59:59'){
                                            $count = $count + 1;
                                    }
                            }/** 剩下的都是2月29日之前升级的，其他退出循环 */

                            if($count >= 20){
                                    $amount = 1000;
                            }else if($count >= 15){
                                    $amount = 750;
                            }else if($count >= 10){
                                    $amount = 500;
                            }else if($count >= 5){
                                    $amount = 250;
                            }
                    }
                    // 100 属于2月份现金红包抽回
                    $reduce = $this->db->select_sum('amount')->where('type',100)->where('uid',$user['parent_id'])->get('user_reduce_commission_logs')->row_array();
                    if($reduce['amount'] == null){
                            $reduce_amount = 0;
                    }else{
                            $reduce_amount = $reduce['amount'];
                    }
                    $new_amount = $cash_bonus_3['amount']-$amount-$reduce_amount;
                    if($new_amount > 0){
                            $this->m_commission->commissionLogs($user['parent_id'],16,-1*$new_amount,$user['id'],"","","现金红包佣金抽回");
                            $this->m_commission->reduceCommissionLogs($user['parent_id'],$new_amount,100,$user['id']);
                            $this->db->where('id',$user['parent_id'])->set('amount', 'amount-' . $new_amount, FALSE)->update('users');
                    }
                }
            }
        }
    }
     /** 抽回团队销售 */
    public function new_reduceTeamSales($id,$user,$store_level,$type){

        $this->load->model('o_cash_account');
         /** 兼容用户$id阶段性升级 */
        $comm_logs = $this->o_cash_account->getCashAccountLog(array('uid'=>$id,'not_use_uid'=>true,'item_type'=>3,'related_uid'=>$id),'distinct uid,related_uid as pay_user_id');
        /** 上線得到的 (減去升級时) 团队销售奖  生成降级记录*/
        $profit = $this->getUpgradeProfit($store_level,4,$id)*0.8; //新的團隊銷售利潤
        if($type==1){
            $txt = "由".config_item('user_rank_name')[$user['user_rank']]."降至".config_item('user_rank_name')[$store_level];
        }else{
            $txt = "退会";
        }
        if($comm_logs)foreach($comm_logs as $comm_log){
            //generation_sales_logs 记录
            $gsl = $this->db->where('parent_id',$comm_log['uid'])->where('child_id',$comm_log['pay_user_id'])->get('generation_sales_logs')->row_array();
            $amount = $this->db->query("select SUM(push_money) amount from generation_sales_logs where child_id={$comm_log['pay_user_id']} and parent_id={$comm_log['uid']} and push_money>0 and sales>=200")->row_array();
            $reduce = $this->db->select_sum('amount')->where('type',3)->where('uid',$comm_log['uid'])->where('pay_user_id',$comm_log['pay_user_id'])->get('user_reduce_commission_logs')->row_array();
            $reduce_amount = $reduce['amount'] == null ? 0 : $reduce['amount'];
            $all_amount = $amount['amount'] == null ? 0 : $amount['amount'];
            $new_push_cash = $profit*$gsl['percent'];
            $real_cash = $all_amount - $new_push_cash - $reduce_amount;
            if($real_cash > 0){
                //添加新的佣金记录
                $comm_id = $this->m_commission->commissionLogs($comm_log['uid'],16,-1*$real_cash,$comm_log['pay_user_id'],"","","下级会员ID:".$id.",于".date("Y-m-d",time()).$txt.",抽回团队销售佣金");
                $this->m_generation_sales->generationSalesLogs($gsl['parent_id'],$gsl['child_id'],$gsl['level'],$profit,$gsl['percent'],-1*$real_cash,$comm_id);
                $this->m_commission->reduceCommissionLogs($comm_log['uid'],$real_cash,3,$comm_log['pay_user_id']);
                $this->db->where('id', $comm_log['uid'])->set('amount', 'amount-' . $real_cash, FALSE)->set('team_commission', 'team_commission-' . $real_cash, FALSE)->update('users');
            }
        }
        /** 抽回个人团队销售奖 **/
    }
     /** 降级银级或金级 状态处理*/
    public function new_updateUsersAllStatus($id,$user,$store_level,$point,$old_level){

        /** 用户等级变动日志 */
        $this->m_user->addUserLevelChangeLog($id,$user['user_rank'],$store_level,2);
        $this->db->insert('user_upgrade_log',array(
            'uid'=>$id,
            'upgrade_rank'=>$store_level,
            'create_time'=>time()
        ));
        /*** 用户等级更新*/
        $this->db->where('id',$id)->set('user_rank',$store_level,FALSE)->update('users');
        //计算用户的职称
        $this->load->model('o_queen');
	$this->o_queen->en_unique_queue(o_queen::QUEEN_USER_RANK_TITLE, $id);
        //等级变动影响积分
        //用户级别更改 需要变更相对应父等级的积分  brady
        $this->load->model("tb_users_credit_queue_user_rank");
        $queue_data = array(
            'uid'=>$id,
            'before_user_rank'=>$old_level,
            'after_user_rank'=>$store_level,
            'type'=>2,//降级或者退会减少的积分
            'created_time'=>date("Y-m-d H:i:s")
        );
        $this->tb_users_credit_queue_user_rank->add_queue($queue_data); //添加进入队列

    }
    /**
     * 抽回分红类奖金
     * @param type $uid @用户id
     * @param type $store_level @目标等级
     * @param type $user @用户信息
     */
    function back_bonus($uid,$store_level,$user,$type){
        $all_bonus_arr = array(6,1,8,2,25,7,23,4,26);//要抽的分红类型
        $tj_arr = array(6,2,26);//铜级
        $yj_arr = array(8);//银级
        $zs_arr = array(1,25,7,23,4);//钻石
        $tj_time = $yj_time = $zs_time = "";
        $daily_logs = $tj_daily_logs = $yj_daily_logs = $zs_daily_logs  =  array();
        if($store_level ==4){//抽回铜级条件的分红，获取时间范围
            $tj_time = $this->db->where(array("uid"=>$uid,"level_type"=>2,"old_level"=>4))->order_by("create_time","desc")->get("users_level_change_log")->row_array();
        }
        if($store_level > 3 && $store_level!=4){//抽回银级条件的分红，获取时间范围
            $yj_time = $this->db->where(array("uid"=>$uid,"level_type"=>2,"old_level >"=>3))->order_by("create_time","desc")->get("users_level_change_log")->row_array();
        }
        if($store_level > 1 && $store_level!=4){//抽回钻石级条件的分红，获取时间范围
            $zs_time = $this->db->where(array("uid"=>$uid,"level_type"=>2,"old_level >"=>1))->order_by("create_time","desc")->get("users_level_change_log")->row_array();
        }
        //fout($tj_time);exit;
        $this->load->model('o_cash_account');
        if(!empty($tj_time)){
            $tj_daily_logs = $this->o_cash_account->getCashAccountLog(array('uid'=>$uid,'item_type in'=>array_merge($tj_arr,$yj_arr,$zs_arr),'start_time'=>$tj_time["create_time"]));
        }
        if(!empty($yj_time)){
            $yj_daily_logs = $this->o_cash_account->getCashAccountLog(array('uid'=>$uid,'item_type in'=>$yj_arr,'start_time'=>$yj_time["create_time"]));
        }
        if(!empty($zs_time)){
            $zs_daily_logs = $this->o_cash_account->getCashAccountLog(array('uid'=>$uid,'item_type in'=>$zs_arr,'start_time'=>$zs_time["create_time"]));
        }
        $daily_logs = array_merge($tj_daily_logs,$yj_daily_logs,$zs_daily_logs);
        if(!empty($daily_logs)){
            $commission_type = $this->config->item('funds_change_report');//佣金类型名称
            if($type==1){
                $txt = "由".config_item('user_rank_name')[$user['user_rank']]."降至".config_item('user_rank_name')[$store_level];
            }else{
                $txt = "退会";
            }
            foreach($daily_logs as $daily_log){
                $amount = $daily_log['amount']/100;
                $this->m_commission->commissionLogs($daily_log['uid'],16,-1*$amount,0,"","",date("Y-m-d",time()).$txt.",抽回".lang($commission_type[$daily_log['item_type']]));//添加用户资金变动记录日志
               // echo $this->db->last_query()."<br>";
                $this->m_commission->reduceCommissionLogs($daily_log['uid'],$amount,$daily_log['item_type'],$daily_log['related_uid']);//添加用户佣金抽回记录
                $this->db->where('id', $daily_log['uid'])->set('amount', 'amount-' . $amount, FALSE)->update('users');//扣除用户余额
            }
        }
        //echo 99;exit;
    }
     /**
     * @当降级到免费的时候清空用户的月费券，否则不变更
     * @param type $uid
     */
    public function new_reduceCoupon($uid){
        $this->db->where('uid',$uid)->delete('monthly_fee_coupon');
    }
    /** 清空用户的佣金记录*/
    function deleteUserLogs($uid){
        $this->db->where('uid',$uid)->delete('commission_logs');
        $this->db->where('uid',$uid)->delete('cash_to_month_fee_logs');
        $this->db->where('uid',$uid)->delete('user_reduce_commission_logs');
        $this->db->where('uid',$uid)->delete('profit_sharing_point_add_log');
        $this->db->where('uid',$uid)->delete('profit_sharing_point_proportion');
        $this->db->where('uid',$uid)->delete('profit_sharing_point_reduce_log');
        $this->db->where('uid',$uid)->delete('users_sharing_point_reward');
        $this->db->where('uid',$uid)->delete('cash_take_out_logs');
        $this->db->where('uid',$uid)->delete('user_upgrade_log');
        $this->db->where('user_id',$uid)->delete('month_fee_change');
        $this->db->where('parent_id',$uid)->delete('generation_sales_logs');
        //各金额清空
        $this->db->where('id',$uid)->update('users',array(
            'amount'=>0.00,
            'amount_store_commission'=>0.00,
            'amount_profit_sharing_comm'=>0.00,
            'amount_weekly_Leader_comm'=>0.00,
            'amount_monthly_leader_comm'=>0.00,
            'profit_sharing_point'=>0.00,
            'profit_sharing_point_from_sale'=>0.00,
            'profit_sharing_point_from_force'=>0.00,
            'profit_sharing_point_from_sharing'=>0.00,
            'profit_sharing_point_manually'=>0.00,
            'profit_sharing_point_to_money'=>0.00,
            'month_fee_pool'=>0.00,
            'personal_commission'=>0.00,
            'company_commission'=>0.00,
            'team_commission'=>0.00,
            'infinite_commission'=>0.00,
        ));
    }

    /**
     * 抽回月費卷
     */
    public function reduceCoupon($user,$level){

        $this->load->model('m_coupons');
        $coupon = $this->db->from('users_coupon_monthfee')->where('uid',$user['id'])->get()->row_array();
		if(!$coupon){
			return ;
		}
        if($coupon['status'] == 1){//已使用未交月份
            $month = $this->m_user->getJoinFeeAndMonthFee();
            $old_month = $month[$user['month_fee_rank']]['month_fee'];
            $chai_month = $old_month - $month[$level]['month_fee'];
            $count = $this->db->from('users_level_change_log')->where('level_type',1)->count_all_results();
            //減去去月費池的
            if($count < 2){
                $this->db->where('id',$user['id'])->set('month_fee_pool','month_fee_pool-'.$chai_month,FALSE)->update('users');
                //月费变动
                $this->load->model('m_commission');
                $date_time = date('Y-m-d H:i:s');
                $monthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($user['id']);
                $this->m_commission->monthFeeChangeLog($user['id'],$user['month_fee_pool'],$user['month_fee_pool'] - $chai_month,-1 * $chai_month,$date_time,6,$monthlyFeeCouponNum,$monthlyFeeCouponNum,0);
            }
        }else if($coupon['status'] == 0){ //未使用
            $this->db->where('uid',$user['id'])->set('coupon_level',$level,FALSE)->update('users_coupon_monthfee');
        }
    }

    /** 抽回团队销售 */
    public function reduceTeamSales($id,$store_level,$monthly_fee_level){

        $this->load->model('o_cash_account');
		/** 兼容用户$id阶段性升级 */
        $comm_logs = $this->o_cash_account->getCashAccountLog(array('item_type'=>3,'related_uid'=>$id),'distinct uid,related_uid as pay_user_id');
		/** 上線得到的 (減去升級时) 团队销售奖  生成降级记录*/
		$profit = $this->getUpgradeProfit($store_level,4,$id)*0.8; //新的團隊銷售利潤
		if($comm_logs)foreach($comm_logs as $comm_log){

			//generation_sales_logs 记录
			$gsl = $this->db->where('parent_id',$comm_log['uid'])->where('child_id',$comm_log['pay_user_id'])->get('generation_sales_logs')->row_array();
			$amount = $this->db->query("select SUM(push_money) amount from generation_sales_logs where child_id={$comm_log['pay_user_id']} and parent_id={$comm_log['uid']} and push_money>0 and sales>=200")->row_array();
			$reduce = $this->db->select_sum('amount')->where('type',3)->where('uid',$comm_log['uid'])->where('pay_user_id',$comm_log['pay_user_id'])->get('user_reduce_commission_logs')->row_array();
			$reduce_amount = $reduce['amount'] == null ? 0 : $reduce['amount'];
			$all_amount = $amount['amount'] == null ? 0 : $amount['amount'];

			$new_push_cash = $profit*$gsl['percent'];
			$real_cash = $all_amount - $new_push_cash - $reduce_amount;
			if($real_cash > 0){
				//添加新的佣金记录
				$comm_id = $this->m_commission->commissionLogs($comm_log['uid'],16,-1*$real_cash,$comm_log['pay_user_id']);
				$this->m_generation_sales->generationSalesLogs($gsl['parent_id'],$gsl['child_id'],$gsl['level'],$profit,$gsl['percent'],-1*$real_cash,$comm_id);
				$this->m_commission->reduceCommissionLogs($comm_log['uid'],$real_cash,3,$comm_log['pay_user_id']);
				$this->db->where('id', $comm_log['uid'])->set('amount', 'amount-' . $real_cash, FALSE)->set('team_commission', 'team_commission-' . $real_cash, FALSE)->update('users');
			}
		}


        /** 本身得到的團隊銷售獎 */
        $percents = config_item('percent_'.$store_level);
        if($store_level==4){ //回收賬號，所有的收入都抽回
            $percents = array();
        }
        $self_comm_logs = $this->o_cash_account->getCashAccountLog(array('item_type'=>3,'uid'=>$id));

        if($self_comm_logs)foreach($self_comm_logs as $self){
            //generation_sales_logs 记录
			/**
			 * 记录：使用CI连贯查询，应该只有commission_id查询，凭空多出查询条件 uid="" 诡异，只能使用query查询了。2016-08-25 john
			 */
			//$gsl2 = $this->db->where('commission_id',$self['id'])->get('generation_sales_logs')->row_array();
			$gsl2 = $this->db->query("select * from generation_sales_logs where commission_id={$self['id']}")->row_array();

			$reduce = $this->db->select_sum('amount')->where('type',3)->where('uid',$self['uid'])->where('pay_user_id',$self['related_uid'])->get('user_reduce_commission_logs')->row_array();
			if($reduce['amount'] == null){
				$reduce_amount = 0;
			}else{
				$reduce_amount = $reduce['amount'];
			}

            /** 查看降級後能否拿到這層的獎勵 */
            if(isset($percents[$gsl2['level']])){
                $new_percent = $percents[$gsl2['level']];
                $new_push_cash2 = $gsl2['sales']*$new_percent;
            }else{
                $new_push_cash2 = 0; //不能拿这层的金额
                $new_percent = 0;
            }

            $real_cash3 = $self['amount']/100 - $new_push_cash2 - $reduce_amount;
            if($real_cash3 > 0){
                //添加新的佣金记录
                $comm_id2 = $this->m_commission->commissionLogs($self['uid'],16,-1*$real_cash3,$self['related_uid']);
                $this->m_generation_sales->generationSalesLogs($gsl2['parent_id'],$gsl2['child_id'],$gsl2['level'],$gsl2['sales'],$new_percent,-1*$real_cash3,$comm_id2);
                $this->m_commission->reduceCommissionLogs($self['uid'],$real_cash3,$self['item_type'],$self['related_uid']);
                $this->db->where('id', $self['uid'])->set('amount', 'amount-' . $real_cash3, FALSE)->set('team_commission', 'team_commission-' . $real_cash3, FALSE)->update('users');
            }
        }
    }

    /** 抽回日分紅 */
    public function reduceDailyBonus($id,$store_level,$user,$point){

        $this->load->model('o_cash_account');
        $daily_logs = $this->o_cash_account->getCashAccountLog(array(
            'uid'=>$id,
            'item_type'=>6,
        ));

        $old_point = $point[$user['user_rank']]['join_fee'] + $user['profit_sharing_point'];
        $chai_point = $point[$user['user_rank']]['join_fee'] - $point[$store_level]['join_fee'];
        $new_point = $old_point - $chai_point;

        if($daily_logs)foreach($daily_logs as $daily_log){

            $daily_log['amount'] = $daily_log['amount']/100;
            $new_push_cash = $daily_log['amount']/$old_point*$new_point;
            $real_cash2 = round($daily_log['amount']-$new_push_cash,2);
            if($real_cash2 > 0){
                $this->m_commission->commissionLogs($daily_log['uid'],16,-1*$real_cash2);
                $this->m_commission->reduceCommissionLogs($daily_log['uid'],$real_cash2,$daily_log['item_type'],$daily_log['related_uid']);
                $this->db->where('id', $daily_log['uid'])->set('amount', 'amount-' . $real_cash2, FALSE)->set('amount_profit_sharing_comm', 'amount_profit_sharing_comm-' . $real_cash2, FALSE)->update('users');
            }
        }
    }

    /** 降级银级或金级 状态处理*/
    public function updateUsersAllStatus($id,$user,$store_level,$point){

        /** 用户等级变动 */
        $this->m_user->addUserLevelChangeLog($id,$user['user_rank'],$store_level,2);

        $this->db->insert('user_upgrade_log',array(
            'uid'=>$id,
            'upgrade_rank'=>$store_level,
            'create_time'=>time()
        )); /** 新的升级时间 */

        /*** 用户等级*/
        $this->db->where('id',$id)->set('user_rank',$store_level,FALSE)->set('month_fee_rank',4,FALSE)->update('users');

		/** 如果用户还没有交月费 */
		if($user['first_monthly_fee_level'] != 0 ){
			$this->db->where('id',$id)->set('first_monthly_fee_level',$store_level,FALSE)->update('users');
		}

    }

	/** 减去分红点，用户分阶段升级，要update或者delete */
	function reduce_sharing_point($uid,$reduce_point){

		$points = $this->db->where('uid',$uid)->order_by('id','desc')->get('users_sharing_point_reward')->result_array();
		if($points)foreach($points as $item){
			if( $reduce_point == 0 ) break;
			if($reduce_point >= $item['point']){
				$this->db->where('id',$item['id'])->delete('users_sharing_point_reward');
				$reduce_point = $reduce_point - $item['point'];
			}else{
				$this->db->where('id',$item['id'])->update('users_sharing_point_reward',array('point'=>$item['point']-$reduce_point));
				break;
			}
		}

	}

    /**
     * @description 新版团队销售分红
     * 免费店铺：第一级店铺销售利润提成5%；
     * 铜级店铺：第一级店铺销售利润提成10%，第二级店铺销售利润提成5%；
     * 银级店铺：第一级店铺销售利润提成12%，第二级店铺销售利润提成7%；
     * 白金店铺：第一级店铺销售利润提成15%，第二级店铺销售利润提成10%；
     * 钻石店铺：第一级店铺销售利润提成20%，第二级店铺销售利润提成12%.
     * @author brady.wangg
     * @param $user_id 用户id
     * @param int $sales 金额
     * @param int $order_id 订单id
     * @date  2017/02/23
     */
    public function generationSalesOverridesNew($user_id , $sales = 0,$order_id=0)
    {
       
        $this->load->model('tb_users');
        $user = $this->db->from('users')->select('parent_ids')->where('id',$user_id)->get()->row_array();
        if(!$user){
            return;
        }
        if($sales <= 0) return;
        $parent_ids = explode(',',$user['parent_ids']);
        $floor = config_item('team_profit_new_floor');

        $parent_ids = array_slice($parent_ids,0,$floor);
        $uids = array_merge($parent_ids,[$user_id]);
        $userInfo = $this->tb_users->getUserInfoByIds($uids,['id','user_rank']);
        $newUserInfo = [];
        foreach($userInfo as &$v) {
            $newUserInfo[$v['id']] = $v;
        }
        foreach($parent_ids as $k => $v) {
            if ($k < $floor && isset($newUserInfo[$v])) { //只能是定义的层数拿分红
                $level = $k + 1;//第几代
                $uid = $v;
                $user_rank = $newUserInfo[$v]['user_rank'];
                $percent = config_item("team_profit_".$user_rank)[$k+1];//当前父类获取的分红百分比

                $cash =  $sales * $percent;
                //佣金记录
                if($cash>=0.01){
                    $comm_log_id = $this->m_commission->commissionLogs($uid,REWARD_3,$cash,$user_id,$order_id);
                    //團隊銷售提成獎
                    $this->m_generation_sales->generationSalesLogs($uid,$user_id,$level,$sales,$percent,$cash,$comm_log_id);
                }

                /* 佣金自动转分红点 */
                $this->load->model('m_profit_sharing');
                $rate = $this->m_profit_sharing->getProportion($uid, 'sale_commissions_proportion') / 100;
                $commissionToPoint = 0;
                if ($rate > 0) {
                    $commissionToPoint = tps_money_format($cash * $rate);
                    if($commissionToPoint>=0.01){
                        $this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+' . $commissionToPoint, FALSE)->update('users');
                        $comm_id = $this->m_commission->commissionLogs($uid,17,-1*$commissionToPoint); //佣金轉分紅點
                        $this->m_profit_sharing->createPointAddLog(array(
                            'uid' => $uid,
                            'commission_id' => $comm_id,
                            'add_source' => 1,
                            'money' => $commissionToPoint,
                            'point' => $commissionToPoint
                        ));

                    }
                }

                $real_cash = $cash - $commissionToPoint;
                //添加提成后的金额
                $this->db->where('id', $uid)
                    ->set('amount', 'amount+' . $real_cash, FALSE)
                    ->set('team_commission', 'team_commission+' . $cash, FALSE)->update('users');

            }
        }
    }

}