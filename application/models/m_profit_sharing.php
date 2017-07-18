<?php

class m_profit_sharing extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 创建分红记录 todo@terry
     */
    public function createLog() {
        for ($i = 0; $i < 1; $i++) {
            $data = array(
                'uid' => 1381234603,
                'type' => 1,
                'money' => 450 + rand(1, 50),
                'create_time' => strtotime('2015-02-10 00:00:01 -' . $i . ' month'),
            );
            $this->db->insert('profit_sharing_logs', $data);
        }
    }

    /**
     * 审核并筛选出本月可以参加《每月杰出店铺分红奖》的会员
     * @author Terry Lu
     */
    public function getCurMonthMonthProfitSharMem(){

        $this->db->trans_start();
        $this->db->query("delete from month_sharing_members"); //清空上个月的参加《每月杰出店铺分红奖》会员列表。

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        $thisMonth = date('Y-m-01');
        $resMemFinal = $this->db->query("select a.id id,a.profit_sharing_point profit_sharing_point from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank<4 and a.sale_rank>0 and b.year_month='".$lastYearMonth."' and b.sale_amount>=10000 group by a.id")->result_array();

        foreach ($resMemFinal as $v) {

            $reward_total = $this->db->query("select sum(a.point) reward_total from users_sharing_point_reward a where a.uid=".$v['id']." and a.end_time>'".date('Y-m-d')."'")->row_object()->reward_total;
            $this->db->insert('month_sharing_members',array(
                'uid'=>$v['id'],
                'sharing_point'=>$v['profit_sharing_point']+$reward_total,
            ));
        }
        
        $this->m_log->createCronLog('[Success]筛选参加《每月杰出店铺分红奖》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月杰出店铺分红奖》的会员。');
        }
    }


    /**
     * 审核并筛选出本月可以参加《每月杰出店铺分红奖》的会员(临时过渡)
     * @author Terry Lu
     */
    public function getCurMonthMonthProfitSharMemTemp(){

        $this->db->trans_start();
        $this->db->query("delete from month_sharing_members"); //清空上个月的参加《每月杰出店铺分红奖》会员列表。

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        $curYearMonth = date('Ym');
            
        $resMemFinal = $this->db->query("select a.id id,a.sale_rank sale_rank,a.profit_sharing_point profit_sharing_point,sum(b.orders_num) total_orders_num,sum(b.sale_amount) total_sale_amount from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank<4 and b.year_month in('".$lastYearMonth."','".$curYearMonth."') group by a.id")->result_array();

        foreach ($resMemFinal as $v) {
            if( ($v['sale_rank']>0 && $v['total_orders_num']>=3 && $v['total_sale_amount']>=7500) || ($v['total_orders_num']>=15 && $v['total_sale_amount']>=37500) ){

                $reward_total = $this->db->query("select sum(a.point) reward_total from users_sharing_point_reward a where a.uid=".$v['id']." and a.end_time>".date('Y-m-d'))->row_object()->reward_total;
                $this->db->insert('month_sharing_members',array(
                    'uid'=>$v['id'],
                    'sharing_point'=>$v['profit_sharing_point']+$reward_total,
                ));
            }
        }
        
        $this->m_log->createCronLog('[Success]筛选参加《每月杰出店铺分红奖》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月杰出店铺分红奖》的会员。');
        }
    }
    
    /**
     * 审核并筛选出本月可以参加周领导对等奖的会员。
     * @author Terry
     */
    public function getCurMonthWeekLeaderMem(){
        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        $this->db->trans_start();
        $this->db->query("delete from week_leader_members"); //清空上个月参加周领导对等奖的会员列表。

        $num = 20000;
        $max_id = 0;
        for ($i=0; $i<1000; $i++) {
            $sql = "select a.id from users a left join users_store_sale_info_monthly b";
            $sql .= " on a.id=b.uid";
            $sql .= " where a.id=b.uid and a.user_rank=1 and a.sale_rank>1";
            $sql .= " and a.status=1 and b.`year_month`=".$lastYearMonth." and b.sale_amount>=10000";
            $sql .= " and a.id>" . $max_id;
            $sql .= " group by a.id order by a.id limit ".$num;
            $resMemFinal = $this->db->query($sql)->result_array();
            if (empty($resMemFinal)) {
                break;
            }
            $insert = "";
            foreach ($resMemFinal as $v) {
                $max_id = $v['id'];
                $insert .= "(".$max_id."),";
            }
            $insert = "insert into week_leader_members(uid) values ".substr($insert,0,-1);
            $this->db->query($insert);
        }
        
        $this->m_log->createCronLog('[Success]筛选参加《每周领导对等奖》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每周领导对等奖》的会员。');
        }
    }
    
    /**
     * 审核并筛选出本月可以参加周领导对等奖的会员。 修复业绩后，重新生成队列
     * @author Terry
     */
    public function getCurMonthWeekLeaderMem_new(){
    
        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
    
        $this->db->trans_start();
        $this->db->query("delete from user_week_leader_member_list"); //清空上个月参加周领导对等奖的会员列表。
    
        $resMemFinal = $this->db->query("select a.id id from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank=1 and a.sale_rank>=2 and b.year_month=$lastYearMonth and b.sale_amount>=10000")->result_array();
    
        foreach ($resMemFinal as $v) {
            $this->db->insert('user_week_leader_member_list',array(
                'uid'=>$v['id']
            ));
        }
        $this->m_log->createCronLog('[Success]筛选参加《每周领导对等奖》的会员。');
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每周领导对等奖》的会员。');
        }
    }

    /*筛选出本月可以参加月领袖分红奖的会员(市场总监)*/
    public function getCurMonthTopLeaderMem(){

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        $curYearMonth = date('Ym');
        $this->db->trans_start();
        $this->db->query("delete from month_top_leader_bonus"); //清空上个月的会员列表

        $resMemFinal = $this->db->query("select a.id id,a.profit_sharing_point profit_sharing_point from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank=1 and a.sale_rank=4 and b.year_month=$lastYearMonth and b.sale_amount>=10000")->result_array();

        foreach ($resMemFinal as $v) {

            $reward_total = $this->db->query("select sum(a.point) reward_total from users_sharing_point_reward a where a.uid=".$v['id']." and a.end_time>".date('Y-m-d'))->row_object()->reward_total;
            $this->db->insert('month_top_leader_bonus',array(
                'uid'=>$v['id'],
                'sharing_point'=>$v['profit_sharing_point']+$reward_total,
            ));
        }
        $this->m_log->createCronLog('[Success]筛选参加《每月领导分红奖－市场总监》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月领导分红奖－市场总监》的会员。');
        }
    }

    /*筛选出本月可以参加月领袖分红奖的会员（New）*/
    public function getCurMonthTopLeaderMemNew(){

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        $this->db->trans_start();
        $this->db->query("delete from month_top_leader_bonus"); //清空上个月的会员列表
        // $resMemFinal = $this->db->query("select a.id id,a.profit_sharing_point profit_sharing_point from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank=1 and a.store_qualified=1 and a.sale_rank>=4 and b.year_month=$lastYearMonth and b.`orders_num`>=10 and b.sale_amount>=25000")->result_array();
        $resMemFinal = $this->db->query("select a.id id from users a where a.user_rank=1 and a.sale_rank=4")->result_array();
        foreach ($resMemFinal as $v) {
            $this->db->insert('month_top_leader_bonus',array(
                'uid'=>$v['id'],
            ));
        }
        $this->m_log->createCronLog('[Success]筛选参加《每月领导分红奖－市场总监》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月领袖分红奖－市场总监》的会员。');
        }
    }

    /*筛选出本月可以参加月领导分红奖的会员（高级市场主管）*/
    public function getCurMonthLeaderMem(){

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));
        
        $this->db->trans_start();
        $this->db->query("delete from month_leader_bonus"); //清空上个月的会员列表

        $resMemFinal = $this->db->query("select a.id id,a.profit_sharing_point profit_sharing_point from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank=1 and a.sale_rank=3 and b.year_month=$lastYearMonth and b.sale_amount>=7500")->result_array();

        foreach ($resMemFinal as $v) {

            $reward_total = $this->db->query("select sum(a.point) reward_total from users_sharing_point_reward a where a.uid=".$v['id']." and a.end_time>".date('Y-m-d'))->row_object()->reward_total;
            $this->db->insert('month_leader_bonus',array(
                'uid'=>$v['id'],
                'sharing_point'=>$v['profit_sharing_point']+$reward_total,
            ));
        }
        $this->m_log->createCronLog('[Success]筛选参加《每月领导分红奖－高级市场主管》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月领导分红奖－高级市场主管》的会员。');
        }
    }

    /*筛选出本月可以参加月领导分红奖的会员-全球销售副总裁*/
    public function getCurMonth5LeaderMem(){

        $lastYearMonth = date('Ym', strtotime('-1 month', time()));

        $this->db->trans_start();
        $this->db->query("delete from month_leader_bonus_lv5"); //清空上个月的会员列表

        $resMemFinal = $this->db->query("select a.id id,a.profit_sharing_point profit_sharing_point from users a left join users_store_sale_info_monthly b on a.id=b.uid where a.user_rank=1 and a.sale_rank=5 and b.year_month=$lastYearMonth and b.sale_amount>=15000")->result_array();


        foreach ($resMemFinal as $v) {

            $reward_total = $this->db->query("select sum(a.point) reward_total from users_sharing_point_reward a where a.uid=".$v['id']." and a.end_time>".date('Y-m-d'))->row_object()->reward_total;
            $this->db->insert('month_leader_bonus_lv5',array(
                'uid'=>$v['id'],
                'sharing_point'=>$v['profit_sharing_point']+$reward_total,
            ));
        }
        $this->m_log->createCronLog('[Success]筛选参加《每月领导分红奖－全球销售副总裁》的会员。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]筛选参加《每月领导分红奖－全球销售副总裁》的会员。');
        }
    }
    
    /**
     * 每月杰出店铺分红
     * @author Terry Lu
     */
    public function monthSharing(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        // $sharingAmount = tps_money_format($lastMonthProfit*0.1);
        $sharingAmount = tps_money_format($lastMonthProfit*0.0707);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润为0，无法发放每月店铺分红。');
            return 0;
        }

        $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_sharing_members a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
        if($totalPoint<=0){
            return 0;
        }

        $totalNum = $this->db->query("select count(*) as totalNum from month_sharing_members")->row_object()->totalNum;

        $sharingAmount1 = $sharingAmount * 0.2;//用来均摊的利润
        $avgAmount = tps_money_format($sharingAmount1/$totalNum);
        $sharingAmount2 = $sharingAmount-$sharingAmount1;

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_sharing_members a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){
            
            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint==0){
                continue;
            }
            $commission_amount = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount2) + $avgAmount;

            if(config_item('leader_bonus_test')){
                $this->db->insert('ly_temp',array(
                    'uid'=>$item['uid'],
                    'commission'=>$commission_amount,
                    'level'=>8
                ));
            }else{
                $this->assignSharingCommission($item['uid'], $commission_amount,8);
            }
            
        }
        $this->m_log->createCronLog('[Success]发放每月杰出店铺分红。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月杰出店铺分红。');
        }
    }

    /**
     * 每月领导分红奖
     * @author Terry Lu
     */
    public function monthLeaderSharing(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        $sharingAmount = tps_money_format($lastMonthProfit*0.03);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润的3%为0，无法发放每月领导分红奖。');
            return 0;
        }

        $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
        if($totalPoint<=0){
            return 0;
        }

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){
            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint==0){
                continue;
            }
            $commission_amount = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount);
            $this->assignSharingCommission($item['uid'], $commission_amount,23);
        }
        $this->m_log->createCronLog('[Success]发放每月领袖分红奖。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月领袖分红奖。');
        }
    }

    /**
     * 每月领导分红奖(new)
     * @author Terry Lu
     */
    public function monthLeaderSharingNew(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        // $sharingAmount = tps_money_format($lastMonthProfit*0.06);
        $sharingAmount = tps_money_format($lastMonthProfit*0.052);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润的3%为0，无法发放每月领导分红奖。');
            return 0;
        }

        $sharingAmount1 = $sharingAmount*0.8;//用来均摊的利润
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

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){

            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint>0){
                $commission_amount2 = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount2);
            }else{
                $commission_amount2 = 0;
            }

            if(config_item('leader_bonus_test')){
                $this->db->insert('ly_temp',array(
                    'uid'=>$item['uid'],
                    'commission'=>$commission_amount1+$commission_amount2,
                    'level'=>3
                ));
            }else{
                $this->assignSharingCommission($item['uid'], $commission_amount1+$commission_amount2,23);
            }
            
        }
        $this->m_log->createCronLog('[Success]发放每月领导分红奖－高级市场主管。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月领导分红奖－高级市场主管。');
        }
    }

    /**
     * 每月领袖分红奖
     * @author Terry Lu
     */
    public function monthTopLeaderSharing(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        $sharingAmount = tps_money_format($lastMonthProfit*0.01);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润的1%为0，无法发放每月领袖分红奖。');
            return 0;
        }

        $totalPoint = $this->db->query("select sum(a.sharing_point) as totalPoint from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->row_object()->totalPoint;
        if($totalPoint<=0){
            return 0;
        }

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){
            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint==0){
                continue;
            }
            $commission_amount = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount);
            $this->assignSharingCommission($item['uid'], $commission_amount,19);
        }
        $this->m_log->createCronLog('[Success]发放每月领袖分红奖。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月领袖分红奖。');
        }
    }

    /**
     * 每月领袖分红奖(new)/
     * @author Terry Lu
     */
    public function monthTopLeaderSharingNew(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        // $sharingAmount = tps_money_format($lastMonthProfit*0.025);
        $sharingAmount = tps_money_format($lastMonthProfit*0.02141);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润的1%为0，无法发放每月领导分红奖－市场总监。');
            return 0;
        }

        $sharingAmount1 = $sharingAmount*0.8;//用来均摊的利润
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

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_top_leader_bonus a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){

            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint>0){
                $commission_amount2 = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount2);
            }else{
                $commission_amount2 = 0;
            }

            if(config_item('leader_bonus_test')){
                $this->db->insert('ly_temp',array(
                    'uid'=>$item['uid'],
                    'commission'=>$commission_amount1+$commission_amount2,
                    'level'=>4
                ));
            }else{
                $this->assignSharingCommission($item['uid'], $commission_amount1+$commission_amount2,23);
            }

        }
        $this->m_log->createCronLog('[Success]发放每月领导分红奖－市场总监。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月领导分红奖－市场总监。');
        }
    }

    /**
     * 每月领导分红奖(全球销售副总裁)
     * @author Terry Lu
     */
    public function monthLeader5Sharing(){

        $this->load->model('m_profit');

        $lastMonthProfit = $this->m_profit->getCompanyProfitLastMonth();/*统计上月全球销售利润*/
        // $sharingAmount = tps_money_format($lastMonthProfit*0.015);
        $sharingAmount = tps_money_format($lastMonthProfit*0.0112);
        if(!$sharingAmount){
            $this->m_log->createCronLog('上月公司利润的0.5%为0，无法发放每月领导分红奖－全球销售副总裁。');
            return 0;
        }

        $sharingAmount1 = $sharingAmount*0.8;//用来均摊的利润
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

        $eligibilityUserInfoList = $this->db->query("select a.uid uid,a.sharing_point sharing_point from month_leader_bonus_lv5 a left join users b on a.uid=b.id where b.store_qualified=1")->result_array();
        $this->db->trans_start();
        foreach($eligibilityUserInfoList as $item){

            $userTotalPoint = $item['sharing_point'];
            if($userTotalPoint>0){
                $commission_amount2 = tps_money_format($userTotalPoint/$totalPoint*$sharingAmount2);
            }else{
                $commission_amount2 = 0;
            }

            if(config_item('leader_bonus_test')){
                $this->db->insert('ly_temp',array(
                    'uid'=>$item['uid'],
                    'commission'=>$commission_amount1+$commission_amount2,
                    'level'=>5
                ));
            }else{
                $this->assignSharingCommission($item['uid'], $commission_amount1+$commission_amount2,23);
            }

        }
        $this->m_log->createCronLog('[Success]发放每月领导分红奖－全球销售副总裁。');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]发放每月领导分红奖－全球销售副总裁。');
        }
    }
    

    


    /*单个处理138分红*/
    public function assign138Commission($uid,$commission_amount,$create_time=''){

        $this->load->model('o_cash_account');

        $commission_amount_int = tps_int_format($commission_amount*100);
        $create_time = $create_time?$create_time:date("Y-m-d H:i:s", time());
        if($commission_amount_int<=0){
            $this->o_cash_account->createCashAccountLog(array(
                'uid'=>$uid,
                'item_type'=>REWARD_2,
                'amount'=>0,
                'create_time'=>$create_time,
            ));
            return false;
        }

        $this->o_cash_account->createCashAccountLog(array(
            'uid'=>$uid,
            'item_type'=>REWARD_2,
            'amount'=>$commission_amount_int,
            'create_time'=>$create_time,
        ));

        /*统计用户奖金*/
        $this->load->model('tb_user_comm_stat');
        $this->tb_user_comm_stat->updateUserCommStat($uid,2,$commission_amount_int);

        /* 佣金自动转分红点 */
        $rate = $this->getProportion($uid, 'sale_commissions_proportion') / 100;
        $commissionToPoint = 0;
        if ($rate > 0) {
            $commissionToPoint = tps_money_format($commission_amount * $rate);
            if($commissionToPoint>=0.01){
                $this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_force', 'profit_sharing_point_from_force+' . $commissionToPoint, FALSE)->update('users');

                $comm_id = $this->o_cash_account->createCashAccountLog(array(
                    'uid'=>$uid,
                    'item_type'=>17,
                    'amount'=>tps_int_format(-1*$commissionToPoint*100),
                    'create_time'=>$create_time,
                ));
                $this->m_profit_sharing->createPointAddLog(array(
                    'uid' => $uid,
                    'commission_id' => $comm_id,
                    'add_source' => 2,
                    'money' => $commissionToPoint,
                    'point' => $commissionToPoint
                ));
            }
        }
        /***累加amount、 personal_commission字段值***/
        $this->db->where('id', $uid)
            ->set('amount', 'amount+' . ($commission_amount - $commissionToPoint), FALSE)
            ->set('company_commission', 'company_commission+' . $commission_amount, FALSE)->update('users');
    }

    /**
     * 单个会员分红
     * @param type $uid
     * @param type $commission_amount
     */
    public function assignSharingCommission($uid, $commission_amount,$commission_type=6,$create_time='') {

        $this->load->model('o_cash_account');

        $commission_amount_int = tps_int_format($commission_amount*100);

        if($commission_amount_int<=0){
            $this->o_cash_account->createCashAccountLog(array(
                'uid'=>$uid,
                'item_type'=>$commission_type,
                'amount'=>0,
                'create_time'=>$create_time,
            ));
            return false;
        }

        $this->o_cash_account->createCashAccountLog(array(
            'uid'=>$uid,
            'item_type'=>$commission_type,
            'amount'=>$commission_amount_int,
            'create_time'=>$create_time,
        ));

        /*统计用户奖金*/
        $this->load->model('tb_user_comm_stat');
        $this->tb_user_comm_stat->updateUserCommStat($uid,$commission_type,$commission_amount_int);

        /*佣金自动转分红点*/
        $rate = $this->getProportion($uid,'sale_commissions_proportion')/100;
        $commissionToPoint = 0;
        if($rate>0){
            $commissionToPoint = tps_money_format($commission_amount*$rate);
            if($commissionToPoint>=0.01){
                $this->db->where('id', $uid)->set('profit_sharing_point','profit_sharing_point+'.$commissionToPoint,FALSE)->set('profit_sharing_point_from_sharing','profit_sharing_point_from_sharing+'.$commissionToPoint,FALSE)->update('users');

                $comm_id = $this->o_cash_account->createCashAccountLog(array(
                    'uid'=>$uid,
                    'item_type'=>17,
                    'amount'=>tps_int_format(-1*$commissionToPoint*100),
                    'create_time'=>$create_time,
                ));

                $dataPointLog = array(
                    'uid' => $uid,
                    'commission_id' => $comm_id,
                    'add_source' => 3,
                    'money' => $commissionToPoint,
                    'point' => $commissionToPoint
                );
                if($create_time){
                    $dataPointLog['create_time'] = strtotime($create_time);
                }
                $this->m_profit_sharing->createPointAddLog($dataPointLog);
            }else{
                $commissionToPoint = 0;
            }
        }
        
        if($commission_type==7){
            $userAmount = 'amount_weekly_Leader_comm';
        }elseif($commission_type==8){
            $userAmount = 'amount_monthly_leader_comm';
        }else{
            $userAmount = 'amount_profit_sharing_comm';
        }
        $this->db->where('id', $uid)->set('amount', 'amount+' . ($commission_amount-$commissionToPoint), FALSE)->set($userAmount, $userAmount.'+' . $commission_amount, FALSE)->update('users');
    }

    private function __getWeekTimePeriod($last=0){

        $lastWeekStartTimestamp = $last==0?strtotime('-1 monday', time()):strtotime('-1 monday', time())-3600*24*7;
        $lastWeekEndTimestamp = $lastWeekStartTimestamp+3600*24*7;
        return array(
            'start'=>date('Y-m-d H:i:s',$lastWeekStartTimestamp),
            'startTimestamp'=>$lastWeekStartTimestamp,
            'end'=>date('Y-m-d H:i:s',$lastWeekEndTimestamp),
            'endTimestamp'=>$lastWeekEndTimestamp,
        );
    }

    /***
     * 周时间计算 新
     * @param number $last
     * @return multitype:string number
     */
    private function __getWeekTimePeriodNew($last=0,$ctime){
    
        $lastWeekStartTimestamp = $last==0?strtotime('-1 monday', strtotime($ctime)):strtotime('-1 monday', strtotime($ctime))-3600*24*7;
        $lastWeekEndTimestamp = $lastWeekStartTimestamp+3600*24*7;
        return array(
            'start'=>date('Y-m-d H:i:s',$lastWeekStartTimestamp),
            'startTimestamp'=>$lastWeekStartTimestamp,
            'end'=>date('Y-m-d H:i:s',$lastWeekEndTimestamp),
            'endTimestamp'=>$lastWeekEndTimestamp,
        );
    }
    

    
    
    
       
    /***
     * 获取用户amount
     * @param 日期 $s_time
     * @param 发奖类型  $item_type
     */
    // public function getUserSalesEliteAmount($s_time,$item_type)
    // {
    //     $tb_name = "cash_account_log_".date("Ym", strtotime($s_time));
    //     $sql = "SELECT uid,amount,create_time FROM ".$tb_name." WHERE item_type = ".$item_type." AND DATE_FORMAT(create_time,'%Y-%m-%d')=DATE_FORMAT('".$s_time."','%Y-%m-%d') AND amount >0  limit 0,1";
       
    //     $user_query = $this->db->query($sql);
    //     $user_return_value = $user_query->row_array();
    //     return $user_return_value;
    // }
    
    /***
     * 获取用户每月的销售额
     * @param 用户id $uid
     * @param 日期 $s_time
     */
    public function getUserSalesInfo($uid,$s_time,$type,$next_month)
    {
        //套餐产品销售额统计
        if($type==1)
        {
            $times = date("Ym", strtotime($s_time));
        }
        else 
        {
            if(date("Ym", strtotime($s_time)) < date('Ym'))
            {               
                
                    $times = date("Ym", strtotime("+1 months", strtotime($s_time)));
            }
            else
            {          
                if($next_month==1)
                {
                    $times = date("Ym", strtotime("-1 months", strtotime($s_time)));
                }
                else
                {
                    $times = date("Ym", strtotime($s_time));
                }                
            }
        }        
        
        $sql = "SELECT pro_set_amount FROM stat_intr_mem_month stat WHERE uid = ".$uid." AND stat.year_month = '".$times."'";    
        
        $user_query = $this->db->query($sql);
        $user_return_value = $user_query->row_array();
        
        $sale_sql = "SELECT sale_amount FROM users_store_sale_info_monthly user_store WHERE uid = ".$uid." AND user_store.year_month = '".$times."'";      
       
        $sale_query = $this->db->query($sale_sql);
        $user_sale_value = $sale_query->row_array();
       
        return $user_return_value['pro_set_amount']+$user_sale_value['sale_amount'];        
    }
    
    
    /**
     * 创建分红点新增记录。
     */
    public function createPointAddLog($data) {
        if(!isset($data['create_time'])){
            $data['create_time'] = time();
        }
        $this->db->insert('profit_sharing_point_add_log', $data);
    }
    
    /**
     * 创建现金池转月费池记录。
     * @author Terry Lu
     */
    public function createCashToMonthFeeLog($data) {
        $this->db->insert('cash_to_month_fee_logs', $data);
    }
    
    /**
     * 创建分红点轉出记录。
     */
    public function createPointReduceLog($data) {
        if(!isset($data['create_time'])){
            $data['create_time'] = time();
        }
        $this->db->insert('profit_sharing_point_reduce_log', $data);
    }

    public function getUserSharingLogsTotalRows($filter) {
        $this->db->from('profit_sharing_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->get()->num_rows();
    }

    public function getUserSharingLogs($filter, $page = false, $perPage = 10) {
        $this->db->from('profit_sharing_logs');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }
    
    public function getPointToMoneyLogs($filter, $page = false, $perPage = 10) {
        $this->db->from('profit_sharing_point_reduce_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }
    
    public function getUserPointToMoneyLogsTotalRows($filter) {
        $this->db->from('profit_sharing_point_reduce_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->count_all_results();
    }

    public function getUserSharingPointAddLogTotalRows($filter) {
        $this->db->from('profit_sharing_point_add_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->count_all_results();
    }

    public function getUserSharingPointAddLog($filter, $page = 1, $perPage = 10) {
        $this->db->from('profit_sharing_point_add_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', strtotime($v));
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', strtotime($v));
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }

    public function saveSharingPointProportion($dataProfitSharingPointProportion) {
        return $this->db->replace('profit_sharing_point_proportion', $dataProfitSharingPointProportion);
    }

    public function getProportion($uid, $proportion_type) {
        $arrProportionType = array(
            'sale_commissions_proportion' => 1,
            'forced_matrix_proportion' => 2,
            'profit_sharing_proportion' => 3,
        );
        // $res = $this->db->from('profit_sharing_point_proportion')->where('uid', $uid)->where('proportion_type', $arrProportionType[$proportion_type])->get()->row_object();
        $pres = $this->db->from('profit_sharing_point_proportion')->where('uid', $uid)->where('proportion_type', $arrProportionType[$proportion_type])->get();
        return $pres ? @$pres->row_object()->proportion : 0;
    }

	/** 現金池轉分紅點 update by john 2015 - 10- 16 */
    public function manuallyAddSharingPoint($userInfo, $money) {
        
        $money = $money>$userInfo['amount']?$userInfo['amount']:$money;
        $newAmount = $userInfo['amount']-$money;
        $newProfitSharingPointManually = $userInfo['profit_sharing_point_manually']+$money;
        $this->db->trans_start();
        $this->db->where('id', $userInfo['id'])->set('amount','amount-'.$money,FALSE)
            ->set('profit_sharing_point','profit_sharing_point+'.$money,FALSE)
            ->set('profit_sharing_point_manually','profit_sharing_point_manually+'.$money,FALSE)
            ->update('users');
        $this->createPointAddLog(array(
            'uid'=>$userInfo['id'],
            'add_source'=>4,
            'money'=>$money,
            'point'=>$money
        ));
		/** 金額變動記錄 17=》現金池轉分紅點 */
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($userInfo['id'],17,-1 * $money);
        $this->db->trans_complete();
        $this->load->model('M_user','m_user');
        $newProfitSharingPoint = $this->m_user->getTotalSharingPoint($userInfo['id']);
        return array('newAmount'=>tps_money_format($newAmount),'newProfitSharingPoint'=>tps_money_format($newProfitSharingPoint),'newProfitSharingPointManually'=>tps_money_format($newProfitSharingPointManually));
    }
    
    /**
     * 现金池转月费池
     * @param arr $userInfo
     * @param decimal $money
     * @return arr
     */
    public function cashToMonthFeePool($userInfo, $money){

        $this->load->model('m_coupons');
        $money = $money>$userInfo['amount']?$userInfo['amount']:$money;
        $newAmount = $userInfo['amount']-$money;
        $newMonthFee = $userInfo['month_fee_pool']+$money;
        $this->db->where('id', $userInfo['id'])
            ->set('amount','amount-'.$money,FALSE)
            ->set('month_fee_pool','month_fee_pool+'.$money,FALSE)
            ->update('users');

        $this->createCashToMonthFeeLog(array('uid'=>$userInfo['id'],'amount'=>$money));

        /** 金額變動記錄 14=》現金池轉月費 */
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($userInfo['id'],14,-1 * $money);

        /**  月費變動記錄 */
        $date_time = date('Y-m-d H:i:s');
        $monthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($userInfo['id']);
        $this->m_commission->monthFeeChangeLog($userInfo['id'],$userInfo['month_fee_pool'],$newMonthFee,$money,$date_time,2,$monthlyFeeCouponNum,$monthlyFeeCouponNum,0);

        return array('newAmount'=>tps_money_format($newAmount),'newMonthFee'=>tps_money_format($newMonthFee));
    }

    public function tranMoneyToMem($fromId,$tranToMemId,$tranToMemAmount){
        $this->load->model('o_cash_account');
        try {
            $this->db->trans_begin();
            $this->db->where('id', (int)$fromId)->where('amount >=',$tranToMemAmount*1)->set('amount','amount-'.$tranToMemAmount,FALSE)->update('users');
            $affectRows = $this->db->affected_rows();
//            var_dump($this->db->last_query());
            if($affectRows==1){

                $this->o_cash_account->createCashAccountLog(array(
                    'uid'=>$fromId,
                    'item_type'=>11,
                    'amount'=>-tps_int_format($tranToMemAmount*100),
                    'related_uid'=>$tranToMemId
                ));
//                var_dump($this->db->last_query());
                $this->db->where('id', (int)$tranToMemId)->set('amount','amount+'.$tranToMemAmount,FALSE)->update('users');
//                var_dump($this->db->last_query());
                $this->o_cash_account->createCashAccountLog(array(
                    'uid'=>$tranToMemId,
                    'item_type'=>11,
                    'amount'=>tps_int_format($tranToMemAmount*100),
                    'related_uid'=>$fromId
                ));
//                var_dump($this->db->last_query());
            }
            
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $return = TRUE;
            } else {
                throw new Exception('会员之间转帐失败.[error]');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $return = false;
        }

        return $return;
    }
    
    public function getMonthPointLimit($uid){

        $this->load->model('tb_users_profit_sharing_point_last_month');
        $this->load->model('tb_profit_sharing_point_reduce_log');

        $enablePoint = $this->tb_users_profit_sharing_point_last_month->getTransferableSharingpoint($uid);
        $curReducePoint = $this->tb_profit_sharing_point_reduce_log->getUserTransferedPointCurMonth($uid);
        return ($enablePoint>$curReducePoint)?tps_money_format($enablePoint-$curReducePoint):0;
    }
    
    public function sharingPointToMoney($userInfo, $point ,$is_admin = FAlSE) {
        
        if (!is_numeric($point) || $point <= 0 ||  get_decimal_places($point)>2) {
            return array('error_code'=>1013);
        }
        if($point>$userInfo['profit_sharing_point']){
            return array('error_code'=>1025);
        }
        $limitPoint = $this->getMonthPointLimit($userInfo['id']);
        if($point>$limitPoint && $is_admin === FALSE){
            return array('error_code'=>1014);
        }

        $newAmount = $userInfo['amount']+$point;
        $newProfitSharingPoint = $limitPoint-$point;
        $new_profit_sharing_point_to_money = $userInfo['profit_sharing_point_to_money']+$point;
        $this->db->trans_begin();
        $this->db->where('id', $userInfo['id'])
            ->set('amount','amount+'.$point,FALSE)
            ->set('profit_sharing_point','profit_sharing_point-'.$point,FALSE)
            ->set('profit_sharing_point_to_money','profit_sharing_point_to_money+'.$point,FALSE)->update('users');
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($userInfo['id'],18,$point); //分紅點轉佣金
        $this->createPointReduceLog(array(
            'uid'=>$userInfo['id'],
            'money'=>$point,
            'point'=>$point
        ));
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        return array('error_code'=>0,'newAmount'=>tps_money_format($newAmount),'newProfitSharingPoint'=>tps_money_format($newProfitSharingPoint),'newProfitSharingPointToMoney'=>tps_money_format($new_profit_sharing_point_to_money));
    }
	
	public function sharingPointToMoneyBase(){
        
        $newAmount = $userInfo['amount']+$point;
        $newProfitSharingPoint = $userInfo['profit_sharing_point']-$point;
        $new_profit_sharing_point_to_money = $userInfo['profit_sharing_point_to_money']+$point;
        $this->db->trans_start();
        $this->db->where('id', $userInfo['id'])->update('users', array(
            'amount' => $newAmount,
            'profit_sharing_point' => $newProfitSharingPoint,
            'profit_sharing_point_to_money' => $new_profit_sharing_point_to_money
        ));
        $this->createPointReduceLog(array(
            'uid'=>$userInfo['id'],
            'money'=>$point,
            'point'=>$point
        ));
        $this->db->trans_complete();
	}
    
}
