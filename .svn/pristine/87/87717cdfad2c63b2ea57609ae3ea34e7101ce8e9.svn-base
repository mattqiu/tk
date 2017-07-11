<?php
/**
 * @author Terry
 */
class tb_month_leader_bonus_lv5 extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月领导分红－销售副总裁奖里面。
     */
    public function checkIfInCurMonthLeaderLv5($uid){

        $res = $this->db->from('month_leader_bonus_lv5')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
    
    /**
     * 当月领导分红- 销售副总裁
     * @param unknown $uid
     * @param unknown $point
     */
    public function addInCurMonthLeaderLv5($uid,$point)
    {
        $user_exits = $this->checkIfInCurMonthLeaderLv5($uid);
        if(!$user_exits)
        {
            $user_sql = "select DISTINCT id,sale_rank,user_rank from users left join users_store_sale_info_monthly b on users.id=b.uid where users.sale_rank=5 and users.user_rank = 1 and users.id=".$uid;
            $user_query = $this->db->query($user_sql);
            $user_value = $user_query->row_array();
            if(!empty($user_value))
            {
                $add_sql = "INSERT INTO month_leader_bonus_lv5 SET uid = ".$uid . ", sharing_point=".$point;
                $this->db->query($add_sql);
            }            
        }
    }
    
}
