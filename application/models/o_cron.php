<?php
/**
 * Cron 逻辑类
 * @author Terry
 */
class o_cron extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function generate_users($num)
    {
        for($i = 0 ;$i<=$num;$i++) {
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->generate_users_page();');//用子进程处理每一页
        }
    }
    public function generate_users_page()
    {
        $numbers = [3,2,3,4,5,6,7,8,9,4];
        $numbers = implode('',$numbers);
        $numbers = str_shuffle($numbers);
        //随便找一个用户作为父id
        $uid_res = $this->db->query('select id from users ORDER by rand() limit 1')->row_array();

        $requestData['pwdOriginal'] = '123456';
        $requestData['mobile'] = '1'.$numbers;
        $requestData['parent_id'] = $uid_res['id'];
        $exists = $this->db->query('select id from users where id = '.$requestData['mobile'])->row_array();
        if (!empty($exists)) {
            exit();
        }
        $requestData['reg_type'] = 0;
        $res = $this->tb_users->register_user($requestData);//注册账号
        var_dump($requestData['mobile']);
    }

    public function calc_company_total_monthly_history($year_months)
    {
        //$this->o_cron->calc_company_total_history_page1($year_months[0]);
        foreach ($year_months as $year_month) {
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->calc_company_total_monthly_history_page(\''. $year_month . '\');');//用子进程处理每一页
        }

    }

    public function calc_company_total_monthly_history_page($year_month)
    {
        exit("calc_company_total_monthly_history_page function exit");
        //分别统计三个字段的总值
        $this->load->model("tb_company_calc_monthly");
        $this->tb_company_calc_monthly->calc_month_total($year_month);
    }

    public function init_user_credit($page_size)
    {
        //获取总用户数
        $sql = "select count(*) as number from users where id != ".config_item('mem_root_id');
        $total_rows = $this->db->query($sql)->row_object()->number;

        $total_page = ceil($total_rows / $page_size);

        //$this->init_user_credit_page(1,$page_size);
        for($i = 1; $i <= $total_page; $i++) {
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->init_user_credit_page(\''. $i . '\',\''.$page_size.'\' );');//用子进程处理每一页
        }
    }

    /**
     * @authro
     * @desc 分页初始化
     * @param $page
     * @param $page_size
     */
    public function init_user_credit_page($page, $page_size)
    {
        $time1 = time();
        $this->db->trans_begin();
        $start = ($page - 1) * $page_size;
        $root_id = config_item('mem_root_id');

        $sql = "select id,user_rank,sale_rank from users where  id != " . config_item('mem_root_id') . " order by id asc limit {$start},{$page_size} ";

        $res = $this->db->query($sql)->result_array();
        if (!empty($res)) {
            foreach ($res as $v) {
                $old_user = [
                    'id' => $v['id'],
                    'user_rank' => 4,
                    'sale_rank' => 0
                ];
                $this->load->model('o_users_credit_init');
                $this->load->model('tb_users_credit');
                $new_credit = $this->o_users_credit_init->calc_user_credit_init($v['id']);
                //var_dump("用户" . $v['id'] . "积分为" . $new_credit);
                $old_res = $this->tb_users_credit->check_exists($v['id']);
                if (!empty($old_res) && isset($old_res['credit'])) {
                    $old_credit = $old_res['credit'];
                } else {
                    $old_credit = 0;
                }
                //插入到用户积分表
                $this->o_users_credit_init->edit_user_credit_init($v['id'], $new_credit, $old_credit, 0, config_item("user_credit_remark_message")['init_user_first']);
            }
        }
        $time2 = time();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $msg = '[success]初始化用户积分成功 page' . $page . " 耗时：" . ($time2 - $time1) . "s";
            var_dump($msg);

            //$this->m_log->createCronLog($msg);
        } else {
            $this->db->trans_rollback();
            $msg = '[faile] 初始化用户积分失败 page' . $page;
            $this->m_log->createCronLog($msg);
        }
    }

    /**
     * @author brady.wang
     * @desc 用户等级变化影响积分
     * @param $page_size
     */
    public function users_rank_queue($page_size)
    {
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users_credit_queue_user_rank");
        $this->db->trans_begin();
        $total = $this->tb_users_credit_queue_user_rank->get([
            'id,uid,before_user_rank,after_user_rank',
            'limit' => $page_size,
            'order' => "id asc"
        ], true, false, true);
        if ($total > 0) {
            $self_page = 2;
            $total_page = ceil($total / $self_page);
            //$this->init_user_credit_page(1,$page_size);
            for($i = 1; $i <= $total_page; $i++) {
                $this->o_pcntl->tps_pcntl_wait('$this->o_cron->users_rank_queue_page(\''. $i . '\',\''.$self_page.'\' );');//用子进程处理每一页
            }
        } else {
            echo "队列为空";
        }

    }

    /**
     * @author brady.wang
     * @desc 一分钟内，将拿到的总数，分开放到事物里面执行，防止事物里面太多
     * @param $page
     * @param $page_size
     */
    public function users_rank_queue_page($page,$page_size)
    {
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users_credit_queue_user_rank");
        $this->db->trans_begin();
        $list = $this->tb_users_credit_queue_user_rank->get([
            'id,uid,before_user_rank,after_user_rank',
            'limit' => $page_size,
            'order' => "id asc"
        ], false, false, true);
        if (!empty($list)) {
            foreach ($list as $v) {
                $this->tb_users_credit_queue_user_rank->set_uids_credit($v);
            }
        } else {
            echo "队列为空";
            exit;
        }

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $msg = '[success]用户等级变化导致上级获取积分成功';
            var_dump($msg);

            //$this->m_log->createCronLog($msg . json_encode($list));
        } else {
            $this->db->trans_rollback();
            $msg = '[faile] 用户等级变化导致上级获取积分失败';
            $this->m_log->createCronLog($msg . json_encode($list));
        }
    }

    /**
     * @author brady
     * @desc 用户职称影响积分
     * @param int $page_size
     */
    public function sale_rank_queue($page_size = 100)
    {
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users_credit_queue_sale_rank");
        $total = $this->tb_users_credit_queue_sale_rank->get([
            'id,uid,before_sale_rank,after_sale_rank',
            'limit' => $page_size,
            'order' => "id asc"
        ], true, false, true);
        if ($total >0) {
            $self_page = 2;
            $total_page = ceil($total / $self_page);
            for($i = 1; $i <= $total_page; $i++) {
                $this->o_pcntl->tps_pcntl_wait('$this->o_cron->sale_rank_queue_page(\''. $i . '\',\''.$self_page.'\' );');//用子进程处理每一页
            }
        } else {
            echo "队列为空";
        }

    }

    /**
     * @author brady.wang
     * @desc 用户职称影响积分分页
     * @param $page
     * @param $page_size
     */
    public function sale_rank_queue_page($page,$page_size)
    {
        $this->load->model("tb_users_credit");
        $this->load->model("tb_users_credit_log");
        $this->load->model("tb_users_credit_queue_sale_rank");
        $this->db->trans_begin();
        $list = $this->tb_users_credit_queue_sale_rank->get([
            'id,uid,before_sale_rank,after_sale_rank',
            'limit' => $page_size,
            'order' => "id asc"
        ], false, false, true);

        if (!empty($list)) {
            foreach ($list as $v) {
                if (config_item("credit_switch") == "on") {
                    $this->db->where('id', $v['uid'])->update('users', array('sale_rank' => $v['after_sale_rank'], 'sale_rank_up_time' => date('Y-m-d H:i:s'))); //更新职称
                }

                $this->tb_users_credit_queue_sale_rank->set_uids_credit($v);
                //删除该条记录
                $this->tb_users_credit_queue_sale_rank->del_queue($v['id']);
            }

        } else {
            echo "队列为空";
            exit;
        }
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $msg = '[success]用户职称变动导致上级获取积分成功';
            var_dump($msg);

            //$this->m_log->createCronLog($msg);
        } else {
            $this->db->trans_rollback();
            $msg = '[faile] 用户职称变动导致上级获取积分失败';
            $this->m_log->createCronLog($msg);
        }
    }




    /**
     * @author brady
     * @desc 每月统计
     */
    public function daily_bonus_month()
    {

        try{
            $this->db->trans_begin();
            $this->db->truncate("daily_bonus_qualified_list");

            //自己奖金满足
            $sql1 = "insert ignore into daily_bonus_qualified_list(uid,amount,user_rank) select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.status=1 and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4)";
            $this->db->query($sql1);
            //推荐人合格
            //$sql2 = "insert ignore into daily_bonus_qualified_list(uid,user_rank,amount) select a.uid,if(b.user_rank=4,1,if(b.user_rank=5,2,if(b.user_rank=3,3,if(b.user_rank=2,4,5)) ) ) ,c.sale_amount from stat_intr_mem_month as a  left join users as b on a.uid=b.id left join users_store_sale_info_monthly
//  as c on a.uid=c.uid  where a.year_month=
//DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m')
//and (a.member_bronze_num>0 or a.member_silver_num>0 or a.member_platinum_num>0 or a.member_diamond_num>0) and a.uid<>1380100217";
            //$this->db->query($sql2);
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 每月统计满足日分红的用户 ');
        } else {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[faile] 每月统计满足日分红的用户');
        }

        }catch(Exception $e) {
            $this->m_log->createCronLog($e->getMessage());
        }
    }


    /**
     * @author brady
     * @desc 修复上月下单会员业绩后，重新生成全球日分红队列
     */
    public function user_daily_bonus_qualified_list()
    {

        try{
            $this->db->trans_begin();
            $this->db->truncate("user_daily_bonus_qualified_list");

            //自己奖金满足
            $sql1 = "insert ignore into user_daily_bonus_qualified_list(uid,amount,user_rank) select a.uid,a.sale_amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) )  from users_store_sale_info_monthly a left join users b
on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.status=1 and a.sale_amount>=2500
and (a.sale_amount>=10000 or b.user_rank<>4)";
            $this->db->query($sql1);
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->m_log->createCronLog('[Success] 每月统计满足日分红的用户 ');
            } else {
                $this->db->trans_rollback();
                $this->m_log->createCronLog('[faile] 每月统计满足日分红的用户');
            }

        }catch(Exception $e) {
            $this->m_log->createCronLog($e->getMessage());
        }
    }



    /**
     * @author brady
     * @desc 定时清空不满足新会员专享奖的用户
     */
    public function del_not_match_new_member_bonus()
    {
        $this->load->model("tb_new_member_bonus");
        $res = $this->tb_new_member_bonus->get_not_match_users();
        try {
            if (empty($res)) {
                throw new Exception("[fail]没有需要剔除的新会员");
            }

            $uids = [];
            foreach($res as $v) {
                $uids[] = $v['uid'];
            }

            $result = $this->tb_new_member_bonus->del_not_match($uids);
            if ($result) {
                $this->m_log->createCronLog("[success]因过期不满足新会员奖剔除人数：".count($res)." ".json_encode($res));
            } else {
                throw new Exception("[faile]因过期不满足新会员奖剔除人数：".count($res)." ".json_encode($res));
            }

        } catch (Exception $e) {
            $this->m_log->createCronLog($e->getMessage());
        }
    }

    /**
     * @author brady
     * @desc 扣月费定时
     * 所有用户都要扣 0 -500美金 (含500) 0.5%；500-1000美金(含1000)1%；1000-5000美金（含5000）2%； 5000美金以上 3%。
     */
