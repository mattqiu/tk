<?php
/**
 * 新订单完成后的处理逻辑队列表
 * @author Terry
 */
class tb_new_order_trigger_queue extends CI_Model {

	public $table_name = 'new_order_trigger_queue';
	
    function __construct() {
        parent::__construct();
    }

    /**
     * 从db表查询记录
     * @author: derrick
     * @date: 2017年4月14日
     * @param: @param unknown $limit
     * @reurn: return_type
     */
    public function get_list($limit = 500) {
    	return $this->db->get($this->table_name, $limit)->result_array();
    }
    
    /**
     * 根据订单ID从数据库删除记录
     * @author: derrick
     * @date: 2017年5月10日
     * @param: @param string $order_id 订单ID
     * @reurn: return_type
     */
    public function delete_by_order_id_from_db($order_id) {
    	return $this->db->delete($this->table_name, array('oid' => $order_id));
    }
    
    /**
     * 添加新订单到队列
     * @param $oid 订单号
     */
    public function addNewOrderToQueue($oid,$uid,$order_amount_usd,$order_profit_usd,$order_year_month=''){
//         file_put_contents("a.txt",date("Y-m-d H:i:s"),FILE_APPEND);
        if($oid!='' && $uid>0 && $order_amount_usd>0){
            if(!$order_year_month){
                $order_year_month = date('Ym');
            }
           $this->load->model ( 'o_queen' );
            $key = 'tb_new_order_trigger_queue_addNewOrderToQueue_'.$oid;
            $incr_val = $this->o_queen->redis_incr($key);
            if (is_null($incr_val)) {
            	//redis关闭则写入db
            	$this->db->insert('new_order_trigger_queue',array('oid'=>$oid,'uid'=>$uid,'order_amount_usd'=>$order_amount_usd,'order_profit_usd'=>$order_profit_usd,'order_year_month'=>$order_year_month));
            	return true;
            }
            
            if ($incr_val > 1) {
            	return false;
            }
            //30分钟内同一订单不能重复入队
            $this->o_queen->redis_expire($key, 1800);
            $queen_data = array(
            	'oid' => $oid,
            	'uid' => $uid,
            	'order_amount_usd' => $order_amount_usd,
            	'order_profit_usd' => $order_profit_usd,
            	'order_year_month' => $order_year_month,
            );
            $queen_data['server_ip'] = $this->_get_server_ip();
            $this->o_queen->en_queen(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER, $queen_data);
             /* $a = $this->db->last_query();
            file_put_contents("a.txt",$a,FILE_APPEND); */
            return true;
        }
    }

