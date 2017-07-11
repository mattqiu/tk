<?php
/**
 * 用户奖金统计表
 * @author Terry
 */
class tb_user_comm_stat extends MY_Model {
    protected $slave_flag = false;

    function __construct() {
        parent::__construct();
        $db_slave = config_item('db_slave');
        if(!empty($db_slave)) {
            $this->slave_flag = true;
        } else {
            $this->slave_flag = false;
        }
    }

    private function __itemTypeToCommFieldName($item_type){
        switch ($item_type) {
            case 24:
                $f_comm = 'daily_bonus_elite';
                break;
            case 2:
                $f_comm = '138_bonus';
                break;
            case 7:
                $f_comm = 'week_bonus';
                break;
            case 25:
                $f_comm = 'week_share_bonus';
                break;
            case 26:
                $f_comm = 'new_member_bonus'; //m by brady 新会员专属奖
                break;
            case 6:
                $f_comm = 'daily_bonus';// 全球利润日分红
                break;
            default:
                $f_comm = '';
                break;
        }
        return $f_comm;
    }

    /*新增、变更用户奖金统计记录*/
    public function updateUserCommStat($uid,$item_type,$amount){

        $f_comm = $this->__itemTypeToCommFieldName($item_type);
        if($f_comm){
            $this->db->query("insert into user_comm_stat(uid,$f_comm) values($uid,$amount) on DUPLICATE KEY 
UPDATE $f_comm=$f_comm+$amount");
        }
        return true;
    }

    /*查询用户某一项奖金的拿奖金额*/
    public function getCommStatOfUserByItem($uid,$item_type){

        $f_comm = $this->__itemTypeToCommFieldName($item_type);
        $res = $this->db->select($f_comm)->from('user_comm_stat')->where('uid',$uid)->get()->row_array();
        if($res){
            $return = $res[$f_comm];
        }else{
            $return = 0;
        }

        return $return;
    }

    /*查询用户全部奖金的拿奖金额统计（数组）*/
    public function getAllCommStatOfUser($uid){
        $db_slave = config_item('db_slave');
        $redis_key = "AllCommStatOfUser:".$uid;
        $tmp = $this->redis_get($redis_key);
        if($tmp) {
          return unserialize($tmp);
        }

        $res = $this->db_slave->select('daily_bonus_elite,138_bonus,week_bonus,week_share_bonus,month_group_share,new_member_bonus,daily_bonus,month_leader_share_bonus')->from('user_comm_stat')->where('uid',$uid)->get()->row_array();
        $tmp = serialize($res);
        $this->redis_set($redis_key,$tmp,3600);
        return $res;
    }
    
    /**
     * 获取指定字段数据
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param unknown $uid
     * @param: @param string $fields
     * @reurn: return_type
     */
    public function get_user_stat_info($uid,$fields=''){
    	$fields = $fields == '' ? '*' : $fields;
    	$res = $this->db->select($fields)->where('uid',(int)$uid)->get('user_comm_stat');
    	if ($res) {
	    	return $res->row_array();
    	} else {
    		return array();
    	}
    }
}
