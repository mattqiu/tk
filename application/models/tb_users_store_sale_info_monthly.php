<?php
/**
 * @author Terry
 */
class tb_users_store_sale_info_monthly extends CI_Model {

	protected $table_name = 'users_store_sale_info_monthly';
	
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户某个月的店铺普通销售额。
     * @return int 销售额
     * @author Terry
     */
    public function getSaleAmountByUidAndYearMonth($uid,$year_month, $only_return_sale_amount = true){
//         $query = $this->db->query("select * from users_store_sale_info_monthly where uid={$uid} and `year_month`={$year_month}")->row_object();

//         $res = $query->row_object();
       // var_dump($row);exit;
       $res = $this->db->from('users_store_sale_info_monthly')->where('uid',$uid)->where('year_month', $year_month)->get();
        //echo $this->db->last_query();exit;
        if ($res) {
        	$res = $res->row_object();
	        if ($only_return_sale_amount) {
		        return $res?$res->sale_amount:0;
	        } else {
	        	return $res;
	        }
        } else {
        	return 0;
        }
    }

    /**
     * 更新某个用户的销售业绩（订单数、订单总额）
     * @param $uid
     * @param $year_month
     * @param $updateArr array('orders_num_update'=>$orders_num_update,'sale_amount_update'=>$sale_amount_update) (数组里面item非必需项)
     * @return boolean
     * @author Terry
     */
    public function updateByUidAndDate($uid,$year_month,$updateArr){

        if( isset($updateArr['orders_num_update']) ){
            if($updateArr['orders_num_update']>=0){
                $updateArr['orders_num_update'] = '+'.$updateArr['orders_num_update'];
            }
            $this->db->set('orders_num', 'orders_num' . $updateArr['orders_num_update'], FALSE);
        }

        if( isset($updateArr['sale_amount_update']) ){
            if($updateArr['sale_amount_update']>=0){
                $updateArr['sale_amount_update'] = '+'.$updateArr['sale_amount_update'];
            }
            $this->db->set('sale_amount', 'sale_amount' . $updateArr['sale_amount_update'], FALSE);
        }

        $this->db->where('uid', $uid)->where('year_month', $year_month)->update('users_store_sale_info_monthly');
        
        return true;
    }

    /**
     * 新增用户月销售数据
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param int $uid 用户ID 
     * @param: @param int $year_month 年月份
     * @param: @param int $orders_num 订单数
     * @param: @param int $sale_amount 销售额
     * @reurn: return_type
     */
    public function add_users_sale_info_monthly($uid, $year_month, $orders_num, $sale_amount) {
    	return $this->db->insert('users_store_sale_info_monthly',array(
    			'uid'=> $uid,
    			'year_month'=> $year_month,
    			'orders_num'=> $orders_num,
    			'sale_amount'=> $sale_amount
    	));
    }