//    public function month_fee()
//    {
//        ini_set('memory_limit', '5000M');
//        $this->load->model("tb_users");
//        $this->load->model("o_month_fee");
//        $total_rows = $this->tb_users->get_total_rows();
//        $total_rows = 1;
//        $page_size = 1000;
//        $total_page = ceil($total_rows / $page_size);
//
//        for($page=1;$page<=$total_page;$page++){
//            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->month_fee_page(\''.$page.'\',\''.$page_size.'\');');//用子进程处理每一页
//        }
//
//        $this->month_fee_page($page, $page_size);
//    }

    /**
     * @author brady
     * @desc  修复新会员用户不足
     */
    public function new_member_bonus_queue()
    {


        //找到昨天升级的
        ini_set("memory_limit", "2048M");
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $today = date("Y-m-d", time());
        $pageSize = 1000;
        $type = "new_member_bonus_queue";
        try {
            $this->load->model("tb_cron_status");
            //获取满足条件的总数
            $total_arr = $this->db->from("users_level_change_log")
                ->select("uid")
                ->where("old_level = 4 and level_type = 2 and create_time >= '".$yesterday ."' and create_time <  '".$today."'")
                //->where("old_level = 4 and level_type = 2 and create_time >= '2017-01-01' and create_time <  '".$today."'")
                ->group_by("uid")
                ->get()
                ->result_array();
            $total = count($total_arr);
            unset($total_arr);

            if ($total > 0) {
                $pageNum = ceil($total / $pageSize);
                for ($page = 1; $page <= $pageNum; $page++) {
                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->new_member_bonus_queue_page(\'' . $page . '\',\'' . $pageSize . '\');');//用子进程处理每一页
                    //$this->o_cron->new_member_bonus_queue_page($page ,$pageSize);
                }
                $this->tb_cron_status->add_one($type,2,'统计合格人数成功');
                $this->tb_cron_status->del_one($type);
            } else {
                $this->tb_cron_status->add_one($type,2,'统计合格人数成功');
                $this->tb_cron_status->del_one($type);
                throw new Exception("[faile]昨天没有满足条件的新会员");
            }
        } catch (Exception $e) {
            $this->m_log->createCronLog($e->getMessage());
        }

    }

    public function new_member_bonus_queue_page($page, $page_size)
    {

        $this->load->model("tb_new_member_bonus");
        $this->load->model("tb_cron_status");
        $this->db->trans_begin();
        $this->tb_new_member_bonus->new_member_bonus_v3($page, $page_size);
        $type = "new_member_bonus_queue";

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            var_dump('[success]修复 通过升级进入新会员队列: page' . $page);
           // $this->tb_cron_status->add_one($type,2,'进入新会员奖队列成功: page' . $page);
            $this->m_log->createCronLog('[success]修复 通过升级进入新会员队列:page' . $page);
        } else {
            $this->db->trans_rollback();
            var_dump('[faile]修复 通过升级进入新会员队列: page' . $page);
            $this->tb_cron_status->add_one($type,3,'进入新会员奖队列失败: page' . $page);
            $this->m_log->createCronLog('[faile]修复 通过升级进入新会员队列' . $page);
        }
    }




    /**
     * @author brady
     * @desc  公司全球日分红预发布
     * @param $yesterdayProfit 昨天全球利润
     */
    public function doNewMemberSharPre($yesterdayProfit)
    {
        ini_set("memory_limit","3048M");
        $this->config->load('config_bonus');
        $this->load->model("tb_new_member_bonus");
        $this->load->model("o_bonus");
        $this->load->model("tb_system_rebate_conf");
        $this->load->model("tb_new_member_bonus");
        $this->load->model("tb_grant_pre_users_new_member_bonus");
        $this->load->model("tb_cron_status");
        $pageSize = config_item('bonus')['newMemberProfitShar']['pageSize']; //每页大小
        $this->load->model("tb_cron_status");
        $type = "new_member_bonus_pre";//预发奖

        $start_time = time();
        //获取新用户分红的发奖比例
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(26);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0) {
            $this->m_log->createCronLog('[fail] 新会员专享奖发放失败 未设置发放比例.');
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1) {
            $this->m_log->createCronLog('[fail] 新会员专享奖发放失败 分红比例不能够超过1.');
            exit;
        }

        $this->tb_bonus_plan_control->changeExecStatus(26,["rate"=>$obonus_rate['rate_a']]);
        $rate = $obonus_rate['rate_a']; //发奖
        unset($obonus_rate);//释放变量

        $totalMoney = tps_int_format($yesterdayProfit * $rate);
        unset($yesterdayProfit);
        if ($totalMoney >0) {

            $totalNum = $this->tb_new_member_bonus->get_bonus_users([],true);

            if ($totalNum >0) {

                //设置权重分批设置，否则内存溢出 每次一千条
                $for_page_size = 1000;
                $for_page = ceil($totalNum/$for_page_size);
                $totalWeight = 0;
                $weight =  [];

                //设置权重前，先删除，要统计的今天的 如果有
                $day = date("Ymd",time());
                $this->load->model("tb_new_member_bonus_total_weight");
                $this->tb_new_member_bonus_total_weight->del_by_day($day);

                //$this->o_cron->set_weight(1,1000);
                for($i=1;$i<= $for_page;$i++) {
                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->set_weight(\''.$i.'\',\''.$for_page_size.'\');');//用子进程处理每一页
                    //$this->o_cron->set_weight($i,$for_page_size);
                }

                $totalWeight = $this->tb_new_member_bonus_total_weight->get_by_day($day);
                if ($totalWeight == false) {
                    $this->tb_cron_status->add_one($type,3,'获取总权重失败');
                    $this->m_log->createCronLog('[fail] 获取总权重失败.');exit;
                }
                //今天预发奖之前，删除昨天的
                $this->tb_grant_pre_users_new_member_bonus->empty_data();
                /*根据拿奖总人数划分分页,并分页处理发奖。*/
                $pageNum = ceil($totalNum/$pageSize);

                for($page=1;$page<=$pageNum;$page++){
                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->doNewMemberSharPagePre(\''.$totalWeight.'\',\''.$page.'\',\''.$pageSize.'\',\''.$totalMoney.'\');');//用子进程处理每一页
                    //$this->o_cron->doNewMemberSharPagePre($totalWeight,$page,$pageSize,$totalMoney);
                }

                $end_time = time();
                $tm = $end_time - $start_time;
                echo "花费时间：".$tm."s ";
                $this->tb_cron_status->add_one($type,2,"预发奖成功");
                $this->tb_cron_status->del_one($type);


            } else {
                $this->m_log->createCronLog('[fail] 今天没有满足新会员专享奖的人.');
            }
        } else {
            $this->m_log->createCronLog('[fail] 新会员专享奖利润不足.');
        }

    }

    public function set_weight($i,$for_page_size)
    {

        $user_list = $this->tb_new_member_bonus->get_bonus_users(['limit'=>['page'=>$i,'pageSize'=>$for_page_size]]);
        $this->db->trans_begin();
        foreach($user_list as $v) {
            $uids[] = $v['uid'];
        }
        unset($user_list);

        $weight_temp =  $this->o_bonus->getUsersTotalWeightArr_new($uids);
        //设置每个用户的权重
        $this->tb_new_member_bonus->set_users_weight($weight_temp);

        $this->load->model("o_bonus");
        //$res = $this->o_bonus->getUsersTotalWeight($uids);        
        $day = date("Ymd",time());
        $sql ="select sum(bonus_shar_weight) as total from new_member_bonus where end_day >=".$day;
        $query = $this->db->query($sql);    
        $query_value=$query->row_array();
        $exists = $this->db->from("new_member_bonus_total_weight")->select("id")->where(['create_time'=>$day])->limit(1)->get()->row_array();
        if (!empty($exists)) {
            $this->db->query("update new_member_bonus_total_weight set total_weight = ".$query_value['total']." where id = ".$exists['id']);
        } else {
            $sqls = "insert into new_member_bonus_total_weight(create_time,total_weight) VALUES('".$day."','".$query_value['total']."')";
            $this->db->query($sqls);
        }

        //设置每个用户的权重
        $sql = $this->db->last_query();
        if ($this->db->trans_status() == true ) {
            $this->db->trans_commit();
            var_dump("更新总权重成功:".$i." 奖金".$query_value['total']);
            $this->m_log->createCronLog('[Success] 更新总权重成功,page:'.$i." 奖金".$query_value['total']);
        } else {
            $this->db->trans_rollback();
            var_dump("更新总权重失败:".$i);
            $this->load->model("tb_cron_status");
            $type = "new_member_bonus_pre";//预发奖
            $this->tb_cron_status->add_one($type,3,"更新总权重失败".$sql);

            $this->m_log->createCronLog('[fail] 更新总权重失败'.$sql);
        }
    }

    public function get_mem_used()
    {
        $m=memory_get_usage(); //获取当前占用内存
        $m = tps_money_format( $m/(1024*1024));
        echo "used memory:".$m."M"." ";
    }


    /**
     * @author brady
     * @param $totalWeight 总的订单金额
     * @param $page 本页
     * @param $pageSize 分页大小
     * @param $totalMoney 总的拿来分的利润总额
     */
    public function doNewMemberSharPagePre($totalWeight,$page,$pageSize,$totalMoney)
    {
        ini_set("memory_limit","1024M");
        $this->load->model("tb_new_member_bonus");
        $this->load->model("tb_grant_pre_users_new_member_bonus");
        $this->load->model("o_bonus");


        $res = $this->tb_new_member_bonus->get_bonus_users(array('limit'=>array('page'=>$page,'pageSize'=>$pageSize)),false);

        $uids = [];
        foreach ($res as $v) {
            $uids[] = $v['uid'];
        }
        
        $weight = $this->o_bonus->getUsersTotalWeightArr_new($uids);
        $pre_data = [];
        foreach($res as $k=>$v) {
            $amount = tps_int_format($v['bonus_shar_weight'] / $totalWeight*$totalMoney);
            $desc_money =  $v['bonus_shar_weight'] - $weight[$v['uid']]['level_money'];
            if ($amount >0  && $desc_money >=  5000 && $weight[$v['uid']]['level_money'] >= 25000) {
            //if ($amount >0 ) {
                $pre_data[] = ['uid'=>$v['uid'],'amount'=>$amount,"create_time"=>time(),"bonus_time"=>date("Ymd",strtotime("-1 day"))];
            }
        }

        $this->o_cron->get_mem_used();
        $this->db->trans_begin();
        if (!empty($pre_data)) {
            $this->tb_grant_pre_users_new_member_bonus->add_batch($pre_data);
        }
        echo "满足条件的人".count($pre_data);
        echo "第".$page."页执行状态：";
        var_dump($this->db->trans_status());


        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 预发放新会员专享奖成功,page:'.$page);
        } else {
            $this->db->trans_rollback();

            $this->load->model("tb_cron_status");
            $type = "new_member_bonus_pre";//预发奖
            $this->tb_cron_status->add_one($type,3,'[fail] 预发放新会员专享奖失败，page:\'.$page." ".json_encode($pre_data)');

            $this->m_log->createCronLog('[fail] 预发放新会员专享奖失败，page:'.$page." ".json_encode($pre_data));
        }

    }

    /**
     * @author brady
     * @desc 新用户分红奖发布
     * 1、获取预发布表里面的数据
     * 2、批量发布
     */
    public function doNewMemberShar()
    {
        $this->load->model("tb_grant_pre_users_new_member_bonus");
        $this->config->load('config_bonus');
        $this->load->model("tb_cron_status");
        $type = "new_member_bonus";

        $pageSize = config_item('bonus')['newMemberProfitShar']['pageSize']; //每页大小;
        $totalNum = $this->tb_grant_pre_users_new_member_bonus->get([
            'select'=>"uid,amount,bonus_time",
            'where'=>[
                'bonus_time'=>(int)(date("Ymd",strtotime("-1 day")))
            ]
        ],true,false,true);
        if ($totalNum > 0) {
            /*根据拿奖总人数划分分页,并分页处理发奖。*/
            $pageNum = ceil($totalNum/$pageSize);
            for($page=1;$page<=$pageNum;$page++){
                $this->o_pcntl->tps_pcntl_wait('$this->o_cron->doNewMemberSharPage(\''.$page.'\',\''.$pageSize.'\');');//用子进程处理每一页
            }
            $this->tb_cron_status->add_one($type,2,"发奖成功");
            $this->tb_cron_status->del_one($type);
        }else {
            $this->m_log->createCronLog('[fail] 预发布表未找到数据'.date("Y-m-d H:i:s"));
        }


    }


    /*分页发放新会员专享奖*/
    public function doNewMemberSharPage($page,$pageSize)
    {

        $this->load->model("tb_new_member_bonus");
        $this->load->model("o_bonus");
        $this->load->model("tb_cron_status");
        $type = "new_member_bonus";

        $res = $this->tb_grant_pre_users_new_member_bonus->get([
            'select'=>"id,uid,amount,bonus_time",
            'where'=>[
                'bonus_time'=>(int)(date("Ymd",strtotime("-1 day")))
            ],
            'limit'=>[
                'page'=>$page,
                'page_size'=>$pageSize
            ]
        ],false,false,true);
        $uids = [];
        foreach ($res as $v) {
            $uids[] = $v['uid'];
        }
        $data = [];
        foreach($res as $v) {
            $data[] = ['uid'=>$v['uid'],'money'=>$v['amount']];
        }
        //批量进行分红处理
        $res = $assign_res = $this->o_bonus->assign_bonus_batch($data,26);
        echo "第".$page."页执行状态：";
        var_dump($res);

        if ($res === TRUE) {
            $this->m_log->createCronLog('[Success] 发放新会员专享奖成功,page:'.$page);
        } else {
            $this->tb_cron_status->add_one($type,3,"发奖失败");
            $this->m_log->createCronLog('[fail] 发放新会员专享奖失败，page:'.$page." ".json_encode($data));
        }


    }