    /**
     * 处理队列[逻辑源于原存储过程new_order_trigger], 多进程逻辑
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param string $oid 订单号
     * @param: @param int $uid 用户id(店主id)
     * @param: @param int $order_amount_usd 订单金额（美分）
     * @param: @param int $order_profit_usd 订单利润（美分）
     * @param: @param int $order_year_month 订单年月
     * @reurn: 
     * 	0: 发奖失败
     *  1: 发奖成功
     *  2: 个人奖发奖失败
     *  3: 团队奖发奖失败
     */
	public function do_job($oid, $uid, $order_amount_usd, $order_profit_usd, $order_year_month) {
		$this->db->throw_db_exception = true;
		$this->db->trans_begin();
		try {
			//更新用户店铺总业绩
			$this->load->model('tb_users_store_sale_info');
			$total_sale_amount = 0;	//总销售额
			$this->db->force_master();
			$user_store_sale_info = $this->tb_users_store_sale_info->getUserSaleInfo($uid, false, true);
			if ($user_store_sale_info) {
				$nu = $this->tb_users_store_sale_info->updateByUid($uid, array('sale_amount_update' => $order_amount_usd));
				if($nu == 0){
                    $this->db->trans_rollback();
                    return 0;
                }
				$total_sale_amount = $user_store_sale_info['sale_amount'] + $order_amount_usd;
			} else {
                $nu = $this->tb_users_store_sale_info->add_user_sale_info($uid, 0, $order_amount_usd);
                if($nu == 0){
                    $this->db->trans_rollback();
                    return 0;
                }
				$total_sale_amount = $order_amount_usd;
			}
			
			//判断用户是否正在补单中
			$this->load->model('tb_withdraw_task');
			$this->db->force_master();
			$is_order_repairing = $this->tb_withdraw_task->checkOrderRepairing($uid);
			$repairing_order_year_month = $order_year_month;
			if ($is_order_repairing) {
				$repairing_order_year_month = $is_order_repairing;
				//正在补单中
				$this->tb_withdraw_task->orderRepair($uid, $order_amount_usd);
					
				switch (substr($oid, 0, 2)) {
					case 'WL':
					case 'W-':
						$this->load->model('tb_mall_orders');
						$this->tb_mall_orders->updateScore_year_month($oid,$repairing_order_year_month);
						break;
					case 'O-':
						$this->load->model('tb_one_direct_orders');
						$this->tb_one_direct_orders->updateScore_year_month($oid,$repairing_order_year_month);
						break;
					case 'A-':
						$this->load->model('tb_walmart_orders');
						$this->tb_walmart_orders->updateScore_year_month($oid,$repairing_order_year_month);
						break;
					default:
						$this->load->model('tb_trade_orders');
						$this->tb_trade_orders->updateScore_year_month($oid,$repairing_order_year_month);
						break;
				}
			}
			
			//更新月统计业绩
			$this->load->model('tb_users_store_sale_info_monthly');
			$month_sale_amount = 0;	//用户当前月销售额
			$this->db->force_master();
			$store_sale_info_monthly = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($uid, $repairing_order_year_month, false);
			if ($store_sale_info_monthly) {
				$this->tb_users_store_sale_info_monthly->updateByUidAndDate($uid, $repairing_order_year_month,array('sale_amount_update' => $order_amount_usd));
				$month_sale_amount = $store_sale_info_monthly->sale_amount + $order_amount_usd;
			} else {
				$this->tb_users_store_sale_info_monthly->add_users_sale_info_monthly($uid, $repairing_order_year_month, 0, $order_amount_usd);
				$month_sale_amount = $order_amount_usd;
			}
			$this->tb_users_store_sale_info_monthly->user_rank_change_week_comm($uid, 0, 0, 2);
			
			//获取用户相关信息
			$this->load->model('tb_users');
			$users_info = $this->tb_users->get_user_info($uid, 'user_rank,sale_rank,`status`,month_fee_pool,parent_ids,store_qualified,amount_profit_sharing_comm');
			if (empty($users_info)) {
				$this->db->trans_rollback();
				return -1;
			}

			$users_info['parent_ids'] = substr($users_info['parent_ids'], 0, 21);
			
			//发放订单对应的个人店铺销售提成
			$this->load->model('o_bonus');
			$personal_result = $this->o_bonus->personal_prize($uid, $oid, $order_profit_usd, $order_year_month);
			if (!$personal_result) {
				$this->db->trans_rollback();
				return 2;
			}
			//发放团队销售提成
			$this->o_bonus->group_prize($users_info['parent_ids'], $oid, $order_profit_usd, $uid, 2, $order_year_month);
			
			//免费店铺满50美金合格，满100美金送100分红点。
			if (isset($users_info['user_rank']) && $users_info['user_rank'] == 4 && $total_sale_amount >= 5000) {
				if ($users_info['store_qualified'] == 0) {
					$this->tb_users->update_info($uid, array('store_qualified' => 1));
				}
				if ($total_sale_amount >= 10000) {
					$this->load->model('tb_users_sharing_point_reward');
					$reward_user = $this->tb_users_sharing_point_reward->find_by_uid($uid);
					if (empty($reward_user)) {
						$reward_user = $this->tb_users_sharing_point_reward->add_users_sharing_point_reward($uid, 100, date('Y-m-d', strtotime('+15 month')));
					}
				}
			}
			
			$this->db->trans_commit();
			return 1;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			write_custom_log($e, 'tb_new_order_trigger_queue_do_job');
			return 0;
		}
	}

	/**
	 * 获取服务器IP. 用于调试代码
	 * @author: derrick
	 * @date: 2017年4月14日
	 * @param: @return string
	 * @reurn: string
	 */
	private function _get_server_ip() {
		$server_ip = '来自脚本服务器';
		if (isset($_SERVER)) {
			if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']) {
				$server_ip = $_SERVER['SERVER_ADDR'];
			} else {
				$server_ip = isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : 'LOCAL_ADDR IP IS NULL';
			}
		} else {
			$server_ip = getenv('SERVER_ADDR');
		}
		return $server_ip;
	}
}