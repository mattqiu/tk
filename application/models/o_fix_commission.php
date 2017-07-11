<?php

/**
 * 修复奖金类
 * @author terry
 */
class o_fix_commission extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('tb_cash_account_log_x');
    }

    public function addUserToCommQualifyList($uid, $commItemId,$new_member_start='')
    {
        switch ($commItemId) {
            case 1:
                // 每月团队组织分红奖
                $this->load->model('tb_month_group_share_list');
                $this->tb_month_group_share_list->addInCurMonthGroupShareList($uid);
                break;
            case 2:
                //每日138见奖
                $this->load->model('tb_user_qualified_for_138');
                $this->load->model('tb_user_coordinates');
                $res138 = $this->tb_user_coordinates->is_exist_138($uid);
                if ($res138) {
                    $this->tb_user_qualified_for_138->addToList($uid, $res138['x'], $res138['y']);
                }
                break;
            case 6:
                //每天全球利润分红

                $this->load->model('tb_daily_bonus_qualified_list');
                $this->tb_daily_bonus_qualified_list->addToDailyQualiList($uid);
                break;
            case 7:
                // 每周领导对等奖
                $this->load->model('tb_week_leader_members');
                $this->tb_week_leader_members->addCurweekQualifiedUserList($uid);
                break;
            case 8:
                // 每月杰出店铺分红
                $this->load->model('m_user');
                $this->load->model('tb_month_sharing_members');
                $user_point = $this->m_user->getTotalSharingPoint($uid);
                $this->tb_month_sharing_members->addCurMonthQualifiedUserList($uid, $user_point);
                break;
            case 23:
                // 每月领导分红奖
                $this->load->model('m_user');
                $this->load->model('tb_month_top_leader_bonus');
                $this->load->model('tb_month_leader_bonus_lv5');
                $this->load->model('tb_month_leader_bonus');

                $user_info = $this->m_user->getUserByIdOrEmail($uid);
                $user_point = $this->m_user->getTotalSharingPoint($uid);

                switch ($user_info['sale_rank']) {
                    case 3:
                        // 领导分红- 高级市场主管
                        $this->tb_month_leader_bonus->addInCurMonthLeader($uid, $user_point);
                        break;
                    case 4:
                        // 领导分红-市场总监
                        $this->tb_month_top_leader_bonus->addInCurMonthTopLeader($uid, $user_point);
                        break;
                    case 5:
                        // 领导分红- 销售副总裁
                        $this->tb_month_leader_bonus_lv5->addInCurMonthLeaderLv5($uid, $user_point);
                        break;
                }
                break;
            case 24:
                //销售精英日分红
                $this->load->model('tb_daily_bonus_elite_qualified_list');
                $this->tb_daily_bonus_elite_qualified_list->addToEliteDailyQualiList($uid);
                break;
            case 25:
                // 每周团队分红
                $this->load->model('tb_week_leader_members');
                $this->tb_week_leader_members->addWeekTeamQualified($uid);
                break;
            case 26:
                // 新会员奖
                $this->load->model("o_bonus");
                $this->load->model('tb_new_member_bonus');
                $weight = $this->o_bonus->getUsersTotalWeightArr_new(array($uid));
                $this->tb_new_member_bonus->add_new_bonus($uid,$new_member_start,$weight[$uid]['total_money']);
                break;

            default:
                // code...
                break;
        }
    }

    /***
     * 获取某一时间段的日期列表
     * @param 开始时间  $startdate
     * @param 结束时间  $enddate
     * @return multitype:multitype:string
     */
    public function getDayM($startdate,$enddate)
    {
        $day_data = array();
        if(!empty($startdate) && !empty($enddate))
        {
            //先把两个日期转为时间戳
            $startdate=strtotime($startdate);
            $enddate=strtotime($enddate);
            for($i=$startdate,$index = 0; $i<=$enddate;$i+=(24*3600),$index++)
            {
                $day_data[$index] = date("Y-m-d",$i);
            }
        }

        return $day_data;
    }
    /***
     * 获取某一时间段的日期列表
     * @param 开始时间  $startdate
     * @param 结束时间  $enddate
     * @return multitype:multitype:string
     */
    public function getDay($startdate,$enddate)
    {
        $day_data = array();
        if(!empty($startdate) && !empty($enddate))
        {
            //先把两个日期转为时间戳
            $startdate=strtotime($startdate);
            $enddate=strtotime($enddate);
            for($i=$startdate,$index = 0; $i<=$enddate;$i+=(24*3600),$index++)
            {
                $day_data[$index] = date("Ymd",$i);
            }
        }

        return $day_data;
    }

    /**
     * 获取某一时间段内有几个星期一
     * @param unknown $startdate
     * @param unknown $enddate
     * @return multitype:string
     */
    function getWeek($startdate,$enddate)
    {
        //参数不能为空
        if(!empty($startdate) && !empty($enddate))
        {

            //先把两个日期转为时间戳
            $startTime=strtotime($startdate);
            $endTime=strtotime($enddate);
            //开始日期不能大于结束日期
            if($startTime<=$endTime){
                $end_date=strtotime("next monday",$endTime);
                if(date("w",$startTime)==1){
                    $start_date=$startTime;
                }
                else{
                    $start_date=strtotime("last monday",$startTime);
                }
                //计算时间差多少周
                $arr=array();
                $countweek=($end_date-$start_date)/(7*24*3600);
                for($i=0;$i<$countweek;$i++){
                    $sd=date("Y-m-d",$start_date);
                    $ed=strtotime("+ 6 days",$start_date);
                    $eed=date("Y-m-d",$ed);
                    if ($sd>=$startdate && $sd<=$enddate) {
                        $arr[$i]=$sd;
                    }

                    $start_date=strtotime("+ 1 day",$ed);
                }
                return $arr;
            }
        }
    }


    public function fixUserComm($uid, $commItemId, $start, $end)
    {
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_users');
        $this->load->model('m_user');
        $this->load->model('tb_week_leader_members');

        $userInfo = $this->tb_users->getUserInfo($uid, array(
            'user_rank',
            'profit_sharing_point'
        ));

        $likeUid = 0;
        $bei = 0;
        if ($commItemId == 6) {
            $this->load->model('tb_daily_bonus_qualified_list');
            $likeUid = $this->tb_daily_bonus_qualified_list->getLikeUid($userInfo['user_rank'], $userInfo['profit_sharing_point'], $uid);
        } elseif ($commItemId == 2) {
            $this->load->model('tb_user_qualified_for_138');
            $this->load->model('tb_user_coordinates');

            $y = $this->tb_user_coordinates->getYByUid($uid);
            if ($y) {
                $likeUid = $this->tb_user_qualified_for_138->getLikeUid($y, $uid);
            }
        }


        $curDateTimeStamp = strtotime($start);
        $num = 0;
        do {

            $curDate = date('Y-m-d', $curDateTimeStamp);

            $curDateTimeStamp += 86400;
            $num ++;

            if ($this->tb_cash_account_log_x->getCommByDate($uid, $commItemId, $curDate) || ! $likeUid) {

                continue;
            }

            $res = $this->tb_cash_account_log_x->getCommByDate($likeUid, $commItemId, $curDate);
            if (! $res) {
                continue;
            }
            switch ($commItemId) {
                //m by brady.wang 日分红采用新方法补发  start
//                case 6:
//                    $this->m_profit_sharing->assignSharingCommission($uid, $res['amount'] / 100, 6, $res['create_time']); // 发放日分红
//                    break;
                //m by brady.wang 日分红采用新方法补发 end
                case 2:
                    $this->m_profit_sharing->assign138Commission($uid, $res['amount'] / 100, $res['create_time']); // 发放138
                    break;
                /*
                case 24:
                    if ($bei) {
                        $this->m_profit_sharing->assignSharingCommission($uid, tps_money_format($res['amount'] * $bei / 100), 24, $res['create_time']);
                    }
                    break;
                */
                default:
                    // code...
                    break;
            }
        } while ($curDateTimeStamp <= strtotime($end) && $num < 32);

        $s_time = date("Ymd",strtotime($start));
        $m_time = date("Ym",strtotime($start));
        switch($commItemId)
        {
            case 25:
                // 每周团队销售分红奖
                if($s_time>=20170305)
                {
                    //使用2017年4月份新的补发机制
                    $this->week_ressiue_group_money_new($uid, $start, $end, $commItemId);
                }
                else
                {
                    //使用之前的补发
                    $this->week_ressiue_group_money($uid, $start, $end, $commItemId, 1);
                }
                break;
            case 7:
                // 每周领导对等奖
                //$this->RessiueWeekLeadAutoAmount($uid, $start, $end, $commItemId);
                $this->weekLeaderBonusAwardFix($uid, $start, $end, $commItemId);
                break;
            case 8:
                // 每月杰出店铺分红
                //$this->everyMoneyUserAmount($uid, $start, $end, $commItemId);
                $this->monthEminentStoreBonusAwardFix($uid, $start, $end, $commItemId);
                break;
            case 1:
                //每月团队组织分红奖
                if($m_time>=201704)
                {
                    $this->every_month_ressiue_group_money_new($uid,$start,$end,$commItemId);
                }
                else
                {
                    //$this->every_month_ressiue_group_money($uid,$start,$end,$commItemId,1);
                }
                break;
            case 6:
                //每天全球日分红
                $this->daily_bonus_reissue($uid,$start,$end,$commItemId);
                break;
            case 26:
                //新会员奖
                $this->new_member_bonus_reissue($uid,$start,$end,$commItemId);
                break;
            case 23:
                //每月领导分红奖
                if($m_time>=201704)
                {
                    //2017年4月份执行新的补发机制
                    $this->resMonthLeaderAmount_new($uid,$start,$end,$commItemId);
                }
                else
                {
                    //老的补发机制
                    //$this->resMonthLeaderAmount($uid,$start,$end,$commItemId,1);
                }
                break;
            case 24:
                $this->resMonthSalesEliteAmount($uid,$start,$end,$commItemId);
                break;
            default:
                // code
                break;
        }

    }




    /***
     * 补发奖统计计算
     * @param unknown $uid
     * @param unknown $commItemId
     * @param unknown $start
     * @param unknown $end
     */
    public function fixUserComm_check($uid, $commItemId, $start, $end)
    {
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_users');
        $this->load->model('m_user');
        $this->load->model('tb_week_leader_members');

        $amount_data = array();
        $userInfo = $this->tb_users->getUserInfo($uid, array(
            'user_rank',
            'profit_sharing_point'
        ));

        $s_time = date("Ymd",strtotime($start));
        $m_time = date("Ym",strtotime($start));
        switch($commItemId)
        {
            case 25:
                // 每周团队销售分红奖
                if($s_time>=20170305)
                {
                    //使用2017年4月份新的补发机制
                    $amount_data = $this->week_ressiue_group_money_new_check($uid, $start, $end, $commItemId);
                }
                break;
            case 7:
                // 每周领导对等奖
                $amount_data = $this->weekLeaderBonusAwardFix_check($uid, $start, $end, $commItemId);
                break;
            case 1:
                //每月团队组织分红奖
                if($m_time>=201704)
                {
                    $amount_data = $this->every_month_ressiue_group_money_new_check($uid,$start,$end,$commItemId);
                }
                break;
            case 6:
                //每天全球日分红
                $amount_data = $this->daily_bonus_reissue_check($uid,$start,$end,$commItemId);
                break;
            case 23:
                //每月领导分红奖
                if($m_time>=201704)
                {
                    //2017年4月份执行新的补发机制
                    $amount_data = $this->resMonthLeaderAmount_new_check($uid,$start,$end,$commItemId);
                }
                break;
        }

        return $amount_data;
    }



    /**
     * @author brady
     * @desc 全球日分红 单个用户佣金补发
     * @param $uid
     * @param $start
     * @param $end
     * @param $commItemId
     */
    public function daily_bonus_reissue($uid,$start,$end,$commItemId)
    {
        ini_set('memory_limit', '5000M');
        $this->config->load('config_bonus');
        $this->load->model("o_bonus");
        $this->load->model('tb_daily_bonus_qualified_list');
        $this->load->model('tb_grant_pre_users_daily_bonus');
        $this->load->model("tb_bonus_log");
        $this->load->model("tb_system_rebate_conf");
        $this->load->model("o_cash_account");
        $this->load->model('o_company_money_today_total');
        //$yearmonth = date("Ym",strtotime("$end -1 month"));
        $yearmonth = date('Ym',(strtotime(date('Ym05',strtotime($end)))-30*24*3600));
        $start_day = "2017-05-01";//4.1日后的补发奖金，必须是满足分红条件

        if (strtotime($end) >= strtotime($start_day)) {
            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid = ".$uid;
            $rs = $this->db->query($sql)->row_array();
            if (empty($rs)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_not_match_daily_bonus")));
                exit;
            }

        }



        //获取新用户分红的发奖比例
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0 ||  $obonus_rate['rate_b'] <= 0 || $obonus_rate['rate_c'] <= 0) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set")));
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1 || $obonus_rate['rate_b'] >= 1 || $obonus_rate['rate_c'] >= 1) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set_1")));
            exit;
        }

        $rate_a = $obonus_rate["rate_a"]<0 ? 0.1 : $obonus_rate['rate_a'];

        $day_all = $this->getDay($start, $end);
        if (!empty($day_all)) {
            for($index = 0; $index < count($day_all) ; $index++)
            {
                //判断用户是否拿过这天的奖金了

                $exists = $this->o_cash_account->check_exists($uid,strtotime($day_all[$index]),$commItemId);
                if ($exists){
                    //获取用户应该发放的奖金
                    //获取昨天全球利润
                    $yesterday =  date("Ymd",strtotime($day_all[$index]) - 24 * 3600);
                    $yesterdayProfitArr = $this->o_company_money_today_total->get_profit_some_day($yesterday);/*统计公司昨天全球销售利润 单位美元*/
                    if (!$yesterdayProfitArr) {
                        echo json_dump(array('success'=>false,'msg'=>lang("not_found_this_day_profit").$yesterday));
                        exit;
                    }

                    $yesterdayProfit = $yesterdayProfitArr['money'];
                    $total_money = tps_int_format($yesterdayProfit * $rate_a);//根据配置的利润比例算出日分红金额

                    if ($total_money > 0) {
                        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
                        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
                        $pre_data = [];
                        $total_money_b = $total_money * $rate_b;
                        $total_money_c = $total_money * $rate_c;

                        $total_rank =$this->tb_daily_bonus_qualified_list->get_total_rank();
                        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();
                        $res = $this->tb_daily_bonus_qualified_list->get_one($uid);

                        if (empty($res)) {
                            //如果没有在队列里面找到
                            $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and c.id =  ".$uid)->row_array();
                            if (empty($res)) {

                                $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where   c.id =  ".$uid. " order by `year_month` desc ")->row_array();
                                if (empty($res)) {

                                    $res = $this->db->query("select * from daily_bonus_qualified_list ORDER  by amount asc limit 1")->row_array();
                                }
                            }
                        }

                        $amount1 = ($res['amount']/$total_sale) * $total_money_b;
                        $amount2 = ($res['user_rank'] / $total_rank )* $total_money_c;
                        $amount = $amount1 + $amount2;
                        $amount = tps_int_format($amount);
                        $pre_data[] = ['uid'=>$uid,'money'=>$amount];
                        $time = strtotime($day_all[$index]);

                        //调用方法发奖
                        $res = $this->o_bonus->assign_bonus_batch_fix($pre_data,$commItemId,date("Y-m-d H:i:s",$time+2*3600));
                    } else {
                        echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_profit_not_enough").$yesterday));
                    }
                }

            }
        }
    }


    /**
     * @author brady
     * @desc 全球日分红 单个用户佣金补发
     * @param $uid
     * @param $start
     * @param $end
     * @param $commItemId
     */
    public function daily_bonus_reissue_new($uid,$start,$end,$commItemId)
    {
        ini_set('memory_limit', '5000M');
        $this->config->load('config_bonus');
        $this->load->model("o_bonus");
        $this->load->model('tb_daily_bonus_qualified_list');
        $this->load->model('tb_grant_pre_users_daily_bonus');
        $this->load->model("tb_bonus_log");
        $this->load->model("tb_system_rebate_conf");
        $this->load->model("o_cash_account");
        $this->load->model('o_company_money_today_total');
        $this->load->model('tb_grant_bonus_user_logs');
        $yearmonth = date('Ym',(strtotime(date('Ym05',strtotime($end)))-30*24*3600));
        $start_day = "2017-05-01";//4.1日后的补发奖金，必须是满足分红条件
        $user_data = array(
            'uid' => $uid,
            'proportion' => 0,
            'share_point' => 0,
            'amount' => 0,
            'bonus' => 0,
            'type' => 0,
            'item_type' => $commItemId
        );
        if (strtotime($end) >= strtotime($start_day)) {
            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid = ".$uid;
            $rs = $this->db->query($sql)->row_array();
            if (empty($rs)) {
                $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
            }

        }



        //获取新用户分红的发奖比例
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0 ||  $obonus_rate['rate_b'] <= 0 || $obonus_rate['rate_c'] <= 0) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set")));
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1 || $obonus_rate['rate_b'] >= 1 || $obonus_rate['rate_c'] >= 1) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set_1")));
            exit;
        }

        $rate_a = $obonus_rate["rate_a"]<0 ? 0.1 : $obonus_rate['rate_a'];

        $day_all = $this->getDay($start, $end);
        if (!empty($day_all)) {
            for($index = 0; $index < count($day_all) ; $index++)
            {
                //判断用户是否拿过这天的奖金了

                $exists = $this->o_cash_account->check_exists($uid,strtotime($day_all[$index]),$commItemId);
                if ($exists){
                    //获取用户应该发放的奖金
                    //获取昨天全球利润
                    $yesterday =  date("Ymd",strtotime($day_all[$index]) - 24 * 3600);
                    $yesterdayProfitArr = $this->o_company_money_today_total->get_profit_some_day($yesterday);/*统计公司昨天全球销售利润 单位美元*/
                    if (!$yesterdayProfitArr) {
                        echo json_dump(array('success'=>false,'msg'=>lang("not_found_this_day_profit").$yesterday));
                        exit;
                    }

                    $yesterdayProfit = $yesterdayProfitArr['money'];
                    $total_money = tps_int_format($yesterdayProfit * $rate_a);//根据配置的利润比例算出日分红金额

                    if ($total_money > 0) {
                        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
                        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
                        $pre_data = [];
                        $total_money_b = $total_money * $rate_b;
                        $total_money_c = $total_money * $rate_c;

                        $total_rank =$this->tb_daily_bonus_qualified_list->get_total_rank();
                        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();
                        $res = $this->tb_daily_bonus_qualified_list->get_one($uid);

                        if (empty($res)) {
                            //如果没有在队列里面找到
                            $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and c.id =  ".$uid)->row_array();
                            if (empty($res)) {

                                $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where   c.id =  ".$uid. " order by `year_month` desc ")->row_array();
                                if (empty($res)) {

                                    $res = $this->db->query("select * from daily_bonus_qualified_list ORDER  by amount asc limit 1")->row_array();
                                }
                            }
                        }

                        $amount1 = ($res['amount']/$total_sale) * $total_money_b;
                        $amount2 = ($res['user_rank'] / $total_rank )* $total_money_c;
                        $amount = $amount1 + $amount2;
                        $amount = tps_int_format($amount);
                        $pre_data[] = ['uid'=>$uid,'money'=>$amount];
                        $time = strtotime($day_all[$index]);

                        //调用方法发奖
                        $res = $this->o_bonus->assign_bonus_batch_fix($pre_data,$commItemId,date("Y-m-d H:i:s",$time+2*3600));
                        $user_data['type']=1;
                        $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                    }
                }

            }
        }
    }

    /**
     * @author brady
     * @desc 全球日分红 单个用户佣金补发
     * @param $uid
     * @param $start
     * @param $end
     * @param $commItemId
     */
    public function daily_bonus_reissue_check($uid,$start,$end,$commItemId)
    {
        ini_set('memory_limit', '5000M');
        $this->config->load('config_bonus');
        $this->load->model("o_bonus");
        $this->load->model('tb_daily_bonus_qualified_list');
        $this->load->model('tb_grant_pre_users_daily_bonus');
        $this->load->model("tb_bonus_log");
        $this->load->model("tb_system_rebate_conf");
        $this->load->model("o_cash_account");
        $this->load->model('o_company_money_today_total');
        $amount_data = array();
        $yearmonth = date("Ym",strtotime("$end -1 month"));
        $start_day = "2017-05-01";//4.1日后的补发奖金，必须是满足分红条件

        if (strtotime($end) >= strtotime($start_day)) {
            $sql = "select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid = ".$uid;
            $rs = $this->db->query($sql)->row_array();
            if (empty($rs)) {
                echo json_dump(array('success'=>false,'msg'=>lang("user_not_match_daily_bonus")));
                exit;
            }
        }

        //获取新用户分红的发奖比例
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);

        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0 ||  $obonus_rate['rate_b'] <= 0 || $obonus_rate['rate_c'] <= 0) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set")));
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1 || $obonus_rate['rate_b'] >= 1 || $obonus_rate['rate_c'] >= 1) {
            echo json_dump(array('success'=>false,'msg'=>lang("daily_bonus_failed_not_set_1")));
            exit;
        }
        $rate_a = $obonus_rate["rate_a"]<0 ? 0.1 : $obonus_rate['rate_a'];

        $day_all = $this->getDay($start, $end);
        if (!empty($day_all)) {
            for($index = 0; $index < count($day_all) ; $index++)
            {
                //判断用户是否拿过这天的奖金了
                $exists = $this->o_cash_account->check_exists($uid,strtotime($day_all[$index]),$commItemId);
                if ($exists){
                    //获取用户应该发放的奖金
                    //获取昨天全球利润
                    $yesterday =  date("Ymd",strtotime($day_all[$index]) - 24 * 3600);
                    $yesterdayProfitArr = $this->o_company_money_today_total->get_profit_some_day($yesterday);/*统计公司昨天全球销售利润 单位美元*/
                    if (!$yesterdayProfitArr) {
                        echo json_dump(array('success'=>false,'msg'=>lang("not_found_this_day_profit").$yesterday));
                        exit;
                    }
                    $yesterdayProfit = $yesterdayProfitArr['money'];
                    $total_money = tps_int_format($yesterdayProfit * $rate_a);//根据配置的利润比例算出日分红金额
                    if ($total_money > 0) {
                        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
                        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
                        $pre_data = [];
                        $total_money_b = $total_money * $rate_b;
                        $total_money_c = $total_money * $rate_c;
                        $total_rank =$this->tb_daily_bonus_qualified_list->get_total_rank();
                        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();
                        $res = $this->tb_daily_bonus_qualified_list->get_one($uid);
                        if (empty($res)) {
                            //如果没有在队列里面找到
                            $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=".$yearmonth." and c.id =  ".$uid)->row_array();
                            if (empty($res)) {
                                $res = $this->db->query(" select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  as user_rank from users_store_sale_info_monthly a left join users b
                                        on a.uid=b.id left join users as c on a.uid=c.id  where   c.id =  ".$uid. " order by `year_month` desc ")->row_array();
                                if (empty($res)) {
                                    $res = $this->db->query("select * from daily_bonus_qualified_list ORDER  by amount asc limit 1")->row_array();
                                }
                            }
                        }

                        $amount1 = ($res['amount']/$total_sale) * $total_money_b;
                        $amount2 = ($res['user_rank'] / $total_rank )* $total_money_c;
                        $amount = $amount1 + $amount2;
                        $amount = tps_int_format($amount);
                        $pre_data[] = ['uid'=>$res['uid'],'money'=>$amount];
                        $time = strtotime($day_all[$index]);

                        $amount_data[] = array
                        (
                            'uid'   => $uid,
                            'type'  => $commItemId,
                            'amount'    => $amount,
                            'time'  => date("Y-m-d H:i:s",$time+2*3600)
                        );

                    }
                }
            }
        }

        return $amount_data;
    }

    /**
     * @author brady
     * @desc 新会员奖 单个会员补发
     * @param $uid
     * @param $start
     * @param $end
     * @param $commItemId
     */
    public function new_member_bonus_reissue($uid,$start,$end,$commItemId)
    {
        $this->config->load('config_bonus');
        $this->load->model("tb_new_member_bonus");
        $this->load->model("o_bonus");
        $this->load->model("tb_system_rebate_conf");
        $this->load->model("tb_new_member_bonus");
        $this->load->model("tb_grant_pre_users_new_member_bonus");
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_bonus');
        $this->load->model("o_cash_account");

        //判断用户是否满足条件
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $today = date("Y-m-d",time());


        $day_all = $this->getDay($start, $end);
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(26);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0) {
            echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_failed_rate")));
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1) {
            echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_failed_rate_1")));
            exit;
        }

        $rate = $obonus_rate['rate_a']; //发奖

        if (!empty($day_all)) {
            for($index = 0; $index < count($day_all) ; $index++)
            {
                //判断用户是否拿过这天的奖金了
                $exists = $this->o_cash_account->check_exists($uid,strtotime($day_all[$index]),$commItemId);
                if (!$exists) {

                } else {
                    //获取用户应该发放的奖金
                    //获取昨天全球利润
                    $yesterday =  date("Ymd",strtotime($day_all[$index]) - 24 * 3600);

                    $yesterdayProfitArr = $this->o_company_money_today_total->get_profit_some_day($yesterday);/*统计公司昨天全球销售利润 单位美元*/
                    if (!$yesterdayProfitArr) {
                        echo json_dump(array('success'=>false,'msg'=>lang("not_found_this_day_profit").$yesterday));
                        exit;
                    }
                    $yesterdayProfit = $yesterdayProfitArr['money'];


                    $totalMoney = tps_int_format($yesterdayProfit * $rate);

                    if ($totalMoney >0) {
                        $this->load->model('td_system_rebate_conf');
                        $this->load->model('tb_new_member_bonus_total_weight');
                        $totalWeight = $this->tb_new_member_bonus_total_weight->get_by_day($day_all[$index]);
                        $res = $this->td_system_rebate_conf->getNewMember($uid);

                        $amount = tps_int_format($res[0]['bonus_shar_weight'] / $totalWeight*$totalMoney);

                        $pre_data = [];
                        if ($amount >0) {
                            $time = strtotime($day_all[$index]);
                            $pre_data[] = ['uid'=>$uid,'money'=>$amount];
                        }
                        //调用方法发奖
                        if (!empty($pre_data)) {
                           $res = $this->o_bonus->assign_bonus_batch_fix($pre_data,$commItemId,date("Y-m-d H:i:s",$time+2*3600));
                        }

                    } else {
                        echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_profit_not_enough")));
                    }
                }
            }
        }
    }


    /**
     * @author brady
     * @desc 新会员奖 单个会员补发 修复满足条件的会员
     * @param $uid
     * @param $start
     * @param $end
     * @param $commItemId
     */
    public function new_member_bonus_reissue_new($uid,$start,$end,$commItemId,$rate)
    {
        $this->config->load('config_bonus');
        $this->load->model("tb_new_member_bonus");
        $this->load->model("o_bonus");
        $this->load->model("tb_new_member_bonus");
        $this->load->model("tb_grant_pre_users_new_member_bonus");
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_bonus');
        $this->load->model("o_cash_account");

        //判断用户是否满足条件
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $today = date("Y-m-d",time());
        $day_all = $this->getDay($start, $end);

        if (!empty($day_all)) {
            for($index = 0; $index < count($day_all) ; $index++)
            {
                //判断用户是否拿过这天的奖金了
                $exists = $this->o_cash_account->check_exists($uid,strtotime($day_all[$index]),$commItemId);
                if (!$exists) {

                } else {

                    //获取用户应该发放的奖金
                    //获取昨天全球利润
                    $yesterday =  date("Ymd",strtotime($day_all[$index]) - 24 * 3600);
                    $yesterdayProfitArr = $this->o_company_money_today_total->get_profit_some_day($yesterday);/*统计公司昨天全球销售利润 单位美元*/
                    if (!$yesterdayProfitArr) {
                        echo json_dump(array('success'=>false,'msg'=>lang("not_found_this_day_profit").$yesterday));
                        exit;
                    }
                    $yesterdayProfit = $yesterdayProfitArr['money'];
                    $totalMoney = tps_int_format($yesterdayProfit * $rate);
                    if ($totalMoney >0) {
                        $this->load->model('tb_new_member_bonus_total_weight');
                        $this->load->model('td_system_rebate_conf');
                        $totalWeight = $this->tb_new_member_bonus_total_weight->get_by_day($day_all[$index]);
                        $res = $this->td_system_rebate_conf->getNewMember($uid);
                        $amount = tps_int_format($res[0]['bonus_shar_weight'] / $totalWeight*$totalMoney);

                        $pre_data = [];
                        if ($amount >0) {
                            $time = strtotime($day_all[$index]);
                            $pre_data[] = ['uid'=>$uid,'money'=>$amount];
                        }

                        //调用方法发奖
                        if (!empty($pre_data)) {
                            $this->m_log->createCronLog('新会员批量补发 用户：'.$uid.'金额：'.$amount."时间".date("Y-m-d H:i:s",$time+2*3600));
                           $res = $this->o_bonus->assign_bonus_batch_fix($pre_data,$commItemId,date("Y-m-d H:i:s",$time+2*3600));
                        }

                    } else {
                        echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_profit_not_enough")));
                    }
                }
            }
        }
    }


    /**
     * 销售精英日分红
     * @param 用户id $uid
     * @param 开始日期 $start
     * @param 结束日期  $end
     * @param 奖励类别 $commItemId
     */
    // public function resMonthSalesEliteAmount($uid,$start,$end,$commItemId)
    // {
    //     $this->load->model('m_profit_sharing');
    //     $this->load->model('tb_week_leader_members');
    //     //获取时间列表
    //     $day_all = $this->getDayM($start, $end);
    //     if(!empty($day_all))
    //     {
    //         //检查用户销售精英日的分红是否发放
    //         for($index = 0; $index < count($day_all) ; $index++)
    //         {

    //             $user_exists = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid,$day_all[$index],$commItemId);
    //             if(!$user_exists)
    //             {
    //                 $sale_user = $this->m_profit_sharing->getUserSalesEliteAmount($day_all[$index],$commItemId);
    //                 if(!empty($sale_user))
    //                 {
    //                     $user_sales_amount = $this->m_profit_sharing->getUserSalesInfo($sale_user['uid'],$day_all[$index],1,0);
    //                     $lv = $sale_user['amount']/$user_sales_amount;
    //                     $sales_amount_new = $this->m_profit_sharing->getUserSalesInfo($uid,$day_all[$index],0,0);
    //                     if(0==$sales_amount_new)
    //                     {
    //                         $sales_amount_new = $this->m_profit_sharing->getUserSalesInfo($uid,$day_all[$index],0,1);
    //                     }
    //                     $amount = $lv * $sales_amount_new/100;
    //                     if($amount > 0)
    //                     {
    //                         $this->m_profit_sharing->assignSharingCommission($uid, $amount,$commItemId,$sale_user['create_time']);
    //                     }
    //                 }
    //             }
    //         }

    //     }
    // }

    /**
     * 每月领导分红奖
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param unknown $commItemId
     * @param unknown $count
     */
