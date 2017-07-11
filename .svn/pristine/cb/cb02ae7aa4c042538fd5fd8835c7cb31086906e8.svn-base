<?php
/**
 * @author Terry
 */
class tb_users_profit_sharing_point_last_month extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户可转出的分红点。
     * @param $uid
     * @return 可转出的分红点
     * @author Terry
     */
    public function getTransferableSharingpoint($uid){

        $res = $this->db->select('profit_sharing_point')->from('users_profit_sharing_point_last_month')->where('uid',$uid)->get()->row_array();
        return $res?tps_money_format($res['profit_sharing_point'] / 100 * 0.3):0.00;
    }

    /**
     * 清空表
     */
    public function clearUserPointStat(){

        $this->db->query("TRUNCATE table users_profit_sharing_point_last_month");
    }

    /**
     * 从user表导数据到用户上月分红点统计表。
     */
    public function importDataFromUserTB(){

        $this->db->query("insert into users_profit_sharing_point_last_month(uid,profit_sharing_point) select id,round(profit_sharing_point*100) from users where profit_sharing_point!=0");
    }
}
