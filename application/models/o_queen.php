<?php
/**
 * redis内存队列类
 * @author derrick
 * @date 2017年3月22日
 */
class o_queen extends MY_Model {

	/**
	 * 推送doba订单队列
	 * 队列数据格式为:
	 * array('trade_orders_id' => '', 'trade_user_address_id' => '')
	 */
	CONST QUEEN_FOR_PUSH_DOBA = 'TASK_QUEEN_FOR_PUSH_DOBA_BY_CREATE_ORDER';
	
	/**
	 * 推送erp订单
	 * 队列数据格式为:
	 * array('trade_orders_id' => '', 'trade_orders_attach_id' => '', 'language_id' => '')
	 */
	CONST QUEEN_FOR_PUSH_ERP = 'TASK_QUEEN_FOR_PUSH_ERP_BY_CRAETE_ORDER';
	
	/**
	 * 个人/团队发奖逻辑订单队列
	 * 队列数据格式为:
	 * array('oid' => $oid, 'uid' => $uid, 'order_amount_usd' => $order_amount_usd, 'order_profit_usd' => $order_profit_usd, 'order_year_month' => $order_year_month)
	 */
	CONST QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER = 'TASK_QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDERSS';
	
	/**
	 * 个人/团队发奖逻辑订单队列错误日志(该队列废弃)
	 * 队列数据格式为: String or Array
	 */
	CONST QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_LOG = 'TASK_QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_LOG';
	
	/**
	 * 个人/团队发奖逻辑订单队列执行日志记录(该队列废弃)
	 * 队列数据格式为:
	 * array('oid' => $oid, 'uid' => $uid, 'order_amount_usd' => $order_amount_usd, 'order_profit_usd' => $order_profit_usd, 'order_year_month' => $order_year_month, 'sysdate' => $sysdate, 'result' => $result)
	 */
	CONST QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_HISTORY = 'TASK_QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_HISTORY';
	
	/**
	 * 修复用户职称队列
	 * 队列数据格式为: uid
	 */
	CONST QUEEN_USER_RANK_TITLE = 'TASK_QUEEN_FOR_USER_RANK_TITLES';
	
	/**
	 * 修复用户职称变动时间队列
	 * 队列数据格式为: uid
	 */
	CONST QUEEN_USER_SALE_RANK_UP_TIME = 'TASK_QUEEN_FOR_USER_SALE_RANK_UP_TIME';
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 *  队尾入队
	 * @author: derrick
	 * @date: 2017年3月22日
	 * @param: @param String $queen_name 队列名称
	 * @param: @param mixed $data 入队元素
	 * @reurn: return_type
	 */
	public function en_queen($queen_name, $data) {
		$data = $this->_check_data($data);
		if ($data) {
			return $this->redis_rPush_one($queen_name, $data);
		}
	}
	
	/**
	 * @author: derrick 加入队列(不重复数据唯一队列 set队列)
	 * @date: 2017年6月29日
	 * @param: 
	 * @reurn: return_type
	 */
	public function en_unique_queue($queue_name, $queue_data) {
		$data = $this->_check_data($queue_data);
		if ($data) {
			return $this->redis_sadd($queue_name, $queue_data);
		}
	}
	
	/**
	 * @author: derrick 出队 (set 队列)
	 * @date: 2017年6月29日
	 * @param: @param String $queue_name
	 * @reurn: return_type
	 */
	public function de_unique_queue($queue_name) {
		return $this->redis_spop($queue_name);
	
	}
	/**
	 * 队头入队
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: @param String $queen_name 队列名称
	 * @param: @param mixed $data 入队元素
	 * @reurn: return_type
	 */
	public function en_queen_from_top($queen_name, $data) {
		$data = $this->_check_data($data);
		if ($data) {
			return $this->redis_lPush_one($queen_name, $data);
		}
	}
	
