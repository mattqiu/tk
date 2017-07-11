<?php
/**
 * @author jason
 */
class tb_profit_sharing_point_add_log extends MY_Model
{
    protected $table = "profit_sharing_point_add_log";
    function __construct() {
        parent::__construct();
    }


    /* 分红明细 */
    public function get_profit_sharing_point_logs($uid,$time){

        $map = array(
            1=>0,       //销售佣金自动转分红
            2=>0,       //见点佣金自动转分红
            3=>0,       //分红自动转分红
            4=>0,       //现金池自动转分红
            5=>0        //分红点转现金池
        );

        $data = array();

        /* 前四项分红 */
        $res = $this->db->query("select add_source,money,point,FROM_UNIXTIME(create_time,'%Y-%m-%d') as new_time from profit_sharing_point_add_log WHERE uid = $uid and FROM_UNIXTIME(create_time,'%Y-%m-%d') LIKE '$time%' order by create_time desc")->result_array();
        $point_to_cash = $this->db->query("select money,point,FROM_UNIXTIME(create_time,'%Y-%m-%d') as new_time from profit_sharing_point_reduce_log WHERE uid = $uid and FROM_UNIXTIME(create_time,'%Y-%m-%d') LIKE '$time%' order by create_time desc")->result_array();
        foreach($res as $item){
            if(!isset($data[$item['new_time']])){
                $data[$item['new_time']] = $map;
                $data[$item['new_time']][$item['add_source']] += $item['point'];
            } else{
                $data[$item['new_time']][$item['add_source']] += $item['point'];
            }
        }

        /* 第五项分红 */
        foreach($point_to_cash as $item){
            $data[$item['new_time']][5] += $item['point'];
        }

        //剔除为0的数据
        foreach($data as $k =>$v){
            foreach($data[$k] as $k1 => $v2)
            {
                if($data[$k][$k1] == 0){
                    unset($data[$k][$k1]);
                }
            }
        }

        $new_data = array();

        //更换成mobile数组格式
        foreach($data as $k =>$v){

            $array = array('time'=>'', 'list'=>array());
            $array['time'] = $k;

            foreach($v as $k1 =>$v1){
                $new_array = array();
                $new_array['type'] = $k1;
                $new_array['point'] = $v1;

                $array['list'][] = $new_array;
            }

            $new_data[] = $array;
        }

        return $new_data;
    }

    /**
     * 新增分红日志
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param unknown $commission_id 关联佣金ID
     * @param: @param unknown $uid 用户id 
     * @param: @param unknown $add_source 分红点的来源1=》销售佣金自动转，2=》见点佣金自动转，3=》分红自动转，4=>手动	
     * @param: @param unknown $money 要转化为分红点金钱的数额
     * @param: @param unknown $point 转化的分红点数
     * @param: @param unknown $create_time 转化的时间戳
     * @reurn: return_type
     */
	public function add_sharing_point_log($commission_id, $uid, $add_source, $money, $point, $create_time) {
		if (empty($create_time)) {
			$create_time = time();
		}
		return $this->db->insert('profit_sharing_point_add_log', array(
			'commission_id' => $commission_id,
			'uid' => $uid,
			'add_source' => $add_source,
			'money' => $money,
			'point' => $point,
			'create_time' => $create_time,
		));
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