//    public function repair_new_member_bonus_failed($data,$item_type)
//    {
//        ini_set("memory_limit","5000M");
//        //第一步 过滤掉已经发奖的用户
//        $this->load->model("o_cash_account");
//        $this->load->model("o_bonus");
//        $uids = [];
//        $create_time = date("Y-m-d H:i:s",$data[0]['create_time']);
//        foreach($data as $k=>$v)
//        {
//            $uid = $v['uid'];
//            $res = $this->o_cash_account->check_exists($uid,$v['create_time'],$item_type);
//            if (!$res) {
//                unset($data[$k]);
//            }
//        }
//        $pre_data = [];
//        foreach($data as $v) {
//            $pre_data[] = ['uid'=>$v['uid'],'money'=>$v['amount']];
//        }
//
//        $res = $this->o_bonus->assign_bonus_batch_fix($pre_data,$item_type,$create_time);
//        var_dump($res);
//        if ($res == true){
//            $this->m_log->createCronLog('[success] 修复新会员奖成功'.json_encode($pre_data));
//        } else {
//            $this->m_log->createCronLog('[failed] 修复新会员奖失败 '.json_encode($pre_data));
//        }
//
//
//    }

//    public function daily_bonus_repair($uids,$limit=17,$table)
//    {
//        if(!empty($uids)) {
//            foreach($uids as $v) {
//                $this->o_pcntl->tps_pcntl_wait('$this->o_cron->daily_bonus_repair_page(\''.$v.'\',\''.$limit.'\',\''.$table.'\');');//用子进程处理每一页
//
//            }
//        }
//    }