	/**
	 * 出队
	 * @author: derrick
	 * @date: 2017年3月22日
	 * @param: @param unknown $queen_name
	 * @reurn: return_type
	 */
	public function de_queen($queen_name) {
		return $this->get_value($this->redis_lPop($queen_name));
	}
	
	/**
	 * 设置队列元素数据
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: @param unknown $queen_name 队列名称
	 * @param: @param unknown $queen_index 所处队列下标
	 * @param: @param unknown $data 队列数据
	 * @reurn: return_type
	 */
	public function set_queen_data($queen_name, $queen_index, $data) {
		if (is_array($data)) {
			$data = $this->format->factory($data)->to_serialized();
		}
		if ($data) {
			return $this->redis_lSet($queen_name, $queen_index, $data);
		}
	}
	
	/**
	 * 阻塞式出队, 当队列为空时, 会阻塞出队操作, 直至队列不为空
	 * @author: derrick
	 * @date: 2017年3月22日
	 * @param: @param String queen_name 队列名称
	 * @param: @param int $timeout 超时时间
	 * @reurn: return_type
	 */
	public function block_de_queen($queen_name, $timeout = 0) {
		$data = $this->redis_blPop($queen_name, $timeout);
		if (isset($data[1]) && $data[1]) {
			$data[1] = $this->get_value($data[1]);
			return $data;
		} else {
			return array();
		}
	}

	/**
	 * 获取队列长度
	 * @author: derrick
	 * @date: 2017年3月28日
	 * @param: @param unknown $queen_name
	 * @param: @return number
	 * @reurn: number
	 */
	public function get_queen_length($queen_name) {
		return $this->redis_lLen($queen_name);
	}
	
	/**
	 * @author: derrick 分页获取队列数据
	 * @date: 2017年6月2日
	 * @param: @param String $queen_name 队列名称
	 * @param: @param number $page 页数
	 * @param: @param number $size 每页记录数
	 * @reurn: json data list
	 */
	public function get_list_by_page($queen_name, $page = 1, $size = 100) {
		$page = max($page, 1);
		$size = max($size, 1);
		
		$start = ($page - 1) * $size;
		$end = max($page * $size - 1, 0);
		return $this->redis_lGetRange($queen_name, $start, $end);
	}
	
	/**
	 * @author: derrick 从队头开始删除
	 * @date: 2017年6月2日
	 * @param: @param String $queen_name 队列名称
	 * @param: @param int $size 删除数量
	 * @reurn: return_type
	 */
	public function remove_from_top($queen_name, $size = 1) {
		$this->redis_ltrim($queen_name, $size, $this->get_queen_length($queen_name));
		return true;
	}
	
	/**
	 * 校验订单是否已经在执行日志记录队列中存在
	 * @author: derrick
	 * @date: 2017年4月17日
	 * @param: @param String $order_id 订单ID
	 * @reurn: return_type
	 */
	public function check_order_exists_in_history_queen($order_id) {
		$page_size = 10000;
		$queen_name = self::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_HISTORY;
		$queen_length = $this->get_queen_length($queen_name);
		for ($i = 1; $i <= ceil($queen_length / $page_size); $i++) {
			$end = $i * $page_size - 1;
			$end = $end < 1 ? 0 : $end;
			$datas = $this->redis_lGetRange($queen_name, ($i - 1) * $page_size, $end);
			foreach ($datas as $d) {
				$d = $this->get_value($d);
				if (isset($d['oid']) && $d['oid'] == $order_id) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * 校验写入redis数据, 如果是array, 则先json_encode一下
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: @param unknown $data
	 * @reurn: return_type
	 */
	private function _check_data($data) {
		return gettype($data) == 'array' && is_array($data) && $data ? json_encode($data) : $data;
	}
	
	/**
	 * 校验从redis中取出的数据, 如果是json_encode数据, 先decode
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: @param unknown $data
	 * @reurn: return_type
	 */
	public function get_value($data) {
		$result = json_decode($data, true);
		return is_null($result) ? $data : $result;
	}
}