    /**
     * 用户等级升级时，如果满足每周团队组织分红奖， 则发奖
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param int $uid 用户id
     * @param: @param int $old_sale_rank 老级别 
     * @param: @param int $new_sale_rank 新级别
     * @param: @param int $option_m 操作类型，0为职称变更，1为级别变更，2为订单变更
     * 注：当option_m 为0时，old_sale_rank,new_sale_rank分别为老的职称和新的职称
     * @reurn: return_type
     */
	public function user_rank_change_week_comm($uid, $old_sale_rank, $new_sale_rank, $option_m) {
		$this->load->model('tb_user_comm_stat');
		$comt = $user_comt = 0;
		$user_stat = $this->tb_user_comm_stat->get_user_stat_info($uid, 'week_share_bonus');
		if (isset($user_stat['week_share_bonus']) && $user_stat['week_share_bonus'] == 0) {
			$user_comt = 1;
		}
		if ($option_m == 0) {
			if ($old_sale_rank == 0 && $new_sale_rank >= 1) {
				$comt = 1;
			}
		} elseif ($option_m == 1) {
			if ($old_sale_rank > 3 && $new_sale_rank <= 3) {
				$comt = 1;
			}
		} else {
			$comt = 1;
		}
		
		if ($user_comt == 1 && $comt == 1) {
			$this->load->model('tb_users_store_sale_info_monthly');
			$sql = 'insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
				) SELECT a.uid,a.sale_amount AS sale_amount_weight,b.sale_rank*b.sale_rank AS sale_rank_weight,IF(b.user_rank=3,1,IF(b.user_rank=1,3,2)) AS store_rank_weight,ROUND(b.profit_sharing_point*100) AS share_point_weight from users_store_sale_info_monthly a left join users b
				on a.uid=b.id where a.uid = "'.$uid.'" and a.year_month = DATE_FORMAT(curdate(),"%Y%m") and b.user_rank = 1 and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000';
			$this->db->query($sql);
		}
	}

	/**
	 * 统计会员销售额
	 */
    public function statistics_user_monthly($uid,$admin=0){
        $this->load->model("tb_trade_orders");
        /**修复店铺业绩统计*/
        $totalAmount = 0;
        $monthAmount = array();

        $this->db->trans_start();//事务开始

//        $res = $this->db->select('goods_amount_usd,score_year_month')->from('trade_orders')->where('shopkeeper_id',$uid)->where_in('status',array('1','3','4','5','6','90','97','111'))->where_in('order_prop',array('0','1'))->get()->result_array();
        $res = $this->tb_trade_orders->get_list_auto([
            "select"=>"goods_amount_usd,score_year_month",
            "where"=>[
                'shopkeeper_id'=>$uid,
                'status'=>array('1','3','4','5','6','90','97','111'),
                'order_prop'=>array('0','1')
            ]
        ]);

        foreach($res as $v){
            $totalAmount+=$v['goods_amount_usd'];
            if(isset($monthAmount[$v['score_year_month']])){
                $monthAmount[$v['score_year_month']]+=$v['goods_amount_usd'];
            }else{
                $monthAmount[$v['score_year_month']] = $v['goods_amount_usd'];
            }
        }

        $res = $this->db->select('order_amount_usd,score_year_month')
            ->from('mall_orders')->where('shopkeeper_id',$uid)->where('status',1)->get()->result_array();
        foreach($res as $v){
            $totalAmount+=$v['order_amount_usd']*100;
            if(isset($monthAmount[$v['score_year_month']])){
                $monthAmount[$v['score_year_month']]+=$v['order_amount_usd']*100;
            }else{
                $monthAmount[$v['score_year_month']] = $v['order_amount_usd']*100;
            }
        }

        $res = $this->db->select('order_amount_usd,score_year_month')
            ->from('one_direct_orders')->where('shopkeeper_id',$uid)->where('status',1)->get()->result_array();
        foreach($res as $v){
            $totalAmount+=$v['order_amount_usd']*100;
            if(isset($monthAmount[$v['score_year_month']])){
                $monthAmount[$v['score_year_month']]+=$v['order_amount_usd']*100;
            }else{
                $monthAmount[$v['score_year_month']] = $v['order_amount_usd']*100;
            }
        }

        $res = $this->db->select('order_amount_usd,score_year_month')
            ->from('walmart_orders')->where('shopkeeper_id',$uid)->where('status',1)->get()->result_array();
        foreach($res as $v){
            $totalAmount+=$v['order_amount_usd']*100;
            if(isset($monthAmount[$v['score_year_month']])){
                $monthAmount[$v['score_year_month']]+=$v['order_amount_usd']*100;
            }else{
                $monthAmount[$v['score_year_month']] = $v['order_amount_usd']*100;
            }
        }

        $this->db->replace('users_store_sale_info',array(
            'uid'=>$uid,
            'orders_num'=>9000,
            'sale_amount'=>$totalAmount
        ));

        //查询之前的数据，做记录
        $before_data = $this->db->query("select * from users_store_sale_info_monthly where uid=".$uid)->result_array();

        $this->db->query("delete from users_store_sale_info_monthly where uid=".$uid);
        foreach($monthAmount as $k=>$v){
            $this->db->insert('users_store_sale_info_monthly',array(
                'uid'=>$uid,
                'year_month'=>$k,
                'orders_num'=>9000,
                'sale_amount'=>$v
            ));
        }

        /**检查补单数据*/
        $res = $this->db->from('withdraw_task')->where('uid',$uid)->get()->result_array();
        foreach($res as $item){
            $sumAmount=isset($monthAmount[$item['order_year_month']])?$monthAmount[$item['order_year_month']]:0;
            $lackComm = 0;
            if($item['order_year_month'] >= config_item('commission_time')) {
                if ($item['comm_type'] == 6) {
                    $lackComm = 2500 - $sumAmount;
                } elseif ($item['comm_type'] == 24) {
                    $lackComm = 25000 - $sumAmount;
                } elseif ($item['comm_type'] == 8) {
                    $lackComm = 10000 - $sumAmount;
                } elseif ($item['comm_type'] == 7) {
                    $lackComm = 10000 - $sumAmount;
                } elseif ($item['comm_type'] == 23) {
                    $lackComm = 10000 - $sumAmount;
                } elseif ($item['comm_type'] == 4) {
                    $lackComm = 25000 - $sumAmount;
                }
            }else{
                if($item['comm_type']==6){
                    $lackComm = 2500-$sumAmount;
                }elseif($item['comm_type']==24){
                    $lackComm = 25000-$sumAmount;
                }elseif($item['comm_type']==8){
                    $lackComm = 7500-$sumAmount;
                }elseif($item['comm_type']==7){
                    $lackComm = 7500-$sumAmount;
                }elseif($item['comm_type']==23){
                    $lackComm = 7500-$sumAmount;
                }elseif($item['comm_type']==4){
                    $lackComm = 25000-$sumAmount;
                }
            }

            if($lackComm<=0){
                $this->db->where('uid',$uid)->where('comm_type',$item['comm_type'])->where('comm_year_month',$item['comm_year_month'])->delete('withdraw_task');
            }else{
                $this->db->where('uid',$uid)->where('comm_type',$item['comm_type'])->where('comm_year_month',$item['comm_year_month'])->set('sale_amount_lack', $lackComm)->update('withdraw_task');
            }
        }
        //记录日志
        $this->load->model('m_log');
        $this->m_log->adminActionLog($admin,'statistics_user_monthly','users_store_sale_info_monthly',$uid,
            'sale_amount',serialize($before_data),serialize($monthAmount));
        $this->db->trans_complete();
    }
    
    /**
     * 上月下单会员队列列表
     */
    public function modify_user_monthly($time)
    {
        $this->load->model('m_log');
        
        if($time==1)
        {
            $stime = date('Y-m-01', strtotime('-1 month'))." 00:00:00";
            $etime = date('Y-m-t', strtotime('-1 month'))." 23:59:59";
        }
        else 
        {
            $stime = date('Y-m-20')." 00:00:00";
            $etime = date('Y-m-22')." 23:59:59";
        }
        $u_time = date("Ym",strtotime($stime));
        $this->db->trans_start(); //开启事务        
        
        //清空队列信息
        $clear_user_monthly_list_sql = "truncate trade_order_user_monthly_list";
        $this->db->query($clear_user_monthly_list_sql);
        
        $clear_mall_user_monthly_list_sql = "truncate mall_order_user_monthly_list";
        $this->db->query($clear_mall_user_monthly_list_sql);
        
        $clear_one_user_monthly_list_sql = "truncate one_direct_order_user_list";
        $this->db->query($clear_one_user_monthly_list_sql);
        
        $clear_wal_user_monthly_list_sql = "truncate walmart_order_user_list";
        $this->db->query($clear_wal_user_monthly_list_sql);
        
        //零售订单
        $sql ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from trade_orders where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."' 
        and pay_time<='".$etime."' group by customer_id";        
        $this->db->query($sql);
        $sqls ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from trade_orders_1706 where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
        $this->db->query($sqls);
        
        $mall_sql ="insert ignore into mall_order_user_monthly_list(uid) select customer_id from mall_orders where `status` = 1 and 
            order_pay_time>='".$stime."' and order_pay_time<='".$etime."' group by customer_id";
        $this->db->query($mall_sql);
        
        $one_sql ="insert ignore into one_direct_order_user_list(uid) select customer_id from one_direct_orders where status = 1 and score_year_month='".$u_time."' group by customer_id";
        $this->db->query($one_sql);
        
        $wal_sql ="insert ignore into walmart_order_user_list(uid) select customer_id from walmart_orders where status = 1 and score_year_month='".$u_time."'  group by customer_id;";
        $this->db->query($wal_sql);
        
        $this->m_log->createCronLog('[Success]统计上个月下单会员队列列表成功。');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]统计上个月下单会员队列列表失败。');
        }        
    }
    
    
    /**
     * 昨天下单会员队列列表
     */
    public function modify_user_monthly_yesterday()
    {
        $this->load->model('m_log');        
        
        $stime = date('Y-m-d', strtotime(date('Ymd'))-24*3600)." 00:00:00";
        $etime = date('Y-m-d', strtotime(date('Ymd'))-24*3600)." 23:59:59";
        $u_time = date("Ym",strtotime($stime));
        $this->db->trans_start(); //开启事务
    
        //清空队列信息
        $clear_user_monthly_list_sql = "truncate trade_order_user_monthly_list";
        $this->db->query($clear_user_monthly_list_sql);
    
        $clear_mall_user_monthly_list_sql = "truncate mall_order_user_monthly_list";
        $this->db->query($clear_mall_user_monthly_list_sql);
    
        $clear_one_user_monthly_list_sql = "truncate one_direct_order_user_list";
        $this->db->query($clear_one_user_monthly_list_sql);
    
        $clear_wal_user_monthly_list_sql = "truncate walmart_order_user_list";
        $this->db->query($clear_wal_user_monthly_list_sql);
    
        //零售订单
        $tab_name = "trade_orders";
        $tab_names = "trade_orders";
        $p_time = date("Ym01");
        if(date('Ymd') < 20170701)
        {
            $sql ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_name." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
            $this->db->query($sql);
            $tab_names = $tab_names."_1706";//检测数据表，如果没有就创建
            $sqls ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_names." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
            $this->db->query($sqls);
        }
        else if(date('Ymd') == 20170701)
        {
            $sql ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_name." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
            $this->db->query($sql);
            $tab_names = $tab_names."_1706";//检测数据表，如果没有就创建
            $sqls ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_names." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
            $this->db->query($sqls);
        }
        else
        {
            if(date('Ymd')==$p_time)
            {
                $tab_names = $tab_names."_".substr(date("Y"),-2).sprintf('%02d',date("m")-1);//检测数据表，如果没有就创建
                $sql ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_names." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
                $this->db->query($sql);
            }
            else
            {
                $tab_names = $tab_names."_".substr(date("Y"),-2).date("m");//检测数据表，如果没有就创建
                $sql ="insert ignore into trade_order_user_monthly_list(uid) select customer_id from ".$tab_names." where `status` in ('1','3','4','5','6','90','97','111') and order_prop in (0,1) and pay_time>='".$stime."'
        and pay_time<='".$etime."' group by customer_id";
                $this->db->query($sql);
            }            
        }
            
        $mall_sql ="insert ignore into mall_order_user_monthly_list(uid) select customer_id from mall_orders where `status` = 1 and
            order_pay_time>='".$stime."' and order_pay_time<='".$etime."' group by customer_id";
        $this->db->query($mall_sql);
    
        $one_sql ="insert ignore into one_direct_order_user_list(uid) select customer_id from one_direct_orders where status = 1 and score_year_month='".$u_time."' group by customer_id";
        $this->db->query($one_sql);
    
        $wal_sql ="insert ignore into walmart_order_user_list(uid) select customer_id from walmart_orders where status = 1 and score_year_month='".$u_time."'  group by customer_id;";
        $this->db->query($wal_sql);
    
        $this->m_log->createCronLog('[Success]统计昨天下单会员队列列表成功。');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]统计昨天下单会员队列列表失败。');
        }
    }
    
 
    
    
    /**
     * 修复会员业绩 
     * @param 订单表类型  $order_table
     * @param 修复业绩类型（0为上个月，1为本月） $s_time
     */
    public function modify_user_monthly_option($order_table,$s_time)
    {       
        
        $this->load->model('o_cron');
        $this->load->model('o_pcntl');
        $this->load->model('tb_user_list_monthly_modify_logs');        
        $pages = 5000;        
        ignore_user_abort(TRUE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $useStart = memory_get_usage();        
        
        if(0==$s_time)
        {
            //修复上个月下单用户业绩
            $month_time="";
        }
        else 
        {
            if(1==(int)date('d'))
            {
                $month_time = date('Ym', strtotime(date('Ymd'))-15*24*3600);
            }
            else 
            {
                $month_time = date('Ym');
            }
        }
        
        switch($order_table)
        {
            case 1:
                $table_name = "trade_order_user_monthly_list";
                break;
            case 2:
                $table_name = "mall_order_user_monthly_list";
                break;
            case 3:
                $table_name = "one_direct_order_user_list";
                break;
            case 4:
                $table_name = "walmart_order_user_list";
                break;
        }
        
        
        $trade_sql = "select count(*) as total from ".$table_name;        
        $trade_query = $this->db->query($trade_sql)->row_array()['total'];
        $trade_page = (int)$trade_query>=$pages?($trade_query/$pages):1;        
        
        //修复零售订单
        if($trade_query!=0)
        {            
            for($index_page = 1;$index_page <= $trade_page;$index_page++)
            {

                $this->o_pcntl->tps_pcntl_wait('$this->tb_users_store_sale_info_monthly->modify_user_monthly_fun(\''.$table_name.'\',\''.$month_time.'\',\''.$index_page.'\',\''.$pages.'\');');//用子进程处理每一页

            }
        }        
    }
    
    /**
     * 修复业绩
     * @param unknown $table_name
     * @param unknown $index_page
     * @param unknown $pages
     */
    public function modify_user_monthly_fun($table_name,$monthtime,$index_page,$pages)
    {
        $this->load->model('o_cron');
        $this->load->model('m_log');
        $this->load->model('tb_grant_bonus_user_logs');
        $this->db->trans_begin();
        $trade_info_sql = "select uid from ".$table_name." limit ".($index_page-1)*$pages.",".$pages;
        $trade_info_query = $this->db->query($trade_info_sql);
        $trade_info_value = $trade_info_query->result_array();
        if(!empty($trade_info_value))
        {
            $user_data = array
            (
                'uid' => 0,
                'proportion' => 0,
                'share_point' => 0,
                'amount' => 0,
                'bonus' => 0,
                'type' => 1,
                'item_type' => 98
            );
            foreach($trade_info_value as $sult)
            {
                //修复会员上个月业绩
                $user_data['uid'] = $sult['uid'];
                $this->o_cron->count_monthly($sult['uid'],$monthtime);
                //$this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
            }
        }
       
        echo "第".$index_page."页执行状态：";
        var_dump($this->db->trans_status());
        
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 修复业绩,page:'.$index_page);
        } else {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[fail] 修复业绩，page:'.$index_page." 修复总数: ".($index_page*$pages)."个");
        }
    }
    
    
    /**
     * 删除掉存在的，留下剩余的
     * @param unknown $order_table
     */
    public function get_daily_bonus_qualified_list($order_table)
    {
       
        ignore_user_abort(TRUE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $useStart = memory_get_usage();
        
        $pages = 5000;
        
        switch($order_table)
        {
            case 1:
                //每天全球日分红
                $table = "daily_bonus_qualified_list";
                $table_name = "user_daily_bonus_qualified_list";
                break;
            case 2:
                //138日分红
                $table = "user_qualified_for_138";
                $table_name = "user_138_bonus_qualified_list";
                break;
            case 3:
                //每周团队销售分红
                $table = "week_share_qualified_list";
                $table_name = "user_week_bonus_qualified_list";
                break;    
            case 4:
                //每周领导对等奖
                $table = "week_leader_members";
                $table_name = "user_week_leader_member_list";
                break;
            case 5:
                //每月团队组织分红
                $table = "month_group_share_list";
                $table_name = "user_month_group_share_list";
                break;
            case 6:
                //每月领导分红 高级市场主管
                $table = "month_leader_bonus";
                $table_name = "user_month_leader_bonus_list";
                break;
            case 7:
                //每月领导分红 市场总监
                $table = "month_top_leader_bonus";
                $table_name = "user_month_top_leader_bonus_list";
                break;
            case 8:
                //每月领导分红 全球副总裁
                $table = "month_leader_bonus_lv5";
                $table_name = "user_month_leader_bonus_lv5_list";
                break;
        }
        
        
        $trade_sql = "select count(*) as total from ".$table;
        $trade_query = $this->db->query($trade_sql)->row_array()['total'];
        $trade_page = $trade_query!=0?round(($trade_query/$pages)):1;
         
        //修复零售订单
        if($trade_query!=0)
        {
            for($index_page = 1;$index_page <= $trade_page;$index_page++)
            {
                switch($order_table)
                {                    
                    case 2:
                        $trade_info_sql = "select user_id as uid from ".$table." limit ".($index_page-1)*$pages.",".$pages;
                        break;
                    default:
                        $trade_info_sql = "select uid from ".$table." limit ".($index_page-1)*$pages.",".$pages;;
                        break;
                }
               
                $trade_info_query = $this->db->query($trade_info_sql);
                $trade_info_value = $trade_info_query->result_array();
                if(!empty($trade_info_value))
                {
                    foreach($trade_info_value as $sult)
                    {
                        $del_sql = "delete from ".$table_name." where ";
                        switch($order_table)
                        {
                            case 2:
                                $del_sql .= " user_id = ".$sult['uid'];
                                break;
                            default:
                                $del_sql .= " uid = ".$sult['uid'];
                                break;
                        }                        
                        $this->db->query($del_sql);                        
                    }
                }
            }
        }
    }
    
    /**
     * @author: haiya 获取用户的业绩
     * @date: 2017年7月5日
     * @param: @param int $user_id
     * @reurn: return_type
     */
    public function find_by_user_id($searchData, $size = 12) {
        if($searchData["uid"]){
            $this->db->where('uid', $searchData["uid"]);
        }
        if($searchData["year_month"]){
            $this->db->where('year_month', $searchData["year_month"]);
        }
        if(isset($searchData['start_year_month']) && $searchData['end_year_month']){
            $this->db->where(array("year_month >="=>$searchData['start_year_month'],"year_month <="=>$searchData['end_year_month']));
        }
        if($searchData["page"]){
            $this->db->limit($size, max($searchData["page"] - 1, 0) * $size);
        }
    	return $this->db->order_by('year_month', 'DESC')->get($this->table_name)->result_array();
    }
     
    /**
     * @author: haiya 获取用户的业绩的数量
     * @param type $searchData
     * @return type
     */
    public function find_by_user_id_Rows($searchData) {
        if($searchData["uid"]){
            $this->db->where('uid', $searchData["uid"]);
        }
        if($searchData["year_month"]){
            $this->db->where('year_month', $searchData["year_month"]);
        }
        if(isset($searchData['start_year_month']) && $searchData['end_year_month']){
            $this->db->where(array("year_month >="=>$searchData['start_year_month'],"year_month <="=>$searchData['end_year_month']));
        }
    	$data = $this->db->select("count(uid) as count")->get($this->table_name)->row_array();
        return isset($data["count"]) ? $data["count"] : 0;
    }
    
    /**
     * 获取用户业绩
     * @param unknown $uid
     */
    public function get_user_monthly($uid,$limit)
    {
        $sql = "select * from users_store_sale_info_monthly where uid = ".$uid." order by `year_month` desc limit ".$limit;
        $query = $this->db->query($sql)->result_array();
        return $query;
    }
    
    /**
     * 获取用户业绩
     * @param unknown $uid
     */
    public function get_user_monthly_by_time($uid,$year_month)
    {
        $sql = "select * from users_store_sale_info_monthly where uid = ".$uid." and  `year_month`='".$year_month."' ";
        $query = $this->db->query($sql)->row_array();
        return $query;
    }
}