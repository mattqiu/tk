<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}

/**
 * redis队列处理计划任务类
 * 
 * @author derrick
 * @date 2017年3月27日
 */
class Cron_queen extends CI_Controller {
	
	public function __construct() {
		if (php_sapi_name() != 'cli') {
			exit('the shell must be execute by cli method');
		}
		parent::__construct();
		ignore_user_abort(true);
		set_time_limit(0);
	}
	
	/**
	 * 处理下单后用户店铺订单数据统计队列
	 * @author: derrick
	 * @date: 2017年3月28日
	 * @param: 
	 * @reurn: return_type
	 */
	public function deal_queen_for_stat_user_store() {
		$max_child_process_num = 15;	//定义最大进程数
		$this->load->model(array('o_pcntl', 'o_queen', 'tb_order_prize_queue_log'));
		
		$queen_length = $this->o_queen->get_queen_length(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER);
		$queen_lists = array();
		$save_db_lists = [];
		$list = $this->o_queen->get_list_by_page(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER, 1, $queen_length);
		$exec_time = date('Y-m-d H:i:s');
		foreach ($list as $queen_data) {
			$queen_data = $this->o_queen->get_value($queen_data);
			if (isset($queen_data['uid'])) {
				$save_db_lists[] = array(
					'order_id' => $queen_data['oid'],
					'exec_time' => $exec_time,
					'exec_result' => 0,
				);
				$arr_idx = $queen_data['uid'] % ($max_child_process_num - 1);
				if (!isset($queen_lists[$arr_idx])) {
					$queen_lists[$arr_idx] = array();
				}
				$queen_lists[$arr_idx][] = $queen_data;
			}
		}
		if ($save_db_lists) {
			$this->tb_order_prize_queue_log->batch_save_ignore($save_db_lists);
			$this->o_queen->remove_from_top(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER, $queen_length);
		}
		unset($save_db_lists);
		$this->db->close();
		
		foreach ($queen_lists as $idx => $list) {
			$this->o_queen->redis_closes(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER);
			$this->o_pcntl->fork_child(function() use ($list){
				foreach ($list as $data) {
					if (isset($data['oid']) && isset($data['uid']) && isset($data['order_amount_usd']) && isset($data['order_profit_usd']) && isset($data['order_year_month'])) {
						//子进程执行任务
						$this->load->model(array('tb_new_order_trigger_queue', 'tb_order_prize_queue_log', 'tb_new_order_trigger_queue_err_log'));
						$result = $this->tb_new_order_trigger_queue->do_job($data['oid'], $data['uid'], $data['order_amount_usd'], $data['order_profit_usd'], $data['order_year_month']);
						
						//记录执行日志
						$result_data = array(
							'exec_result' => $result,
							'uid' => $data['uid'],
							'order_amount_usd' => $data['order_amount_usd'],
							'order_profit_usd' => $data['order_profit_usd'],
							'score_year_month' => $data['order_year_month']
						);
						if ($result == 1) {
							//执行成功
							$this->tb_order_prize_queue_log->update_by_order_id($data['oid'], $result_data);
						} elseif ($result < 0) {
							//已知原因确定无法执行, 删除log.
							$this->tb_order_prize_queue_log->delete_by_order_id($data['oid']);
							
							$this->tb_new_order_trigger_queue_err_log->add_log($data['uid'], $data['oid'], $data['order_amount_usd'], $data['order_profit_usd'], $data['order_year_month']);
						} else {
							//不明原因执行失败, 标记log等待下次执行
							$this->tb_order_prize_queue_log->update_by_order_id($data['oid'], $result_data, true);
						}
					}
				}
			});
		}
		exit(1);
	}
	
	/**
	 * 零售订单发奖, 个人/团队奖.  db队列
	 * @author: derrick
	 * @date: 2017年4月14日
	 * @param: 
	 * @reurn: return_type
	 */
	public function deal_queen_for_stat_user_store_from_db() {
		$this->load->model(array('tb_new_order_trigger_queue', 'tb_order_prize_queue_log'));
		$lists = $this->tb_new_order_trigger_queue->get_list(500);
		foreach ($lists as $data) {
			$this->tb_order_prize_queue_log->add(array(
					'order_id' => $data['oid'],
					'uid' => $data['uid'],
					'order_amount_usd' => $data['order_amount_usd'],
					'order_profit_usd' => $data['order_profit_usd'],
					'score_year_month' => $data['order_year_month']
			));
			
			$result = $this->tb_new_order_trigger_queue->do_job($data['oid'], $data['uid'], $data['order_amount_usd'], $data['order_profit_usd'], $data['order_year_month']);
			
			//记录执行日志
			$result_data = array(
				'exec_result' => $result,
			);
			if ($result == 1) {
				//执行成功
				$this->tb_order_prize_queue_log->update_by_order_id($data['oid'], $result_data);
			} elseif ($result < 0) {
				//已知原因确定无法执行, 删除log.
				$this->tb_order_prize_queue_log->delete_by_order_id($data['oid']);
					
				$this->tb_new_order_trigger_queue_err_log->add_log($data['uid'], $data['oid'], $data['order_amount_usd'], $data['order_profit_usd'], $data['order_year_month']);
			} else {
				//不明原因执行失败, 标记log等待下次执行
				$this->tb_order_prize_queue_log->update_by_order_id($data['oid'], $result_data, true);
			}
			
			$this->tb_new_order_trigger_queue->delete_by_order_id_from_db($data['oid']);
		}
		exit;
	}

