<?php
/**
 * 个人,团队业绩奖手动发奖日志类
 * @author derrick
 * @date 2017年4月14日
 */
class tb_new_order_trigger_queue_admin_log extends MY_Model {

	protected $table = 'new_order_trigger_queue_admin_log';
	
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 新增日志
     * @author: derrick
     * @date: 2017年4月14日
     * @param: @param unknown $uid 用户ID
     * @param: @param unknown $oid 订单ID
     * @param: @param unknown $order_amount_usd 订单金额(美分)
     * @param: @param unknown $order_profit_usd 订单利润(美分)
     * @param: @param unknown $order_year_month 订单月份
     * @param: @param string $create_time 创建时间
     * @reurn: return_type
     */
    public function add_log($admin_id, $oid, $create_time = '') {
    	if (empty($create_time)) {
    		$create_time = date('Y-m-d H:i:s');
    	}
    	if (is_numeric($admin_id) && $oid) {
    		return $this->db->insert($this->table, array(
    			'admin_id' => $admin_id,
    			'oid' => $oid,
    			'create_time' => $create_time
    		));
    	}
    	return false;
    }

    /**
     * 根据订单ID查询
     * @author: derrick
     * @date: 2017年4月14日
     * @param: @param String $oid 订单ID
     * @reurn: return_type
     */
	public function find_by_order_id($oid) {
		$res = $this->db->where('oid', $oid)->get($this->table);
		if ($res) {
			return $res->row_array();
		}
		return array();
	}
}