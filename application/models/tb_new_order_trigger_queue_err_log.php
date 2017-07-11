<?php
/**
 * 个人,团队业绩奖错误日志类
 * @author derrick
 * @date 2017年4月14日
 */
class tb_new_order_trigger_queue_err_log extends MY_Model {

	protected $table = 'new_order_trigger_queue_err_log';
	
    function __construct() {
        parent::__construct();
    }
    
    /**
     * @author: derrick 新增日志
     * @date: 2017年4月14日
     * @param: @param unknown $uid 用户ID
     * @param: @param unknown $oid 订单ID
     * @param: @param unknown $order_amount_usd 订单金额(美分)
     * @param: @param unknown $order_profit_usd 订单利润(美分)
     * @param: @param unknown $order_year_month 订单月份
     * @param: @param string $create_time 创建时间
     * @reurn: return_type
     */
    public function add_log($uid, $oid, $order_amount_usd, $order_profit_usd, $order_year_month, $create_time = '') {
    	if (empty($create_time)) {
    		$create_time = date('Y-m-d H:i:s');
    	}
    	if (is_numeric($uid) && $oid && is_numeric($order_amount_usd) && is_numeric($order_profit_usd) && is_numeric($order_year_month)) {
    		return $this->db->insert($this->table, array(
    			'uid' => $uid,
    			'oid' => $oid,
    			'order_amount_usd' => $order_amount_usd,
    			'order_profit_usd' => $order_profit_usd,
    			'order_year_month' => $order_year_month,
    			'create_time' => $create_time
    		));
    	}
    	return false;
    }
}