	/**
	 * @author: derrick 监控order_prize_queue_log表, 拿出上次执行失败的订单, 1小时检测一次. 失败超过3次的数据删除
	 * @date: 2017年5月6日
	 * @param: 
	 * @reurn: return_type
	 */
	public function check_personal_prize_is_success() {
		$this->load->model(array('tb_order_prize_queue_log', 'tb_new_order_trigger_queue', 'tb_new_order_trigger_queue_err_log'));
		$lists = $this->tb_order_prize_queue_log->find_by_reverse_result_and_time(array(1), date('Y-m-d H:i:s', strtotime('-12 hours')), date('Y-m-d H:i:s'));
		foreach ($lists as $l) {
			if ($l['err_count'] >= 3) {
				$this->tb_order_prize_queue_log->delete_by_pks($l['log_id']);
				$this->tb_new_order_trigger_queue_err_log->add_log($l['uid'], $l['order_id'], $l['order_amount_usd'], $l['order_profit_usd'], $l['score_year_month']);
				continue;
			}
			$this->tb_new_order_trigger_queue->addNewOrderToQueue($l['order_id'], $l['uid'],$l['order_amount_usd'],$l['order_profit_usd'],$l['score_year_month']);
		}
	}

	/**
	 * @author: derrick 定时修复职称数据
	 * @date: 2017年6月28日
	 * @param: 
	 * @reurn: return_type
	 */
	public function recheck_user_rank() {
		set_time_limit(0);
                ini_set('memory_limit', '5000M');
		$this->load->model(array('o_queen', 'tb_users_child_group_info'));
		//修复职称错误
		while ($user_id = $this->o_queen->de_unique_queue(o_queen::QUEEN_USER_RANK_TITLE)) {
			$this->tb_users_child_group_info->fix_user_rank($user_id);
		}
		//修复职称更新时间错误
		while ($user_id = $this->o_queen->de_unique_queue(o_queen::QUEEN_USER_SALE_RANK_UP_TIME)) {
			$this->tb_users_child_group_info->fix_user_sale_rank_up_time($user_id);
                }
		
                //修复会员上下级对应关系错误
		while ($user_id = $this->o_queen->de_unique_queue(o_queen::QUEEN_USER_LOGIC)) {
			$this->tb_users_child_group_info->fix_user_logic($user_id);
		}
		exit('done');
	}
	
	/**
	 * @author: derrick 手动将用户加入138矩阵
	 * @date: 2017年7月12日
	 * @param: @param int $user_id
	 * @reurn: return_type
	 */
	public function add_in_138($user_id) {
		$this->load->model('m_forced_matrix');
		var_dump($this->m_forced_matrix->save_user_for_138($user_id));
	}
	
	/**
	 * 计算每个进程需要执行的任务数
	 * @author: derrick
	 * @date: 2017年3月28日
	 * @param: @param itn $max_child_process_num 最大子进程数
	 * @param: @param itn $job_nums 总任务数
	 * @reurn: return_type
	 * 		child_process_no 子进程序号
	 * 		begin 子进程任务执行开始编号
	 * 		end 子进程任务执行结束编号
	 */
	private function get_job_nums_by_child_process_num($max_child_process_num, $job_nums) {
		if ($max_child_process_num == 0 || $job_nums == 0) {
			return array('max_child_process_num' => 0, 'per_child_job_nums' => 0, 'division' => 0);
		}
		$division = $job_nums % $max_child_process_num;
		if ($job_nums <= $max_child_process_num) {
			$per_child_job_nums = 1;
			$max_child_process_num = $job_nums;
			$division = 0;
		} else {
			$per_child_job_nums = floor($job_nums / $max_child_process_num);
		}
		$data = array();
		for($i = 0; $i < $max_child_process_num; $i++) {
			if ($i < $division) {
				$begin = $i * ($per_child_job_nums + 1);
				$end = $begin + $per_child_job_nums;
			} else {
				$begin = $i * $per_child_job_nums + $division;
				$end = $begin + $per_child_job_nums - 1;
			}
			$data[] = array('child_process_no' => $i, 'begin' => $begin, 'end' => $end);
		}
		return $data;
	}
}