//    public function daily_bonus_repair_page($uid,$limit,$table)
//    {
//        $this->load->model("o_fix_commission");
//        $sql = "select create_time from ".$table." where uid = ".$uid;
//        $res = $this->db->query($sql)->result_array();
//        for($i= 1; $i<= $limit;$i++) {
//            if ($i <10) {
//                $i = '0'.$i;
//            }
//            $arr[] = "2017-05-".$i;
//        }
//        if(!empty($res)) {
//            $create_arr = [];
//            foreach($res as $v) {
//                foreach($arr as $cretime) {
//                    if($cretime != substr($v['create_time'],0,10)) {
//                        $create_arr[$uid][] = $cretime;
//                    }
//                }
//
//            }
//
//            $create_arr[$uid] = array_unique($create_arr[$uid]);
//            if (!empty($create_arr[$uid])) {
//                foreach($create_arr[$uid] as $cre) {
//                    $this->o_fix_commission->daily_bonus_reissue($uid,$cre,$cre,6);
//                    var_dump("修复日分红：".$uid."--".$cre);
//                   // $this->m_log->createCronLog("修复日分红：".$uid."--".$cretime);
//                }
//            }
//        }
//
//
//    }

//    public function repaire_daily_bonus($yesterdayProfit)
//    {
//        ini_set("memory_limit","5000M");
//        $this->load->model('tb_daily_bonus_qualified_list');
//        $this->load->model('tb_grant_pre_users_daily_bonus');
//        $this->load->model("tb_bonus_log");
//        $this->load->model("tb_system_rebate_conf");
//        $this->config->load('config_bonus');
//        $this->load->model("o_bonus");
//        $this->load->model("tb_bonus_log");
//        $this->load->model("tb_system_rebate_conf");
//        //找出满足条件的用户
//        $sql = "select a.uid  from users_store_sale_info_monthly a left join users b
//on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500
//and (a.sale_amount>=10000 or b.user_rank<>4) ";
//        $res =$this->db->query($sql)->result_array();
//        $uids = [];
//        foreach($res as $v) {
//            $uids[] = $v['uid'];
//        }
//        //查询所有用户是否在日分红表里面
//        $in_list = $this->db->from('daily_bonus_qualified_list')
//            ->select("uid")
//            ->where_in("uid",$uids)
//            ->get()
//            ->result_array();
//        $in_uids = [];
//        if (!empty($in_list)) {
//            foreach($in_list as $v) {
//                $in_uids[] = $v['uid'];
//            }
//        }
//
//        $new_uids = array_diff($uids,$in_uids);
//        $this->m_log->createCronLog('[success] 修复日分红的的用户'.json_encode($new_uids));
//        $str = join(",",$new_uids);
//        //第一步，将其插入到表里面去
//        $sql1 = "select a.uid,a.sale_amount as amount,if(c.user_rank=4,1,if(c.user_rank=5,2,if(c.user_rank=3,3,if(c.user_rank=2,4,5)) ) ) as user_rank  from users_store_sale_info_monthly a left join users b
//on a.uid=b.id left join users as c on a.uid=c.id  where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500
//and (a.sale_amount>=10000 or b.user_rank<>4) and a.uid in ({$str})";
//        $data = $this->db->query($sql1)->result_array();
//
//        $sql = "insert ignore into daily_bonus_qualified_list(uid,amount,user_rank) values";
//        foreach($data as $v) {
//            $sql .= "(".$v['uid'].",".$v['amount'].",".$v['user_rank']."),";
//        }
//        $sql = substr($sql,0,strlen($sql) -1);
//        $this->db->query($sql);
//        //第二步，进行日分红
//        $this->load->model("o_bonus");
//
//
//        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
//        $rate_a = $obonus_rate['rate_a'];
//        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();/*统计今天日分红的总人数*/
//
//        $total_money = tps_int_format($yesterdayProfit * $rate_a);//根据配置的利润比例算出日分红金额
//        $total_rank =$this->tb_daily_bonus_qualified_list->get_total_rank();
//
//        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
//        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
//        $pre_data = [];
//        $num = 0;
//        $total_money_b = $total_money * $rate_b;
//        $total_money_c = $total_money * $rate_c;
//
//
//        foreach($data as $k=>$v) {
//
//
//            $amount1 = ($v['amount']/$total_sale) * $total_money_b;
//            $amount2 = ($v['user_rank'] / $total_rank )* $total_money_c;
//            $amount = $amount1 + $amount2;
//            $amount = tps_int_format($amount);
//            $num++;
//            $pre_data[] = ['uid'=>$v['uid'],'money'=>$amount];
//
//        }
//
//        $this->o_bonus->assign_bonus_batch($pre_data,6);
//        var_dump($this->db->trans_status());
//        if ($this->db->trans_status() == true){
//            echo "修复成功";
//            $this->m_log->createCronLog('[success] 修复日分红成功'.json_encode($pre_data));
//        } else {
//            $this->m_log->createCronLog('[failed] 修复日分红失败'.json_encode($pre_data));
//        }
//
//    }

    /**
     * @author brady
     * @desc  修复因为添加进入日分红队列amount 为0 而导致的发奖为0的记录
     */
