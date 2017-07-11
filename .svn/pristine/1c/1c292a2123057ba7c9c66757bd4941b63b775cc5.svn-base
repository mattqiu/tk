<?php
/**
 * @author soly
 */
class tb_trade_order_exchange_log extends MY_Model {
	
	protected $table_name = 'my_order_exchange_log';
	protected $table      = 'my_order_exchange_log';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_trade_orders_exchange_log($filter, $perPage = 10) {
		$this->db->from($this->table_name);
        $this->filterForOrderLog($filter);

        return $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	
	//获取订单流水记录行数
    public function get_trade_orders_log_rows($filter) {
        $this->db->from($this->table_name);
        $this->filterForOrderLog($filter);
        return $this->db->count_all_results();
    }
	
	//过滤时间
    public function filterForOrderLog($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }
}
