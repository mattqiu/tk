<?php
/**
 * @author Terry
 */
class tb_month_leader_bonus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 检查某用户是否在当月领导分红－高级市场主管奖里面。
     */
    public function checkIfInCurMonthLeader($uid){

        $res = $this->db->from('month_leader_bonus')->select('uid')->where('uid',$uid)->get()->row_array();
        return $res?true:false;
    }
    
    
    /**
     * 当月领导分红- 高级市场主管
     * @param unknown $uid
     * @param unknown $point
     */
    public function addInCurMonthLeader($uid,$point)
    {
        $user_exits = $this->checkIfInCurMonthLeader($uid);
        if(!$user_exits)
        {
            $user_sql = "select DISTINCT id,sale_rank,user_rank from users left join users_store_sale_info_monthly b on users.id=b.uid where users.sale_rank=3 and users.user_rank = 1 and users.id=".$uid;
            $user_query = $this->db->query($user_sql);
            $user_value = $user_query->row_array();
            if(!empty($user_value))
            {
                $add_sql = "INSERT INTO month_leader_bonus SET uid = ".$uid . ", sharing_point=".$point;
                $this->db->query($add_sql);
            }           
        }
    }
    
    
    /**
     * **
     * 补发每月领导分红奖
     * @param 用户id $uid
     * @param 用户分红点 $point
     * @param 发奖类型 $item_type
     * @param 时间 $c_time
     * @param 不在查询范围的用户id $uid_array
     */
