<?php
/**
 * @author Terry
 */
class tb_profit_sharing_point_reduce_log extends MY_Model {

    protected $table = "profit_sharing_point_reduce_log";
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户本月已经转出的分红点。
     * @param $uid
     * @return 可转出的分红点
     * @author Terry
     */
    public function getUserTransferedPointCurMonth($uid){

        $monthFirstdayTimestamp = strtotime(date("Y-m-01", time()));
        $res = $this->db->select('sum(point) as curReducePoint')->from('profit_sharing_point_reduce_log')->where('uid',$uid)->where('create_time >',$monthFirstdayTimestamp)->get()->row_object();
        return $res?tps_money_format($res->curReducePoint):0;
    }

	/**
	 * @author: derrick 根据用户ID统计
	 * @date: 2017年6月1日
	 * @param: @param string $count_column 统计字段
	 * @param: @param int $uid 用户ID
	 * @reurn: return_type
	 */
	public function count_by_uid($count_column, $uid) {
		$res = $this->db->select('SUM(`'.$count_column.'`) AS `total`')->get_where($this->table, array('uid' => $uid))->row_array();
		return isset($res['total']) ? $res['total'] : 0;
	}
}
