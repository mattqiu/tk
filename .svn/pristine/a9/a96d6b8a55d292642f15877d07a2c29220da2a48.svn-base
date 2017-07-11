<?php
/**
 * 会员状态变更记录表model
 */
class tb_users_status_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询会员状态变更记录
     * @return array
     * @author Ckf
     */
    public function selectAll($filter,$perPage = 10) {
        $this->db->from('users_status_log');
        $this->filterForNews($filter);
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->order_by('create_time','desc')->get()->result_array();
        return $list;
    }

    /**
     * 获取分页总数
     */
    function  getExceptionRows($filter){
        $this->db->from('users_status_log');
        $this->filterForNews($filter);
        $row = $this->db->count_all_results();
        return $row;
    }

    public function filterForNews($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v)+86400-1);
                    break;
                case 'uid':
                    if(is_numeric($v)){
                        $this->db->where('uid', $v);
                    }else{
                        $this->db->where('uid', $v);
                    }
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }
	
    /**
     * 添加日志
     * @author: derrick
     * @date: 2017年4月5日
     * @param: @param unknown $uid
     * @param: @param unknown $front_status
     * @param: @param unknown $back_status
     * @param: @param number $type
     * @param: @param string $create_time
     * @reurn: return_type
     */
	public function add_log($uid, $front_status, $back_status, $type = 1, $create_time = '') {
		if (empty($create_time)) {
			$create_time = time();
		}
		$this->db->insert('users_status_log', array(
			'uid' => $uid,
			'front_status' => $front_status,
			'back_status' => $back_status,
			'type' => $type,
			'create_time' => $create_time
		));
	}
}
