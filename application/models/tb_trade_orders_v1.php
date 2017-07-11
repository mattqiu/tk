<?php
/**
 * 新订单表实体类
 * @author derrick
 * @date 2017年5月4日
 */
class tb_trade_orders_v1 extends CI_Model {
	
	private $table_prefix = 'trade_orders';
	
	/**
	 * 获取表名
	 * @author: derrick
	 * @date: 2017年5月4日
	 * @param: @param string $order_id_or_date 订单ID或者日期(如果为日期,则日期格式为: 1705, 表示17年5月)
	 * @reurn: return_type
	 */
	public function get_table_name($order_id_or_date = '') {
		$datetime = '_'.date('Ym');
		if ($order_id_or_date) {
			if (preg_match("/^\d{4}$/", $order_id_or_date)) {
				$datetime = '_'.$order_id_or_date;
			} elseif (preg_match("/^[A-Z]{1}20/", $order_id_or_date)) {
				//单字母开头订单
				$datetime = substr($order_id_or_date, 3, 4);
			} elseif (preg_match("/^[A-Z]{2}20/", $order_id_or_date)) {
				//双字母开头订单
				$datetime = substr($order_id_or_date, 4, 4);
			} elseif (preg_match("/^[A-Z]{2}/", $order_id_or_date)) {
				//双字母开头订单, 2位数年订单
				$datetime = substr($order_id_or_date, 2, 4);
			} else if (preg_match("/^[A-Z]{1}/", $order_id_or_date)) {
				//单字母开头订单, 2位数年订单
				$datetime = substr($order_id_or_date, 1, 4);
			} else if (preg_match("/^\d+$/", $order_id_or_date)) {
				//纯数字订单号, 遗留订单
				$datetime = '';
			}
			$datetime = $datetime < 1705 ? '' : '_'.$datetime;
		}
		return $this->table_prefix.$datetime;
	}

	/**
	 * 根据订单号查询订单
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param string $order_id 订单ID
	 * @reurn: return_type
	 */
	public function find_by_order_id($order_id) {
		$res = $this->db->from($this->get_table_name($order_id))->where('order_id', $order_id)->get();
		if ($res) {
			return $res->row_array();
		}
		return [];
	}
	
	/**
	 * 根据买家ID查询
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param int $user_id 用户ID
	 * @param: @param string $date 查询日期, 如果日期为空,则默认查询当月用户订单
	 * @reurn: return_type
	 */
	public function find_by_customer_id($user_id, $date = '') {
		$res = $this->db->from($this->get_table_name($date))->where('customer_id', $user_id)->get();
		if ($res) {
			return $res->row_array();
		}
		return [];
	}
	
	/**
	 * 根据店铺ID查询
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param int $user_id 用户ID
	 * @param: @param string $date 查询日期, 如果日期为空,则默认查询当月用户订单
	 * @reurn: return_type
	 */
	public function find_by_shopkeeper_id($user_id, $date = '') {
		$res = $this->db->from($this->get_table_name($date))->where('shopkeeper_id', $user_id)->get();
		if ($res) {
			return $res->row_array();
		}
		return [];
	}

	/**
	 * 生成订单ID
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param string $prefix 订单前缀
	 * @reurn: return_type
	 */
	public function generate_order_id($prefix) {
		$this->load->library('RedisCache','', 'redis');
		$length = 19 - 10 - strlen($prefix);
		$this->redis->connect(true);
		$incr = $this->redis->incr('order_count_'.date('ymdHis'), 2);
		if (is_null($incr)) {
			$max = '';
			for ($i = 1; $i <= $length; $i++) $max .= 9;
			$incr = rand(0, $max);
		}
		return strtoupper($prefix).date('ymHis').sprintf('%0'.$length.'s', $incr);
	}

	/**
	 * 根据时间获取满足发个人奖资格的订单
	 * @author: derrick
	 * @date: 2017年5月6日
	 * @param: @param unknown $b	egin 起始时间
	 * @param: @param unknown $end 结束时间
	 * @param: @param string $select_fields 查询字段
	 * @reurn: return_type
	 */
	public function get_personal_prize_order_between_time($begin, $end, $select_fields = '*') {
		return $this->db->select($select_fields)->where('pay_time BETWEEN "'.$begin.'" AND "'.$end.'"')->where_in('status', array(1,3,4,5,6))->where_in('order_prop', array(0,1))->where('is_doba_order !=', 1)->where('order_type', 4)->where('shopkeeper_id !=', 0)->get($this->get_table_name(date('ym', strtotime($begin))))->result_array();
	}

}