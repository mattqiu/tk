
<?php
    class m_helper extends CI_Model{

        function __construct() {
            parent::__construct();
            $this->load->Model('m_forced_matrix');
        }
        /***用户降级后2x5佣金抽回***/
        public function minus_2x5_commission($user_id,$month_fee_rank)
        {
            $this->load->model('o_cash_account');
            $user = $this->m_forced_matrix->userInfo($user_id);
            if ($user != false) {
                //$user_rank = $user->user_rank;
                //$month_fee_rank = $user->month_fee_rank;
                //1.减去佣金差额,并增加记录
                $result = $this->o_cash_account->getCashAccountLog(array('related_uid'=>$user_id,'item_type'=>1));
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $parent_id = $value['uid'];
                        $commission = $value['amount']/100;     //原来的佣金
                        $lower_commission = 0.0;          //降级之后的佣金
                        if ($commission == DIAMOND_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = GOLD_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = DIAMOND_CASH;}
                        }
                        if ($commission == GOLD_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = GOLD_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = GOLD_CASH;}
                        }
                        if ($commission == SILVER_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = SILVER_CASH;}
                        }

                        $res_commission = $commission - $lower_commission;      //佣金差额
                        $sql = "update users set amount=amount-$res_commission,personal_commission=personal_commission-$res_commission where id=" . $parent_id;
                        $this->db->query($sql);

                        /**记录到commission_log表**/
                        if ($res_commission > 0) {
                            $this->o_cash_account->createCashAccountLog(array(
                                'uid'=>$parent_id,
                                'item_type'=>16,
                                'amount'=>-tps_int_format($res_commission*100),
                                'create_time'=>date("Y-m-d H:i:s", time()),
                                'related_uid'=>$user_id,
                            ));
                            $this->load->model('m_commission');
                            $this->m_commission->reduceCommissionLogs($parent_id,$res_commission,1,$user_id);
                        }
                    }
                }

                //2.降级后,判断此ID是否拿过2x5佣金，如果拿过，减去此ID的佣金差额
                $result = $this->o_cash_account->getCashAccountLog(array('uid'=>$user_id,'item_type'=>1));
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $commission = $value['amount'];
                        $pay_user_id=$value['related_uid'];
                        $lower_commission = 0.0;          //降级之后的佣金
                        if ($commission == DIAMOND_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = GOLD_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = DIAMOND_CASH;}
                        }
                        if ($commission == GOLD_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = GOLD_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = GOLD_CASH;}
                        }
                        if ($commission == SILVER_CASH) {
                            if ($month_fee_rank == 4) {$lower_commission = FREE_CASH;}
                            if ($month_fee_rank == 3) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 2) {$lower_commission = SILVER_CASH;}
                            if ($month_fee_rank == 1) {$lower_commission = SILVER_CASH;}
                        }
                        $res_commission = $commission - $lower_commission;      //佣金差额
                        $sql = "update users set amount=amount-$res_commission,personal_commission=personal_commission-$res_commission where id=" . $user_id;
                        $this->db->query($sql);

                        /**记录到commission_log表**/
                        if ($res_commission > 0) {
                            $this->o_cash_account->createCashAccountLog(array(
                                'uid'=>$user_id,
                                'item_type'=>16,
                                'amount'=>-tps_int_format($res_commission*100),
                                'create_time'=>date("Y-m-d H:i:s", time()),
                                'related_uid'=>$pay_user_id,
                            ));
                            $this->load->model('m_commission');
                            $this->m_commission->reduceCommissionLogs($user_id,$res_commission,1,$pay_user_id);
                        }
                    }
                }
            }
        }

		/** 用户登录ip信息-》中文 */
		function zh_ip_to_address(){
			$cronName = 'zh_ip_to_address';

			$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
			if($cron){
				if($cron['false_count'] > 29){
					$this->db->delete('cron_doing', array('cron_name' => $cronName));
					return false;
				}
				$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
				return false;
			}

				$this->db->insert('cron_doing',array(
					'cron_name'=>$cronName
				));

				$ip_data = $this->db->select('id,ip')->from('sync_ip_to_address')->where('is_zh',0)->limit(200)->get()->result_array();
				if($ip_data)foreach($ip_data as $ip_item){

					$type_2 =  $this->db->from('ip_address_info')->where('ip',$ip_item['ip'])->where('type',2)->count_all_results();
					if(!$type_2){

						$ipInfo = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip_item['ip']);
						$ipInfo = json_decode($ipInfo,true);
						if($ipInfo['code']==0){
							$ipInfo = $ipInfo['data'];
							if($ipInfo['country'] && strlen($ipInfo['country_id'])==2){

									$this->db->insert('ip_address_info',array(
										'ip'=>$ip_item['ip'],
										'country_code'=>$ipInfo['country_id'],
										'country_name'=>$ipInfo['country'],
										'region_code'=>$ipInfo['region_id'],
										'region_name'=>$ipInfo['region'],
										'city_code'=>$ipInfo['city_id'],
										'city'=>$ipInfo['city'],
										'type'=>2,
									));
									$this->db->where('id',$ip_item['id'])->update('sync_ip_to_address',array('is_zh'=>1));
								}
							}
						}else{
							$this->db->where('id',$ip_item['id'])->update('sync_ip_to_address',array('is_zh'=>1));
						}
						$count = $this->db->from('sync_ip_to_address')->where('id',$ip_item['id'])->where('is_zh',1)->where('is_english',1)->count_all_results();
						if($count){
							$this->db->where('id',$ip_item['id'])->delete('sync_ip_to_address');
						}
					}

				$this->db->delete('cron_doing', array('cron_name' => $cronName));

		}

		/** 用户登录ip信息-》English */
		function english_ip_to_address(){
			$cronName = 'english_ip_to_address';

			$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
			if($cron){
				if($cron['false_count'] > 29){
					$this->db->delete('cron_doing', array('cron_name' => $cronName));
					return false;
				}
				$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
				return false;
			}

				$this->db->insert('cron_doing',array(
					'cron_name'=>$cronName
				));

				$ip_data = $this->db->select('id,ip')->from('sync_ip_to_address')->where('is_english',0)->limit(200)->get()->result_array();
				if($ip_data)foreach($ip_data as $ip_item){

					$type_1 =  $this->db->from('ip_address_info')->where('ip',$ip_item['ip'])->where('type',1)->count_all_results();
					if(!$type_1){
						//$ipInfo1 = @file_get_contents("http://freegeoip.net/json/".$ip_item['ip']);
						$ipInfo1 = @file_get_contents("http://ip-api.com/json/".$ip_item['ip']);
						$ipInfo1 = json_decode($ipInfo1,true);

						if($ipInfo1['status'] === 'success'){

								$this->db->insert('ip_address_info',array(
									'ip'=>$ip_item['ip'],
									'country_code'=>$ipInfo1['countryCode'],
									'country_name'=>$ipInfo1['country'],
									'region_code'=>$ipInfo1['region'],
									'region_name'=>$ipInfo1['regionName'],
									'city'=>$ipInfo1['city'],
									'type'=>1,
								));
								$this->db->where('id',$ip_item['id'])->update('sync_ip_to_address',array('is_english'=>1));
							}
						}else{
							$this->db->where('id',$ip_item['id'])->update('sync_ip_to_address',array('is_english'=>1));
						}
						$count = $this->db->from('sync_ip_to_address')->where('id',$ip_item['id'])->where('is_zh',1)->where('is_english',1)->count_all_results();
						if($count){
							$this->db->where('id',$ip_item['id'])->delete('sync_ip_to_address');
						}
					}

				$this->db->delete('cron_doing', array('cron_name' => $cronName));

		}

		/**  */
		function getIpAddress(){
			$ips = $this->db->select('ip')->where('type',2)->get('ip_address_info')->result_array();
			if($ips)foreach($ips as $ip){
				$type_1 =  $this->db->from('ip_address_info')->where('ip',$ip['ip'])->where('type',1)->count_all_results();
				if(!$type_1){
					//$ipInfo1 = @file_get_contents("http://freegeoip.net/json/".$ip_item['ip']);
					$ipInfo1 = @file_get_contents("http://ip-api.com/json/".$ip['ip']);
					$ipInfo1 = json_decode($ipInfo1,true);

					if($ipInfo1['status'] === 'success'){

						$this->db->insert('ip_address_info',array(
							'ip'=>$ip['ip'],
							'country_code'=>$ipInfo1['countryCode'],
							'country_name'=>$ipInfo1['country'],
							'region_code'=>$ipInfo1['region'],
							'region_name'=>$ipInfo1['regionName'],
							'city'=>$ipInfo1['city'],
							'type'=>1,
						));

					}
				}
			}
		}

		/** 订单回滚后处理 */
		public function processOrdersRollback($idx){
			/* $cronName = 'processOrdersRollback';

			$cron = $this->db->from('cron_doing')->where('cron_name',$cronName)->get()->row_array();
			if($cron){
				if($cron['false_count'] > 3){
					$this->db->delete('cron_doing', array('cron_name' => $cronName));
					return false;
				}
				$this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
				return false;
			}

			$this->db->insert('cron_doing',array(
				'cron_name'=>$cronName
			)); */

			$this->load->model('M_order','m_order');
			$sql = 'SELECT * FROM `logs_orders_rollback` WHERE `status` = 0 AND process_num < 10 AND id % 10 = '.$idx.' LIMIT 200';
                        @$this->db->reconnect();//重连数据库
			$orders = $this->db->query($sql)->result_array();
// 			$orders = $this->db->from('logs_orders_rollback')->where('status',0)->where('process_num < ',10)->where('id % 10', $idx)->limit(10)->get()->result_array();
			if($orders)foreach($orders as $order_rollback){

				$act = $this->m_order->getPayAction($order_rollback['order_id']);
                if($act['table'] == "trade_orders") {
//                    $order = $this->db->from($act['table'])->where($act['field'], $order_rollback['order_id'])->get()->row_array();
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one_auto([
                        "where"=>[$act['field']=>$order_rollback['order_id']]
                    ]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $order_rollback['order_id'])->get()->row_array();
                }
                
                if (empty($order)) {
                	$this->db->where('order_id',$order_rollback['order_id'])->update('logs_orders_rollback',array('process_num'=> 99));
                	echo 'rollback';
                }
                
				if($act['table'] !== 'user_month_fee_order'){   /** 除了月费充值表，其他表的 notify_num 字段清零 */
				    if($act['table'] == "trade_orders")
                    {
                        $this->load->model("tb_trade_orders");
                        $this->tb_trade_orders->update_one([$act['field']=>$order_rollback['order_id']],
                            ["pay_time"=>"0000-00-00 00:00:00"]);//使用pay_time替换notify_num的判断
//                        $this->db->where($act['field'],$order_rollback['order_id'])->update($act['table'],array('notify_num'=>0));
                    }else{
                        $this->db->where($act['field'],$order_rollback['order_id'])->update($act['table'],array('notify_num'=>0));
                    }
				}

				if($act['payFunc'] === 'mall_order_paid' && isset($act['order_type'])){ //  兼容以前的功能
					$order['join_fee'] = $act['order_type']['amount'];
					$order['level'] = $act['order_type']['level'];
				}
				$result = $this->m_order->$act['payFunc']($order, $order_rollback['txn_id']);
				if($result){ /** 成功 */
					$this->db->where('order_id',$order_rollback['order_id'])->update('logs_orders_rollback',array('status'=>1,'process_num'=>$order_rollback['process_num']+1));
					echo 'success';
				}else{
					$this->db->where('order_id',$order_rollback['order_id'])->update('logs_orders_rollback',array('process_num'=>$order_rollback['process_num']+1));
					echo 'rollback';
				}
			}
// 			$this->db->delete('cron_doing', array('cron_name' => $cronName));
		}

		/** 订单表有记录，而订单商品表不存在记录的情况 商品下架 或 订单没有记录到产品列表 */
		public function get_order_goods2(){
			$return = array();
			foreach(config_item('supportLanguage') as $v){
				$lanFileKey = array_search("mall_lang.php",$this->lang->is_loaded);
				if($lanFileKey!==false){
					unset($this->lang->is_loaded[$lanFileKey]);
				}
				$this->lang->load('mall',$v);
				$return[$v] = array(
					'label_size'=>lang('label_size'),
					'label_color'=>lang('label_color'),
				);
			}
            $this->load->model("tb_trade_orders");
			$lang_arr = array(1=>'english',2=>'zh',3=>'hk');
            $orders = $this->tb_trade_orders->get_empty_mall_goods_trade_orders();


            $this->load->model("tb_trade_orders_goods");
			foreach($orders as $order_info){
				$goods_arr = array();
				$goods = explode("$", $order_info['goods_list']);
				if($goods)foreach ($goods as $v)
				{
					if(!$v){
						continue;
					}
					list($goods_sn, $quantity) = explode(":", $v);

					$user = $this->db->select('country_id')->where('id',$order_info['customer_id'])->get('users')->row_array();
					if($user['country_id'] == 1){
						$language_id = 2;
					}else if($user['country_id'] == 4){
						$language_id = 3;
					}else{
						$language_id = 1;
					}

					$lan = $lang_arr[$language_id];

					// 获取商品信息
//					$goods_info = $this->db
//						->select('goods_sn_main,price,size,color')
//						->from('mall_goods')
//						->where('goods_sn', $goods_sn)
//						//->where('language_id', $language_id)
//						->get()
//						->row_array();
                    $this->load->model("tb_mall_goods");
                    $goods_info = $this->tb_mall_goods->get_one('goods_sn_main,price,size,color,purchase_price',
                        ['goods_sn'=>$goods_sn]);
                    if (empty($goods_info))
					{
						echo $goods_sn.'not exist!';
						continue;
					}

					// 获取商品主要信息
					$goods_main_info = $this->db
						->select('is_doba_goods,market_price,cate_id,goods_name')
						->from('mall_goods_main')
						->where('goods_sn_main', $goods_info['goods_sn_main'])
						//->where('language_id', $language_id)
						->get()
						->row_array();
					if (empty($goods_main_info))
					{
						echo $order_info['goods_list'].'not exist!!!';
						continue;
					}

					// 订单商品信息
					$str = $goods_info['size'] ? $return[$lan]['label_size'].':'.$goods_info['size'] : '';
					$temp = $str ? ',':'';
					$str .= $goods_info['color'] ? $temp.$return[$lan]['label_color'].':'.$goods_info['color'] : '';

					$goods_attr_arr[$goods['goods_sn'].'_'.$language_id] = $str;
					$goods_arr[$goods_sn]['goods_sn'] = $goods_sn;
					$goods_arr[$goods_sn]['goods_name'] = $goods_main_info['goods_name'];
					$goods_arr[$goods_sn]['goods_attr'] = $str;
					$goods_arr[$goods_sn]['goods_number'] = $quantity;
					$goods_arr[$goods_sn]['goods_price'] = $goods_info['price'];
					$goods_arr[$goods_sn]['cate_id'] = $goods_main_info['cate_id'];
					$goods_arr[$goods_sn]['goods_sn_main'] = $goods_info['goods_sn_main'];
					$goods_arr[$goods_sn]['market_price'] = $goods_main_info['market_price'];
					$goods_arr[$goods_sn]['is_doba_goods'] = $goods_main_info['is_doba_goods'];
                    $goods_arr[$goods_sn]['supply_price'] = $goods_info['purchase_price'];//本币采购价
				}
				if($goods_arr)foreach($goods_arr as $goods_info_ins){
                    $goods_info_ins['order_id'] = $order_info['order_id'];
//					$this->db->insert('trade_orders_goods',$goods_info);
					$this->tb_trade_orders_goods->insert_one($goods_info_ins);
				}
			}
		}

		/** 个人店铺升级产品套装销售 一月份现金红包 */
		function cash_bonus(){
			$this->db->trans_start();
			//$this->db->query("TRUNCATE `users_cash_bonus`");
			$users = $this->db->query("select u.uid,member_silver_num + member_platinum_num + member_diamond_num all_count
							from users_referrals_count_info u
							where member_silver_num + member_platinum_num + member_diamond_num >=3")->result_array();
			foreach($users as $ke=>$user){
				$child_ids = $this->db->select('id')->where('parent_id',$user['uid'])->where('user_rank <',4)->where('status !=',4)->get('users')->result_array();
				$child_count = count($child_ids);
				$count = 0;
				if($child_count >= 3){
					foreach($child_ids as $child_id){
						$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
						if(!empty($date) &&$date['create_time'] > '2016-01-01 00:00:00' && $date['create_time'] <= '2016-01-31 23:59:59'){
							$count = $count + 1;
						}
					}/** 剩下的都是1月升级的，其他退出循环 */

					if($count < 3){
						continue;
					}
					/**  1月 店铺的销售金额 和 合格订单 */
					$sales_info = $this->db->where('uid',$user['uid'])->where('year_month','201601')->get('users_store_sale_info_monthly')->row_array();
					$amount = 0;
					if(!empty($sales_info) && $sales_info['sale_amount']>=25000 && $sales_info['orders_num']>=10 && $count >= 10){
						$amount = 1000;
					}else if(!empty($sales_info) && $sales_info['sale_amount']>=15000 && $sales_info['orders_num']>=6 && $count >= 6){
						$amount = 500;
					}else if(!empty($sales_info) && $sales_info['sale_amount']>=7500 && $sales_info['orders_num']>=3 && $count >= 3){
						$amount = 200;
					}

					if(!empty($sales_info) && $amount!=0 ){
						//var_dump($user['uid'].'-----'.$count.'---'.$sales_info['sale_amount']/100 .'-----'.$sales_info['orders_num']);
						$this->db->insert('users_cash_bonus',array(
							'uid'=>$user['uid'],
							'amount'=>$amount,
							'type'=>1,
							'referrals_num'=>$count,
							'sale_amount'=>$sales_info['sale_amount']/100,
							'orders_num'=>$sales_info['orders_num'],
						));
					}

				}
				//var_dump($ke);
			}
			/** 统计好之后发放奖励红包 */
			$this->grant_cash_bonus();

			$this->db->trans_complete();
			echo 'success';
		}

		/** 个人店铺升级产品套装销售 三月份现金红包 */
		function cash_bonus_3(){
			$this->db->trans_start();
			$users = $this->db->query("select u.uid,member_silver_num + member_platinum_num + member_diamond_num all_count
							from users_referrals_count_info u
							where member_silver_num + member_platinum_num + member_diamond_num >=5")->result_array();
			foreach($users as $ke=>$user){
				$child_ids = $this->db->select('id')->where('parent_id',$user['uid'])->where('user_rank <',4)->where_in('store_qualified',1)->get('users')->result_array();
				$child_count = count($child_ids);
				$count = 0;
				if($child_count >= 5){

					foreach($child_ids as $child_id){
						$date = $this->db->select('create_time')->where('level_type',2)->where('uid',$child_id['id'])->get('users_level_change_log')->row_array();
						if(!empty($date)&&$date['create_time'] <= '2016-02-29 23:59:59'){
							$count = $count + 1;
						}
					}/** 剩下的都是2月29日之前升级的，其他退出循环 */

					if($count < 5){
						continue;
					}

					$amount = 0;
					if($count >= 20){
						$amount = 1000;
					}else if($count >= 15){
						$amount = 750;
					}else if($count >= 10){
						$amount = 500;
					}else if($count >= 5){
						$amount = 250;
					}

					if($amount!=0 ){
						//var_dump($user['uid'].'-----'.$count);
						$this->db->insert('users_cash_bonus',array(
							'uid'=>$user['uid'],
							'amount'=>$amount,
							'type'=>2,
							'referrals_num'=>$count,
							'sale_amount'=>0,
							'orders_num'=>0,
						));
					}

				}
				//var_dump($ke);
			}
			/** 统计好之后发放奖励红包 */
			$this->grant_cash_bonus();

			$this->db->trans_complete();
			echo 'success';
		}

		/** 发放一月份现金红包 */
		public function grant_cash_bonus(){
			//$this->db->trans_start();
			$logs = $this->db->where('status','0')->get('users_cash_bonus')->result_array();
			$this->load->model('m_commission');
			$date = date('Y-m-d H:i:s');
			if($logs)foreach($logs as $log){
				$u = $this->db->select('store_qualified')->where('id',$log['uid'])->get('users')->row_array();
				if($u['store_qualified'] == 1){
					$comm_id = $this->m_commission->commissionLogs($log['uid'],9,$log['amount'],'0','',$date);
					$this->db->where('id',$log['id'])->update('users_cash_bonus',array('status'=>1,'commission_id'=>$comm_id));
					$this->db->where('id',$log['uid'])->set('amount', 'amount+' . $log['amount'], FALSE)->update('users');
				}
			}
			//$this->db->trans_complete();
			//echo 'success';
		}

		/** 用户重复升级多发的奖励抽回：6位 */
//		public function repeat_upgrade(){
//			return 'success';
//			$this->db->trans_start();
//			$sql = "select * from cash_account_log_201609 where related_uid=1380265874 and item_type=16 and order_id='N201609092141046368' limit 10;";
//			$logs = $this->db->query($sql)->result_array();
//			$this->load->model('m_commission');
//			foreach($logs as $log){
//
//				$amount = abs($log['amount']/100);
//				//添加抽回佣金记录
//				$this->db->where('id',$log['id'])->delete('cash_account_log_201609');
//				//抽回佣金
//				$this->db->where('id',$log['uid'])->set('amount', 'amount+' . $amount, FALSE)->set('team_commission', 'team_commission+' . $amount, FALSE)->update('users');
//			}
//			$this->db->trans_complete();
//			if($this->db->trans_status() == TRUE){
//				return 'success';
//			}else{
//				return 'fail';
//			}
//		}
    }