//    public function everyMonthLeadAutoAmount($uid,$tabel_name, $item_type, $c_time)
//    {
//       // $month_team_tb_name = "cash_account_log_" . date("Ym", strtotime($c_time));
//        //$month_sql = "SELECT uid, amount, create_time FROM  " . $month_team_tb_name . "  WHERE  item_type=".$item_type." AND DATE_FORMAT(create_time,'%Y-%m-%d')='" . $c_time . "'  group by amount  order by amount asc  limit 0,2";
//        //$query = $this->db->query($month_sql);
//        $this->load->model("tb_cash_account_log_x");
//        $year_month = date("Ym", strtotime($c_time));
//        $table = get_cash_account_log_table($uid,$year_month);
//        $this->tb_cash_account_log_x->set_table($table);
//        $param = [
//            'select'=>"uid, amount, create_time",
//            'where'=>[
//                'item_type'=>$item_type,
//                'DATE_FORMAT(create_time,"%Y-%m-%d")'=>$c_time
//            ],
//            'group'=>'amount',
//            'order'=>"amount asc ",
//            'limit'=>2
//        ];
//        $res = $this->tb_cash_account_log_x->get($param);
//        return $res;
//    }
    
    
    /***
     * 每月团队销售分红补发佣金获取
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
//            $user_sql = "SELECT uid FROM  month_group_share_list WHERE uid >= ((SELECT MAX(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank.")-(SELECT MIN(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank." ) ) * RAND() + (SELECT MIN(uid) FROM month_group_share_list where sale_rank_weight=".$sale_rank." )  LIMIT 1";
//            $user_query = $this->db->query($user_sql);
//            $user_return_value = $user_query->row_array();
//
//            if(!empty($user_return_value))
//            {
//                $sql = "SELECT id,uid,item_type,amount,create_time FROM ".$week_team_tb_name." WHERE uid=".$user_return_value['uid']." AND item_type=".$item_type."  AND  date_format(create_time,'%Y-%m-%d')='".$s_time."'";
//                $query = $this->db->query($sql);
//                $return_value = $query->row_array();
//
//
//
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
//
//    }
    
    /**
     * 获取用户权重信息
     * @param 用户id $uid
     * @param 时间(格式:201702) $m_time
     */
    public function getUserAmountInfo($uid,$m_time)
    {
        $sql = "select a.uid,a.sale_amount as sale_amount_weight,(b.sale_rank+1)*(b.sale_rank+1) as sale_rank_weight,if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4))) as store_rank_weight from
            users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`='" . $m_time . "' and a.uid=" . $uid . " and b.user_rank=1 and b.sale_rank in (2,3,4,5) ;";
        
        $query = $this->db->query($sql);
        $exits_value = $query->row_array();
               
             
        return $exits_value;
    }
    
    /***
     * 每月团队销售分红补发佣金获取
     * @param unknown $s_time
     * @param unknown $item_type     
     * @param unknown $uid
     */
    public function getRessiueWeekTeamAutoAmount_new($s_time,$item_type,$uid)
    {
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_month_leader_bonus_option');
        $this->load->model('m_debug');
        $users_info_data = array();
        $start_time = date('Ym',strtotime($s_time));
        $month_time = date('Ym',(strtotime(date('Ym05',strtotime($s_time)))-30*24*3600));
        
        //获取利润
        $last_month_total = $this->o_company_money_today_total->get_month_profit_pare($month_time);
        
        //每周团队销售组织分红奖的权重统计
       
        $user_weight = $this->o_month_leader_bonus_option->month_weight_total_pre_time($start_time);
       
        //获取发奖比率
        $grant_lv_sql = "SELECT * FROM system_rebate_conf WHERE category_id=1";
        $grant_lv_query = $this->db->query($grant_lv_sql);
        $grant_lv_value = $grant_lv_query->result_array();
        if(!empty($grant_lv_value))
        {
            foreach($grant_lv_value as $grant_lv)
            {
                switch($grant_lv['child_id'])
                {
                    case 1:
                        $global = $grant_lv['rate_a'];
                        $rank_amount_w = $grant_lv['rate_b'];
                        break;
                    case 2:
                        $global = $grant_lv['rate_a'];
                        $store_amount_w = $grant_lv['rate_b'];
                        break;
                    case 3:
                        $global = $grant_lv['rate_a'];
                        $sale_amount_w = $grant_lv['rate_b'];
                        break;
                }
            }
            $this->m_debug->log($uid.':184_tb_month');
            if(0!= $global)
            {
                $amount_weight = round($last_month_total['money'] * $global * $sale_amount_w); //个人销售权重
                $rank_weight = round($last_month_total['money'] * $global * $rank_amount_w); // 职称权重
                $user_store_weight = round($last_month_total['money'] * $global * $store_amount_w); //店铺权重                
                $user_value = $this->getUserAmountInfo($uid,$month_time);                
                if(!empty($user_value))
                {
                    $sale_rank_weights = 0;
                    if($user_value['sale_rank_weight']>=16)
                    {
                        $sale_rank_weights = $user_value['sale_rank_weight'] * 10;
                    }
                    else
                    {
                        $sale_rank_weights = $user_value['sale_rank_weight'];
                    }
                    $user_amount_total = 0;
                    $user_sale_amount = 0;
                    $sale_rank_weight = 0;
                    $store_point_weight = 0;
                    $user_sale_amount = round(($user_value['sale_amount_weight']/$user_weight['sale_amount_weight']) * $amount_weight);
                    $sale_rank_weight = round(($sale_rank_weights/$user_weight['sale_rank_weight']) * $rank_weight);
                    $store_point_weight = round(($user_value['store_rank_weight']/$user_weight['store_rank_weight']) * $user_store_weight);
                    $user_amount_total = round($user_sale_amount + $sale_rank_weight + $store_point_weight);
                    $this->m_debug->log($uid.':212_tb_month');
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
     * 每月领导分红补发佣金获取   2017年4月新机制
     * @param unknown $s_time
     * @param unknown $item_type
     * @param unknown $uid
     */
    public function getRessiueLeaderAutoAmount_new($sale_rank,$user_share_point,$s_time,$uid)
    {
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_month_leader_bonus_option');
    
        $users_info_data = array();        
        $month_time = date('Ym',(strtotime(date('Ym05',strtotime($s_time)))-30*24*3600));
        $b_month_time = date('Ym',strtotime($s_time));
       
        //获取利润
        $last_month_total = $this->o_company_money_today_total->get_month_profit_pare($month_time);        
        //获取分红百分比
        $lv_sql = "SELECT rate_a,rate_b,rate_c FROM system_rebate_conf WHERE category_id = 23 and child_id = ".($sale_rank+1);       
        $lv_query = $this->db->query($lv_sql);
        $lv_value = $lv_query->row_array();
        if(!empty($lv_value) && $lv_value['rate_a'] > 0)
        {
            $lastMonthProfit = $last_month_total['money'] * $lv_value['rate_a'];
            $sharingAmount = tps_money_format($lastMonthProfit);
            if(!$sharingAmount){
                $this->m_log->createCronLog('上月公司利润的3%为0，无法发放每月领导分红奖。');
                return 0;
            }
        
            $sharingAmount1 = round($sharingAmount*$lv_value['rate_b']);//用来均摊的利润
            $sharingAmount2 = $sharingAmount-$sharingAmount1;//用来按照分红点发放的利润
        
            //总分红点
            $share_point_total = $this->o_month_leader_bonus_option->getLeaderSharePoint($sale_rank,$b_month_time);
            
            //参与总分红的人数
            $person_nb_total = $this->o_month_leader_bonus_option->getLeaderSharePointPerTotal($sale_rank,$b_month_time);
            
            //均分利润
            $commission_amount1 = tps_int_format($sharingAmount1/$person_nb_total);
            if($user_share_point > 0){
                $commission_amount2 = tps_int_format($user_share_point/$share_point_total*$sharingAmount2);
            }else{
                $commission_amount2 = 0;
            }
            
            $users_info_data[] = array
            (
                'uid'     => $uid,
                'money'   => $commission_amount1+$commission_amount2
            );                        
        }
        
        return $users_info_data;
    }
    
    
}
