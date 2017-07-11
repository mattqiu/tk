<?php
class tb_user_sale_rank_repair_log extends CI_Model {

	private $table_name = 'user_sale_rank_repair_log';
	
	/**
	 * @author: derrick 新增日志
	 * @date: 2017年7月3日
	 * @param: @param int $user_id 用户ID
	 * @param: @param int $before_rank 修复前职称
	 * @param: @param int $after_rank 修复后职称
	 * @reurn: return_type
	 */
	public function add_log($user_id, $before_rank, $after_rank, $repair_time = '') {
		$repair_time = $repair_time ? $repair_time : date('Y-m-d H:i:s');
		return $this->db->insert($this->table_name, array(
			'user_id' => $user_id,
			'before_rank' => $before_rank,
			'after_rank' => $after_rank,
			'repair_time' => $repair_time
		));
	}
	
}