//    public function repair_daily_bonus_amount()
//    {
//        $res = $this->db->query("select * from cash_account_log_201704 where item_type = 6 and amount = 0")->result_array();
//        $ids = [];
//        foreach($res as $v) {
//            $ids[] = $v['id'];
//        }
//        $this->db->trans_start();
//        $this->db->where_in('id',$ids)->delete("cash_account_log_201704");
//        var_dump($this->db->trans_status() == true);
//        if ($this->db->trans_status() == true) {
//            $this->db->trans_complete();
//            echo "修复成功";
//            $this->m_log->createCronLog('[success] 修复日分红为0成功'.json_encode($res));
//        } else {
//            $this->m_log->createCronLog('[failed] 修复日分红为0失败'.json_encode($res));
//        }
//    }




    /**
     * @author brady
     * @param $yesterdayProfit
     */
    public function doDailySharPre($yesterdayProfit)
    {
        ini_set('memory_limit', '5000M');
        $this->config->load('config_bonus');
        $this->load->model("o_bonus");
        $this->load->model('tb_daily_bonus_qualified_list');
        $this->load->model('tb_grant_pre_users_daily_bonus');
        $this->load->model("tb_bonus_log");
        $this->load->model("tb_system_rebate_conf");


        $pageSize = config_item('bonus')['dailyProfitShar']['pageSize']; //每页大小
        $start_time = time();
        //获取新用户分红的发奖比例
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0 ||  $obonus_rate['rate_b'] <= 0 || $obonus_rate['rate_c'] <= 0) {
            $this->m_log->createCronLog('[faile] 日分红发放失败 未设置发放比例.');
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1 || $obonus_rate['rate_b'] >= 1 || $obonus_rate['rate_c'] >= 1) {
            $this->m_log->createCronLog('[faile] 日分红发放失败 分红比例不能够超过1.');
            exit;
        }
        $rate_a = $obonus_rate["rate_a"]<0 ? 0.1 : $obonus_rate['rate_a'];

        $total_money = tps_int_format($yesterdayProfit * $rate_a);//根据配置的利润比例算出日分红金额

        if ($total_money > 0) {
            $totalNum = $this->tb_daily_bonus_qualified_list->getTodayDaiyUserList();/*统计今天日分红的总人数*/

            $total_rank =$this->tb_daily_bonus_qualified_list->get_total_rank();
            //今天预发奖之前，删除昨天的
            $this->tb_grant_pre_users_daily_bonus->empty_data();

            if ($totalNum > 0 ) {
                /*根据拿奖总人数划分分页,并分页处理发奖。*/

                $pageNum = ceil($totalNum / $pageSize);
                for($page=1;$page<=$pageNum;$page++){
                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->doDailySharPrePagePre(\''.$page.'\',\''.$pageSize.'\',\''.$total_money.'\',\''.$total_rank.'\');');//用子进程处理每一页
                }

                $end_time = time();
                echo "一共耗时".($end_time-$start_time)."s";
            } else {
                $this->m_log->createCronLog('[faile] 没有满足日分红的用户'.date("Y-m-d H:i:s"));
            }


        } else {
            $this->m_log->createCronLog('[faile] 日分红发放利润不足'.date("Y-m-d H:i:s"));
        }

    }

    /**
     * @author brady
     * @desc 分页预发布 日分红
     * @param $page 当前页
     * @param $pageSize 每页大小
     * @param $amountAvg 用来均分的金额
     * @param $totalAmountPoint 用来按照分红点分的金额
     * @param $totalPoint 用户总的分红点
     */
    public function doDailySharPrePagePre($page,$pageSize,$total_money,$total_rank)
    {
        ini_set('memory_limit', '5000M');
        $this->load->model("tb_new_member_bonus");
        $this->load->model("o_bonus");
        $this->load->model("tb_daily_bonus_qualified_list");
        $this->load->model("tb_users_sharing_point_reward");
        $this->load->model("tb_grant_pre_users_daily_bonus");
        $this->db->trans_begin();
        $res = $this->tb_daily_bonus_qualified_list->getTodayDaiyUserListByPage($page,$pageSize);
        //找到总的排序
        $this->config->load('config_bonus');
        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();/*统计今天日分红的总人数*/
        $uids = [];
        foreach ($res as $v) {
            $uids[] = $v['uid'];
        }
        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
        $pre_data = [];
        $num = 0;
        $total_money_b = $total_money * $rate_b;
        $total_money_c = $total_money * $rate_c;

        $uid_error = ['1380598335','1380619456','1380797257', '1381209207', '1381211480', '1381595612', '1381682103', '1381688855', '1381876393','1381883822','1382292467'];
        $this->load->model("tb_debug_logs");
        //$this->tb_debug_logs->add_logs(['content'=>'page:'.$page.' 分红的人:'.json_encode($res)]);
        foreach($res as $k=>$v) {

//            var_dump("用户id".$v['uid']);
//            var_dump("我的销售额".$v['sale_amount']);

            $amount1 = ($v['amount']/$total_sale) * $total_money_b;
            $amount2 = ($v['user_rank'] / $total_rank )* $total_money_c;
            $amount = $amount1 + $amount2;

            $amount = tps_int_format($amount);
            if (in_array($v['uid'],$uid_error)) {
                $this->tb_debug_logs->add_logs(['content'=>'用户'.$v['uid']."进入预发奖队列 amount=".$amount]);
            }
            //if ($amount >0) {
                $num++;
                $pre_data[] = ['uid'=>$v['uid'],'amount'=>$amount,"create_time"=>time(),"bonus_time"=>date("Ymd",strtotime("-1 day"))];
            //}
        }

        $this->tb_grant_pre_users_daily_bonus->add_batch($pre_data);
        echo $num;
        echo "第".$page."页执行状态：";
        var_dump($this->db->trans_status());

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 预发全球放日分红成功,page:'.$page);
        } else {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[fail] 预发放全球日分红失败，page:'.$page." 分红大于0满足条件的人数: ".$num."个".json_encode($pre_data));
        }
    }



