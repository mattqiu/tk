<?php
/**
 * @author Terry
 */
class tb_month_group_share_list extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在每月团队组织分红奖
     */
    public function checkIfInCurMonthGroupShareList($uid){

        $res = $this->db->from('month_group_share_list')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
    
    
    /**
     * 每月团队组织分红奖
     * @param unknown $uid
     */
    public function addInCurMonthGroupShareList($uid)
    {
        $user_exits = $this->checkIfInCurMonthGroupShareList($uid);
        if(!$user_exits)
        {
            $add_sql = "insert ignore into month_group_share_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight) select a.uid,
                a.sale_amount,(b.sale_rank+1)*(b.sale_rank+1),if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4) ) ) from 
                users_store_sale_info_monthly a left join users b 
                on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.sale_rank in (2,3,4,5) and a.sale_amount>=10000  and b.id=".$uid;
            $this->db->query($add_sql);
        }
    }
    
    /**
     * 获取用户当月在每周团队销售分红队列表中的信息
     * @param 用户id $uid
     */
    public function get_user_month_bonus_info($uid)
    {
        $sql = "select * from month_group_share_list where uid = ".$uid;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    
}