//    public function resMonthLeaderAmount($uid,$start,$end,$commItemId,$count)
//    {
//        $this->load->model('m_user');
//        $this->load->model('tb_month_sharing_members');
//        $this->load->model('m_profit_sharing');
//        $this->load->model('tb_month_leader_bonus');
//        $this->load->model('tb_week_leader_members');
//
//        $user_sale_rank_state = 0;        //用户职称
//
//        //获取用户总分红点
//        $user_point = $this->m_user->getTotalSharingPoint($uid);
//        $user_info = $this->m_user->getUserByIdOrEmail($uid); //根据用户id获取用户职称信息
//        $month_array = $this->everyMonthFixedDay($start, $end);
//
//        switch ($user_info['sale_rank']) {
//            case 3:
//                // 领导分红- 高级市场主管
//                $user_sale_rank_state = 3;
//                $table_name = "month_leader_bonus";
//                break;
//            case 4:
//                // 领导分红-市场总监
//                $user_sale_rank_state = 4;
//                $table_name = "month_top_leader_bonus";
//                break;
//            case 5:
//                // 领导分红- 销售副总裁
//                $user_sale_rank_state = 5;
//                $table_name = "month_leader_bonus_lv5";
//                break;
//            default:
//                $user_sale_rank_state = 0;
//                break;
//        }
//
//        if($user_sale_rank_state!=0)
//        {
//            for($index = 0; $index < count($month_array); $index++)
//            {
//                //检测奖金是否已经发过
//                $check_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid,$month_array[$index],$commItemId);
//                if(!$check_exits)
//                {
//                    $user_ressiue_amount = $this->tb_month_leader_bonus->everyMonthLeadAutoAmount($uid,$table_name,$commItemId,$month_array[$index]);
//                    if(!empty($user_ressiue_amount))
//                    {
//
//                        $fir_user = $this->m_user->getTotalSharingPoint($user_ressiue_amount[0]['uid']);
//                        $tw_user = $this->m_user->getTotalSharingPoint($user_ressiue_amount[1]['uid']);
//
//                        $amount_c = $user_ressiue_amount[1]['amount']-$user_ressiue_amount[0]['amount'] > 0 ?($user_ressiue_amount[1]['amount']-$user_ressiue_amount[0]['amount']):0;
//                        $user_new_point = $user_point-$fir_user;
//                        $one_point = ($amount_c/($tw_user-$fir_user))*$user_new_point + $user_ressiue_amount[0]['amount'];
//
//                        //补发每月领导分红奖奖金
//                        if($one_point > 0)
//                        {
//                            $user_amount = $one_point/100;
//                            $this->m_profit_sharing->assignSharingCommission($uid, $user_amount,23,$user_ressiue_amount[0]['create_time']);
//                        }
//                    }
//                }
//            }
//        }
//
//    }

    /**
     * 每月领导分红奖 使用4月份新的机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param unknown $commItemId
     * @param unknown $count
     */
    public function resMonthLeaderAmount_new($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_month_leader_bonus');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_sharing_members');

        //获取用户总分红点
        $user_point = $this->m_user->getTotalSharingPoint($uid);

        $user_info = $this->m_user->getUserByIdOrEmail($uid); //根据用户id获取用户职称信息
        $month_array = $this->everyMonthFixedDay($start, $end);

        for ($index = 0; $index < count($month_array); $index ++)
        {
            // 检测奖金是否已经发过

            $check_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid, $month_array[$index], $commItemId);

            if (! $check_exits)
            {
                $ressiue_info = $this->tb_month_leader_bonus->getRessiueLeaderAutoAmount_new($user_info['sale_rank'], $user_point, $month_array[$index], $uid);
                if (!empty($ressiue_info)&&$ressiue_info[0]['money']>0) {
                    $c_time = $month_array[$index]." ".date('H:i:s');
                    $this->o_bonus->assign_bonus_batch_fix($ressiue_info,$commItemId,$c_time);
                }
            }
        }


    }

    /**
     * 每月领导分红奖 使用4月份新的机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param unknown $commItemId
     * @param unknown $count
     */
    public function resMonthLeaderAmount_new_check($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_month_leader_bonus');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_sharing_members');

        //获取用户总分红点
        $user_point = $this->m_user->getTotalSharingPoint($uid);
        $amount_data = array();
        $user_info = $this->m_user->getUserByIdOrEmail($uid); //根据用户id获取用户职称信息
        $month_array = $this->everyMonthFixedDay($start, $end);

        for ($index = 0; $index < count($month_array); $index ++)
        {
            // 检测奖金是否已经发过
            $ressiue_info = $this->tb_month_leader_bonus->getRessiueLeaderAutoAmount_new($user_info['sale_rank'], $user_point, $month_array[$index], $uid);
            if (! empty($ressiue_info) && $ressiue_info[0]['money'] >= 0) {
                $c_time = $month_array[$index] . " " . date('H:i:s');
                $amount_data[] = array(
                    'uid' => $uid,
                    'type' => $commItemId,
                    'amount' => $ressiue_info[0]['money'],
                    'time' => $c_time
                );
            }

        }

        return $amount_data;
    }

    /**
     * 获取每月的15号日期
     * @param unknown $start
     * @param unknown $end
     * @return multitype:string
     */
    public function everyMonthFixedDay($start,$end)
    {
        $stimestamp = strtotime($start);
        $etimestamp = strtotime($end);
        // 计算日期段内有多少天
        $days = ($etimestamp-$stimestamp)/86400+1;
        // 保存每天日期
        $date = array();
        for($i=0; $i<$days; $i++)
        {
            if(date('d', $stimestamp+(86400*$i))==15)
            {
                $date[] = date('Y-m-d', $stimestamp+(86400*$i));
            }
        }
        return $date;
    }

    /***
     * 每月杰出店铺分红
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param unknown $commItemId
     */
    public function everyMoneyUserAmount($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('tb_month_sharing_members');
        $this->load->model('m_profit_sharing');

        //获取用户总分红点
        $user_point = $this->m_user->getTotalSharingPoint($uid);
        $month_array = $this->everyMonthFixedDay($start, $end);

        for($index=0;$index < count($month_array);$index++)
        {
            $return_value = $this->tb_month_sharing_members->checkEveryMonthAmountByUid($uid,$commItemId,$month_array[$index]);
            if(!$return_value)
            {
                $user_ressiue_amount = $this->tb_month_sharing_members->everyMonthRessiueAutoAmount($uid,$user_point,$commItemId,$month_array[$index],array(),1);
                $this->m_user->getTotalSharingPoint($uid);
                if(!empty($user_ressiue_amount))
                {

                    $fir_user = $this->m_user->getTotalSharingPoint($user_ressiue_amount[0]['uid']);
                    $tw_user = $this->m_user->getTotalSharingPoint($user_ressiue_amount[1]['uid']);
                    $amount_c = $user_ressiue_amount[1]['amount']-$user_ressiue_amount[0]['amount'] > 0 ?($user_ressiue_amount[1]['amount']-$user_ressiue_amount[0]['amount']):0;
                    $user_new_point = $user_point-$fir_user;
                    $one_point = ($amount_c/($tw_user-$fir_user))*$user_new_point + $user_ressiue_amount[0]['amount'];

                    //每月杰出店铺分红奖--发奖
                    if($one_point > 0)
                    {
                        $this->m_profit_sharing->assignSharingCommission($uid,$one_point/100,$commItemId,$user_ressiue_amount[0]['create_time']);
                    }
                }
            }
        }
    }


    /**
     * 每周领导对等奖
     * @param 用户id $uid
     * @param 开始日期 $start
     * @param 结束日期 $end
     * @param 发奖类型 $commItemId
     */
    public function RessiueWeekLeadAutoAmount($uid,$start,$end,$commItemId)
    {
        $this->load->model('tb_week_leader_members');
        $this->load->model('m_profit_sharing');
        $ressiue_day = $this->getWeek($start,$end);
        for ($index=0;$index < count($ressiue_day); $index++)
        {

            $ressiue_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid,$ressiue_day[$index],$commItemId);
            if(!$ressiue_exits)
            {
                $amount = $this->m_profit_sharing->fixWeekLeaderAutoRessiue($uid,$ressiue_day[$index]);
                if($amount >=0 )
                {
                    $user_sql = "select count(*) as total from users where user_rank = 1 and sale_rank > 2 and id=".$uid;
                    $user_query = $this->db->query($user_sql);
                    $user_value = $user_query->row_array();
                    if($user_value['total']>0)
                    {
                        $this->m_user->ressiue_user_amount($uid,$amount*100,$commItemId,'',$ressiue_day[$index]." ".date("H:i:s"));
                    }
                }
                else
                {
                    $user_sql = "select count(*) as total from users where user_rank = 1 and sale_rank > 2 and id=".$uid;
                    $user_query = $this->db->query($user_sql);
                    $user_value = $user_query->row_array();
                    if($user_value['total']>0)
                    {
                        $this->m_user->ressiue_user_amount($uid,0,$commItemId,'',$ressiue_day[$index]." ".date("H:i:s"));
                    }
                }
            }
        }
    }

    /**
     * 补发周领导对等奖
     * @param $uid
     * @param $start
     * @param $end
     * @param $item_type
     * @return bool
     * @author carl.zhou
     */
    public function weekLeaderBonusAwardFix($uid, $start, $end, $item_type){
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_week_leader_preview');
        $this->load->model('o_bonus');
        $this->load->model('m_user');

        $children = $this->m_user->getChildMemberForLevelTwo($uid);
        if (empty($children)) {
            return true;
        }
        $award_day = $this->getWeek($start, $end);

        $today = date('Y-m-d');
        $types = $this->tb_week_leader_preview->item_type;
        $percent = $this->tb_week_leader_preview->percent / 100;
        $num = count($award_day);

        for ($index=0;$index < $num; $index++) {
            $amountList = [];
            $day = $award_day[$index];
            $day_start = $day;
            $day_end = date('Y-m-d', strtotime('+1 week', strtotime($day)));
            if ($day > $today) {
                break;
            }
            if (!$this->checkWeekLeaderQualify($uid, $day_start)) {
                continue;
            }
            if ($this->checkBonusExists($uid, $item_type, $day_start, $day_end)) {
                continue;
            }

            $lastMonday = date('Y-m-d',strtotime('last monday', strtotime($day)));
            $weekReward = $this->tb_cash_account_log_x->getWeekSumAmount($children, $types, $lastMonday);
            $amountList[] = ['uid' => $uid, 'money' => round($weekReward * $percent)];
            $res = $this->o_bonus->assign_bonus_batch_fix($amountList, 7, $day_start);
            if (!$res) {
                echo json_dump(array('success'=>false,'msg'=>'failed'));
                return false;
            }

        }
        return true;
    }


    /**
     * 补发周领导对等奖
     * @param $uid
     * @param $start
     * @param $end
     * @param $item_type
     * @return bool
     * @author carl.zhou
     */
    public function weekLeaderBonusAwardFix_check($uid, $start, $end, $item_type){
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_week_leader_preview');
        $this->load->model('o_bonus');
        $this->load->model('m_user');

        $children = $this->m_user->getChildMemberForLevelTwo($uid);
        if (empty($children)) {
            return true;
        }
        $amount_data = array();
        $award_day = $this->getWeek($start, $end);

        $today = date('Y-m-d');
        $types = $this->tb_week_leader_preview->item_type;
        $percent = $this->tb_week_leader_preview->percent / 100;
        $num = count($award_day);

        for ($index=0;$index < $num; $index++) {
            $amountList = [];
            $day = $award_day[$index];
            $day_start = $day;
            $day_end = date('Y-m-d', strtotime('+1 week', strtotime($day)));
            if ($day >= $today) {
                break;
            }
            if (!$this->checkWeekLeaderQualify($uid, $day_start)) {
                continue;
            }
            if ($this->checkBonusExists($uid, $item_type, $day_start, $day_end)) {
                continue;
            }

            $lastMonday = date('Y-m-d',strtotime('last monday', strtotime($day)));
            $weekReward = $this->tb_cash_account_log_x->getWeekSumAmount($children, $types, $lastMonday);

            $amount_data[] = array
            (
                'uid'   => $uid,
                'type'  => 7,
                'amount'    => round($weekReward * $percent),
                'time'  => $day_end
            );

        }
        return $amount_data;
    }

    public function checkWeekLeaderQualify($uid, $date){
        $lastYearMonth = date('Ym', strtotime('-1 month', strtotime($date)));
        $sql = "select a.id id from users a left join users_store_sale_info_monthly b on a.id=b.uid";
        $sql .= " where a.user_rank=1 and a.sale_rank>=2 and b.year_month=".$lastYearMonth." and b.sale_amount>=10000";
        $sql .= " and uid=".$uid;
        $res = $this->db->force_master()->query($sql)->row_array();
        return $res['id']>0 ? true : false;
    }

    public function checkEminentStoreQualify($uid, $date){
        $lastYearMonth = date('Ym', strtotime('-1 month', strtotime($date)));
        $sql = "select id from users where user_rank<4 and sale_rank>0 and status=1 and id=".$uid;
        $res = $this->db->force_master()->query($sql)->row_array();
        if (empty($res['id'])){
            return false;
        }
        $sql = "select uid from users_store_sale_info_monthly where `year_month`='".$lastYearMonth."'";
        $sql .= " and sale_amount>=10000 and uid=".$uid;
        $res = $this->db->force_master()->query($sql)->row_array();
        return $res['uid']>0 ? true : false;
    }

    public function checkBonusExists($uid, $item_type, $time_start, $time_end){
        $yearMonth = date('Ym', strtotime($time_start));
        $table = get_cash_account_log_table($uid,$yearMonth);
        $sql = "select count(*) as num from {$table} ";
        $sql .= " where uid=" . $uid . " and item_type=" . $item_type;
        $sql .= " and create_time>='" .$time_start . "'";
        $sql .= " and create_time<'" . $time_end ."'";
        $res = $this->db->force_master()->query($sql)->row_array();
        return $res['num']>0 ? true : false;
    }

    public function monthEminentStoreBonusAwardFix($uid, $start, $end, $item_type){
        $temp = $start = date('Y-m-15', strtotime($start));
        $end = date('Y-m-15', strtotime($end));
        if ($start > $end) {
            return false;
        }

        $curr_date = date('Y-m-d');
        $award_month = [];
        while ($temp<=$end) {
            if ($temp>$curr_date) {
                break;
            }
            $award_month[] = $temp;
            $temp = date('Y-m-15', strtotime('+1 month', strtotime($temp)));
        }

        $this->load->model('o_company_money_today_total');
        $this->load->model('td_system_rebate_conf');
        $this->load->model('o_bonus');

        $conf = $this->td_system_rebate_conf->getBonusData($item_type);
        if (empty($conf[0]) || !is_array($conf[0])) {
            return false;
        }
        $conf = $conf[0];

        $bonus_percent = $conf['rate_a'] > 0 ? floatval($conf['rate_a']) : 0;
        $average_percent = $conf['rate_b'] > 0 ? floatval($conf['rate_b']) : 0;

        if ($bonus_percent==0 || $average_percent==0) {
            return false;
        }

        $sql = "select uid,sharing_point from month_sharing_members where uid=" . $uid;
        $user = $this->db->query($sql)->row_array();

        if (empty($user)) {
            $user = array();
            $sql = "select sum(point) reward_total from users_sharing_point_reward where uid=".$uid." and end_time>'".$start."'";
            $reward_total = $this->db->query($sql)->row_object()->reward_total;

            $sql = "select profit_sharing_point from users where id=".$uid;
            $point = $this->db->query($sql)->row_object()->profit_sharing_point;

            $total = floatval($reward_total) + floatval($point);
            $user['uid'] = $uid;
            $user['sharing_point'] = $total;
        }

        foreach($award_month as $month) {
            $time_start = $month;
            $time_end = date('Y-m-01 00:00:00', strtotime('+1 month', strtotime($time_start)));
            $last_month = date('Ym',strtotime('-1 month', strtotime($time_start)));

            if (!$this->checkEminentStoreQualify($uid, $time_start)) {
                continue;
            }
            $exists = $this->checkBonusExists($uid, $item_type, date('Y-m-01', strtotime($time_start)), $time_end);
            if ($exists) {
                continue;
            }
            //上月总利润
            $sql = 'select money from company_money_today_total where create_time='.$last_month;
            $total_info = $this->db->query($sql)->row_array();

            $total_reward = isset($total_info['money']) ? $total_info['money'] : 0;

            if ($total_reward <= 0) {
                continue;
            }

            $sql = 'select count(uid) as total_num, sum(sharing_point) as total_point from month_sharing_members';
            $point_result = $this->db->query($sql)->row_array();
            //参与分红总人数
            $total_num = $point_result['total_num'];
            //参与分红的总分红点
            $total_point = $point_result['total_point'];

            //总利润的一部分拿出来作为奖金
            $total_bonus = $total_reward * $bonus_percent;

            //所有人平均分享总奖金的20%
            $average_bonus = $total_bonus * $average_percent / $total_num;

            //总奖金的剩余部分根据个人分红点分配
            $last_reward = $total_bonus * (1 - $average_percent);

            if ($user['sharing_point']>0) {
                $personal_bonus = $last_reward * $user['sharing_point'] / $total_point;
            } else {
                $personal_bonus = 0;
            }
            $bonus = round($average_bonus + $personal_bonus);
            if ($bonus<0) {
                $bonus = 0;
            }

            $amountList[] = ['uid' => $uid, 'money' => round($bonus)];
            $result = $this->o_bonus->assign_bonus_batch_fix($amountList, $item_type, $time_start);
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    /***
     * 每月团队组织分红奖 - 补发奖
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
//    public function every_month_ressiue_group_money($uid,$start,$end,$commItemId,$count)
//    {
//        $this->load->model('m_user');
//        $this->load->model('tb_week_leader_members');
//        $this->load->model('tb_month_leader_bonus');
//
//        $user_title_info = $this->m_user->get_user_title_info($uid);
//        if (! empty($user_title_info)) {
//
//            if($user_title_info['sale_rank'] >= 3)
//            {
//                $user_sale_rank = ($user_title_info['sale_rank'] + 1) * ($user_title_info['sale_rank'] + 1) * 10;
//            }
//            else
//            {
//                $user_sale_rank = ($user_title_info['sale_rank'] + 1) * ($user_title_info['sale_rank'] + 1);
//            }
//
//            $ressiue_day = $this->everyMonthFixedDay($start, $end); // 每月15号发奖
//
//            for ($index = 0; $index < count($ressiue_day); $index ++) {
//
//                $ressiue_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid, $ressiue_day[$index], $commItemId);
//                if (! $ressiue_exits) {
//
//                    $ressiue_info = $this->tb_month_leader_bonus->getRessiueWeekTeamAutoAmount($ressiue_day[$index], $commItemId, $user_sale_rank, array(), $count);
//                    if (! empty($ressiue_info)) {
//                        $this->m_user->ressiue_user_amount($uid, $ressiue_info['amount'], $commItemId, '', $ressiue_info['create_time']);
//                    }
//                }
//            }
//        }
//    }

    /***
     * 每月团队组织分红奖 - 2017年4月份新机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
    public function every_month_ressiue_group_money_new($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('m_debug');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_leader_bonus');

        $user_title_info = $this->m_user->get_user_title_info($uid);
        if (!empty($user_title_info)) {

            $ressiue_day = $this->everyMonthFixedDay($start, $end); // 每月15号发奖
            
            for ($index = 0; $index < count($ressiue_day); $index ++) {
                $this->m_debug->log($uid.':1524');
                $ressiue_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid, $ressiue_day[$index], $commItemId);
                if (! $ressiue_exits) {
                    $this->m_debug->log($uid.':1524');
                    $ressiue_info = $this->tb_month_leader_bonus->getRessiueWeekTeamAutoAmount_new($ressiue_day[$index], $commItemId, $uid);
                    $this->m_debug->log($uid.':1531');
                    if (!empty($ressiue_info)&&$ressiue_info[0]['money']>0) {
                        $c_time = $ressiue_day[$index]." ".date('H:i:s');
                        $this->m_debug->log($uid.':1534');
                        $this->o_bonus->assign_bonus_batch_fix($ressiue_info,$commItemId,$c_time);
                        $this->m_debug->log($uid.':1535');
                    }
                }
            }
        }
    }

    /***
     * 每月团队组织分红奖 - 2017年4月份新机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
    public function every_month_ressiue_group_money_new_check($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_leader_bonus');
        $amount_data = array();
        $user_title_info = $this->m_user->get_user_title_info($uid);
        if (!empty($user_title_info)) {

            $ressiue_day = $this->everyMonthFixedDay($start, $end); // 每月15号发奖

            for ($index = 0; $index < count($ressiue_day); $index ++) {
                $ressiue_info = $this->tb_month_leader_bonus->getRessiueWeekTeamAutoAmount_new($ressiue_day[$index], $commItemId, $uid);

                if (! empty($ressiue_info) && $ressiue_info[0]['money'] >= 0) {
                    $c_time = $ressiue_day[$index] . " " . date('H:i:s');
                    $amount_data[] = array(
                        'uid' => $uid,
                        'type' => $commItemId,
                        'amount' => $ressiue_info[0]['money'],
                        'time' => $c_time
                    );
                }

            }
        }
        return $amount_data;
    }

    /***
     * 每周团队销售分红补发佣金
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
//    public function week_ressiue_group_money($uid,$start,$end,$commItemId,$count)
//    {
//        $this->load->model('m_user');
//        $this->load->model('tb_week_leader_members');
//        $user_title_info = $this->m_user->get_user_title_info($uid);
//        if (! empty($user_title_info))
//        {
//            $ressiue_day = $this->getWeek($start, $end);
//            for ($index = 0; $index < count($ressiue_day); $index ++)
//            {
//                $ressiue_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid, $ressiue_day[$index], $commItemId);
//                if (! $ressiue_exits)
//                {
//
//                    $ressiue_info = $this->tb_week_leader_members->getRessiueWeekTeamAutoAmount($ressiue_day[$index], $commItemId, $user_title_info['sale_rank']*$user_title_info['sale_rank'], array(), $count);
//                    if (! empty($ressiue_info)) {
//                        $this->m_user->ressiue_user_amount($uid, $ressiue_info['amount'], $commItemId, '', $ressiue_info['create_time']);
//                    }
//                }
//            }
//        }
//    }

    /***
     * 每周团队销售分红补发佣金 2017年4月份新机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
    public function week_ressiue_group_money_new($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('m_debug');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_week_leader_members');

        $user_title_info = $this->m_user->get_user_title_info($uid);
        if (! empty($user_title_info))
        {
            $ressiue_day = $this->getWeek($start, $end);

            for ($index = 0; $index < count($ressiue_day); $index ++)
            {

                $this->m_debug->log($uid.':1633_tb_week');
                $ressiue_exits = $this->tb_week_leader_members->check_ressiue_week_team_exits($uid, $ressiue_day[$index], $commItemId);
                if (! $ressiue_exits)
                {
                    $this->m_debug->log($uid.':1637_tb_week');
                    $ressiue_info = $this->tb_week_leader_members->getRessiueWeekTeamAutoAmount_new($ressiue_day[$index], $commItemId, $uid);
                    $this->m_debug->log($uid.':1639_tb_week');
                    if(!empty($ressiue_info)&&$ressiue_info[0]['money']>0)
                    {
                        $c_time = $ressiue_day[$index]." ".date('H:i:s');
                        $this->o_bonus->assign_bonus_batch_fix($ressiue_info,$commItemId,$c_time);
                    }
                }

            }
        }
    }

    /***
     * 每周团队销售分红补发佣金 2017年4月份新机制
     * @param unknown $uid
     * @param unknown $start
     * @param unknown $end
     * @param 补奖类型  $commItemId
     */
    public function week_ressiue_group_money_new_check($uid,$start,$end,$commItemId)
    {
        $this->load->model('m_user');
        $this->load->model('o_bonus');
        $this->load->model('m_profit_sharing');
        $this->load->model('tb_week_leader_members');

        $amount_data = array();
        $user_title_info = $this->m_user->get_user_title_info($uid);
        if (! empty($user_title_info))
        {
            $ressiue_day = $this->getWeek($start, $end);

            for ($index = 0; $index < count($ressiue_day); $index ++)
            {

                $ressiue_info = $this->tb_week_leader_members->getRessiueWeekTeamAutoAmount_new_check($ressiue_day[$index], $commItemId, $uid);


                if(!empty($ressiue_info)&&$ressiue_info[0]['money']>=0)
                {
                    $c_time = $ressiue_day[$index]." ".date('H:i:s');
                    $amount_data[] = array
                    (
                        'uid'   => $uid,
                        'type'  => $commItemId,
                        'amount'    => $ressiue_info[0]['money'],
                        'time'  => $c_time
                    );
                }

            }
        }
        return $amount_data;
    }


}
