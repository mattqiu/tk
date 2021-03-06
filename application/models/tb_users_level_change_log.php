<?php
/**
 * 用户等级变动表
 * @author Terry
 */
class tb_users_level_change_log extends MY_Model {

    protected $table = "users_level_change_log";
    protected $table_name = "users_level_change_log";
    function __construct() {
        parent::__construct();
    }

    /*获取用户过去升级中停留的最高等级*/
    public function getMaxLevelOfLast($uid){

        $oldLevels = array();
        $res = $this->db->get_where($this->table, array('uid' => $uid))->result_array();
//         $res = $this->db->query("select old_level from users_level_change_log where uid=".$uid)->result_array();
        foreach ($res as $value) {
            
            $oldLevels[] = $value['old_level'];
        }

        if(in_array(1, $oldLevels)){
            return 1;
        }
        if(in_array(2, $oldLevels)){
            return 2;
        }
        if(in_array(3, $oldLevels)){
            return 3;
        }
        if(in_array(5, $oldLevels)){
            return 5;
        }
        return 4;
    }

    /*
     * 检测用户是否有过升级记录
     */
    public function checkExists($uid)
    {
        $res = $this->db->from($this->table_name)
            ->where(array('uid'=>$uid))->get()->row_array();
        if (!$res ||empty($res)) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 根据用户ID, 等级类型查询
     * @author: derrick
     * @date: 2017年4月5日
     * @param: @param unknown $uid
     * @param: @param unknown $level_type
     * @reurn: return_type
     */
    public function find_by_uid_level($uid, $level_type) {
    	$res = $this->db->from($this->table_name)->where(array('uid'=>$uid, 'level_type' => $level_type))->order_by('id', 'ASC')->get();
    	if ($res) {
    		return $res->row_array();
    	} else {
    		return array();
    	}
    }
    
    /**
     * @author: derrick 校验指定用户在某个时间段内是否有状态变动
     * @date: 2017年5月18日
     * @param: @param array $uid
     * @param: @param datetime $start
     * @param: @param datetime $end
     * @param: @param unknown $level_type
     * @reurn: return_type
     */
    public function check_user_is_change_in_time(Array $uid, $start, $end, $level_type) {
    	if (empty($uid)) {
    		return array();
    	}
    	return $this->db->where_in('uid', $uid)->where('create_time >=', $start)->where('create_time <=', $end)->order_by('create_time', 'desc')->get($this->table_name)->result_array();
    }

    /**
     * @author: derrick 获取用户最后一次等级变动
     * @date: 2017年6月29日
     * @param: @param int $user_id 用户ID
     * @reurn: return_type
     */
    public function find_user_last_change($user_id,$type = 2) {
    	return $this->db->where('uid', $user_id)->where('level_type', $type)->order_by('create_time', 'DESC')->get($this->table_name)->row_array();
    }

    public function get_user($uid){
        return $this->db->from($this->table_name)->where("uid",$uid)->where("old_level",4)->where("level_type",2)->group_by("uid")->get()->row_array();
    }

    /**
     * @author: derrick 获取指定用户升级记录, 例如: 获取第三个付费用户记录, find_last_no_users_up_time(array(1,2,3,4,5), 3); 则查询出1,2,3,4,5用户中第三个升级的记录
     * @date: 2017年7月3日
     * @param: @param array $user_ids
     * @reurn: return_type
     */
	public function find_last_no_users_up_time(Array $user_ids, $no = 3) {
		if (empty($user_ids)) {
			return array();
		}
                foreach($user_ids as $uid){//只查询直属下线
                    $new_user_ids[] = $uid["id"];
                }
		$res = $this->db->where_in('uid', $new_user_ids)->where('level_type', 2)->order_by("create_time", "ASC")->limit($no)->get($this->table_name)->result_array();
		while ($no >= 0) {
			$no--;
			if (isset($res[$no])) {
				return $res[$no];
			}
		}
		return array();
	}
}