//    public function doDailySharPrePagePreSpecial($page,$pageSize,$total_money,$total_rank)
//    {
//        ini_set('memory_limit', '5000M');
//        $this->load->model("tb_new_member_bonus");
//        $this->load->model("o_bonus");
//        $this->load->model("tb_daily_bonus_qualified_list");
//        $this->load->model("tb_users_sharing_point_reward");
//        $this->load->model("tb_grant_pre_users_daily_bonus");
//        $this->db->trans_begin();
//        $sql = "SELECT a.* from daily_bonus_qualified_list as a join users as b on a.uid = b.id  WHERE b.`status` = 1   and  a.uid  not in (SELECT uid from grant_pre_users_daily_bonus) GROUP BY a.uid
//";
//        $res = $this->db->force_master()->query($sql)->result_array();
//
//        //找到总的排序
//        $this->config->load('config_bonus');
//        $total_sale = $this->tb_daily_bonus_qualified_list->getTotalSale();/*统计今天日分红的总人数*/
//        $uids = [];
//        foreach ($res as $v) {
//            $uids[] = $v['uid'];
//        }
//        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(6);
//        $rate_b = $obonus_rate['rate_b'] <0 ? 0.5 : $obonus_rate['rate_b'];
//        $rate_c = $obonus_rate['rate_c'] <0 ? 0.5 : $obonus_rate['rate_c'];
//        $pre_data = [];
//        $num = 0;
//        $total_money_b = $total_money * $rate_b;
//        $total_money_c = $total_money * $rate_c;
//
//        foreach($res as $k=>$v) {
//
//
//            $amount1 = ($v['amount']/$total_sale) * $total_money_b;
//            $amount2 = ($v['user_rank'] / $total_rank )* $total_money_c;
//            $amount = $amount1 + $amount2;
//
//            $amount = tps_int_format($amount);
//
//            //if ($amount >0) {
//            $num++;
//            $pre_data[] = ['uid'=>$v['uid'],'amount'=>$amount,"create_time"=>time(),"bonus_time"=>date("Ymd",strtotime("-1 day"))];
//            //}
//        }
//
//        $this->tb_grant_pre_users_daily_bonus->add_batch($pre_data);
//        echo $num;
//        echo "第".$page."页执行状态：";
//        var_dump($this->db->trans_status());
//
//        if ($this->db->trans_status() === TRUE) {
//            $this->db->trans_commit();
//            $this->m_log->createCronLog('[Success] 特殊————预发全球放日分红成功,page:'.json_encode($res));
//        } else {
//            $this->db->trans_rollback();
//            $this->m_log->createCronLog('[fail] 特殊————预发放全球日分红失败，page:'.$page." 分红大于0满足条件的人数: ".$num."个".json_encode($pre_data));
//        }
//    }


    /*发放日分红*/
    public function doDailyShar($yesterdayProfit){
        $this->load->model("tb_grant_pre_users_daily_bonus");
        $this->config->load('config_bonus');
        $start_time = time();
        $pageSize = config_item('bonus')['dailyProfitShar']['pageSize']; //每页大小;
        $totalNum = $this->tb_grant_pre_users_daily_bonus->get([
            'select'=>"uid,amount,bonus_time",
            'where'=>[
                'bonus_time'=>(int)(date("Ymd",strtotime("-1 day")))
            ]
        ],true);

        if ($totalNum > 0) {
            /*根据拿奖总人数划分分页,并分页处理发奖。*/
            $pageNum = ceil($totalNum/$pageSize);
            for($page=1;$page<=$pageNum;$page++){

                $this->o_pcntl->tps_pcntl_wait('$this->o_cron->doDailySharPage(\''.$page.'\',\''.$pageSize.'\');');//用子进程处理每一页
            }
            $end_time = time();
            echo "一共耗时".($end_time-$start_time);
        }else {
            $this->m_log->createCronLog('[fail] 日分红预发布表未找到数据'.date("Y-m-d H:i:s"));
        }
    }
    /*分页发放日分红*/
    public function doDailySharPage($page,$pageSize)
    {

        $this->load->model("tb_grant_pre_users_daily_bonus");
        $this->load->model("o_bonus");
        $param =[
            'select'=>"id,uid,amount,bonus_time",
            'where'=>[
                'bonus_time'=>(int)(date("Ymd",strtotime("-1 day")))
            ],
            'limit'=>[
                'page'=>$page,
                'page_size'=>$pageSize
            ]
        ];

        $res = $this->tb_grant_pre_users_daily_bonus->get($param);

        //var_dump($res);
//        $start = ($page - 1) * $pageSize;
//        $sql = "SELECT `id`, `uid`, `amount`, `bonus_time`
//FROM (`grant_pre_users_daily_bonus`)
//WHERE `bonus_time` =  ".(int)(date("Ymd",strtotime("-1 day")))."
//LIMIT {$start},{$pageSize}";
//        $res = $this->db_slave->query($sql)->result_array();
//        var_dump($res);exit;
        $ids = [];
        $uids = [];
        foreach ($res as $v) {
            $uids[] = $v['uid'];
        }
       // echo $this->db_slave->last_query();

        $data = [];
        foreach($res as $v) {
            $data[] = ['uid'=>$v['uid'],'money'=>$v['amount']];
        }

        //批量进行分红处理

         $res = $assign_res = $this->o_bonus->assign_bonus_batch($data,6);
         echo "第".$page."页执行状态：";
         var_dump($res);

        if ($res === TRUE) {
            $this->m_log->createCronLog('[Success] 发放日分红奖成功,page:'.$page);
        } else {
            $this->m_log->createCronLog('[fail] 发放日分红奖失败，page:'.$page." ".json_encode($data));
        }
    }

    /*发放138分红*/
    public function do138Shar($yesterdayProfit){

        $this->load->model('tb_user_qualified_for_138');

        $totalSharAmount = tps_money_format($yesterdayProfit * config_item('bonus')['138Shar']['profitProp']);//根据配置的利润比例算出发放金额
        if($totalSharAmount>0){//-------有金额

            $this->db->query("call 138_shar_prepare(@totalNum,@totalSharNum)");
            $res = $this->db->query("select @totalNum,@totalSharNum")->row_array();
            sleep(8);
            @$this->db->reconnect();
            // $totalNum = $res['@totalNum'];/*今天138发奖的总人数*/
            // $totalSharNum = $res['@totalSharNum'];//今天138人员矩阵底下人数总和
            $totalNum = $this->db->query("select count(*) as totalNum from user_qualified_for_138")->row_object()->totalNum;
            $totalSharNum = $this->db->query("select sum(num_share) as totalSharNum from 138_grant_tmp")->row_object()->totalSharNum;

            if($totalNum > 0){//-----有需要发奖的人员

                $totalAmountAvg = $totalSharAmount * config_item('bonus')['138Shar']['avgProp'];//用来均摊的总利润.
                $amountAvg = tps_money_format($totalAmountAvg/$totalNum);//每个人可以拿到的均摊利润。
                $totalAmountOther = $totalSharAmount-$totalAmountAvg;//用来按照矩阵发放的总利润.

                /*根据拿奖总人数划分分页,并分页处理发奖。*/
                $pageNum = ceil($totalNum/config_item('bonus')['138Shar']['pageSize']);
                for($page=1;$page<=$pageNum;$page++){

                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->do138SharPage(\''.config_item('bonus')['138Shar']['pageSize'].'\',\''.$amountAvg.'\',\''.$totalAmountOther.'\',\''.$totalSharNum.'\');');//用子进程处理每一页
                }
                $this->m_log->createCronLog('[Success] 发放138分红。');
            }else{

                $this->m_log->createCronLog('[false] 今天没有满足138的人。');
            }
        }else{

            $this->m_log->createCronLog('[false] 138发放利润不足。');
        }
    }

    /*分页发放138*/
    public function do138SharPage($pageSize,$averageMoney,$totalAmountOther,$totalSharNum){

        $this->load->model('tb_users');
        $this->load->model('tb_138_grant_tmp');
        $this->load->model('m_profit_sharing');

        $this->db->trans_begin();

        $res = $this->tb_138_grant_tmp->getToday138UserListByPage($pageSize);
        foreach($res as $v){

            $uid = $v['uid'];
            $resItem = $this->tb_users->getQualifiedUser($uid);
            if($resItem){

                $commission_amount = tps_money_format($v['num_share']/$totalSharNum*$totalAmountOther)+$averageMoney;
                $this->m_profit_sharing->assign138Commission($uid,$commission_amount);//发放138
            }
            $this->tb_138_grant_tmp->deleteByUid($uid);
        }

        if ($this->db->trans_status() === TRUE) {

            $this->db->trans_commit();
        } else {

            $this->db->trans_rollback();
            $this->m_log->createCronLog('[fail] 发放138失败，page:'.$page);
        }
    }

    public function fix_138_page(){

        // $this->o_pcntl->tps_pcntl_wait('$this->o_cron->do138SharPage(\'1000\',\'0.5\',\'52077.78\',\'111012229\');');//用子进程处理每一页

        /*根据拿奖总人数划分分页,并分页处理发奖。*/
        $res = $this->db->query("select count(*) as totalNum from 138_grant_tmp")->row_array();
        $totalNum = $res['totalNum'];
        $pageNum = ceil($totalNum/1000);
        for($page=1;$page<=$pageNum;$page++){

            // $this->o_pcntl->tps_pcntl_wait('$this->o_cron->do138SharPage(\''.config_item('bonus')['138Shar']['pageSize'].'\',\''.$amountAvg.'\',\''.$totalAmountOther.'\',\''.$totalSharNum.'\');');//用子进程处理每一页
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->do138SharPage(\'1000\',\'0.5\',\'52077.78\',\'111012229\');');//用子进程处理每一页
        }
    }

    /*发放精英日分红*/
    public function doDailyEliteShar($yesterdayProfit){

        $this->load->model('tb_daily_bonus_elite_qualified_list');

        $totalSharAmount = tps_int_format($yesterdayProfit * config_item('bonus')['dailyEliteProfitShar']['profitProp'] *100);//由配置利润比例算出发放金额
        if($totalSharAmount>0){//-------有金额

            $totalNum = $this->tb_daily_bonus_elite_qualified_list->getDailyEliteSharNum();/*统计今天分红的总人数*/
            if($totalNum > 0){//-----有需要发奖的人员

                $totalWeight = $this->tb_daily_bonus_elite_qualified_list->statDailyEliteSharTotalWeight();/*统计发奖人员总权重点*/

                /*根据拿奖总人数划分分页,并分页处理发奖。*/
                $pageNum = ceil($totalNum/config_item('bonus')['dailyEliteProfitShar']['pageSize']);
                for($page=1;$page<=$pageNum;$page++){

                    $this->o_pcntl->tps_pcntl_wait('$this->o_cron->doDailyEliteSharPage(\''.$page.'\',\''.config_item('bonus')['dailyEliteProfitShar']['pageSize'].'\',\''.$totalSharAmount.'\',\''.$totalWeight.'\');');//用子进程处理每一页
                }
                $this->m_log->createCronLog('[Success] 发放精英日分红。');
            }else{

                $this->m_log->createCronLog('[false] 今天没有满足精英日分红的人。');
            }
        }else{

            $this->m_log->createCronLog('[false] 精英日分红发放利润不足。');
        }
    }

    /*分页发放精英日分红*/
    public function doDailyEliteSharPage($page,$pageSize,$totalSharAmount,$totalWeight){

        $this->db->query("call do_daily_elite_shar_page($page,$pageSize,$totalSharAmount,$totalWeight)");
    }

    public function count_monthly_pages($tol_pages = 0,$pageSize = 5, $page = 0){
        $this->load->model('m_debug');

        $lim_sta = $page  * $pageSize;
        $user_list = $this->db->query("select uid from daily_bonus_qualified_list ORDER BY uid limit $lim_sta,$pageSize")->result_array();
//        print_r($user_list);exit;
        foreach($user_list as $k=>$v){
            $uid = $v['uid'];
            $monthAmount = 0;

            $this->db->trans_start();//事务开始

            //统计会员三月份销售额
            //本条件无需使用tb_trade_orders
            $res = $this->db->select('goods_amount_usd')->from('trade_orders')->where('shopkeeper_id',$uid)->where('score_year_month','201703')->where_in('status',array('1','3','4','5','6','90','97','111'))->where_in('order_prop',array('0','1'))->get()->result_array();
//            print_r($res);exit;

            if(!empty($res)){
                foreach($res as $v){
                    $monthAmount+=empty($v['goods_amount_usd'])?0:$v['goods_amount_usd'];
                }
            }

            $res = $this->db->select('order_amount_usd')->from('mall_orders')->where('shopkeeper_id',$uid)->where('score_year_month','201703')->where('status',1)->get()->result_array();
            if(!empty($res)){
                foreach($res as $v){
                    $monthAmount+=empty($v['goods_amount_usd'])?0:$v['goods_amount_usd'];
                }
            }

            $res = $this->db->select('order_amount_usd')->from('one_direct_orders')->where('shopkeeper_id',$uid)->where('score_year_month','201703')->where('status',1)->get()->result_array();
            if(!empty($res)){
                foreach($res as $v){
                    $monthAmount+=empty($v['goods_amount_usd'])?0:$v['goods_amount_usd'];
                }
            }

            $res = $this->db->select('order_amount_usd')->from('walmart_orders')->where('shopkeeper_id',$uid)->where('status',1)->where('score_year_month','201703')->get()->result_array();
            if(!empty($res)){
                foreach($res as $v){
                    $monthAmount+=empty($v['goods_amount_usd'])?0:$v['goods_amount_usd'];
                }
            }

            //查询之前的数据，做记录
            $before_data = $this->db->query("select sale_amount from users_store_sale_info_monthly where uid = $uid and `year_month` = '201703'")->row_array();
//            print_r($monthAmount);exit;
            //当金额不一致时需要修复，并记录日志
            $before_data = empty($before_data['sale_amount']) ? 0 : $before_data['sale_amount'];
            $ce = $monthAmount - $before_data;
            if($ce != 0){
                //更新会员没有销售额
                $this->db->where('uid',$uid)->where('year_month','201703')->update('users_store_sale_info_monthly',array('orders_num'=>9999,'sale_amount'=>$monthAmount));

                //更新会员总销售额
                $this->db->query("UPDATE users_store_sale_info SET sale_amount = sale_amount + $ce WHERE uid = $uid");

                //把3月份月绩有误的数据记录下来并更新数据
                $this->db->query("insert into user_march_monthly_logs (uid,front_monthly,back_monthly)VALUE ($uid,$before_data,$monthAmount)");
            }else{
                $this->m_debug->dd_log("无需修改",$uid,$before_data,$monthAmount,'1223');
            }
            $this->db->trans_complete();
            //事务回滚了,记录日志
            if ($this->db->trans_status() === FALSE)
            {
                $this->m_debug->dd_log("事务回滚",$uid,$before_data,$monthAmount,'1222');
            }
            echo $k.' '.$uid."\n";
        }
        echo '总共页码数:'.$tol_pages.';每页的条数'.$pageSize.';现在运行到的页码数：'.$page."\n";
    }

    public function unfrost_user()
    {
        $this->load->model("tb_user_frost_list");
        $ids = $this->tb_user_frost_list->cron_unfrost_user();
        $msg = "[success]解冻用户成功".json_encode($ids);
        var_dump($msg);
        $this->m_log->createCronLog($msg);
    }

    /**
     * 修复会员单个月的月绩
     * @param string $uid            不能为空
     * @param null $score_year_month 月份默认获取当前月份的上个月，如果传参则为指定的月份，格式：201706
     */
    public function count_monthly($uid = '',$score_year_month = null){
        $this->load->model('m_debug');

        if($uid == ''){
            echo 'User ID cannot be empty';
            exit;
        }
        if($score_year_month == null){
            $score_year_month = date("Ym", strtotime("-1 month"));
        }
        $monthAmount = 0;

        //统计会员销售额
//        $res = $this->db->select('goods_amount_usd')->from('trade_orders')->where('shopkeeper_id',$uid)->where('score_year_month',$score_year_month)->where_in('status',array('1','3','4','5','6','90','97','111'))->where_in('order_prop',array('0','1'))->get()->result_array();
        $this->load->model("tb_trade_orders");
        $res = $this->tb_trade_orders->get_list_auto([
            "select"=>"goods_amount_usd",
            "where"=>[
                "shopkeeper_id"=>$uid,
                "score_year_month"=>$score_year_month,
                "status"=>array('1','3','4','5','6','90','97','111'),
                "order_prop"=>array('0','1')
            ]
        ]);
        if(!empty($res)){
            foreach($res as $v){
                $monthAmount+=empty($v['goods_amount_usd'])?0:$v['goods_amount_usd'];
            }
        }

        $res = $this->db->select('order_amount_usd')->from('mall_orders')->where('shopkeeper_id',$uid)->where('score_year_month',$score_year_month)->where('status',1)->get()->result_array();
        if(!empty($res)){
            foreach($res as $v){
                $monthAmount+=empty($v['order_amount_usd'])?0:$v['order_amount_usd'] * 100;
            }
        }

        $res = $this->db->select('order_amount_usd')->from('one_direct_orders')->where('shopkeeper_id',$uid)->where('score_year_month',$score_year_month)->where('status',1)->get()->result_array();
        if(!empty($res)){
            foreach($res as $v){
                $monthAmount+=empty($v['order_amount_usd'])?0:$v['order_amount_usd'] * 100;
            }
        }

        $res = $this->db->select('order_amount_usd')->from('walmart_orders')->where('shopkeeper_id',$uid)->where('status',1)->where('score_year_month',$score_year_month)->get()->result_array();
        if(!empty($res)){
            foreach($res as $v){
                $monthAmount+=empty($v['order_amount_usd'])?0:$v['order_amount_usd'] * 100;
            }
        }

        //查询之前的数据，做记录
        $before_data = $this->db->query("select sale_amount from users_store_sale_info_monthly where uid = $uid and `year_month` = $score_year_month")->row_array();

        //当金额不一致时需要修复，并记录日志
        $before_data = empty($before_data['sale_amount']) ? 0 : $before_data['sale_amount'];
        //四舍五入
        $before_data = round($before_data);
        $monthAmount = round($monthAmount);
        $ce = $monthAmount - $before_data;
        if((int)$monthAmount != (int)$before_data){
            //更新会员没有销售额
            if(empty($before_data)){
                $this->db->query("insert into users_store_sale_info_monthly (uid,`year_month`,orders_num,sale_amount)VALUE ($uid,$score_year_month,9996,$monthAmount)");
            }else{
                $this->db->where('uid',$uid)->where('year_month',$score_year_month)->update('users_store_sale_info_monthly',array('orders_num'=>9997,'sale_amount'=>$monthAmount));
            }

            //更新会员总销售额
            $this->db->query("UPDATE users_store_sale_info SET sale_amount = sale_amount + $ce WHERE uid = $uid");

            //把3月份月绩有误的数据记录下来并更新数据
            $this->db->query("insert into user_march_monthly_logs (uid,front_monthly,back_monthly)VALUE ($uid,$before_data,$monthAmount)");
        }
    }
    
    /**
     * 获取修复业绩会员总数
     * @return 执行修复会员总数 <number, unknown>
     */
    public function get_modify_user_monthly_total()
    {
        $s_time = date('Y-m-d')." 00:00:00";
        $e_time = date('Y-m-d')." 23:59:59";
        $sql = "SELECT count(*) as total FROM user_march_monthly_logs where create_time BETWEEN '".$s_time."' AND '".$e_time."'";
        $query = $this->db->query($sql)->row_array();
        return $query['total']>0?$query['total']:0;
    }
    
 
    /**
     * 获取会员转账监控错误总数
     * @return Ambigous <number, unknown>
     */
    public function get_user_account_err_total()
    {
        $s_time = date('Y-m-d')." 00:00:00";
        $e_time = date('Y-m-d')." 23:59:59";
        $sql = "SELECT count(*) as total FROM user_transfer_account_waring where create_time BETWEEN '".$s_time."' AND '".$e_time."'";
        $query = $this->db->query($sql)->row_array();
        return $query['total']>0?$query['total']:0;
    }
    
    
    
    
    
    
}
