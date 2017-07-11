<?php
/**
 * 发奖订单执行日志记录表
 */
class tb_order_prize_queue_log extends CI_Model {

	public $table_name = 'order_prize_queue_log';
	
    function __construct() {
        parent::__construct();
    }

    /**
     * 批量新增日志
     * @author: derrick
     * @date: 2017年5月9日
     * @param: @param unknown $datas
     * @reurn: return_type
     */
    public function batch_save($datas) {
    	return $this->db->insert_batch($this->table_name, $datas);
    }
    
    /**
     * @author: derrick 批量ignore写入日志
     * @date: 2017年6月2日
     * @param: @param unknown $data
     * @param: @param boolean $ignore
     * @param: @return boolean
     * @reurn: boolean
     */
    public function batch_save_ignore($data = [], $ignore = true){
    	if (empty($data)) {
    		return true;
    	}
    	$CI = &get_instance();
    	$sql = '';
		$rows = [];
		
    	foreach ($data as $row) {
    		$insert_string = $CI->db->insert_string($this->table_name, $row);
    		if(empty($rows) && $sql ==''){
    			$sql = substr($insert_string,0,stripos($insert_string,'VALUES'));
    		}
    		$rows[] = trim(substr($insert_string,stripos($insert_string,'VALUES')+6));
    	}
    
    	$sql.=' VALUES '.implode(',',$rows);
    
    	if ($ignore) $sql = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
    	
    	return $this->db->query($sql);
    }
    
    /**
     * @author: derrick 新增记录
     * @date: 2017年5月10日
     * @param: @param Array $data
     * @reurn: return_type
     */
    public function add($data) {
    	return $this->db->insert($this->table_name, $data);
    }
    
    /**
     * @author: derrick 根据订单ID更新执行结果
     * @date: 2017年5月9日
     * @param: @param string $order_id
     * @param: @param Array $update_data
     * @reurn: return_type
     */
    public function update_by_order_id($order_id, $update_data, $err_count_auto_increment = false) {
    	$res = $this->db->where('order_id',$order_id);
    	if ($err_count_auto_increment) {
    		$res = $res->set('err_count', '`err_count` + 1', false);
    	}
    	return $res->update($this->table_name, $update_data);
    }

    /**
     * @author: derrick 根据订单号删除记录
     * @date: 2017年6月6日
     * @param: @param String $order_id
     * @reurn: return_type
     */
	public function delete_by_order_id($order_id) {
		return $this->db->delete($this->table_name, array('order_id' => $order_id));
	}

	/**
	 * @author: derrick 根据执行结果和时间查询记录
	 * @date: 2017年6月6日
	 * @param: @param Array $exec_result 需要过滤的数组
	 * @param: @param unknown $begin_time 起始时间
	 * @param: @param unknown $end_time 结束时间
	 * @reurn: return_type
	 */
	public function find_by_reverse_result_and_time($exec_result, $begin_time, $end_time) {
		return $this->db->where('exec_time >=', $begin_time)->where('exec_time <=', $end_time)->where_not_in('exec_result', $exec_result)->get($this->table_name)->result_array();
	}
	
	/**
	 * @author: derrick 根据主键批量删除记录
	 * @date: 2017年6月6日
	 * @param: @param array or int $pks
	 * @reurn: return_type
	 */
	public function delete_by_pks($pks) {
		if (empty($pks)) {
			return true;
		}
		$pks = is_array($pks) ? $pks : array($pks);
		return $this->db->where_in('log_id', $pks)->delete($this->table_name);
	}
}