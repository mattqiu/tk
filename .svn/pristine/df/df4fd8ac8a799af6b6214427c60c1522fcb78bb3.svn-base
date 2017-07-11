<?php
/**
 * @author Terry
 */
class tb_month_top_leader_bonus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月领导分红－市场总监奖里面。
     */
    public function checkIfInCurMonthTopLeader($uid){

        $res = $this->db->from('month_top_leader_bonus')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
    
    /**
     * 当月领导分红- 市场总监
     * @param unknown $uid
     * @param unknown $point
     */
    public function addInCurMonthTopLeader($uid,$point)
    {
        $user_exits = $this->checkIfInCurMonthTopLeader($uid);
        if(!$user_exits)
        {
            $user_sql = "select DISTINCT id,sale_rank,user_rank from users left join users_store_sale_info_monthly b on users.id=b.uid where users.sale_rank=4 and users.user_rank = 1  and users.id=".$uid;
            $user_query = $this->db->query($user_sql);
            $user_value = $user_query->row_array();
            if(!empty($user_value))
            {
                $add_sql = "INSERT INTO month_top_leader_bonus SET uid = ".$uid . ", sharing_point=".$point;
                $this->db->query($add_sql);
            }            
        }
    }
    
    
    
}
