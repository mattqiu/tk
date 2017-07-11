<?php
/**
 * 每月领导分红奖  分红用户队列类
 * @author john
 */
class o_month_leader_bonus_option extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    private $page_nbs = 1000;
    
    /***
     * 每月领导分红奖  判断用户是否满足发奖条件，满足的话， 将其添加到队列中
     */
    public function new_month_leader_bonus()
    {
        $this->load->model('m_log');
        $s_time = date('Ym', strtotime('last month')); //上个月时间
        if($s_time==date('Ym'))
        {
            $s_time = date('Y02', time()); //上个月时间
        }
        
        $now_time = date("Y-m-d"); //当前时间
        $sale_time = date('Y-m-01')." 00:00:00";
        
        //创建每月团队组织分红队列日志表
        $create_month_leader_tb_log = "month_leader_bonus_".$s_time;
        $create_month_leader_sql = "create table ".$create_month_leader_tb_log." select * from month_leader_bonus";
        $this->db->query($create_month_leader_sql);
        
        $create_month_leader_top_tb_log = "month_top_leader_bonus_".$s_time;
        $create_month_leader_top_sql = "create table ".$create_month_leader_top_tb_log." select * from month_top_leader_bonus";
        $this->db->query($create_month_leader_top_sql);
        
        $create_month_leader_lv5_tb_log = "month_leader_bonus_lv5_".$s_time;
        $create_month_leader_lv5_sql = "create table ".$create_month_leader_lv5_tb_log." select * from month_leader_bonus_lv5";
        $this->db->query($create_month_leader_lv5_sql);
        
        //清空队列
        $clear_queue_list_sql_led = "truncate month_leader_bonus";
        $this->db->query($clear_queue_list_sql_led);
        //清空队列
        $clear_queue_list_sql_top = "truncate month_top_leader_bonus";
        $this->db->query($clear_queue_list_sql_top);
        //清空队列
        $clear_queue_list_sql_lv5 = "truncate month_leader_bonus_lv5";
        $this->db->query($clear_queue_list_sql_lv5);
        
        $total_sql = "select count(*) as total  from users where (sale_rank BETWEEN 3 and 5) and user_rank = 1";
        $total_query = $this->db->query($total_sql);
        $total_value = $total_query->row_array();        
        if($total_value['total']!=0)
        {
            $this->db->trans_start();
            
            //获取满足提交的发奖用户
            $user_sql = "SELECT id,sale_rank,profit_sharing_point FROM users where sale_rank BETWEEN 3 and 5 and user_rank = 1 and sale_rank_up_time < '".$sale_time."'";
            $user_query_all = $this->db->query($user_sql);
            $user_value_all = $user_query_all->result_array();
            
            if(!empty($user_value_all))
            {
                foreach($user_value_all as $sult)
                {
                    $user_point = 0;
                    
                    $check_sql = "select uid from users_level_change_log where uid = ".$sult['id']." and new_level=1 and level_type =2 and create_time > '".$sale_time."'";
                    $check_query = $this->db->query($check_sql)->row_array();
                    if(!empty($check_query))
                    {
                        //升级时间大于每月1号 时，此用户不满足队列
                        continue;
                    }                    
                    
                    $user_monthly_sql = "select uid from users_store_sale_info_monthly a where a.uid = ".$sult['id']."  and a.year_month = '".$s_time."'";
                    switch($sult['sale_rank'])
                    {
                        case 3:
                            //高级市场主管
                            $user_monthly_sql .= " and sale_amount >= 10000";
                            break;
                        case 4:
                            //市场总监
                            $user_monthly_sql .= " and sale_amount >= 20000";
                            break;
                        case 5:
                            //副总裁
                            $user_monthly_sql .= " and sale_amount >= 30000";
                            break;                            
                    }
                    
                    $user_monthly_query = $this->db->query($user_monthly_sql);
                    $user_monthly_value = $user_monthly_query->row_array();
                    if(!empty($user_monthly_value))
                    {
                        $monthly_sql = "select sum(point) as points from users_sharing_point_reward where end_time >= '".$now_time."' and uid = ".$sult['id'];
                        $monthly_query = $this->db->query($monthly_sql);
                        $monthly_query_value = $monthly_query->row_array();
                        if(!empty($monthly_query_value))
                        {
                            $user_point = $sult['profit_sharing_point'] + $monthly_query_value['points'];
                        }
                        else 
                        {
                            $user_point = $sult['profit_sharing_point'];
                        }                        
                        
                        switch($sult['sale_rank'])
                        {
                            case 3:
                                //高级市场主管
                                $leader_tb = "month_leader_bonus";
                                break;
                            case 4:
                                //市场总监
                                $leader_tb = "month_top_leader_bonus";
                                break;
                            case 5:
                                //副总裁
                                $leader_tb = "month_leader_bonus_lv5";
                                break;
                        }
                                                
                        $add_sql = "insert ignore into ".$leader_tb."(uid,sharing_point) values(".$sult['id'].",".$user_point.")";
                        $this->db->query($add_sql);    
                    }                      
                }
            }
            
            $this->m_log->createCronLog('[Success]每月领导组织分红奖队列创建成功。');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
               $this->m_log->createCronLog('[Fail]每月领导组织分红奖队列创建失败。');
            }            
        }
    }
    
    /***
     * 修复业绩后， 每月领导分红奖  判断用户是否满足发奖条件，满足的话， 将其添加到队列中
     */
    public function user_new_month_leader_bonus_list()
    {
        $this->load->model('m_log');
        $s_time = date('Ym', strtotime('last month')); //上个月时间
        if($s_time==date('Ym'))
        {
            $s_time = date('Y02', time()); //上个月时间
        }
    
        $now_time = date("Y-m-d"); //当前时间
        
        //清空队列
        $clear_queue_list_sql_led = "truncate user_month_leader_bonus_list";
        $this->db->query($clear_queue_list_sql_led);
        //清空队列
        $clear_queue_list_sql_top = "truncate user_month_top_leader_bonus_list";
        $this->db->query($clear_queue_list_sql_top);
        //清空队列
        $clear_queue_list_sql_lv5 = "truncate user_month_leader_bonus_lv5_list";
        $this->db->query($clear_queue_list_sql_lv5);
    
        $total_sql = "select count(*) as total  from users where (sale_rank BETWEEN 3 and 5) and user_rank = 1";
        $total_query = $this->db->query($total_sql);
        $total_value = $total_query->row_array();
        if($total_value['total']!=0)
        {
            $this->db->trans_start();
    
            //获取满足提交的发奖用户            
            $user_sql = "SELECT u.id,u.sale_rank,u.profit_sharing_point FROM users u left join  users_level_change_log as b  on u.id = b.uid 
                where u.sale_rank BETWEEN 3 and 5 and u.user_rank = 1 and u.sale_rank_up_time < '".date('Y-m-01')." 00:00:00' and b.create_time < '".date('Y-m-01')." 00:00:00' and b.level_type=2 and b.new_level=1";
            $user_query_all = $this->db->query($user_sql);
            $user_value_all = $user_query_all->result_array();
    
            if(!empty($user_value_all))
            {
                foreach($user_value_all as $sult)
                {
                    $user_point = 0;
                    $user_monthly_sql = "select uid from users_store_sale_info_monthly a where a.uid = ".$sult['id']."  and a.year_month = '".$s_time."'";
                    switch($sult['sale_rank'])
                    {
                        case 3:
                            //高级市场主管
                            $user_monthly_sql .= " and sale_amount >= 10000";
                            break;
                        case 4:
                            //市场总监
                            $user_monthly_sql .= " and sale_amount >= 20000";
                            break;
                        case 5:
                            //副总裁
                            $user_monthly_sql .= " and sale_amount >= 30000";
                            break;
                    }
    
                    $user_monthly_query = $this->db->query($user_monthly_sql);
                    $user_monthly_value = $user_monthly_query->row_array();
                    if(!empty($user_monthly_value))
                    {
                        $monthly_sql = "select sum(point) as points from users_sharing_point_reward where end_time >= '".$now_time."' and uid = ".$sult['id'];
                        $monthly_query = $this->db->query($monthly_sql);
                        $monthly_query_value = $monthly_query->row_array();
                        if(!empty($monthly_query_value))
                        {
                            $user_point = $sult['profit_sharing_point'] + $monthly_query_value['points'];
                        }
                        else
                        {
                            $user_point = $sult['profit_sharing_point'];
                        }
    
                        switch($sult['sale_rank'])
                        {
                            case 3:
                                //高级市场主管
                                $leader_tb = "user_month_leader_bonus_list";
                                break;
                            case 4:
                                //市场总监
                                $leader_tb = "user_month_top_leader_bonus_list";
                                break;
                            case 5:
                                //副总裁
                                $leader_tb = "user_month_leader_bonus_lv5_list";
                                break;
                        }
    
                        $add_sql = "insert ignore into ".$leader_tb."(uid,sharing_point) values(".$sult['id'].",".$user_point.")";
                        $this->db->query($add_sql);
                    }
                }
            }
    
            $this->m_log->createCronLog('[Success]每月领导组织分红奖队列创建成功。');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->m_log->createCronLog('[Fail]每月领导组织分红奖队列创建失败。');
            }
        }
    }
    
    /**
     * 每月初生成新的月团队组织分红发奖列表 
     */
    public function new_every_month_team_group_sharelist()
    {

        $this->load->model('m_log');        
        $s_time = date('Ym', strtotime('last month')); //上个月时间
        if($s_time==date('Ym'))
        {
            $s_time = date('Y02', time()); //上个月时间
        }
        $sale_time = date('Y-m-01')." 00:00:00";
        $this->db->trans_start(); //开启事务
        
        //创建每月团队组织分红队列日志表
        $create_month_tb_log = "month_group_share_list_".$s_time;
        $create_month_log_sql = "create table if not EXISTS ".$create_month_tb_log." select * from month_group_share_list";
        $this->db->query($create_month_log_sql);
        
        //清空队列
        $clear_queue_list_sql = "truncate month_group_share_list";
        $this->db->query($clear_queue_list_sql);
        
         $sql = "insert ignore into month_group_share_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight) select a.uid,
        a.sale_amount,(b.sale_rank+1)*(b.sale_rank+1),if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4) ) ) from 
        users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`='".$s_time."' and b.user_rank=1 and b.sale_rank_up_time <'".$sale_time."' and b.sale_rank in (2,3,4,5) and a.sale_amount>=10000;";
                
        $this->db->query($sql);
        
        $this->m_log->createCronLog('[Success]每月初生成新的月团队组织分红发奖列表成功。');
        $this->db->trans_complete();
    var_dump($this->db->trans_status());
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]每月初生成新的月团队组织分红发奖列表失败。');
        }
        
    }
    
    
    /**
     * 修复业绩后，生成新的月团队组织分红发奖列表
     */
    public function user_every_month_team_group_sharelist()
    {
    
        $this->load->model('m_log');
        $s_time = date('Ym', strtotime(date("Ym05"))-30*24*3600); //当月第一天        
        
        $this->db->trans_start(); //开启事务
    
        //创建每月团队组织分红队列日志表
       
        $sql = "insert ignore into user_month_group_share_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight) select a.uid,a.sale_amount,(b.sale_rank+1)*(b.sale_rank+1),if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4) ) ) from
        users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`='".$s_time."' and b.user_rank=1 and b.sale_rank in (2,3,4,5) and a.sale_amount>=10000;";
    
        $this->db->query($sql);
    
        $this->m_log->createCronLog('[Success]每月初生成新的月团队组织分红发奖列表成功。');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]每月初生成新的月团队组织分红发奖列表失败。');
        }
    
    }
    
    
    /**
     * 每月初生成新的周团队分红发奖列表  
     */
    public function new_every_week_share_queue_list()
    {
        $this->load->model('m_log');
        $s_time = date('Y-m-01', strtotime(date("Y-m-d"))); //当月第一天       
        $m_time = date('Ym', strtotime('last month')); //上个月时间
        if($s_time==date('Ym'))
        {
            $m_time = date('Y02', time()); //上个月时间
        }
        $sale_time = date('Y-m-01')." 00:00:00";
        $this->db->trans_start(); //开启事务        
        
        //创建每周团队分红队列
        $create_week_tb_log = "week_share_qualified_list_".$m_time;
        $create_week_share_log_sql = "create table ".$create_week_tb_log." select * from week_share_qualified_list where create_time < '".$s_time."'";
        $this->db->query($create_week_share_log_sql);
        
        //清空队列信息
        $clear_week_share_sql = "delete from week_share_qualified_list where create_time < '".$s_time."'";
        $this->db->query($clear_week_share_sql);
//         $sql ="insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`='".$m_time."' and b.user_rank =1  
//             and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000";
       
        $sql = "insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) 
         from (users_store_sale_info_monthly a left join users b on  a.uid=b.id ) left join users_level_change_log c on a.uid=c.uid 
        where a.`year_month`='".$m_time."' and b.sale_rank_up_time < '".$sale_time."' and b.user_rank =1 and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000 
        and c.new_level= 1 and c.level_type =2 and c.create_time < '".$sale_time."'";        
        
        $this->db->query($sql);
        
        $this->m_log->createCronLog('[Success]每月初生成新的周团队分红发奖列表成功。');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]每月初生成新的周团队分红发奖列表失败。');
        }        
    }
    
   
    /**
     * 修复下单会员业绩，重新生成新的每周团队分红队列
     */
    public function user_week_bonus_qualified_list()
    {
        $this->load->model('m_log');
        $s_time = date('Y-m-01', strtotime(date("Y-m-d"))); //当月第一天
        $m_time = date('Ym', strtotime('last month')); //上个月时间
        if($s_time==date('Ym'))
        {
            $m_time = date('Y02', time()); //上个月时间
        }
    
        $this->db->trans_start(); //开启事务    
        
        //清空队列信息
        $clear_week_share_sql = "truncate user_week_bonus_qualified_list";
        $this->db->query($clear_week_share_sql);
        $sql ="insert ignore into user_week_bonus_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`='".$m_time."' and b.user_rank =1
            and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000";
        $this->db->query($sql);
    
        $this->m_log->createCronLog('[Success]每月初生成新的周团队分红发奖列表成功。');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]每月初生成新的周团队分红发奖列表失败。');
        }
    }
    
    
    /**
     * 参与每周团队销售组织分红奖的权重统计
     */
    public function week_weight_total()
    {
        
        $sql = "select sum(sale_amount_weight) as sale_amount_weight,sum(sale_rank_weight) as sale_rank_weight,sum(share_point_weight) as share_point_weight,sum(store_rank_weight) as store_rank_weight from week_share_qualified_list";
        $query = $this->db->query($sql);
        return $query->row_array();        
    }
    
    /***
     * 参与每周团队销售组织分红奖的权重统计
     * @param unknown $c_time
     * @return unknown
     */
    public function week_weight_total_par_time($c_time)
    {
        $return_value = array();
        if($c_time==date('Ym'))
        {
            $return_value = $this->week_weight_total();
        }
        else
        {
            $week_share_tb = "week_share_qualified_list_".$c_time;
            $sql = "select sum(sale_amount_weight) as sale_amount_weight,sum(sale_rank_weight) as sale_rank_weight,sum(share_point_weight) as share_point_weight,sum(store_rank_weight) as store_rank_weight from ".$week_share_tb ." limit 1";
            $query = $this->db->query($sql);
            $return_value = $query->row_array();
            if(empty($return_value))
            {
                $return_value = $this->week_weight_total();
            }
        }       
        return $return_value;
    }
    
    
    /**
     * 参与每月团队销售组织分红奖的权重统计
     */
    public function month_weight_total()
    {
    
        $sql = "select sum(sale_amount_weight) as sale_amount_weight,sum(sale_rank_weight) as sale_rank_weight,sum(store_rank_weight) as store_rank_weight from month_group_share_list";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    
    
    /**
     * 参与每月团队销售组织分红奖的权重统计
     */
    public function month_weight_total_pre_time($c_time)
    {
        $return_value = array();
        if($c_time==date('Ym'))
        {
            $return_value = $this->month_weight_total();
        }
        else
        {
            $month_share_tb = "month_group_share_list_".$c_time;
            $sql = "select sum(sale_amount_weight) as sale_amount_weight,sum(sale_rank_weight) as sale_rank_weight,sum(store_rank_weight) as store_rank_weight from ".$month_share_tb;
            $query = $this->db->query($sql);
            $return_value = $query->row_array();
            if(empty($return_value))
            {
                $return_value = $this->month_weight_total();
            }
        }
        
        return $return_value;
    }
    
        
    
    /**
     * 预发放每周团队销售分红    预发奖
     */
    public function grant_pre_every_week_team_dividend()
    {
        
        $to_day = date("Y-m-d 23:59:59");
        $this->load->model('o_company_money_today_total');    
        $this->load->model('o_bonus');
        $this->load->model('o_grant_pre_user_bonus');
        $this->load->model('tb_grant_pre_bonus_state'); //预发奖执行状态
        
        $this->tb_grant_pre_bonus_state->edit_state(25,0,"");  //预发奖状态初始化
        
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        
        //清空上次预发每周团队销售分红奖
        $clear_pre_sql = "TRUNCATE grant_pre_every_week_team_bonus";
        $this->db->query($clear_pre_sql);
        
        //上一周利润统计
        $last_week_total = $this->o_company_money_today_total->get_last_week_profit();      
        
        
        //获取发奖比率
        $grant_lv_sql = "SELECT * FROM system_rebate_conf WHERE category_id=25 limit 1";
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
                $week_weight = $this->week_weight_total(); //用户所有权重
                 
                if(!empty($week_weight))
                {
                   
                    $num_count_sql  = "select count(*) as total from week_share_qualified_list WHERE create_time < '".$to_day."'";
                    $num_count_query = $this->db->query($num_count_sql);                    
                    $num_count = $num_count_query->row_array()['total'];
                    $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;                    
                    
                    if($page_total > 0)
                    {
                        $this->tb_grant_pre_bonus_state->edit_state(25,1,""); //开始发奖
                        for($i=0; $i < $page_total; $i++)
                        {                            
                            $sql = "select uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight from
                                week_share_qualified_list WHERE create_time < '".$to_day."' order by uid asc limit ".$i*$page_nb.",".$page_nb;
                                                        
                            $query = $this->db->query($sql);
                            $week_share_list = $query->result_array();                                  
                            if(!empty($week_share_list))
                            {
                                $users_info_data = array();
                                foreach($week_share_list as $sult)
                                {
                                    
                                    $user_sql = "SELECT id FROM users WHERE id=".$sult['uid']." AND status = 1";
                                    $user_query = $this->db->query($user_sql);
                                    $user_query_value = $user_query->row_array();
                                    if(!empty($user_query_value['id']))
                                    {
                                        $user_amount_total = 0;
                                        $user_sale_amount = 0;
                                        $sale_rank_weight = 0;
                                        $share_point_weight = 0;
                                        $user_sale_amount = round($sult['sale_amount_weight']/$week_weight['sale_amount_weight'] * $amount_weight);
                                        $sale_rank_weight = round($sult['sale_rank_weight']/$week_weight['sale_rank_weight'] * $rank_weight);
                                        $share_point_weight = round($sult['share_point_weight']/$week_weight['share_point_weight'] * $sahre_point_weight);
                                        $store_rank_weight = round($sult['store_rank_weight']/$week_weight['store_rank_weight'] * $store_weight);
                                        $user_amount_total = round($user_sale_amount + $sale_rank_weight + $share_point_weight + $store_rank_weight);
                                        
                                        //发奖数据
                                        $users_info_data[] = array
                                        (
                                            'uid' => $sult['uid'],
                                            'amount' => $user_amount_total
                                        );
                                    }                           
                                }                                 
                                //预发奖
                                $this->o_grant_pre_user_bonus->add_grant_pre_user_bonus(25,0,$users_info_data);
                                $this->tb_grant_pre_bonus_state->edit_state(25,2,$i."/".$page_total);
                            }             
                        }
                        $this->tb_grant_pre_bonus_state->edit_state(25,3,$i."/".$page_total); //发奖完成
                    }                                           
                } 
            }
        }        
    }
    
    
    /**
     * 预发奖
     * @return boolean
     * @author Terry
     */
    public function pre_bonus_list($filter,$type,$perPage = 10) {
        
        $tb = "";
        $date = array();
        switch($type)
        {
            case 1:
                //每周团队分红奖
                $tb = "grant_pre_every_week_team_bonus";
                break;
            case 2:
                //每月团队分红奖
                $tb = "grant_pre_every_month_team_bonus";
                break;
            case 3:
                //每月领导分红奖
                $tb = "grant_pre_every_month_leader_bonus";
                break;
        }
        if(!empty($tb))
        {
            $sql = "select uid,amount ,FROM_UNIXTIME(create_time) as create_time from ".$tb." limit ".($filter['page'] - 1) * $perPage.",".$perPage;
            $query = $this->db->query($sql);
            $query_value = $query->result_array();
            if(!empty($query_value))
            {
                foreach($query_value as $sult)
                {
                    $date[] = array
                    (
                        'uid'   => $sult['uid'],
                        'amount'   => "$".round($sult['amount']/100,2),
                        'create_time'   => $sult['create_time']
                    );
                }
            }
            return $date;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * 预发奖表总数据
     * @return boolean
     * @author Terry
     */
    public function pre_bonus_all($filter,$type) {
    
        $tb = "";
        switch($type)
        {
            case 1:
                //每周团队分红奖
                $tb = "grant_pre_every_week_team_bonus";
                break;
            case 2:
                //每月团队分红奖
                $tb = "grant_pre_every_month_team_bonus";
                break;
            case 3:
                //每月领导分红奖
                $tb = "grant_pre_every_month_leader_bonus";
                break;
        }
        if(!empty($tb))
        {
            $sql = "select count(*) as total from ".$tb;
            $query = $this->db->query($sql);
            return $query->row_array()['total'];
        }
        else
        {
            return 0;
        }
    }
    
    
    
    /**
     * 发放每周团队销售分红
     */
    public function grant_every_week_team_dividend()
    {
        $this->load->model('o_bonus');
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        
        $num_count_sql  = "select count(*) as total from grant_pre_every_week_team_bonus";
        $num_count_query = $this->db->query($num_count_sql);
        $num_count = $num_count_query->row_array()['total'];
        $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
        
        if($page_total > 0)
        {
            for($i=0; $i < $page_total; $i++)
            {
                $sql = "select uid,amount as money from grant_pre_every_week_team_bonus limit ".$i*$page_nb.",".$page_nb;
        
                $query = $this->db->query($sql);
                $week_share_list = $query->result_array();
                if(!empty($week_share_list))
                {                    
                    //发奖每周团队销售分红
                    $this->o_bonus->assign_bonus_batch($week_share_list,25);
                }
            }
        }
        
    }
    
    
    /**
     * 发奖每月团队组织分红奖   预发奖
     */
    public function grant_pre_every_month_team_dividend()
    {
        
        $this->load->model('o_bonus');
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_grant_pre_user_bonus');        
        $this->load->model('tb_grant_pre_bonus_state'); //预发奖执行状态
        
        $this->tb_grant_pre_bonus_state->edit_state(1,0,"");  //预发奖状态初始化
        
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        
        $global = 0; //全球利率比
        $sale_amount_w = 0; //个人销售额利率比
        $store_amount_w = 0; //店铺销售额利率比
        $rank_amount_w = 0; //职称利率比
        $sale_time = date('Y-m-01')." 00:00:00";
        
        //清空上次预发每周团队销售分红奖
        $clear_pre_sql = "TRUNCATE grant_pre_every_month_team_bonus";
        $this->db->query($clear_pre_sql);
        
        //上一周利润统计
        $last_week_total = $this->o_company_money_today_total->get_last_month_profit();
        
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
        }
        
        $user_weight = $this->month_weight_total(); //用户发奖权重
        if(0!= $global)
        {
            $amount_weight = round($last_week_total['money'] * $global * $sale_amount_w); //个人销售权重
            $rank_weight = round($last_week_total['money'] * $global * $rank_amount_w); // 职称权重
            $user_store_weight = round($last_week_total['money'] * $global * $store_amount_w); //店铺权重
            
            $num_count_sql  = "select count(*) as total from month_group_share_list";
            $num_count_query = $this->db->query($num_count_sql);
            $num_count = $num_count_query->row_array()['total'];
            $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
            
            if($page_total > 0)
            {
                $this->tb_grant_pre_bonus_state->edit_state(1,1,"");  //预发奖状态初始化
                for($i=0; $i < $page_total; $i++)
                {
                    $user_sql = " select uid,sale_amount_weight,sale_rank_weight,store_rank_weight from month_group_share_list order by uid asc limit ".$i*$page_nb.",".$page_nb;;
                    $user_query = $this->db->query($user_sql);
                    $month_share_list = $user_query->result_array();                  
                    if(!empty($month_share_list))
                    {
                        $users_info_data = array();
                        foreach($month_share_list as $sult)
                        {
                                                        
                            $check_sql = "select uid from users_level_change_log where uid = ".$sult['id']." and new_level=1 and level_type =2 and create_time > '".$sale_time."'";
                            $check_query = $this->db->query($check_sql)->row_array();
                            if(!empty($check_query))
                            {
                                //升级时间大于每月1号 时，此用户不满足队列
                                continue;
                            }
                            
                            $user_sql = "SELECT id FROM users WHERE id=".$sult['uid']." AND status = 1";
                            $user_query = $this->db->query($user_sql);
                            $user_query_value = $user_query->row_array();
                            if(!empty($user_query_value['id']))
                            {
                                $user_amount_total = 0;
                                $user_sale_amount = 0;
                                $sale_rank_weight = 0;
                                $store_point_weight = 0;
                                $user_sale_amount = round(($sult['sale_amount_weight']/$user_weight['sale_amount_weight']) * $amount_weight);
                                $sale_rank_weight = round(($sult['sale_rank_weight']/$user_weight['sale_rank_weight']) * $rank_weight);
                                $store_point_weight = round(($sult['store_rank_weight']/$user_weight['store_rank_weight']) * $user_store_weight);
                                $user_amount_total = round($user_sale_amount + $sale_rank_weight + $store_point_weight);
                                 
                                //发奖数据
                                $users_info_data[] = array
                                (
                                    'uid' => $sult['uid'],
                                    'amount' => $user_amount_total
                                );
                            }                            
                        }
                        $this->tb_grant_pre_bonus_state->edit_state(1,2,$i."/".$page_total);  //预发奖状态初始化
                        //预发奖
                        $this->o_grant_pre_user_bonus->add_grant_pre_user_bonus(1,0,$users_info_data);                        
                    }                   
                }
                $this->tb_grant_pre_bonus_state->edit_state(1,3,$i."/".$page_total);  //预发奖状态初始化
            }
        }
        
    }
    
    /**
     * 发放每月团队组织分红奖
     */
    public function grant_every_month_team_dividend()
    {
    
        $this->load->model('o_bonus');
       
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        $num_count_sql  = "select count(*) as total from grant_pre_every_month_team_bonus";
        $num_count_query = $this->db->query($num_count_sql);
        $num_count = $num_count_query->row_array()['total'];
        $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
        
        if($page_total > 0)
        {
            for($i=0; $i < $page_total; $i++)
            {
                $user_sql = " select uid,amount as money from grant_pre_every_month_team_bonus limit ".$i*$page_nb.",".$page_nb;
                $user_query = $this->db->query($user_sql);
                $month_share_list = $user_query->result_array();
                if(!empty($month_share_list))
                {                    
                    //发奖每月团队组织销售分红
                    $this->o_bonus->assign_bonus_batch($month_share_list,1);
                }
            }
        }
    }
    
    
    /***
     * 获取参与领导分红总分红点 2017年4月新的分红机制
     * @param 职称 $sale_rank
     * @param 时间(时间格式：201703)  $c_time
     */
    public function getLeaderSharePoint($sale_rank,$c_time)
    {
        $table_name = "";
        $table_postfix = "";
        if(!empty($c_time))
        {
            if($c_time!=date('Ym'))
            {
                $table_postfix .= "_".$c_time;
            }            
        }
        switch ($sale_rank)
        {
            case 3:
                $table_name = "month_leader_bonus";
                break;
            case 4:
                $table_name = "month_top_leader_bonus";
                break;
            case 5:
                $table_name = "month_leader_bonus_lv5";
                break;
        }
        if(!empty($table_name))
        {
            
            $exits_sql = "SELECT uid FROM ".$table_name.$table_postfix." limit 1";           
            $exits_query = $this->db->query($exits_sql);
            $exits_value = $exits_query->row_array();      
            if(!empty($exits_value))
            {               
                $sql ="select sum(a.sharing_point) as totalPoint from ".$table_name.$table_postfix." a left join users b on a.uid=b.id where b.store_qualified=1";
            }
            else
            {
                $sql ="select sum(a.sharing_point) as totalPoint from ".$table_name." a left join users b on a.uid=b.id where b.store_qualified=1";
            }  
           
            $query = $this->db->query($sql);
            return $query->row_array()['totalPoint'];
        }
        else 
        {
            return 0;
        }
    }
    
    /***
     * 获取参与领导分红总人数 2017年4月新的分红机制
     * @param 职称 $sale_rank
     * @param 时间  $c_time
     */
    public function getLeaderSharePointPerTotal($sale_rank,$c_time)
    {
        $table_name = "";
        $table_postfix = "";
        if(!empty($c_time))
        {
            if($c_time!=date('Ym'))                
            {
                $table_postfix .= "_".$c_time;
            }            
        }
        switch ($sale_rank)
        {
            case 3:
                $table_name = "month_leader_bonus";
                break;
            case 4:
                $table_name = "month_top_leader_bonus";
                break;
            case 5:
                $table_name = "month_leader_bonus_lv5";
                break;
        }
        if(!empty($table_name))
        {
            $exits_sql = "SELECT uid FROM ".$table_name.$table_postfix." limit 1";
            $exits_query = $this->db->query($exits_sql);
            $exits_value = $exits_query->row_array();
            if(!empty($exits_value))
            {
                $sql = "select count(*) as totalNum from ".$table_name.$table_postfix." a left join users b on a.uid=b.id where b.store_qualified=1";
            }
            else
            {
                $sql = "select count(*) as totalNum from ".$table_name." a left join users b on a.uid=b.id where b.store_qualified=1";
            }       
           
            $query = $this->db->query($sql);
            return $query->row_array()['totalNum'];
        }
        else 
        {
            return 0;
        }
    }

    /**
     * 预发奖  每月领导分红奖(new) 市场主管  
     * @author Terry Lu
     */
    public function pre_monthLeaderSharing_new()
    {
    
        $this->load->model('m_profit');
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_grant_pre_user_bonus');        
        
        //上一周利润统计
        
        $lastMonthProfit = 0; //发奖金额
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        
        //清空上次预发每周团队销售分红奖
        $clear_pre_sql = "DELETE FROM grant_pre_every_month_leader_bonus WHERE level = 3";
        $this->db->query($clear_pre_sql);
        
        $last_week_total = $this->o_company_money_today_total->get_last_month_profit();

        //获取分红利润比
        $lv_sql = "SELECT rate_a,rate_b,rate_c FROM system_rebate_conf WHERE category_id = 23 and child_id = 4";
        $lv_query = $this->db->query($lv_sql);
        $lv_value = $lv_query->row_array();
        if(!empty($lv_value) && $lv_value['rate_a'] > 0)
        {
            $lastMonthProfit = $last_week_total['money'] * $lv_value['rate_a'];
            $sharingAmount = tps_money_format($lastMonthProfit);
            if(!$sharingAmount){
                $this->m_log->createCronLog('上月公司利润的3%为0，无法发放每月领导分红奖。');
                return 0;
            }
    
            $sharingAmount1 = round($sharingAmount*$lv_value['rate_b']);//用来均摊的利润
            $sharingAmount2 = $sharingAmount-$sharingAmount1;//用来按照分红点发放的利润
    
            //统计用户总分红点
            $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
            if($totalPoint<=0){
                return 0;
            }
    
            $totalNum = $this->db->query("select count(*) as totalNum from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalNum;
            if($totalNum<=0){
                return 0;
            }
            $commission_amount1 = tps_money_format($sharingAmount1/$totalNum);    
            
            $num_count_sql  = "select count(*) as total from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1";
            $num_count_query = $this->db->query($num_count_sql);
            $num_count = $num_count_query->row_array()['total'];
            $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
            
            if($page_total > 0)
            {                
                for($i=0; $i < $page_total; $i++)
                {
                    $user_list_data = array();
                    $eligibility_sql ="select a.uid uid,a.sharing_point sharing_point,b.status status from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1 limit ".$i*$page_nb.",".$page_nb;                    
                    $eligibility_query = $this->db->query($eligibility_sql);
                    $eligibilityUserInfoList = $eligibility_query->result_array();
                    
                    foreach($eligibilityUserInfoList as $item)
                    {
                                        
                        if(1==$item['status'])
                        {
                            $userTotalPoint = $item['sharing_point'];
                            if($userTotalPoint>0){
                                $commission_amount2 = tps_int_format($userTotalPoint/$totalPoint*$sharingAmount2);
                            }else{
                                $commission_amount2 = 0;
                            }
                            
                            $user_list_data[] = array
                            (
                                'uid'   => $item['uid'],
                                'amount'   => $commission_amount1+$commission_amount2
                            );
                        }                        
                    }                    
                    //预发奖
                    $this->o_grant_pre_user_bonus->add_grant_pre_user_bonus(23,3,$user_list_data);
                }                
            }
        }
    }
    
    /**
     * 预发奖 每月领袖分红奖(new)/ 市场总监
     * @author Terry Lu
     */
    public function pre_monthTopLeaderSharing_new(){
    
        $this->load->model('m_profit');
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_grant_pre_user_bonus');
        
        //上一周利润统计
    
        $lastMonthProfit = 0; //发奖金额
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        //清空上次预发每周团队销售分红奖
        $clear_pre_sql = "DELETE FROM grant_pre_every_month_leader_bonus WHERE level = 4";
        $this->db->query($clear_pre_sql);
        $last_week_total = $this->o_company_money_today_total->get_last_month_profit();
        //获取分红利润比
        $lv_sql = "SELECT rate_a,rate_b,rate_c FROM system_rebate_conf WHERE category_id = 23 and child_id = 5";
        $lv_query = $this->db->query($lv_sql);
        $lv_value = $lv_query->row_array();
    
        if(!empty($lv_value) && $lv_value['rate_a'] > 0)
        {
            $lastMonthProfit = $last_week_total['money'] * $lv_value['rate_a'];
            $sharingAmount = tps_money_format($lastMonthProfit);
            if(!$sharingAmount){
                $this->m_log->createCronLog('上月公司利润的1%为0，无法发放每月领袖分红奖。');
                return 0;
            }
    
            $sharingAmount1 = round($sharingAmount*$lv_value['rate_b']);//用来均摊的利润
            $sharingAmount2 = $sharingAmount-$sharingAmount1;//用来按照分红点发放的利润    
    
            $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
            if($totalPoint<=0){
                return 0;
            }
    
            $totalNum = $this->db->query("select count(*) as totalNum from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalNum;
            if($totalNum<=0){
                return 0;
            }
            $commission_amount1 = tps_money_format($sharingAmount1/$totalNum);    
    
            $num_count_sql  = "select count(*) as total from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1";
            $num_count_query = $this->db->query($num_count_sql);
            $num_count = $num_count_query->row_array()['total'];
            $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
    
            if($page_total > 0)
            {                
                for($i=0; $i < $page_total; $i++)
                {
    
                    $user_list_data = array();
                    $eligibility_sql = "select a.uid uid,a.sharing_point sharing_point,b.status status from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1  limit  ".$i*$page_nb.",".$page_nb;
                    $eligibility_query = $this->db->query($eligibility_sql);
                    $eligibilityUserInfoList = $eligibility_query->result_array();
    
                    foreach($eligibilityUserInfoList as $item)
                    {
    
                        if(1==$item['status'])
                        {
                            $userTotalPoint = $item['sharing_point'];
                            if($userTotalPoint>0){
                                $commission_amount2 = tps_int_format($userTotalPoint/$totalPoint*$sharingAmount2);
                            }else{
                                $commission_amount2 = 0;
                            }
                            
                            $user_list_data[] = array
                            (
                                'uid'   => $item['uid'],
                                'amount'   => $commission_amount1+$commission_amount2
                            );
                        }
                        
                    }                    
                    //预发奖
                    $this->o_grant_pre_user_bonus->add_grant_pre_user_bonus(23,4,$user_list_data);
                }               
            }
        }
    }
    
    
    /**
     * 预发奖   每月领导分红奖(全球销售副总裁)
     * @author Terry Lu
     */
    public function pre_monthLeader5Sharing_new(){
    
        $this->load->model('m_profit');
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_grant_pre_user_bonus');
       
        //上一周利润统计        
        $lastMonthProfit = 0; //发奖金额
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        //清空上次预发每周团队销售分红奖
        $clear_pre_sql = "DELETE FROM grant_pre_every_month_leader_bonus WHERE level = 5";
        $this->db->query($clear_pre_sql);
        $last_week_total = $this->o_company_money_today_total->get_last_month_profit();        
        //获取分红利润比
        $lv_sql = "SELECT rate_a,rate_b,rate_c FROM system_rebate_conf WHERE category_id = 23 and child_id = 6";
        $lv_query = $this->db->query($lv_sql);
        $lv_value = $lv_query->row_array();
        if(!empty($lv_value) && $lv_value['rate_a'] > 0)
        {
            $lastMonthProfit = $last_week_total['money'] * $lv_value['rate_a'];
            $sharingAmount = tps_money_format($lastMonthProfit);
            if(!$sharingAmount){
                $this->m_log->createCronLog('上月公司利润的0.5%为0，无法发放每月领导分红奖－全球销售副总裁。');
                return 0;
            }
    
            $sharingAmount1 = round($sharingAmount*$lv_value['rate_b']);//用来均摊的利润
            $sharingAmount2 = $sharingAmount-$sharingAmount1;//用来按照分红点发放的利润
    
    
            $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_leader_bonus_lv5 a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
            if($totalPoint<=0){
                return 0;
            }
    
            $totalNum = $this->db->query("select count(*) as totalNum from month_leader_bonus_lv5 a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalNum;
            if($totalNum<=0){
                return 0;
            }
            $commission_amount1 = tps_money_format($sharingAmount1/$totalNum);
    
            
            $num_count_sql  = "select count(*) as total from month_leader_bonus_lv5 a left join users b on a.uid=b.id where b.store_qualified=1";
            $num_count_query = $this->db->query($num_count_sql);
            $num_count = $num_count_query->row_array()['total'];
            $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
            
            if($page_total > 0)
            {                
                for($i=0; $i < $page_total; $i++)
                {
                    $user_list_data = array();
                    $eligibility_sql = "select a.uid uid,a.sharing_point sharing_point,b.status status from month_leader_bonus_lv5 a left join users b on a.uid=b.id where b.store_qualified=1 limit  ".$i*$page_nb.",".$page_nb;
                    $eligibility_query = $this->db->query($eligibility_sql);
                    $eligibilityUserInfoList = $eligibility_query->result_array();                    
                    foreach($eligibilityUserInfoList as $item)
                    {
                    
                        if(1==$item['status'])
                        {
                            $userTotalPoint = $item['sharing_point'];
                            if($userTotalPoint>0){
                                $commission_amount2 = tps_int_format($userTotalPoint/$totalPoint*$sharingAmount2);
                            }else{
                                $commission_amount2 = 0;
                            }
                            
                            $user_list_data[] = array
                            (
                                'uid'   => $item['uid'],
                                'amount'   => $commission_amount1+$commission_amount2
                            );
                        }                        
                    }               
                    //预发奖
                    $this->o_grant_pre_user_bonus->add_grant_pre_user_bonus(23,5,$user_list_data);                    
                }
                
            }            
        }    
    }
    
    /**
     * 预发奖
     */
    public function grant_pre_leader_user_bonus()
    {
        $this->load->model('tb_grant_pre_bonus_state'); //预发奖执行状态        
        $this->tb_grant_pre_bonus_state->edit_state(23,0,"");  //预发奖状态初始化
        $this->tb_grant_pre_bonus_state->edit_state(23,2,"");  //预发奖状态初始化
        $this->pre_monthLeaderSharing_new();
        $this->pre_monthTopLeaderSharing_new();
        $this->pre_monthLeader5Sharing_new();
        $this->tb_grant_pre_bonus_state->edit_state(23,3,"");  //预发奖状态初始化
    }
   
    /***
     * 发奖   每月领导分红奖
     * @param 职称类型 (3市场主管，4市场总监，5副总裁)  $type
     */
    public function grant_monthLeaderSharing_new($type)
    {
    
        $this->load->model('o_bonus');
         
        $page_nb = $this->page_nbs;//每页100条数据
        $page_total = 0; //总页数
        $num_count_sql  = "select count(*) as total from grant_pre_every_month_leader_bonus where level = ".$type;
        $num_count_query = $this->db->query($num_count_sql);
        $num_count = $num_count_query->row_array()['total'];
        $page_total = (int)$num_count>=$page_nb?$num_count/$page_nb:1;
    
        if($page_total > 0)
        {
            for($i=0; $i < $page_total; $i++)
            {
                $user_sql = " select uid,amount as money from grant_pre_every_month_leader_bonus where level = ".$type." limit ".$i*$page_nb.",".$page_nb;
                $user_query = $this->db->query($user_sql);
                $month_share_list = $user_query->result_array();
                if(!empty($month_share_list))
                {
                    //发奖每月团队组织销售分红
                    $this->o_bonus->assign_bonus_batch($month_share_list,23);
                }
            }
        }
    }
    
    
    
    
  
    

}
