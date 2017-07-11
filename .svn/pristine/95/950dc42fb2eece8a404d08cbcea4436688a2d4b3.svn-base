<?php
/**
 * @author Terry
 */
class tb_users_sharing_point_reward extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户未过期的奖励分红点
     * @param $uid
     * @return 奖励分红点
     * @author Terry
     */
    public function getUserRewardSharingPoint($uid){

        $res = $this->db->select('sum(point) sum_point')->from('users_sharing_point_reward')->where('uid',$uid)->where('end_time >',date('Y-m-d'))->get()->row_array();
        return $res?$res['sum_point']:0;
    }
    /**
     * @author brady
     * @desc 批量获取用户id的奖励分红点
     */
    public function getUserRewardSharingPointBatch($uids)
    {
        $res = $this->db->select('uid,sum(point) sum_point')
            ->from('users_sharing_point_reward')->
            where_in('uid',$uids)
            ->where('end_time >',date('Y-m-d'))
            ->group_by('uid')
            ->order_by('id asc')
            ->get()
            ->result_array();
        return $res;
    }

    /**
     * 统计一批用户的奖励分红点总额
     * @param $uids var 1380100223,1380100456,1380100706
     * @author Terry
     */
    public function sumRewardSharPointsOfUids($uids){

        $res = $this->db->query("select sum(point) total_point from users_sharing_point_reward where uid in (".$uids.") and end_time>'".date('Y-m-d')."'")->row_object()->total_point;
        return tps_money_format($res);
    }
    
    /**
     * 根据用户ID获取记录
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param unknown $uid
     * @reurn: return_type
     */
    public function find_by_uid($uid) {
    	return $this->db->select('id')->from('users_sharing_point_reward')->where('uid', $uid)->get()->row_array();
    }

    /**
     * 新增用户奖励分红点
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param unknown $uid 用户ID
     * @param: @param unknown $point 分红点
     * @param: @param unknown $end_time $截止日期
     * @reurn: return_type
     */
	public function add_users_sharing_point_reward($uid, $point, $end_time) {
		return $this->db->insert('users_sharing_point_reward', array(
			'uid' => $uid,				
			'point' => $point,
			'end_time' => $end_time		
		));
	}
}
