<?php
/**
 * @author Terry
 */
class tb_week_leader_members extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月杰出店铺分红奖里面。
     */
    public function checkIfInCurMonthQualifiedUserList($uid){

        $res = $this->db->from('week_leader_members')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
    
    /**
     * 检查某用户是否在当月杰出店铺分红奖里面。
     */
    public function checkIfInWeekTeamExits($uid){
    
        $res = $this->db->from('week_share_qualified_list')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
 
    /**
     * 添加周领导对等奖
     * @param 用户id $uid
     */
    public function addCurweekQualifiedUserList($uid)
    {
        $customer_exits = $this->checkIfInCurMonthQualifiedUserList($uid);
        if(!$customer_exits)
        {
            $lastYearMonth = date('Ym', strtotime('-1 month'));
            $sql = "select a.id id from users a left join users_store_sale_info_monthly b on a.id=b.uid";
            $sql .= " where a.user_rank=1 and a.sale_rank>=2 and b.year_month=".$lastYearMonth." and b.sale_amount>=10000";
            $sql .= " and uid=".$uid;
            $res = $this->db->force_master()->query($sql)->row_array();
            if(!empty($res['id']))
            {
                $sql = "INSERT INTO  week_leader_members SET uid =".$uid;
                $this->db->query($sql);
            }
        }
    }
    
    /**
     * 每周团队分红奖
     * @param 用户id $uid
     */
    public function addWeekTeamQualified($uid)
    {
        $team_exits = $this->checkIfInWeekTeamExits($uid);
        if(!$team_exits)
        {
            $sql = "insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),
            round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b
            on a.uid=b.id where b.sale_rank in(1,2,3,4,5)  and b.id=".$uid;
            $this->db->query($sql);
        }
    }
    
    /**
     * 检查用户是否已经发放每周团队分红奖
     * @param 用户id $uid
     * @param 时间(时间为每周一的日) $s_time
     */
    public function check_ressiue_week_team_exits($uid,$s_time,$item_type)
    {
        
         //$week_team_tb_name = "cash_account_log_".date("Ym", strtotime($s_time));
         $week_team_tb_name = get_cash_account_log_table($uid,date("Ym", strtotime($s_time)));
         $sql = "SELECT id  FROM ".$week_team_tb_name." WHERE uid=".$uid." AND item_type=".$item_type. " AND  date_format(create_time,'%Y-%m-%d')='".$s_time."' LIMIT 0,1";        
         $query = $this->db->query($sql);
         $return_value = $query->row_array();
         if(!empty($return_value))
         {
             return true;
         }
         else
         {
             return false;
         }
    }
    
    /***
     * 获取最小用户id获取的返利信息
     * @param unknown $uid
     * @param unknown $s_time
     * @return unknown|NULL
     */
    // public function getRessiueWeekTeamQualifiedById($uid,$s_time,$item_type)
    // {
    //     $week_team_tb_name = "cash_account_log_".date("Ym", strtotime($s_time));
    //     $sql = "SELECT id,uid,item_type,amount,create_time FROM ".$week_team_tb_name." WHERE uid=".$uid." AND item_type=".$item_type."  AND  date_format(create_time,'%Y-%m-%d')='".$s_time."'";      
        
    //     $query = $this->db->query($sql);
    //     $return_value = $query->row_array();           
    //     if(!empty($return_value))
    //     {
    //         return $return_value;
    //     }
    //     else
    //     {
    //         return null;
    //     }  
    // }
    
    
    /***
     * 每团队销售分红补发佣金获取
     * @param unknown $s_time
     * @param unknown $item_type
     * @param unknown $sale_rank
     * @param unknown $uid_array
     * @param unknown $count
     */
//    public function getRessiueWeekTeamAutoAmount($s_time,$item_type,$sale_rank,$uid_array, $count)
//    {
//        if($count<1000)
//        {
//            $week_team_tb_name = "cash_account_log_".date("Ym", strtotime($s_time));
//
//            //$user_sql = "SELECT id FROM  users WHERE id >= ((SELECT MAX(id) FROM users where sale_rank=".$sale_rank.")-(SELECT MIN(id) FROM users where sale_rank=".$sale_rank." ) ) * RAND() + (SELECT MIN(id) FROM users where sale_rank=".$sale_rank." )  LIMIT 1";
//            $user_sql = "SELECT uid FROM  week_share_qualified_list WHERE uid >= ((SELECT MAX(uid) FROM week_share_qualified_list where sale_rank_weight=".$sale_rank.")-(SELECT MIN(uid) FROM week_share_qualified_list where sale_rank_weight=".$sale_rank." ) ) * RAND() + (SELECT MIN(uid) FROM week_share_qualified_list where sale_rank_weight=".$sale_rank." )  LIMIT 1";
//            $user_query = $this->db->query($user_sql);
//            $user_return_value = $user_query->row_array();
//
//            if(!empty($user_return_value))
//            {
//                $sql = "SELECT id,uid,item_type,amount,create_time FROM ".$week_team_tb_name." WHERE uid=".$user_return_value['uid']." AND item_type=".$item_type."  AND amount > 0  AND  date_format(create_time,'%Y-%m-%d')='".$s_time."' order by amount asc";
//                $query = $this->db->query($sql);
//                $return_value = $query->row_array();
//                if(!empty($return_value))
//                {
//                    return $return_value;
//                }
//                else
//                {
//                    $n_uid_array = array(
//                        'uid' => $return_value['uid']
//                    );
//                    $uid_array = array_merge($uid_array, array(
//                        $n_uid_array
//                    ));
//                    $count ++;
//                    $this->getRessiueWeekTeamAutoAmount($s_time, $item_type, $sale_rank, $uid_array, $count);
//                }
//            }
//        }
//    }
    
    /**
     * 获取用户权重信息
     * @param 用户id $uid
     * @param 时间(格式:201702) $m_time
     */
    public function getUserAmountInfo($uid,$m_time)
    {
        $exits_value = array();
        if($m_time==date('Ym'))
        {
//             $m_time = date('Ym',(strtotime(date('Ym05',strtotime(date('Ym'))))-30*24*3600));
//             $sql = "select a.uid,a.sale_amount as sale_amount_weight,b.sale_rank*b.sale_rank as sale_rank_weight,if(b.user_rank=3,1,if(b.user_rank=1,3,2)) as store_rank_weight,
//             round(b.profit_sharing_point*100) as share_point_weight from users_store_sale_info_monthly
//             a left join users b on a.uid=b.id where a.`year_month`='".$m_time."' and b.user_rank =1 and a.uid = ".$uid." and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000";
             
            $sql = "select * from week_share_qualified_list where uid=".$uid;
            
            $query = $this->db->query($sql);
            $exits_value = $query->row_array();
        }
        else
        {
            $week_share_tb = "week_share_qualified_list_".$m_time;
            $exits_sql = "select * from ".$week_share_tb." where uid = ".$uid;
            $exits_query = $this->db->query($exits_sql);
            $exits_value = $exits_query->row_array();
            if(empty($exits_value))
            {                 
//                 $sql = "select a.uid,a.sale_amount as sale_amount_weight,b.sale_rank*b.sale_rank as sale_rank_weight,if(b.user_rank=3,1,if(b.user_rank=1,3,2)) as store_rank_weight,
//             round(b.profit_sharing_point*100) as share_point_weight from users_store_sale_info_monthly
//             a left join users b on a.uid=b.id where a.`year_month`='".$m_time."' and b.user_rank =1 and a.uid = ".$uid." and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000";
                $sql = "select * from week_share_qualified_list where uid=".$uid;
                $query = $this->db->query($sql);
                $exits_value = $query->row_array();
            }
        }
        
        return $exits_value;        
    }
    
    /**
     * 获取用户权重信息
     * @param 用户id $uid
     * @param 时间(格式:201702) $m_time
     */
    public function getUserAmountInfo_check($uid,$m_time)
    {       
        $m_time = date('Ym',(strtotime(date('Ym05',strtotime(date('Ym'))))-30*24*3600));
        $sql = "select a.uid,a.sale_amount as sale_amount_weight,b.sale_rank*b.sale_rank as sale_rank_weight,if(b.user_rank=3,1,if(b.user_rank=1,3,2)) as store_rank_weight,
                            round(b.profit_sharing_point*100) as share_point_weight from users_store_sale_info_monthly
                            a left join users b on a.uid=b.id where a.`year_month`='" . $m_time . "' and b.user_rank =1 and a.uid = " . $uid . " and b.sale_rank in(1,2,3,4,5) ";

        $query = $this->db->query($sql);
        $exits_value = $query->row_array();
        return $exits_value;
    }
    
    /***
     * 每团队销售分红补发佣金获取
     * @param unknown $s_time
     * @param unknown $item_type
     * @param unknown $sale_rank
     * @param unknown $uid_array
     * @param unknown $count
     */
    public function getRessiueWeekTeamAutoAmount_new($s_time,$item_type,$uid)
    {
         
        $this->load->model('m_debug');
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_month_leader_bonus_option');
        
        $users_info_data = array();
        $start_time = date('Ymd',strtotime($s_time)-7*24*3600);           
        $end_time = date('Ymd',strtotime($s_time));        
        $last_month_time = date('Ym',strtotime($s_time));     
        
        //获取利润        
        $last_week_total = $this->o_company_money_today_total->get_last_week_profit_pare($start_time,$end_time);
     
        //每周团队销售组织分红奖的权重统计
        $week_weight = $this->o_month_leader_bonus_option->week_weight_total_par_time($last_month_time); //有些问题， 需要做以前的记录
        $this->m_debug->log($uid.':239_tb_week');
        //获取发奖比率
        $grant_lv_sql = "SELECT * FROM system_rebate_conf WHERE category_id=".$item_type." limit 1";
        $grant_lv_query = $this->db->query($grant_lv_sql);
        $grant_lv_value = $grant_lv_query->row_array();
        if(!empty($grant_lv_value))
        {
            if(0 != $grant_lv_value['rate_a'] || 0 != $grant_lv_value['rate_b'] || 0 != $grant_lv_value['rate_c'] || 0 != $grant_lv_value['rate_d'])
            {
               
                $amount_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_b']); //销售权重
                $rank_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_c']); // 职称权重
                $sahre_point_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_d']); //分红点权重
                $store_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_e']); //店铺等级权重
                $this->m_debug->log($uid.':253_tb_week');
                $user_query_value = $this->getUserAmountInfo($uid,$last_month_time);    
                $this->m_debug->log($uid.':255_tb_week');
                if(!empty($user_query_value['uid']))
                {
                    $user_amount_total = 0;
                    $user_sale_amount = 0;
                    $sale_rank_weight = 0;
                    $share_point_weight = 0;
                    $user_sale_amount = round($user_query_value['sale_amount_weight']/$week_weight['sale_amount_weight'] * $amount_weight);
                    $sale_rank_weight = round($user_query_value['sale_rank_weight']/$week_weight['sale_rank_weight'] * $rank_weight);
                    $share_point_weight = round($user_query_value['share_point_weight']/$week_weight['share_point_weight'] * $sahre_point_weight);
                    $store_rank_weight = round($user_query_value['store_rank_weight']/$week_weight['store_rank_weight'] * $store_weight);
                    $user_amount_total = round($user_sale_amount + $sale_rank_weight + $share_point_weight + $store_rank_weight);
                
                    //发奖数据
                    $users_info_data[] = array
                    (
                        'uid' => $uid,
                        'money' => $user_amount_total
                    );
                }
            }
        }
        
        return $users_info_data;
    }
    
    /***
     * 每团队销售分红补发佣金获取
     * @param unknown $s_time
     * @param unknown $item_type
     * @param unknown $sale_rank
     * @param unknown $uid_array
     * @param unknown $count
     */
    public function getRessiueWeekTeamAutoAmount_new_check($s_time,$item_type,$uid)
    {
         
    
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_month_leader_bonus_option');
    
        $users_info_data = array();
        $start_time = date('Ymd',strtotime($s_time)-7*24*3600);
        $end_time = date('Ymd',strtotime($s_time));
        $last_month_time = date('Ym',strtotime($s_time));
    
        //获取利润
        $last_week_total = $this->o_company_money_today_total->get_last_week_profit_pare($start_time,$end_time);
         
        //每周团队销售组织分红奖的权重统计
        $week_weight = $this->o_month_leader_bonus_option->week_weight_total_par_time($last_month_time); //有些问题， 需要做以前的记录
         
        //获取发奖比率
        $grant_lv_sql = "SELECT * FROM system_rebate_conf WHERE category_id=".$item_type." limit 1";
        $grant_lv_query = $this->db->query($grant_lv_sql);
        $grant_lv_value = $grant_lv_query->row_array();
        if(!empty($grant_lv_value))
        {
            if(0 != $grant_lv_value['rate_a'] || 0 != $grant_lv_value['rate_b'] || 0 != $grant_lv_value['rate_c'] || 0 != $grant_lv_value['rate_d'])
            {
                 
                $amount_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_b']); //销售权重
                $rank_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_c']); // 职称权重
                $sahre_point_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_d']); //分红点权重
                $store_weight = round($last_week_total['money'] * $grant_lv_value['rate_a'] * $grant_lv_value['rate_e']); //店铺等级权重
    
                $user_query_value = $this->getUserAmountInfo_check($uid,$last_month_time);
               
                if(!empty($user_query_value['uid']))
                {
                    
                    $user_amount_total = 0;
                    $user_sale_amount = 0;
                    $sale_rank_weight = 0;
                    $share_point_weight = 0;
                    $user_sale_amount = round($user_query_value['sale_amount_weight']/$week_weight['sale_amount_weight'] * $amount_weight);
                    $sale_rank_weight = round($user_query_value['sale_rank_weight']/$week_weight['sale_rank_weight'] * $rank_weight);
                    $share_point_weight = round($user_query_value['share_point_weight']/$week_weight['share_point_weight'] * $sahre_point_weight);
                    $store_rank_weight = round($user_query_value['store_rank_weight']/$week_weight['store_rank_weight'] * $store_weight);
                    $user_amount_total = round($user_sale_amount + $sale_rank_weight + $share_point_weight + $store_rank_weight);
    
                    //发奖数据
                    $users_info_data[] = array
                    (
                        'uid' => $uid,
                        'money' => $user_amount_total
                    );
                }
            }
        }       
        return $users_info_data;
    }
    
}
