<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
header("Content-type: text/html; charset=utf-8");

/**
 * 计划任务类
 * @author  Terry Lu
 */
class Cron extends MY_Controller {

    public function __construct() {
        parent::__construct();
        ignore_user_abort();
        set_time_limit(0);

        /*Force run this script in CLI. Added by Terry.*/
//        if(!$this->input->is_cli_request()){
//            echo 'Please run this script in CLI.';
//            exit;
//        }
        $this->load->model('o_cron');
        $this->load->model('o_pcntl');
               
    }

    /**
     * @author brady
     * @desc 初始化用户的积分
     */
    public function init_user_credit($page_size = 100)
    {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("memory_limit","5000M");
        $this->o_cron->init_user_credit($page_size);
    }

    /**
     * @author brady
     * @desc 用户积分队列
     */
    public function users_rank_queue($page_size=100)
    {
        $this->o_cron->users_rank_queue($page_size);
    }

    /**
     * @author brady
     * @desc 用户积分队列
     */
    public function sale_rank_queue($page_size=100)
    {
        $this->o_cron->sale_rank_queue($page_size);
    }


    public function generate_users($num = 100)
    {
        $this->load->model("tb_users");

        $this->o_cron->generate_users($num);

    }

    public function calc_company_total_monthly_history($month='')
    {
        ini_set("memory_limit","4000M");

//        $year_month = 201606;
//        $year_months = [$year_month];
//        $this->o_cron->calc_company_total_monthly_history($year_months);
//        exit;


            if(!empty($month)) {
                $year_month = $month;
                $year_months = [$year_month];
            } else {
                $sql_del = "delete from temp_uid where 1";
                $this->db->query($sql_del);
                $sql = "insert into temp_uid(uid) select id from users where country_id = 1";
                $this->db->query($sql);
                $rows = $this->db->affected_rows();
                if ($rows >0) {
                    $year_months = ['201601','201602','201603','201604','201605','201606','201607','201608','201609','201610','201611','201612','201701','201702','201703'];
                } else {
                    echo "进入临时uid表失败";
                }

            }
            $this->o_cron->calc_company_total_monthly_history($year_months);


    }

    /**
     * @author brady
     * @desc   每月初统计满足条件的日分红用户
     */
    public function daily_bonus_month()
    {
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(6,1,0,0,"重置",1);
            $this->bonus_plan_control(6,1,time(),0,"日分红用户加入队列",0);
            $this->o_cron->daily_bonus_month();
            $this->bonus_plan_control(6,1,0,time(),"日分红用户加入队列完毕",0);
        }        
    }

    /**
     * @author brady
     * @description 统计
     * 统计每天全球利润
     */
    public function calc_profit()
    {

        $this->load->model("o_company_money_today_total");
        $this->o_company_money_today_total->calc_company_yesterday_profit();
    }

    /**
     * @author brady
     * @desc    每天凌晨 统计完全球利润后几分钟 定时删除 已经不满足新会员奖的用户
     */
    public function del_not_match_new_member_bonus()
    {
        $this->o_cron->del_not_match_new_member_bonus();
    }

    /**
     * @author brady
     * @desc 每天统计预发奖队列
     */
    public function new_member_bonus_queue()
    {
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(26,1,0,0,"重置",1);
            $this->load->model("tb_cron_status");
            $type = "new_member_bonus_queue";

            $where = ['type'=>$type,'cron_day'=>date("Ymd")];
            $this->tb_cron_status->delete($where);
            
            $insert_id = $this->tb_cron_status->add_one($type,1,"开始统计合格人数"); //正在执行中

            if ($insert_id <= 0) {
                throw new Exception("[faile]插入cron_status表失败");
            }
            $this->bonus_plan_control(26,1,time(),0,"新会员加入队列",0);
            $this->o_cron->del_not_match_new_member_bonus();
            $this->o_cron->new_member_bonus_queue();
            $this->bonus_plan_control(26,1,0,time(),"新会员加入队列完毕",0);
        }
    }

    /**
     * 每天凌晨跑任务，删除该表，没用的验证码
     */
    public function del_mobile_message_log()
    {
        $sql = "DELETE  from mobile_message_log  where create_time < unix_timestamp(DATE_SUB(curdate(),INTERVAL 3 DAY))";
        $this->db->query($sql);
        $this->m_log->createCronLog('[success]每天清空mobile_message_log表  ');
    }

    /**
     * @author brady
     * @description 新用户分红奖 预发
     */
    public function new_member_bonus_pre()
    {
        $this->bonus_plan_control(26,1,time(),0,"新会员统计预发奖队列开始",0);
        ini_set('memory_limit', '2048M');
        $this->load->model('o_company_money_today_total');
        $this->load->model("tb_cron_status");
        $type = "new_member_bonus_pre";//预发奖
        //删除类型为预发奖的
        $where = ['type'=>$type,'cron_day'=>date("Ymd")];
        $this->tb_cron_status->delete($where);
        $failed = $this->tb_cron_status->get_failed("new_member_bonus_queue");
        if (!$failed) {
            echo "统计合格用户失败";
            $this->m_log->createCronLog('[faile]统计合格用户失败');
            exit;
        } else {

            $insert_id = $this->tb_cron_status->add_one($type,1,"开始预发奖"); //正在执行中
            if ($insert_id <= 0) {
                $this->m_log->createCronLog('预发奖插入cron队列失败');
                exit;
            }
        }
        $yesterdayProfit = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润 单位美元*/
        $this->o_cron->doNewMemberSharPre($yesterdayProfit['money']);/*处理发放新用户专属奖*/
        $this->o_cron->get_mem_used();
        $this->bonus_plan_control(26,1,0,time(),"新会员统计预发奖队列完毕",0);
    }

    /**
     * @author brady
     * @description 新用户分红奖 实际发奖
     */
    public function new_member_bonus()
    {
        
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(26,1,time(),0,"新会员实际发奖开始",0);
            ini_set('memory_limit', '2048M');
            $this->load->model('o_company_money_today_total');
            $this->load->model("tb_cron_status");
            $failed = $this->tb_cron_status->get_failed("new_member_bonus_pre");
            $type = "new_member_bonus";
            //删除类型为预发奖的
            $where = ['type'=>$type,'cron_day'=>date("Ymd")];
            $this->tb_cron_status->delete($where);
            if (!$failed) {
                echo "预发奖异常";
                $this->m_log->createCronLog('[faile]预发奖异常 不能进行发奖');
                exit;
            } else {
            
                $insert_id = $this->tb_cron_status->add_one($type,1,"开始发奖"); //正在执行中
                if ($insert_id <= 0) {
                    $this->m_log->createCronLog('发奖插入cron队列失败');
                    exit;
                }
            }
            
            $yesterdayProfit = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润 单位美元*/
            //$this->o_cron->doNewMemberSharPre($yesterdayProfit['money']);/*处理发放新用户专属奖*/
            //sleep(10);
            $this->o_cron->doNewMemberShar($yesterdayProfit['money']);/*处理发放新用户专属奖*/
            $this->bonus_plan_control(26,2,0,time(),"新会员实际发奖完成",0);
        }        
    }

    /**
     * 新会员手动发奖
     * author Able
     */
    public function set_new_member_control(){
        $this->load->model("tb_bonus_plan_control");
        $this->db->trans_begin();
        $data = $this->tb_bonus_plan_control->getState(26);
        if($data['status'] ==2){ //等于2为已执行过发奖
            return false;
        }
        $this->tb_bonus_plan_control->changeExecStatus(26,['ishanding'=>1]);
        if($data['ishand']>0){
            $this->new_member_bonus_pre();
        }
        $this->new_member_bonus();
        $this->tb_bonus_plan_control->changeExecStatus(26,["ishand"=>0,"ishanding"=>0]);
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }
    }



    /**
     * @author brady
     * @description每天全球利润分红 预发布
     */
    public function daily_bonus_pre()
    {
        $this->bonus_plan_control(6,1,time(),0,"日分红用户加入预发队列开始",0);
        $this->load->model('o_company_money_today_total');
        $yesterdayProfitArr = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润 单位美元*/
        $this->o_cron->doDailySharPre($yesterdayProfitArr['money']);/*处理发放日分红*/
        $this->bonus_plan_control(6,1,0,time(),"日分红用户加入预发队列完成",0);
    }

    /**
     * @author brady
     * @description每天全球利润分红
     */
    public function daily_bonus()
    {
        $this->bonus_plan_control(6,1,time(),0,"每天全球利润分红发奖开始",0);
        $this->load->model('o_company_money_today_total');
        $yesterdayProfitArr = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润 单位美元*/

        $this->o_cron->doDailySharPre($yesterdayProfitArr['money']);/*预处理发放日分红*/
        sleep(10);
        $this->o_cron->doDailyShar($yesterdayProfitArr['money']);/*处理发放日分红*/
        $this->bonus_plan_control(6,2,0,time(),"每天全球利润分红发奖完成",0);
    }

    public function daily_bonus_real()
    {
        $this->load->model('o_company_money_today_total');
        $yesterdayProfitArr = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润 单位美元*/

        $this->o_cron->doDailyShar($yesterdayProfitArr['money']);/*处理发放日分红*/
    }

    /**
     * @author brady
     * @description 每月初 统计每月利润，把每天全球利润加起来
     */
    public function calc_profit_month()
    {
        $this->load->model("o_company_money_today_total");
        $this->o_company_money_today_total->calc_profit_month();
    }

    /**
     * 计划任务test,在CLI中执行方法：php index.php cron test param1 param2
     * 执行频率：每天下午17点。
     * @param type $param1
     * @param type $param2
     */
    public function test($uid, $commission) {

        $this->load->model('m_profit_sharing');
        $this->m_profit_sharing->assignSharingCommission($uid, $commission,24);
    }

    //new r
    public function fixCeoComm($istest=1){

        ini_set('memory_limit', '5120M');

        $this->load->model('m_profit_sharing');
        $this->load->model('m_commission');
        $this->load->model('o_bonus');       
        
        $ceoList = $this->db->query("select id,child_count from users where sale_rank=5 and sale_rank_up_time<'2017-06-01'
 and user_rank=1 order by id asc")->result_array();
        foreach($ceoList as $v){
            $uid = $v['id'];

            $check_fix_sql = "select * from cash_account_log_201706 where item_type = 4 and uid = ".$uid;
            $check_query = $this->db->query($check_fix_sql)->result_array();
            if(!empty($check_query))
            {
                continue;
            }
            $this->m_debug->log($uid.':'.-1001);
            
            /*检查上个月销售额是否满足*/
            $res = $this->db->query("select sale_amount from users_store_sale_info_monthly where uid=$uid and `year_month`=201705")->row_array();
            if($res && $res['sale_amount']>=25000){

                /*检查团队是否满足3000*/
                $branchUids = $this->db->query("select id from users where parent_id=".$uid)->result_array();
                $totalBranchCount = 0;
                foreach($branchUids as $v2){
                    $branchUid = $v2['id'];
                    $countBranch = $this->db->query("select count(*) as countBranch from users where user_rank<>4 and `status`=1 and enable_time<'2017-06-01' 
    and parent_ids like '%".$branchUid."%'")->row_object()->countBranch;
                    $totalBranchCount+=($countBranch>1500?1500:$countBranch);
                    if($totalBranchCount>=3000){
                        break;
                    }
                }
                if($totalBranchCount>=3000){

                    if($uid==1380100223){
                        $v['child_count'] = $v['child_count']/2.9;
                    }elseif($uid==1380100227){
                        $v['child_count'] = $v['child_count']/1.8;
                    }elseif($uid==1380100360){
                        $v['child_count'] = $v['child_count']/2.1;
                    }elseif($uid==1380100948){
                        $v['child_count'] = $v['child_count']/1.6;
                    }elseif($uid==1380100970){
                        $v['child_count'] = $v['child_count']/1.9;
                    }elseif($uid==1380101023){
                        $v['child_count'] = $v['child_count']/1.6;
                    }

                    if($v['child_count']<13000){
                        $v['child_count']=round($v['child_count']/10)+13000;
                    }
                    $ceoComm = tps_money_format($v['child_count']/83885 * 2426.54);

                    if($istest==1){
                        $this->m_debug->log($uid.':'.$ceoComm);
                    }else{

                        //发放全球副总裁奖                        
                        $user_data[] = array
                        (
                            'uid' => $uid,
                            'money' => $ceoComm * 100
                        );
                        $c_time = date('Y-m-15 12:00:00');
                        $this->o_bonus->assign_bonus_batch_fix($user_data,REWARD_4,$c_time);
                        
                        //插入佣金记录
                        //$this->m_commission->commissionLogs($uid,REWARD_4,$ceoComm);

                        /* 佣金自动转分红点 */
                        /*
                        $rate = $this->m_profit_sharing->getProportion($uid, 'sale_commissions_proportion') / 100;
                        $commissionToPoint = 0;
                        if ($rate > 0) {
                            $commissionToPoint = tps_money_format($ceoComm * $rate);
                            if($commissionToPoint>=0.01){
                                $this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+' . $commissionToPoint, FALSE)->update('users');

                                $comm_id = $this->m_commission->commissionLogs($uid,17,-1*$commissionToPoint); //佣金轉分紅點

                                $this->m_profit_sharing->createPointAddLog(array(
                                    'uid' => $uid,
                                    'commission_id' => $comm_id,
                                    'add_source' => 1,
                                    'money' => $commissionToPoint,
                                    'point' => $commissionToPoint
                                ));
                            }
                        }
                        $real_cash = $ceoComm - $commissionToPoint;
                        $this->db->where('id', $uid)->set('amount','amount+'.$real_cash,FALSE)->set('infinite_commission','infinite_commission+'.$ceoComm,FALSE)->update('users');

                         //$this->db->query("INSERT INTO `infinity_generation_log` (`uid`, `money`, `qualified_time`, `grant`) VALUES ($uid, $ceoComm, '2017-02', '1')");
                        */
                    }
                    
                }
            }
        }
    }
    
    /**
     * 补发2017-06-15 遗漏的每月团队奖未发用户
     * @param number $istest
     */
    public function fixCeoComm_fx($istest=1){
    
        ini_set('memory_limit', '5120M');
    
        $this->load->model('m_profit_sharing');
        $this->load->model('m_commission');
        $this->load->model('o_bonus');
        $this->load->model('m_debug');
    
        $uid_str = "1380599153,
        1380605964,
        1380610726,
        1380614427,
        1380644118,
        1380646583,
        1380662446,
        1380662654,
        1380665675,
        1380682555,
        1380686166,
        1380686700,
        1380696182,
        1380718854,
        1380721454,
        1380725399,
        1380729513,
        1380730303,
        1380742266,
        1380746294,
        1380750909,
        1380752093,
        1380753785,
        1380753902,
        1380761051,
        1380762287,
        1380769436,
        1380769738,
        1380771197,
        1380781967,
        1380785574,
        1380797973,
        1380801080,
        1380811669,
        1380824410,
        1380833181,
        1380859130,
        1380883541,
        1380892184,
        1380911790,
        1380911817,
        1380917078,
        1380917084,
        1380925338,
        1380937995,
        1380947227,
        1380974058,
        1381007681,
        1381059064,
        1381064079,
        1381126509,
        1381151606,
        1381151612,
        1381151620,
        1381154616,
        1381164316,
        1381193775,
        1381193810,
        1381193849,
        1381213522,
        1381234925,
        1381235118,
        1381235478,
        1381236776,
        1381271014,
        1381308562,
        1381311655,
        1381394259,
        1381471891,
        1381556666,
        1381593212,
        1381598720,
        1381605350,
        1381683128,
        1381796105,
        1381800637,
        1381813782,
        1381863076,
        1381952187,
        1382070508,
        1382084465,
        1382152426,
        1382152848,
        1382152988,
        1382153328,
        1382244009,
        1382244046,
        1382312737,
        1382314299,
        1382341644,
        1382764825";
        
        $bonus_sql = "select id,child_count from users where id in (".$uid_str.")";
        $ceoList = $this->db->query($bonus_sql)->result_array();
        foreach($ceoList as $v){
            $uid = $v['id'];    
            
            $check_fix_sql = "select * from cash_account_log_201706 where item_type = 4 and uid = ".$uid;
            $check_query = $this->db->query($check_fix_sql)->result_array();
            if(!empty($check_query))
            {
                continue;
            }            
            $this->m_debug->log($uid.':'.-1001);
            /*检查上个月销售额是否满足*/
            $res = $this->db->query("select sale_amount from users_store_sale_info_monthly where uid=$uid and `year_month`=201705")->row_array();
            if($res && $res['sale_amount']>=25000){
    
                /*检查团队是否满足3000*/
                $branchUids = $this->db->query("select id from users where parent_id=".$uid)->result_array();
                $totalBranchCount = 0;
                foreach($branchUids as $v2){
                    $branchUid = $v2['id'];
                    $countBranch = $this->db->query("select count(*) as countBranch from users where user_rank<>4 and `status`=1 and enable_time<'2017-06-01'
    and parent_ids like '%".$branchUid."%'")->row_object()->countBranch;
                    $totalBranchCount+=($countBranch>1500?1500:$countBranch);
                    if($totalBranchCount>=3000){
                        break;
                    }
                }
                if($totalBranchCount>=3000){
    
                    if($uid==1380100223){
                        $v['child_count'] = $v['child_count']/2.9;
                    }elseif($uid==1380100227){
                        $v['child_count'] = $v['child_count']/1.8;
                    }elseif($uid==1380100360){
                        $v['child_count'] = $v['child_count']/2.1;
                    }elseif($uid==1380100948){
                        $v['child_count'] = $v['child_count']/1.6;
                    }elseif($uid==1380100970){
                        $v['child_count'] = $v['child_count']/1.9;
                    }elseif($uid==1380101023){
                        $v['child_count'] = $v['child_count']/1.6;
                    }
    
                    if($v['child_count']<13000){
                        $v['child_count']=round($v['child_count']/10)+13000;
                    }
                    $ceoComm = tps_money_format($v['child_count']/83885 * 2426.54);
    
                    if($istest==1){
                        $this->m_debug->log($uid.':'.$ceoComm);
                    }else{
    
                        //发放全球副总裁奖
                        $user_data[] = array
                        (
                            'uid' => $uid,
                            'money' => $ceoComm * 100
                        );
                        $c_time = date('Y-m-15 12:00:00');
                        $this->o_bonus->assign_bonus_batch_fix($user_data,REWARD_4,$c_time);
    
                        //插入佣金记录
                        //$this->m_commission->commissionLogs($uid,REWARD_4,$ceoComm);
    
                        /* 佣金自动转分红点 */
                        /*
                         $rate = $this->m_profit_sharing->getProportion($uid, 'sale_commissions_proportion') / 100;
                         $commissionToPoint = 0;
                         if ($rate > 0) {
                         $commissionToPoint = tps_money_format($ceoComm * $rate);
                         if($commissionToPoint>=0.01){
                         $this->db->where('id', $uid)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_sale', 'profit_sharing_point_from_sale+' . $commissionToPoint, FALSE)->update('users');
    
                         $comm_id = $this->m_commission->commissionLogs($uid,17,-1*$commissionToPoint); //佣金轉分紅點
    
                         $this->m_profit_sharing->createPointAddLog(array(
                         'uid' => $uid,
                         'commission_id' => $comm_id,
                         'add_source' => 1,
                         'money' => $commissionToPoint,
                         'point' => $commissionToPoint
                         ));
                         }
                         }
                         $real_cash = $ceoComm - $commissionToPoint;
                         $this->db->where('id', $uid)->set('amount','amount+'.$real_cash,FALSE)->set('infinite_commission','infinite_commission+'.$ceoComm,FALSE)->update('users');
    
                         //$this->db->query("INSERT INTO `infinity_generation_log` (`uid`, `money`, `qualified_time`, `grant`) VALUES ($uid, $ceoComm, '2017-02', '1')");
                        */
                    }
    
                }
            }
        }
    }
    
    /**
     * 店铺会员统计
     * @param 开始时间  时间格式：2016-01-26 $start_time
     * @param 结束时间 时间格式：2016-01-26 $end_time
     */
    public function store_expstore_total($start_time, $end_time)
    {
        $this->load->model('tb_users_level_statistics_total');
        $this->tb_users_level_statistics_total->getDataShow($start_time,$end_time);
    }
    
    public function cancelOrder($order_id,$orderFrom=''){

        $this->load->model('o_order_cancel');
        $this->o_order_cancel->preWithdrawOfOrder($order_id,$orderFrom);
    }

    public function moveUserPosition($moveUid,$parentUid){
      
        $this->load->model('tb_users');
        $this->tb_users->movePosition($moveUid,$parentUid);
    }

    public function fixWithDrawCommission($uid){

      $this->load->model('tb_users');
      $res = $this->db->query("SELECT order_id FROM commission_logs where uid=$uid and `type`=16 and order_id!='' and order_id!='0' 
group by order_id having count(id)>=2")->result_array();
      foreach($res as $v){
        $deleteItem = $this->db->select('id,uid,amount')->from('commission_logs')->where('uid',$uid)->where('order_id',$v['order_id'])->where('type',16)->get()->row_array();
        $this->db->where('id',$deleteItem['id'])->delete('commission_logs');
        $this->tb_users->updateUserAmount($uid,abs($deleteItem['amount']));
      }
    }

    public function fixWithDrawCommissionAll(){
        $res = $this->db->select('id')->from('users')->where('id >',1380100217)->order_by('id')->get()->result_array();
        foreach($res as $item){
            echo $item['id'].'|';
            $this->fixWithDrawCommission($item['id']);
        }
    }

    public function fixSaleAmount($uid){
        $this->load->model('tb_users_store_sale_info_monthly');
        $this->tb_users_store_sale_info_monthly->statistics_user_monthly($uid);
    }

    public function doDayliShareReissue($uid,$date){
       $this->load->model('m_profit_sharing');
       $this->m_profit_sharing->dayliShareReissue($uid,$date);
    }

    public function doDayliEliteReissue($uid,$date,$month=''){
       $this->load->model('m_profit_sharing');
       $this->m_profit_sharing->dayliEliteReissue($uid,$date,$month);
    }

    public function test24($uid,$amount,$time){

      $this->load->model('m_profit_sharing');
      $this->m_profit_sharing->assignSharingCommission($uid, $amount,24,$time);
    }

    public function test24s($uid,$bei,$startTime,$endTime){

      $this->load->model('m_profit_sharing');
      $this->load->model('o_cash_account');

      $arr = $this->o_cash_account->getCashAccountLog(array('start_time'=>$startTime,'end_time'=>$endTime,'uid'=>1380100264,'item_type'=>24));
      foreach($arr as $v){
        $this->m_profit_sharing->assignSharingCommission($uid, tps_money_format($v['amount']*$bei/100),24,$v['create_time']);
      }

    }

    public function test6s($uid,$bei,$startTime,$endTime){

      $this->load->model('m_profit_sharing');
      $this->load->model('o_cash_account');

      $arr = $this->o_cash_account->getCashAccountLog(array('start_time'=>$startTime,'end_time'=>$endTime,'uid'=>1380200723,'item_type'=>6));
      foreach($arr as $v){
        $this->m_profit_sharing->assignSharingCommission($uid, tps_money_format($v['amount']*$bei/100),6,$v['create_time']);
      }

    }

    public function test2($uid,$yearMonth){
      $this->load->model('m_store');
      echo $this->m_store->getStoreSaleAmount($uid,$yearMonth);
    }

    public function checkMonthFeeGap($uid){
      $this->load->model('m_user');
      $this->m_user->checkMonthFeeGap($uid);//检查是否欠月费。
    }

    public function ly($uid){
        $this->load->model('m_user');
        $this->m_user->checkSelfLevel($uid);
    }

    /*补发日分红*/
    public function ly_fix_daymoney($uid,$commission,$dateTime){
      $this->load->model('m_profit_sharing');
      $this->m_profit_sharing->assignSharingCommission($uid, $commission,6,$dateTime);
    }

    /*补发月杰出店铺奖*/
    public function ly_fix_month_share($uid,$commission){
      $this->load->model('m_profit_sharing');
      $this->m_profit_sharing->assignSharingCommission($uid, $commission,8);
    }

    /*清除用户所欠月费，将账户从休眠恢复为正常*/
    public function enableAccountFromDormancy($uid){
        $this->db->query("delete from users_month_fee_fail_info where uid=$uid");
        $this->db->where('id',$uid)->update('users', array('status' => 1));
        $this->db->where('id',$uid)->update('users', array('store_qualified'=>1));
    }

    public function fix_week_leader($uid,$commission_amount,$date){
        $this->load->model('m_profit_sharing');
        $this->m_profit_sharing->assignSharingCommission($uid, $commission_amount,7,$date.' 06:04:54');
    }

    public function fixWeekLeaderAuto($uid,$last=0){

      $this->load->model('m_profit_sharing');
      $this->m_profit_sharing->fixWeekLeaderAuto($uid,$last);
    }

    /*补发月领导分红奖－高级市场主管*/
    public function ly_fix_monthleader_bonus_3($uid,$commission){
      $this->load->model('m_profit_sharing');
      $this->m_profit_sharing->assignSharingCommission($uid, $commission,23);
    }

    /*给用户加月费券*/
    public function addMonthFeeCoupon($uid,$num=1){
        $this->load->model('m_coupons');
        $this->m_coupons->giveMonthlyFeeCoupon($uid,$num);
    }

    public function unionRecall($orderId,$txn_id){
        $this->load->model('M_order','m_order');
//        $order = $this->db->from('trade_orders')->where('order_id',$orderId)->get()->row_array();
        $this->load->model("tb_trade_orders");
        $order = $this->tb_trade_orders->get_one("*",['order_id'=>$orderId]);
        $result = $this->m_order->mall_general_order_paid($order, $txn_id);
        echo $result;
    }

    public function newOrderTrigger($orderId,$type='wohao'){
        $this->load->model('M_order','m_order');
        $this->load->model("tb_trade_orders");
        $this->db->trans_start();
        if($type=='wohao'){
          $data = $this->db->from('mall_orders')->where('order_id',$orderId)->get()->row_array();
        }elseif($type=='tps'){
//          $data = $this->db->from('trade_orders')->where('order_id',$orderId)->get()->row_array();
          $data = $this->tb_trade_orders->get_one("*",['order_id'=>$orderId]);
          if($data){
              $data['order_amount_usd'] = $data['goods_amount_usd'] / 100;
              $data['order_profit_usd'] = tps_money_format($data['order_profit_usd'] / 100);
          }
          
        }
        
        if(isset($data) && $data){
            $this->m_order->newOrderTrigger($data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
            }
        }
    }

    public function newRuleInit(){
        $this->load->model('m_order');
        $this->m_order->newRuleInit();
    }

    public function processOver3Month(){
        $this->load->model('m_user');
        $this->m_user->processOver3Month();
    }

    public function memberSyncWohaoInit(){
      $this->load->model('m_user');
        $res = $this->db->select('id,month_fee_rank')->from('users')->where('status !=',0)->get()->result_array();
        foreach($res as $v){
            if($v['month_fee_rank']==4 && !$this->m_user->getMemCheckedStatus($v['id'])){
                continue;
            }
            $this->m_user->addInfoToWohaoSyncQueue($v['id'],array(0));
        }
    }

    /**
     * @author brady
     * @desc 修复 user_comm_stat 表 week_bonus字段
     */
    public function repaire_week_bonus_amount($page_size=10)
    {
        ini_set('memory_limit', '5120M');
        $time = time();
        $total_rows = $this->db->query("select count(*) as num from week_leader_members ")->row_array()['num'];
        $pageNum = ceil($total_rows/$page_size);

        $this->load->model("o_pcntl");
        for($page=1;$page<=$pageNum;$page++){
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->repaire_week_bonus_amount_page(\''.$page.'\',\''.$page_size.'\');');//
        }
        $time2 = time();
        echo "耗时".($time2 - $time)."s";
    }

    /**
     * 系统初始化
     * 执行时间：手动
     * @author Terry Lu
     */
    public function sysInit(){
        $this->load->model('m_user');
        $this->load->model('M_currency','m_currency');
        $this->load->model('m_admin_user');
        $this->m_user->createRootMembers();
        $this->m_admin_user->createRootAccount();
        // $this->m_currency->createCurrency();
        // $this->updateRate();
    }

    public function resetAdminPwd($id,$pwd){
        $this->load->model('m_admin_user');
        $this->m_admin_user->reset_admin_pwd($id,$pwd);
        echo 'ok';
    }

    //同步会员信息到沃好，每分钟执行一次
    public function doWohaoSyncQueue(){
      set_time_limit(1200);
      $this->load->model('m_user');
      $this->m_user->doWohaoSyncQueue();
    }

	/** 收据邮件 */
	public function sendEmailSyncQueue(){

      $this->load->model('m_user');
      $this->m_user->sendEmailSyncQueue();
    }

	/** 注册验证码 */
	public function sendRegisterCode(){

		$this->load->model('o_login_register');
		$this->o_login_register->sendRegisterCode();
		exit;
	}

	public function initUnionpay(){

      $this->load->model('m_helper');
      echo $this->m_helper->initUnionpay();
    }

	public function initPayPal(){

      $this->load->model('m_helper');
      echo $this->m_helper->initPayPal();
    }

	/** 异步 用户登录Ip信息 */
	public function zh_ip_to_address(){

      $this->load->model('m_helper');
      $this->m_helper->zh_ip_to_address();
    }

	/** 异步 用户登录Ip信息 */
	public function english_ip_to_address(){

      $this->load->model('m_helper');
      $this->m_helper->english_ip_to_address();
    }

	/** 异步 用户登录Ip信息 */
	public function getIpAddress(){

      $this->load->model('m_helper');
      $this->m_helper->getIpAddress();
    }

    /**
     *  Array ( [status] => ALREADY [scur] => CNY [tcur] => USD [ratenm] => 人民币/美元 [rate] => 0.161426 [update] => 2015-04-22 18:01:47 )
     *
     * 执行时间：一天一次
     * @author john he
     * http://www.k780.com  免费注册账号：521314 密码：521314    R1免费版 	最大配额(每60分钟)720次 	0.0000元 	0元/天
     *
     */
    public function updateRate(){
		require APPPATH . 'third_party/nowapi.class.php';
        $this->load->model('M_currency','m_currency');
        $this->m_currency->addRateHistoryEveryday();
		$currency_arr = array('HKD','CNY','KRW');
		foreach($currency_arr as $currency){
			if(!$result=nowapi::callapi('finance.rate',array('scur'=>'USD','tcur'=>$currency),'json')){
                $this->m_currency->addRateSendMail($currency,nowapi::error());
                echo nowapi::error();
                continue;
				//exit;
			}
            print_r($result);
			if(is_array($result) && $result['status'] == 'ALREADY' && $result['rate'] > 0 ){

				$this->m_currency->updateRate($result,$currency);

			}else{

                $this->m_currency->addRateSendMail($currency,'返回数据不正确');

            }
		}
	}

	/**
	 * 通过银联国际的获取USD-》CNY的汇率
	 * http://www.unionpayintl.com/MainServlet
	 */
	public function update_rate_by_unionpay($currency){

		$postData = array(
			'go'=>'BIZTOOL_MERCHANT_PG_exchangeRateEn',
			'curDate'=>date('Y-m-d'),
			'baseCurrency'=>'CNY',
			'transactionCurrency'=>'USD',
		);
		$url = 'http://www.unionpayintl.com/MainServlet';
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData,
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);
		$pos = strrpos($result,'USD');
		$star_str =  substr($result,$pos,60);
		$pos2 = strrpos($star_str,'=');
		$rate = trimall(htmlspecialchars_decode(substr($star_str,$pos2+1,40)));
		echo $rate;
		$this->m_currency->updateRate(array('rate'=>$rate),$currency);
	}

    /**
     * 扣月费以及提醒月费扣取失败的用户
     * 执行时间：每天的0点1分执行。
     * @author Terry Lu
     */
    // public function chargeMonthFee(){
    //     $this->load->model('m_user');
    //     $this->m_user->monthFeeCharge();//扣月费
    //     $this->m_user->processMonthFeeFailMem();//处理月费扣取失败的人。
    // }

	/** 统计扣月费人数
		执行时间：每天的0点1分执行。
	 */
//	public function count_charge_month_fee(){
//		$this->load->model('o_month_fee');
//		$this->o_month_fee->count_charge_month_fee();
//	}

	/**
	 * 队列式扣取月费
	 * 每一分钟扫描统计表，每次处理1000会员
	 */
//	public function charge_month_fee(){
//		$this->load->model('o_month_fee');
//		$this->o_month_fee->charge_month_fee();
//	}

	/* 临时测试 */
	// public function chargeMonthFee_temp(){
 //        $this->load->model('m_user');
 //        $this->m_user->monthFeeCharge();//扣月费
 //    }

    /**
     * 超2周未补单的佣金抽回。每天4点1分执行
     * @author Terry
     */
    public function withdrawOfOrderCancel(){

        $this->load->model('o_order_cancel');

        $this->db->trans_start();
        $this->o_order_cancel->withdrawOfOrder();
        $this->m_log->createCronLog('[Success]超2周未补单的佣金抽回。');
        $this->db->trans_complete();
        var_dump($this->db->trans_status());
        if ($this->db->trans_status() === FALSE){
            $this->m_log->createCronLog('[Fail]超2周未补单的佣金抽回。');
        }
    }

    /**
     * 月初定时任务
     * 执行时间：每月的1号0点2分执行
     * @author Terry
     */
    public function taskInMonthBegin(){

        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            ini_set('memory_limit', '5120M');
            $this->bonus_plan_control(7,0,0,0,"重置",1);
            $this->bonus_plan_control(7,1,time(),0,"每周领导对等奖分红会员放入队列",0);
            
            $this->load->model('m_profit_sharing');
            
            $this->m_profit_sharing->getCurMonthWeekLeaderMem();//筛选出本月可以参加每周领导对等奖的会员
            $this->bonus_plan_control(7,1,0,time(),"每周领导对等奖分红会员放入队列完成",0);
            
            $this->bonus_plan_control(8,0,0,0,"重置",1);
            $this->bonus_plan_control(8,1,time(),0,"每月杰出店铺分红会员放入队列",0);
            $this->m_profit_sharing->getCurMonthMonthProfitSharMem();//筛选出本月可以参加每月杰出店铺分红的会员
            $this->bonus_plan_control(8,1,0,time(),"每月杰出店铺分红会员放入队列完成",0);
            
            /*统计领导奖相关*/
            // $this->m_profit_sharing->getCurMonthLeaderMem();
            // $this->m_profit_sharing->getCurMonthTopLeaderMem();
            // $this->m_profit_sharing->getCurMonth5LeaderMem();
        }        
    }
    
    /**
     * 月初定时任务
     * 修复业绩后，重新生成新的队列(处理5月份业绩统计错误问题) 每月领导对等奖
     * @author Terry
     */
    public function taskInMonthBegin_new(){
    
        ini_set('memory_limit', '5120M');
    
        $this->load->model('m_profit_sharing');
    
        $this->m_profit_sharing->getCurMonthWeekLeaderMem_new();//筛选出本月可以参加每周领导对等奖的会员       
    }

    /*创建周领导对等奖发放列队. 执行时间：每周1的6点1分执行.*/
    public function createWeekLeaderMemQueue(){

        sleep(10);//延迟10秒，防止和列队发放任务时间上锁表冲突。
        $this->load->model('tb_week_leader_members_queue');

        $this->db->trans_begin();
        $this->tb_week_leader_members_queue->insertWeekLeaderQueue();
        $this->m_log->createCronLog('生成周领导对等奖发放列队。 [执行完成]');
        
        if ($this->db->trans_status() === TRUE) {

          $this->db->trans_commit();
        } else {

          $this->db->trans_rollback();
          $this->m_log->createCronLog('生成周领导对等奖发放列队。 [执行失败]');
        }
    }

    /**
     * 发放日奖（日分红，138分红）
     * 执行时间：每天1点0分
     * @author Terry
     */
    public function grantDailyBonus(){

        $this->load->model('m_profit');
        $this->config->load('config_bonus');
        
        $yesterdayProfit = $this->m_profit->getCompanyProfitYesterday();/*统计公司昨天全球销售利润*/
        $this->o_cron->doDailyShar($yesterdayProfit);/*处理发放日分红*/
        $this->o_cron->do138Shar($yesterdayProfit);/*处理发放138分红*/
        $this->o_cron->doDailyEliteShar($yesterdayProfit);/*处理发放精英日分红*/
    }

    public function ly_22(){
      
      $this->o_cron->fix_138_page();
    }

    public function fixGrantDailyBonus(){

        $this->load->model('m_profit');
        $this->config->load('config_bonus');
        
        $yesterdayProfit = $this->m_profit->getCompanyProfitYesterday();/*统计公司昨天全球销售利润*/
        $this->o_cron->doDailyShar2($yesterdayProfit,413);/*处理发放日分红*/
        // $this->o_cron->do138Shar($yesterdayProfit);/*处理发放138分红*/
        // $this->o_cron->doDailyEliteShar($yesterdayProfit);/*处理发放精英日分红*/
    }

    /**
     * 每月杰出店铺分红
     * 执行时间：每月15号11点30分执行
     * @author Terry
     */
    public function monthProfitSharing(){

        ini_set('memory_limit', '5120M');
        $this->load->model('m_profit_sharing');
        $this->m_profit_sharing->monthSharing();
    }

    /**
     * 每月领导分红奖
     * 执行时间：每月15号14点30分执行
     * @author Terry
     */
    public function monthLeaderProfitSharing(){

        $this->load->model('m_profit_sharing');
        
        $this->m_profit_sharing->monthLeaderSharingNew();
        $this->m_profit_sharing->monthTopLeaderSharingNew();
        $this->m_profit_sharing->monthLeader5Sharing();
    }

    /**
     * 列队发放周领导对等奖
     * 执行时间：每周1的每分种扫描执行
     * @author Terry
     */
    public function weekLeadershipMatchingBonus(){

        ini_set('memory_limit', '5120M');
        $this->load->model('m_profit_sharing');
        $this->m_profit_sharing->assignWeekLeaderRewardQueue();
    }

    /**********新周领导对等奖发放脚本 start carl.zhou**********/
    /**周领导对等奖自动预发、实发
     * @param string $start
     * @param int $num
     */
    public function weekLeaderBonusReward($start='', $num=500){
        ini_set('memory_limit', '5120M');
        $this->weekLeadershipMatchingBonusPreview($start, $num);
        $this->weekLeadershipMatchingBonusImplement(5000);
        return;
    }

    /**
     * 预发放
     * @param string $start 脚本执行时间，默认当天
     * @param int $num 一次性写入预览表的数据量
     */
    public function weekLeadershipMatchingBonusPreview($start='', $num=200){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ALL);
        $this->bonus_plan_control(7,1,time(),0,"周领导对等会员统计预发队列",0);

        $this->load->model('tb_week_leader_preview');
        $this->tb_week_leader_preview->truncateTable();
        //获取开始时间所在周的周一日期
        $start = $start=='' ? date('Y-m-d') : $start;
        $time = strtotime($start);
        $w = date('w', $time);

        if ($w==1) {
            $start_date = date('Y-m-d', strtotime('-1 week monday', $time));
        } else {
            $start_date = date('Y-m-d', strtotime('-2 week monday', $time));
        }

        $this->tb_week_leader_preview->previewWeekLeaderReward($start_date, $num);
        $this->bonus_plan_control(7,1,0,0,"周领导对等会员统计预发队列完成",0);
        return;
    }

    /**
     * 执行周领导对等奖发奖程序
     * @param int $num 一次发放个数
     * @param int $debug 是否调试模式，调试模式下只发一次 1是 0否
     */
    public function weekLeadershipMatchingBonusImplement($num=1000, $debug = 0){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $this->bonus_plan_control(7,1,time(),0,"周领导对等奖发奖开始",0);
        $this->load->model('tb_week_leader_preview');
        $this->tb_week_leader_preview->weekLeaderRewardImplement($num ,$debug);
        $this->bonus_plan_control(7,2,0,time(),"周领导对等奖发奖完成",0);
        return;
    }

    /**********新周领导对等奖发放脚本 end **********/

    /**********新月杰出店铺奖发放脚本 start carl.zhou**********/
    /**
     * 月杰出店铺奖预发、实发
     * @param int $num
     */
    public function monthEminentStoreBonusReward($num = 500){
        ini_set('memory_limit', '5120M');
        $this->monthEminentStoreBonusPreview($num);
        $this->monthEminentStoreBonusImplement(1000);
        return;
    }

    /**
     * 月杰出店铺奖预发奖
     * @param int $num
     */
    public function monthEminentStoreBonusPreview($num = 500){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ALL);
        $this->bonus_plan_control(8,1,time(),0,"月杰出店铺用户红加入预发队列",0);
        $this->load->model('tb_month_eminent_store_preview');
        $this->tb_month_eminent_store_preview->truncateTable();

        $this->tb_month_eminent_store_preview->previewMonthEminentStoreReward($num);
        return;
    }

    public function monthEminentStoreBonusImplement($num=1000, $debug = 0){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $this->bonus_plan_control(8,1,time(),0,"月杰出店铺实际发奖开始",0);
        $this->load->model('tb_month_eminent_store_preview');
        $this->tb_month_eminent_store_preview->monthEminentStoreRewardImplement($num ,$debug);
        $this->bonus_plan_control(8,2,0,time(),"月杰出店铺实际发奖完成",0);
        return;
    }
    /**********新月杰出店铺奖发放脚本 end **********/

    /**********供应商推荐奖发放脚本 start carl.zhou*********/

    public function supplierRecommendationRewardPreview($start='', $end='', $num = 500){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $start = $start=='' ? date('Y-m-01 00:00:00', strtotime('-3 month')) : date('Y-m-01 00:00:00',strtotime($start));
        $end = $end=='' ? date('Y-m-01 00:00:00',strtotime('+3 month',strtotime($start))) : date('Y-m-01 00:00:00',strtotime($end));
        echo  "\n".$start . " TO " . $end . "\n";
        $this->load->model('tb_supplier_recommendation_preview');
        $this->tb_supplier_recommendation_preview->truncateTable();
        //$this->tb_supplier_recommendation_preview->truncateOrderTemp();
        //$this->tb_supplier_recommendation_preview->createTempData($start, $end);exit;
        $this->tb_supplier_recommendation_preview->previewSupplierRecommendationReward($start, $end, $num);
    }

    public function supplierRecommendationBonusImplement($num=5000, $debug = 0){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $this->load->model('tb_supplier_recommendation_preview');
        $this->tb_supplier_recommendation_preview->supplierRecommendationRewardImplement($num ,$debug);
        return;
    }

    /***********供应商推荐奖发放脚本 end **********/

    /***********会员月费收取脚本 start carl.zhou**********/

    /**会员月费预收、实收脚本
     * @param string $start
     * @param int $num
     */
    public function monthExpenseScript($start = '', $num = 5000){
        ini_set('memory_limit', '5120M');
        $this->monthExpensePreview($start, $num);
        $this->monthExpenseImplement(5000);
    }

    public function monthExpensePreview($start = '', $num = 5000){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $start = $start=='' ? date('Y-m-01 00:00:00', strtotime('-1 month')) : date('Y-m-01 00:00:00',strtotime($start));

        $this->load->model('tb_month_expense_preview');
        $this->tb_month_expense_preview->truncateTable();
        $this->tb_month_expense_preview->previewMonthExpense($start, $num);
    }

    public function monthExpenseImplement($num=5000, $debug = 0){
        ini_set('memory_limit', '5120M');
        ini_set("display_errors","On");
        error_reporting(E_ERROR);
        $this->load->model('tb_month_expense_preview');
        $this->tb_month_expense_preview->monthExpenseImplement($num, $debug);
    }

    /**将用户月费池全部转到现金池
     * @param int $num
     * @author carl.zhou
     */
    public function clearUserMonthFeePool($num = 5000){
        $this->load->model('tb_month_expense_preview');
        $this->tb_month_expense_preview->clearUserMonthFeePool($num);exit;
    }

    /***********会员月费收取脚本 end ***********/

    /**
     * 团队销售总裁奖。
     * 每月15号5点1分
     * 统计总裁奖的人数
     */
    public function countInfinity(){
        $this->load->model('M_infinite_generation', 'm_infinite_generation');
        $this->m_infinite_generation->countInfinity();
    }

	/**
     * 团队销售总裁奖。
     * 每月15号5点15分
     * 统计总裁奖的金额
     */
    public function countInfinity2(){
        $this->load->model('M_infinite_generation', 'm_infinite_generation');
        $this->m_infinite_generation->countInfinity2();
    }

    /*手动发总裁奖*/
	public function grantInfinityCash($yearMonth = false){
		$this->load->model('M_infinite_generation', 'm_infinite_generation');
		$this->m_infinite_generation->grantInfinityCash($yearMonth);
	}

    /*手动升级店铺的特殊脚本，不发放佣金，不发货。*/
    public function upStoreLevelFree($uid,$level){
        $this->load->model('m_order');
        $res = $this->m_order->upgradeLevelFree($uid,$level);
        echo $res?'Success':'Fail';
    }

    /***2x5所有用户添加leader_id***/
    public function addLeaderColumn(){
        $this->load->model('m_helper');
        $this->m_helper->addLeaderColumn();
    }

    public function initPaymentConfig(){
        $this->load->model('m_helper');
        echo $this->m_helper->initPaymentConfig();
    }

	/**
	 * 扫描超过 2 小时未支付的订单，状态改为取消
	 * 执行时间：每十分钟执行一次
	 */
	public function order_payment_timeout()
	{
		$this->m_trade->scan_order_payment_timeout();
		exit;
	}

	/**
	 * 扫描发货时间超过30天，状态仍然是等待收货的，状态改为等待评价
	 * 执行时间：每十分钟执行一次
	 */
	public function order_cargo_receive_timeout()
	{
		$this->m_trade->scan_order_cargo_receive_timeout();
		exit;
	}

	/**
	 * 收到货物超过一个星期，状态仍然是等待评价的，状态改为已完成
	 * 执行时间：每十分钟执行一次
	 */
	public function order_estimate_timeout()
	{
		$this->m_trade->scan_order_estimate_timeout();
		exit;
	}

    /**
     * 定时读取save_user_for_138,将表中用户排进138矩阵
     * 执行时间:每一分钟执行一次
     */
    public function timer_sort_138(){
        $this->db->trans_begin();
        $this->load->model('m_forced_matrix');
        $this->m_forced_matrix->userSort138();
        $this->db->trans_commit();
    }

	/**
     * 定时从邮箱服务器收取邮件
     * 执行时间:每一分钟执行一次
     */
    public function get_email(){

        $this->load->model('o_receive_mail');
        $this->o_receive_mail->get_email();

    }

	/** 统计1月推荐红包奖 */
	public function cash_bonus(){
		$this->load->model('m_helper');
		$this->m_helper->cash_bonus();
	}

	/** 统计3月推荐红包奖 */
	public function cash_bonus_3(){
		$this->load->model('m_helper');
		$this->m_helper->cash_bonus_3();
	}

	/** 发放推荐红包奖 */
	public function grant_cash_bonus(){
		$this->load->model('m_helper');
		$this->m_helper->grant_cash_bonus();
	}

	/** 订单回滚后续处理 */
	public function processOrdersRollback(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(0);
	}
	public function processOrdersRollback1(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(1);
	}
	public function processOrdersRollback2(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(2);
	}
	public function processOrdersRollback3(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(3);
	}
	public function processOrdersRollback4(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(4);
	}
	public function processOrdersRollback5(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(5);
	}
	public function processOrdersRollback6(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(6);
	}
	public function processOrdersRollback7(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(7);
	}
	public function processOrdersRollback8(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(8);
	}
	public function processOrdersRollback9(){
		$this->load->model('m_helper');
		echo $this->m_helper->processOrdersRollback(9);
	}

	/** 统计 精英分红奖 */
	public function fixEliteCash(){
		$this->load->model('m_helper');
		echo $this->m_helper->fixEliteCash();
	}

	/** 以前的会员重复升级抽回 */
	public function repeat_upgrade(){
		$this->load->model('m_helper');
		echo $this->m_helper->repeat_upgrade();
	}

	/** 以前的会员退会，又返回 */
	public function wxpay(){
		$this->load->model('m_helper');
		echo $this->m_helper->wxpay();
	}

	/** 把以前订单的产品 放进 trade_order_goods 表中*/
	public function get_order_goods2(){
		$this->load->model('m_helper');
		$this->db->trans_begin();

		$this->m_helper->get_order_goods2();

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo 'rollback';
		}
		else
		{
			$this->db->trans_commit();
			echo 'success';
		}
	}

	/*
	 * doba平台订单状态监测任务 每隔10分钟执行一次
	 * @date: 2015-11-13 
	 * @author: sky yuan
	 * @parameter: 
	 * @return: 
	 */ 
	public function doba_order_check() {
	    $this->load->model('m_goods');
	    
	    $this->m_goods->cron_doba_order_status_check();
	}

    /***推送doba订单***/
    public function send_doba_order(){
        $this->load->model('m_trade');
        $this->m_trade->send_doba_order();
    }

    /* 同步订单到erp */
    public function sync_order_to_erp(){
        $this->load->model('m_erp');
        $this->m_erp->sync_order_to_erp();
    }

    /* 同步商品库存到erp */
    public function mall_goods_sync_number(){
        $this->load->model('m_erp');
        $this->m_erp->mall_goods_sync_number();
    }

    /* 定时扫描商品库存 */
    public function check_goods_number_exception(){
        $this->load->model('tb_mall_goods_number_exception');
        $this->tb_mall_goods_number_exception->check_goods_number();
    }

    /* 命令行抓取doba产品数据,无需设置定时任务 */
    public function grab_doba_info() {
        $this->load->model('m_goods');
         
        $this->m_goods->import_doba_products_cli();
    }
	
	/* 导入杭州给到的韩国产品  ,无需设置定时任务*/
	public function import_koreal_goods() {
	    $this->load->model('m_goods');
	    $this->m_goods->import_koreal_products_cli();
	}
	
	/* 更新doba产品价格和库存 ,每天12点执行一次 */
	public function modify_doba_inventory_cli() {
	    $this->load->model('m_goods');
	    
	    $this->m_goods->modify_doba_inventory_cli();
	}
	
	public function modify_doba_inventory_cli_outofstock() {
	    $this->load->model('m_goods');
	     
	    $this->m_goods->modify_doba_inventory_cli(true);
	}
	
	/* 检查doba产品图片的完整性，不完整的下架 */
	public function check_goods_img_cli() {
	    $this->load->model('m_goods');
	     
	    $this->m_goods->check_goods_img_cli();
	}

//    public function commission_2x5(){
//        $this->load->model("m_forced_matrix");
//        $leader_id = '1380151053';
//        $count = 0;
//        $user_list = array(
//            1380151070,
//            1380151075,
//            1380151083,
//        );
//        foreach($user_list as $id){
//            $this->m_forced_matrix->getCommission($id,$leader_id,1,0);
//            var_dump(++$count);
//        }
//        exit;
//    }

    /****创建发货商表数据****/
    public function create_shipper_data(){
        $data = $this->db->query("select * from mall_goods_storehouse ORDER BY supplier_id ASC ")->result_array();
        foreach($data as $item)
        {

            if($item['supplier_id'] == 0){
                continue;
            }

            $record = $this->db->query("select * from mall_goods_shipper where shipper_id = {$item['supplier_id']}")->row_array();
            if(empty($record)) {
                $insert_attr = array(
                    'shipper_id' => $item['supplier_id'],
                    'sale_area' => $item['sale_area'],
                    'freight_company_code' => $item['freight_company_code'],
                    'permit_customer_pickup' => $item['permit_customer_pickup'],
                    'shipping_currency' => $item['shipping_currency'],
                    'area_rule' => $item['rule_type'],
                    'store_location' => $item['store_location'],
                    'store_location_code' => $item['store_location_code']
                );
                $this->db->insert("mall_goods_shipper", $insert_attr);
            }
        }
    }

	/** 无限代奖 满足有3000个银级会员 至少2个分支 每个分支最多计数1500银级会员 */
	public function countMember($uid = null){

		$uid = $uid == null ? "1380100335" : $uid ;
		$referrer =$this->db->select('id,user_rank')->from('users')->where('parent_id',$uid)->get()->result_array();

		if(count($referrer) < TEAM_COUNT_LIMIT ){ //至少有2个分支以上
			return FALSE;
		}
		$this->load->model("m_generation_sales");
		$all_count = 0 ;
		foreach($referrer as $refer){

			$add = 0; //如果第一代会员满足银级以上，统计就要加上第一代会员
			$count = $this->m_generation_sales->getChildCount($refer['id']);

			if($refer['user_rank'] <= LEVEL_SILVER){
				$add = 1;
			}
			$count = $count + $add > TEAM_MEMBER_LIMIT ? TEAM_MEMBER_LIMIT : $count + $add; //加上本身（直推會員）

			$all_count = $all_count + $count;
		}
		echo $all_count;
		
	}

    /* 修改订单推送数据参数 */
    public function modify_order_param(){
        $count = 0;
        $res = $this->db->query("select * from trade_order_to_erp_logs")->result_array();
        foreach($res as $k=>$item){
            $array = unserialize($item['api_param']);
            if(isset($array['logistics_code']))
            {
                if (!is_numeric($array['logistics_code'])) {
                    $company_code_arr = $this->db->select('company_code')->where('company_shortname', $array['logistics_code'])
                        ->get('trade_freight')->row_array();

                    if (!empty($company_code_arr)) {
                        $array['logistics_code'] = $company_code_arr['company_code'];
                        $this->db->set('api_param', serialize($array))->where('id', $item['id'])->update('trade_order_to_erp_logs');
                    } else {
                        $array['logistics_code'] = 0;
                        $this->db->set('api_param', serialize($array))->where('id', $item['id'])->update('trade_order_to_erp_logs');
                    }
                    var_dump(++$count);
                }
            }
        }
    }

    /**
     * @param $order_id 订单id
     */
    public  function get_order_type(){
        $this->load->model('m_split_order');
        $this->m_split_order->get_order_type_2();
    }

    /**
     * 商品库存异常修正
     */
    public function number_exception(){
        $this->load->model('m_split_order');
        $this->m_split_order->number_exception1();
    }

	/**
	 * 月费日已第一次升级店铺为准
	 */
	public function check_month_fee_date(){
		$this->db->trans_start();

		$users = $this->db->query("select id,month_fee_date from users where user_rank!=4")->result_array();
		if($users)foreach($users as $k=>$user){
			$uid = $user['id'];
			$upgrade = $this->db->query("select create_time from users_level_change_log where uid=$uid and level_type=2 limit 1")->row_array();
			$day =(int)date('d',strtotime($upgrade['create_time']));
			if($user['month_fee_date'] != $day){
				$this->db->where('id',$uid)->update('users',array('month_fee_date'=>$day));
			}
			var_dump($k);
		}

		$this->db->trans_complete();
	}

	/**
	 * 退会会员又返回
	 */
	public function return_back($uid = ''){

		$this->db->trans_start();

		$uid = $uid=='' ? '1380189369' : $uid;
		$this->load->model('m_overrides');
		$this->m_overrides->deleteUserLogs($uid);
//		$this->db->where('id',$uid)->update('users',array('user_rank'=>3,'status' => 1,'store_qualified'=>1,'is_choose'=>0));
        $this->load->model("tb_users");
        $this->tb_users->modify_user_rank(array('id'=>$uid),array('user_rank'=>3,'status' => 1,'store_qualified'=>1,'is_choose'=>0));
		$this->db->where('uid',$uid)->delete('user_suite_exchange_coupon');
		$this->db->query("UPDATE `users_level_change_log` SET `new_level`=3 WHERE  `id`=98699;");
		$this->db->query("UPDATE `users_sharing_point_reward` SET `point`=500.00 WHERE  `id`=120003;");
		//团队销售
		$this->m_overrides->generationSalesOverrides2($uid,400,'');

		$this->db->trans_complete();
	}


	/**
	 * 重新统计特定会员和父类的下线数量
	 */
	public function fix_child_count($uid = null ){


		$this->db->trans_start();

		if($uid == null){
			var_dump('Uid is null!');exit;
		}

		/**
		 * 本身的下线会员统计
		 */
		$childs = $this->db->from('users')->like('parent_ids',$uid,'both')->where('status <>',0)->count_all_results();

		var_dump($childs);

		$this->db->where('id',$uid)->update('users',array('child_count'=>$childs));


		/**
		 * 父类的下线会员统计
		 */
		$user = $this->db->from('users')->select('parent_ids')->where('id',$uid)->get()->row_array();
		if(!$user){
			return;
		}

		$parent_ids = explode(',',$user['parent_ids']);
		//去掉最後的公司號
         $ids_last = array_pop($parent_ids);
         $mem_root_id = config_item('mem_root_id'); 
         if($ids_last !=$mem_root_id) {   
                array_push($parent_ids,$ids_last);
         }

		if($parent_ids)foreach($parent_ids as $key=>$parent_id){

			$childs = $this->db->from('users')->like('parent_ids',$parent_id,'both')->count_all_results();

			$this->db->where('id',$parent_id)->update('users',array('child_count'=>$childs));

			var_dump($key);
		}
		$this->db->trans_complete();
	}

	/* 发放供应商推荐奖 */
	public function get_supplier_recommend_commission(){

        $this->load->model('m_commission');
        $this->m_commission->get_supplier_recommend_commission();
    }

	/**
	 * 12天後关闭“申請关闭”工单，每天凌晨5点15分执行一次
	 */
	public function closeTickets(){
		$this->load->model('tb_admin_tickets');
		$this->tb_admin_tickets->closeTickets();
	}

	/**
	 * 5天,10天后，用户没有关闭“申请关闭”状态的工单，发送提醒邮件,每天凌晨6点15分
	 */
	public function alert_tickets_email(){
		$this->load->model('tb_admin_tickets');
		//$this->tb_admin_tickets->send_tickets_email_5();
		$this->tb_admin_tickets->send_tickets_email_10();
	}

    /*
     * 系统自动分配工单,定时每天上午 9 点
     * **/
    public function auto_assign_tickets(){

        $this->load->model('tb_admin_tickets');
        $this->tb_admin_tickets->auto_assign_zh();//自动分配中文
        $this->tb_admin_tickets->auto_assign_kr();//自动分配韩语
        $this->tb_admin_tickets->auto_assign_hk();//自动分配繁体
    }

    //自动分配中文
    public function auto_assign_tickets_zh(){
        $this->load->model('tb_admin_tickets');
        $this->tb_admin_tickets->auto_assign_zh();//自动分配中文
    }

    //自动分配韩文
    public function auto_assign_tickets_kr(){
        $this->load->model('tb_admin_tickets');
        $this->tb_admin_tickets->auto_assign_kr();//自动分配韩文
    }

    //修复数据->总数
    public function repair_tickets_count(){
        //$this->load->model('tb_admin_tickets_count');
        //$this->tb_admin_tickets_count->repair();
    }

    //mvp报名，发送手机短信，手动执行，只执行一次
    public function mvp_send_phone_msg(){
        //$this->load->model('tb_mvp_list');
        //$this->tb_mvp_list->mvp_send_phone_msg(18824863694);
    }
    //mvp报名，发送邮件，手动执行，只执行一次
    public function mvp_send_email(){
        //$this->load->model('tb_mvp_list');
        //$this->tb_mvp_list->mvp_send_email();
    }

    //每天晚上 00:00:01 执行一次
    public function input_daily_statistics_data(){
        $this->load->model('tb_admin_tickets_daily_count');
        $this->tb_admin_tickets_daily_count->input_daily_statistics_data();
    }

    //更新email为xxx@shoptps.com
    //author:andy
    public function modify_admin_email(){
        $res = $this->db->from('admin_users')->select('id,email')->get()->result_array();
        if($res){
            foreach($res as $v){
                $email = explode('@',$v['email'])[0].'@shoptps.com';
                //var_dump($email);
                $this->db->set('email',$email)->where('id',$v['id'])->update('admin_users');
            }
        }
    }

	/**
	 * 升级会员没有加入138矩阵
	 */
	public function join_138(){
		$users = $this->db->query("select distinct uid from users_level_change_log where level_type=2 and create_time > '2016-08-01 00:00:00';")->result_array();
		$this->db->trans_start();
		foreach($users as $user){
			//检查会员是否进入138矩阵
			$is_join = $this->db->from('user_coordinates')->where('user_id',$user['uid'])->count_all_results();
			if($is_join == 0){
				$this->load->model('m_forced_matrix');
				var_dump($user['uid']);
				$this->m_forced_matrix->save_user_for_138($user['uid']);
				$this->db->replace('reissue_138_tmp',array('uid'=>$user['uid']));
			}
		}
		$this->db->trans_complete();
	}

    /**
     * 会员状态变更表删除多余的数据
     */
    public function delUserStatus(){
        $uidArr = $this->db->query("select distinct uid from users_status_log")->result_array();
        foreach($uidArr as $k=>$v) {
            $uid = $v['uid'];
            $oneArr = $this->db->query("select * from users_status_log where uid = '$uid'")->result_array();
//            echo $this->db->last_query();exit;
            $fonStatus = $oneArr[0]['front_status'];
            $no_id = $oneArr[0]['id'];

            if ($fonStatus == 1) {
                foreach ($oneArr as $k1 => $v1) {
                    if ($fonStatus == $v1['front_status']) {
                        $this->db->where('id', $v1['id']);
                        $this->db->where_not_in('id', $no_id);
                        $this->db->delete('users_status_log');
                    }
                }
            }
        }

    }

    /**
         * 获取未审核身份证列表
	 * 执行时间：每1分钟执行一次
	 */
    public function card_list_ck1() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,1);
    }
    public function card_list_ck2() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,2);
    }
    public function card_list_ck3() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,3);
    }
    public function card_list_ck4() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,4);
    }
    public function card_list_ck5() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,5);
    }
    public function card_list_ck6() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,6);
    }
    public function card_list_ck7() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,7);
    }
    public function card_list_ck8() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,8);
    }
    public function card_list_ck9() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,9);
    }
    public function card_list_ck10() {
        ini_set ('memory_limit', '512M');
        set_time_limit(600);
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(1,10);
    }

    //1.通过身份证认证的排进138矩阵
    public function script_1(){
        $this->load->model('m_forced_matrix');
        $count = 0;
        $time = strtotime('2016-10-28 00:00:00');
        $res = $this->db->query("select uid from user_id_card_info where check_status = 2 and check_time>$time")->result_array();
        foreach($res as $item){
            $uid = $item['uid'];
            $record = $this->db->query("select user_id from user_coordinates WHERE user_id = $uid")->row_array();
            if(empty($record)){
                $this->m_forced_matrix->save_user_for_138($uid);
                var_dump(++$count);
            }
        }
    }

    /**
         * 对已驳回的信息，重新比对身份证信息，此为对之前代码bug的修复
	 * 执行时间：每1分钟执行一次
	 */
    public function card_list_ck_to() {
        $this->load->model('m_admin_helper');
        $this->m_admin_helper->getCardListChina(0);
    }

    /**
     * 订单推送到ERP定时任务
     * 执行时间:每1分钟执行一次
     * User:Ckf
     * Date:2016/11/26
     */
    public function trade_order_to_erp_orders(){
        //关掉php进程 100
        set_time_limit(100);
        $this->load->model('m_erp');
        $this->m_erp->trade_order_to_erp_orders();
    }

    /**
     * 库存同步到ERP定时任务,
     * 执行时间:每分钟执行一次
     * User:Ckf
     * Date:2016/11/26
     */
    public function trade_stock_to_erp(){
        //关掉php进程 100秒
        set_time_limit(100);
        $this->load->model('m_erp');
        $this->m_erp->trade_stock_to_erp();
    }

    /**
     * 把之前订单推送列表的数据转换成新机制的数组格式
     * User:Ckf
     * Date:2016/12/03
     * Time:16:59:00
     */
    public function change_orders_data(){
        $this->load->model('m_erp');
        $num = '128';
        $do = true;
        do {
            $order_list = $this->db->query("select * from trade_order_to_erp_logs WHERE error is NULL order by id limit $num")->result_array();
            if(empty($order_list)){
                return false;
            }
            if (count($order_list) < $num) {
                $do = false;
            }
            //记录id删除记录
            $idArr = array();
            foreach($order_list as $v){
                $idArr[] = $v['id'];
            }
            $ids = implode(',',$idArr);
    //        print_r($order_list);exit;
            foreach($order_list as $k=>$v){
                $apiUrl = trim($v['api_url']);
                $insert_data = array();
                $insert_data_remark = array();
                $apiParam = unserialize($v['api_param']);
                switch ($apiUrl)
                {
                    //修改订单
                    case 'Api/Order/updateTPSOrder':
                        //取消订单和退货，加库存数
                        /*事务开始*/
                        $this->db->trans_start();
                        if($apiParam['status'] == 99 || $apiParam['status'] == 98){
                            $insert_data['oper_type'] = 'modify';
                            $insert_data['data']['order_id'] = $apiParam['order_id'];
                            $insert_data['data']['status'] = $apiParam['status'];

                            //插入推送表
                            $insert_arr = array(
                                'order_id' => $insert_data['data']['order_id'],
                                'oper_data' => serialize($insert_data),
                                'oper_time'=>$v['create_time']
                            );
                            $this->db->insert('trade_order_to_erp_oper_dev',$insert_arr);

                            foreach($apiParam['goods'] as $key=>$attrs){
                                //插入到库存推送队列表
                                $goods_num = array();
                                $goods_num['goods_sn'] = $attrs['goods_sn'];
                                $goods_num['quantity'] = $attrs['alter_number'];
                                $goods_num['oper_type'] = 'inc'; //jia库存

                                //插入到库存队列表
                                $this->db->insert('trade_order_to_erp_inventory_dev',$goods_num);
                            }
                        }else{
                            $insert_data['oper_type'] = 'modify';
                            $insert_data['data']['order_id'] = $apiParam['order_id'];
                            $insert_data['data'] = $apiParam;

                            //插入推送表
                            $insert_arr = array(
                                'order_id' => $insert_data['data']['order_id'],
                                'oper_data' => serialize($insert_data),
                                'oper_time'=>$v['create_time']
                            );
                            $this->db->insert('trade_order_to_erp_oper_dev',$insert_arr);
                        }

                        $this->db->trans_complete();//事务结束
                        //事务回滚了
                        if ($this->db->trans_status() === FALSE)
                        {
                            $this->db->where('id',$v['order_id'])->set('error','转换出错')->update('trade_order_to_erp_logs');
                            break;
                        }
                        //事务提交
                        else
                        {
                            $this->db->trans_commit();
                        }
                        break;
                    //添加订单
                    case 'Api/Order/addTPSOrder':
                        /*事务开始*/
                        $this->db->trans_start();

                        $insert_data['oper_type'] = 'create';
                        $insert_data['data']['order_id'] = $v['order_id'];
                        $insert_data['data']['order_prop'] = $apiParam['order_prop'];
                        $insert_data['data']['customer_id'] = $apiParam['customer_id'];
                        $insert_data['data']['consignee'] = $apiParam['consignee'];
                        $insert_data['data']['phone'] = $apiParam['phone'];
                        $insert_data['data']['reserve_num'] = $apiParam['reserve_num'];
                        $insert_data['data']['address'] = $apiParam['address'];
                        $insert_data['data']['zip_code'] = $apiParam['zip_code'];
                        $insert_data['data']['customs_clearance'] = $apiParam['customs_clearance'];
                        $insert_data['data']['id_no'] = $apiParam['id_no'];
                        $insert_data['data']['id_img_front'] = $apiParam['id_img_front'];
                        $insert_data['data']['id_img_reverse'] = $apiParam['id_img_reverse'];
                        $insert_data['data']['remark'] = $apiParam['remark'];
                        $insert_data['data']['created_at'] = $apiParam['created_at'];
                        $insert_data['data']['currency'] = $apiParam['currency'];
                        $insert_data['data']['currency_rate'] = $apiParam['currency_rate'];
                        $insert_data['data']['payment_type'] = $apiParam['payment_type'];
                        $insert_data['data']['discount_type'] = $apiParam['discount_type'];
                        $insert_data['data']['goods_amount'] = $apiParam['goods_amount'];
                        $insert_data['data']['deliver_fee'] = $apiParam['deliver_fee'];
                        $insert_data['data']['order_amount'] = $apiParam['order_amount'];
                        $insert_data['data']['goods_amount_usd'] = $apiParam['goods_amount_usd'];
                        $insert_data['data']['deliver_fee_usd'] = $apiParam['deliver_fee_usd'];
                        $insert_data['data']['discount_amount_usd'] = $apiParam['discount_amount_usd'];
                        $insert_data['data']['order_amount_usd'] = $apiParam['order_amount_usd'];
                        $insert_data['data']['order_profit_usd'] = $apiParam['order_profit_usd'];
                        $insert_data['data']['txn_id'] = $apiParam['txn_id'];
                        $insert_data['data']['pay_time'] = $apiParam['pay_time'];
                        $insert_data['data']['shipper_id'] = $apiParam['shipper_id'];
                        $insert_data['data']['status'] = $apiParam['status'];

                        //商品数据
                        foreach($apiParam['goods'] as $key=>$attrs){
                            $gn = $attrs['goods_sn'];
                            $insert_data['data']['goods'][$gn]['supplier_id'] = $attrs['supplier_id'];
                            $insert_data['data']['goods'][$gn]['goods_sn_main'] = $attrs['goods_sn_main'];
                            $insert_data['data']['goods'][$gn]['goods_sn'] = $attrs['goods_sn'];
                            $insert_data['data']['goods'][$gn]['quantity'] = $attrs['quantity'];
                            $insert_data['data']['goods'][$gn]['price'] = $attrs['price'];

                            //插入到库存推送队列表
                            $goods_num = array();
                            $goods_num['goods_sn'] = $attrs['goods_sn'];
                            $goods_num['quantity'] = $attrs['quantity'];
                            $goods_num['oper_type'] = 'dec'; //减库存

                            //插入到库存队列表
                            $this->db->insert('trade_order_to_erp_inventory_dev',$goods_num);
                        }
                        //插入到订单推送队列表
                        $insert_arr = array(
                            'order_id' => $insert_data['data']['order_id'],
                            'oper_data' => serialize($insert_data),
                            'oper_time'=>date('Y-m-d H:i:s',time())
                        );
                        $this->db->insert('trade_order_to_erp_oper_dev',$insert_arr);

                        $this->db->trans_complete();//事务结束
                        //事务回滚了
                        if ($this->db->trans_status() === FALSE)
                        {
                            $this->db->where('id',$v['order_id'])->set('error','转换出错')->update('trade_order_to_erp_logs');
                            break;
                        }
                        //事务提交
                        else
                        {
                            $this->db->trans_commit();
                        }
                        break;
                    //添加备注
                    case 'Api/Order/addTPSOrderRemark':

                        $insert_data_remark['oper_type'] = 'remark';
                        $insert_data_remark['data']['order_id'] = $v['order_id'];
                        $insert_data_remark['data']['remark'] = $apiParam['remark'];
                        $insert_data_remark['data']['type'] = $apiParam['type']; //1 系统可见备注，2 用户可见备注
                        $insert_data_remark['data']['recorder'] = $apiParam['recorder']; //操作人
                        $insert_data_remark['data']['created_time'] = $apiParam['created_time'];

                        //插入推送表
                        $insert_arr = array(
                            'order_id' => $insert_data_remark['data']['order_id'],
                            'oper_data' => serialize($insert_data_remark),
                            'oper_time'=>$v['create_time']
                        );
                        $res = $this->db->insert('trade_order_to_erp_oper_dev',$insert_arr);
                        if(!$res){
                            $this->db->where('id',$v['order_id'])->set('error','转换出错')->update('trade_order_to_erp_logs');
                            break;
                        }
                        break;
                }

            }
            $this->db->query("delete from trade_order_to_erp_logs WHERE id in ($ids) and error is NULL");
        } while ($do);

    }

    /**
     * 修复订单快递公司脚本
     * User:Ckf
     * Date:2016/12/08
     * Time:16:43:00
     */
    public function repair_order_freight(){
        $order_list = $this->db->query("SELECT order_id,freight_info FROM `trade_orders` WHERE freight_info LIKE \"107|800000%\" AND shipper_id = 1376")->result_array();
        $this->load->model("tb_trade_orders");
        $strFreight = '114';
        foreach($order_list as $k=>$v){
            $freight = trim($v['freight_info']);
            $arr = explode('|',$freight);
            if(count($arr) == 2){
                $NewFreight = $strFreight.'|'.$arr[1];
//                $this->db->where('order_id',$v['order_id'])->set('freight_info',$NewFreight)->update('trade_orders');
                $this->tb_trade_orders->update_one(['order_id'=>$v['order_id']],['freight_info'=>$NewFreight]);
            }
        }
    }

    /**
	 * 执行时间：每十分钟执行一次
	 */
	public function ailapy_active_query()
	{
		$this->load->model('m_alipay');
                $this->m_alipay->ailapy_active_query();
	}
        /**
	 *6.20修复已支付的订单
	 */
	public function repair_order()
	{
		$this->load->model('m_alipay');
                $this->m_alipay->repair_order();
	}
        /**
	 * 执行时间：每十分钟执行一次
	 */
        public function unionpay_active_query() {
        $this->load->model('m_unionpay');
        print_r($this->m_unionpay->unionpay_active_query());
    }

    /**
     * 修复订单地址异常脚本
     * User:Ckf
     * Date:2016/12/13
     * Time:18:46:00
     */
    public function repair_order_address(){
        $order_list = $this->db->query("SELECT order_id,address FROM trade_orders WHERE address NOT LIKE '%中国%' and address NOT LIKE '%省%' and address NOT LIKE '%市%' and `area` = '156' and updated_at > '2016-12-07 00:00:00'")->result_array();

        foreach($order_list as $k=>$v){
            $address = trim($v['address']);
            $res = $this->db->select('*')->where('address_detail',$address)->get('trade_user_address')->row_array();

            if(empty($res)){
                continue;
            }

            $lv1 = '中国';//国家
            $lv2 = $this->db->select('name')->where('code',$res['addr_lv2'])->where('country_code','156')->get('trade_addr_linkage')->row_array();
            $lv3 = $this->db->select('name')->where('code',$res['addr_lv3'])->where('country_code','156')->get('trade_addr_linkage')->row_array();
            $lv4 = $this->db->select('name')->where('code',$res['addr_lv4'])->where('country_code','156')->get('trade_addr_linkage')->row_array();

            $lv2 = isset($lv2['name'])?$lv2['name']:'';
            $lv3 = isset($lv3['name'])?$lv3['name']:'';
            $lv4 = isset($lv4['name'])?$lv4['name']:'';
            $nes_address = $lv1.' '.$lv2.' '.$lv3.' '.$lv4.' '.$v['address'];

            $this->db->where('order_id',$v['order_id'])->set('address',$nes_address)->update('trade_orders');
        }

//        print_r($order_list);exit;
    }
    /**
     *  删除重复SKU
     */
    public function delgoods()  {
        $sale_country = array('156' => 2, '410' => 4, '840' => 1, '344' => 3, '000' => 1);
        $list = $this->db->query("SELECT goods_id,sale_country,language_id,goods_sn_main from mall_goods_main  
            where goods_sn_main in  (
                SELECT
                  goods_sn_main
                FROM
                  mall_goods_main
                GROUP BY
                  goods_sn_main
                HAVING
                  count(*) > 1
                ) and length(sale_country) > 3")
            ->result_array();
        if ($list) {
            $this->load->model("tb_mall_goods_main");
            foreach ($list as $k => $v) {
                if (strlen($v['sale_country']) > 3) {
                    $arr = explode("$", $v['sale_country']);
                    foreach ($sale_country as $sk => $sv) {
                        if (in_array($sk, $arr)) {
                            if ($sv == $v['language_id']) {
//                                $this->db->update('mall_goods_main', ['sale_country' => $sk ], array('goods_id' =>$v['goods_id']));
                                $this->tb_mall_goods_main->update_one_auto([
                                    "data"=> ['sale_country' => $sk ],
                                    "where"=>array('goods_id' =>$v['goods_id'])
                                ]);
                                echo "update one\n\r";
                            } else {
//                                $this->db->delete("mall_goods_main", array('goods_id' =>$v['goods_id']));
                                $this->tb_mall_goods_main->delete_one_auto([
                                    "where"=>array('goods_id' =>$v['goods_id'])
                                ]);
                                echo  "delete one\n\r";
                            }
                        }
                    }
                }
            }
        }
        $list = $this->db->query("SELECT goods_id,sale_country,language_id,goods_sn_main from mall_goods_main  
            where goods_sn_main in  (
                SELECT
                  goods_sn_main
                FROM
                  mall_goods_main
                GROUP BY
                  goods_sn_main
                HAVING
                  count(*) > 1
                )")
            ->result_array();
        if ($list) {
            $this->load->model("tb_mall_goods_main");
            foreach ($list as $k => $v) {
                if (array_key_exists($v['sale_country'], $sale_country)  &&  $v['language_id'] != $sale_country[$v['sale_country']]) {
//                    $this->db->delete("mall_goods_main", array('goods_id' =>$v['goods_id']));
                    $this->tb_mall_goods_main->delete_one_auto([
                        "where"=>array('goods_id' =>$v['goods_id'])
                    ]);
                    echo  "delete one\n\r";
                }
            }
        }
    }

    /**
     * 修复会员收货地址数量
     * User:Ckf
     * Date:2016/12/28
     * Time:19:00:00
     */
    public function repair_users_address(){
        $order_list = $this->db->query("SELECT distinct `uid` FROM trade_user_address where `uid` in (SELECT `uid` FROM trade_user_address GROUP BY `uid` HAVING COUNT(`uid`) > '5')")->result_array();
        foreach($order_list as $k=>$v){
            $uid = $v['uid'];
            $adds_list = $this->db->query("SELECT id FROM trade_user_address WHERE uid = '$uid' ORDER BY updated_at LIMIT 5,150")->result_array();
            $ids ='';
            foreach($adds_list as $kk=>$vv){
                $ids .= "'".$vv['id']."',";
            }
            $ids = rtrim($ids,',');
            $this->db->query("delete from trade_user_address WHERE id in ($ids)");
        }
    }

    /**
     * 移除产品数量为0的订单
     * 取数量为0的子订单
     * @param bool $b 如果是0或false打印结果
     * @return array
     */
    public function get_zero_trade_orders_goods($b=true)
    {
        if(!$b)
        {
            echo("start:get_zero_trade_orders_goods\n");
        }
        $res = [];
        $tmp =  $this->db->select("id,order_id")
            ->from("trade_orders_goods")
            ->where("goods_number","0")
            ->limit(100)
            ->get()
            ->result_array();
        foreach($tmp as $k=>$v)
        {
            $res[] = $v['order_id'];
        }
        if(!$b)
        {
            var_dump($res);
        }
        if(!$b)
        {
            echo("finish:get_zero_trade_orders_goods\n");
        }
        return $res;
    }
    /**
     * 移除产品数量为0的订单
     * 取数量为0的订单
     */
    public function get_zero_trade_orders($order_id_list)
    {
//        return $this->db->select("order_id,goods_list")
//            ->from("trade_orders")
//            ->where_in("order_id",$order_id_list)
//            ->get()
//            ->result_array();
        $this->load->model("tb_trade_orders");
        return $this->tb_trade_orders->get_list_auto([
           "select"=>"order_id,goods_list",
            "where"=>["order_id"=>$order_id_list]
        ]);
    }

    /**
     * 清理所有数量为0的goods_list的字符串
     * @param $goods_list_str
     * @return string
     */
    public function clear_zero_goods_list($goods_list_str)
    {
        $res = "";
        $goods_list_arr = explode("$",$goods_list_str);
        foreach($goods_list_arr as $k=>$v)
        {
            $goods = explode(":",$v);
            if(!empty($goods))
            {
                if("0" != $goods[1])
                {
                    if(!empty($res))
                    {
                        $res .= "$";
                    }
                    $res .= $goods[0].":".$goods[1];
                }
            }
        }
        return $res;
    }

    /**
     * 清理数量为0的TPS订单
     * @param bool $b,传0或不传为预览数据。传1或true为正式执行
     */
    public function remove_zero_order($b=false)
    {
        $this->load->model("m_debug");
        if(!$b){
            echo("testing mode....\n");
            $this->m_debug->log("testing mode....\n");
        }
        echo("start...\n");
        if(!$b){echo("line:".__LINE__."\n");}
        $max = 1000;//最大循环次数
        if(!$b){echo("line:".__LINE__."\n");}
        $order_ids = $this->get_zero_trade_orders_goods();
        if(!$b){echo("line:".__LINE__."\n");}
        ob_flush();
        for($i=0;$i<$max;$i++)
        {
            echo("page:". $i ."\n");
            ob_flush();
            if(empty($order_ids))
            {
                break;
            }
            $orders  = $this->get_zero_trade_orders($order_ids);
            foreach($orders as $ko=>$vo)
            {
                $order_id = $vo['order_id'];
                $goods_list_str = $this->clear_zero_goods_list($vo['goods_list']);
                //如果没有子订单了，删除母订单
                if(empty($goods_list_str))
                {
                    echo("delete trade_orders : $order_id \n");ob_flush();
                    $this->m_debug->log("delete trade_orders : $order_id \n");
                    if($b){
                        $this->db->where("order_id",$order_id)
                            ->limit(1)->delete("trade_orders");
                    }
                }else{
                    //如果还有子订单，更新子订单
                    echo("update trade_orders :$order_id goods_list to $goods_list_str\n");ob_flush();
                    $this->m_debug->log("update trade_orders :$order_id goods_list to $goods_list_str\n");
                    if($b){
                        $this->db->where("order_id",$order_id)->limit(1)
                            ->update("trade_orders",["goods_list"=>$goods_list_str]);
//                        echo $this->db->last_query()."\n";
                    }
                }
                echo("delete trade_orders_goods : $order_id and goods_number = 0 \n");ob_flush();
                $this->m_debug->log("delete trade_orders_goods : $order_id and goods_number = 0 \n");
                if($b)
                {
                    $this->db->where("order_id",$order_id)->where("goods_number",0)
                        ->limit(1)->delete("trade_orders_goods");//一个母订单有多个为0子订单存在无法删除bug.
                }
            }
            //继续清理goods_number为0的子订单，残留了378个子订单
            foreach($order_ids as $order_id)
            {
                $this->db->where("order_id",$order_id)->where("goods_number",0)
                    ->limit(5)->delete("trade_orders_goods");
            }
            $order_ids = $this->get_zero_trade_orders_goods();
        }
        echo("end...\n");
    }

    /**
     *  删除mall_goods重复SKU
     */
    public function del_mall_goods()  {
        $list = $this->db->query("SELECT goods_sn  from mall_goods GROUP BY goods_sn HAVING count(*) > 1 ")->result_array();
        if ($list) {
            $this->load->model("tb_mall_goods");
            $this->load->model("tb_mall_goods_main");
            foreach ($list as $k => $v) {
                $arr = explode("-", $v['goods_sn']);
                $goods_sn_main = (string)$arr[0];
//                $goods_info = $this->db->query("SELECT language_id from  mall_goods_main where goods_sn_main='{$goods_sn_main}'")->row_array();
                $goods_info = $this->tb_mall_goods_main->get_one_auto([
                    "select"=>"language_id",
                    "where"=>['goods_sn_main'=>$goods_sn_main]
                ]);
                if ($goods_info) {
                    $language_id = (int)$goods_info['language_id'];
//                    $this->db->delete("mall_goods", "goods_sn = '{$v['goods_sn']}' AND  goods_sn_main = '{$goods_sn_main}' AND  language_id != {$language_id} " );
                    $this->tb_mall_goods->delete_one_auto([
                       "where"=>["goods_sn"=>$v['goods_sn'],
                           "goods_sn_main"=>$goods_sn_main,
                           "language_id !="=>$language_id]
                    ]);
                }
            }
        }
    }
    
    /**
     * 从文件读取trade_addr_linkage并写成sql
     * @param $file
     */
    public function sql_from_csv($file="/tmp/us_db.csv")
    {
        $out_file = "/tmp/trade_addr.sql";
        @unlink($out_file);

        $res_insert = [];
        $res_codes = [];
        $res = file_get_contents($file);
        $res = explode("\n",$res);
//        $linkage = $this->db->select("country_code,code")
//            ->where("country_code",840)->where("level",2)->limit(1000)
//            ->get("trade_addr_linkage")->result_array();
        $i = 0;
        foreach($res as $k=>$v)
        {
            if($i > 100)
            {
                //break;
            }
            $i ++;
            if($k != 0)
            {
                $res_arr = explode(",",$v);
                if(in_array($res_arr[2],$res_insert)){
                    continue;
                }
                $res_insert[] = $city_name = $res_arr[2];//城市名
                $parent_code = $res_arr[3];//州代码
                $country_code = 840;//国家代码
                $code = substr(preg_replace("/\s/","",$city_name),0,10);//城市代码
                if(in_array($code,$res_codes))
                {
                    for($i=0;$i<10;$i++)
                    {
                        if(!in_array($code.$i,$res_codes))
                        {
                            $code = $code.$i;
                            $res_codes[] = $code;
                            @file_put_contents($out_file."code","$code;\n",8);
                            break;
                        }
                    }
                }else{
                    $res_codes[] = $code;
                }
                $name = ucwords(strtolower($city_name));//城市名

                if($country_code and $code and $parent_code and $name){
                    @file_put_contents($out_file,"INSERT INTO trade_addr_linkage(`country_code`,`code`,`parent_code`,`name`,`level`) VALUES('$country_code','$code','$parent_code','$name',3);\n",8);
                }
            }
        }
    }
    public function data_convert_to_json_file($b=true)
    {
        echo(__FILE__.",".__LINE__."\n");
        $this->load->model('m_trade');
        $this->m_trade->data_convert_to_json_file($b);
        echo(__FILE__.",".__LINE__."\n");
    }

    //批量驳回
    public function batch_refuse_id_card(){
        $this->load->model('tb_user_id_card_info');
        $this->tb_user_id_card_info->batch_refuse_id_card();
    }

    /**
     * 修复订单的SKU,并调用api接口
     * @param $old_sn_main，旧的SKU_MAIN
     * @param $new_sn_main，新的SKU_MAIN
     * @param int $b,是否真的执行,0为不执行实际更新，1为执行更新，-1为预览执行详细也不更新数据
     */
    public function repair_order_sn_api($old_sn_main="",$new_sn_main="",$b=0,$block=1)
    {
        set_time_limit(0);
        if(!$old_sn_main)
        {
            echo("input old_sn_main value plz. in 1 param position.");
            return;
        }
        if(!$new_sn_main)
        {
            echo("input new_sn_main value plz. in 2 param position.");
            return;
        }
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
        $old_orders_goods = $this->tb_trade_orders_goods->get_list("id,order_id,goods_sn_main,goods_sn",
            ["goods_sn_main"=>$old_sn_main]);
        $ords_ids = [];
        foreach($old_orders_goods as $k=>$v)
        {
            $ords_ids[] = $v['order_id'];
        }
        if(count($ords_ids)){
//            $old_orders = $this->db->select("order_id,goods_list")->where_in("order_id",$ords_ids)
//                ->get("trade_orders")->result_array();
            $old_orders = $this->tb_trade_orders->get_list("order_id,goods_list",["order_id"=>$ords_ids]);
        }else{
            $old_orders = [];
        }
        if($b == 0)
        {
            echo("trade_orders:".count($old_orders)."\n");
            echo("trade_orders_goods:".count($old_orders_goods)."\n");
            return;
        }
        $this->load->model("m_erp");
        foreach($old_orders_goods as $k=>$v)
        {

            if($b == 1){
                $res = $this->m_erp->repair_erp_order_sn($old_sn_main,$new_sn_main,$v['order_id']);
                if($res['code'] != "200")
                {
                    file_put_contents("/tmp/repair_orders.log",$res['msg']."\n",8);
                    echo($res['msg']."\n");ob_flush();
                    if(substr($v['order_id'],0,1) !== 'P')
                    {
                        //如果是非P开头订单不进行替换。
                        continue;
                    }else{
                        echo("order_id no start by P ,continue replace.");ob_flush();
                    }
                }else{
                    echo($res['msg']."\n");ob_flush();
                }
            }
            //替换trade_orders_goods表的goods_sn_main
            $new_goods_sn = str_replace($old_sn_main."-",$new_sn_main."-",$v['goods_sn']);
            $str_tmp = "update trade_orders_goods set goods_sn = '{$new_goods_sn}',goods_sn_main = '{$new_sn_main}' where id = {$v['id']};\n";
            if($b == 1){
                echo("execute query:".$str_tmp);ob_flush();
                $this->db->query($str_tmp);
            }
            if($b == -1)
            {
                echo($str_tmp);ob_flush();
            }
            foreach($old_orders as $k2=>$v2)
            {
                if($v2['order_id'] == $v['order_id'])
                {
                    //替换trade_orders表的goods_sn_main
                    $new_goods_list = $this->replace_goods_list($v2['goods_list'],$old_sn_main,$new_sn_main);
//                    $str_tmp = "update trade_orders set goods_list = '{$new_goods_list}' where order_id = '{$v2['order_id']}';\n";
                    if($b == 1){
                        echo("execute query:".$str_tmp);ob_flush();
//                        $this->db->query($str_tmp);
                        $this->tb_trade_orders->update_one(["order_id"=>$v2['order_id']],
                            ["goods_list"=>$new_goods_list]);
                    }
                    if($b == -1)
                    {
                        echo($str_tmp);ob_flush();
                    }
                    unset($old_orders[$k2]);
                }
            }
        }

    }

    /**
     * 修复订单的SKU
     * @param $old_sn_main，旧的SKU_MAIN
     * @param $new_sn_main，新的SKU_MAIN
     * @param int $b,是否真的执行,0为不执行实际更新，1为执行更新，-1为预览执行详细也不更新数据
     */
    public function repair_order_sn($old_sn_main="",$new_sn_main="",$b=0,$block=1)
    {
        if(!$old_sn_main)
        {
            echo("input old_sn_main value plz. in 1 param position.");
            return;
        }
        if(!$new_sn_main)
        {
            echo("input new_sn_main value plz. in 2 param position.");
            return;
        }
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
        $old_orders_goods = $this->tb_trade_orders_goods->get_list("id,order_id,goods_sn_main,goods_sn",
            ["goods_sn_main"=>$old_sn_main]);
        $ords_ids = [];
        foreach($old_orders_goods as $k=>$v)
        {
            $ords_ids[] = $v['order_id'];
        }
        if(count($ords_ids)){
//            $old_orders = $this->db->select("order_id,goods_list")->where_in("order_id",$ords_ids)
//                ->get("trade_orders")->result_array();
            $old_orders = $this->tb_trade_orders->get_list_auto(
                [
                    "select"=>"order_id,goods_list",
                    "where"=>["order_id"=>$ords_ids]
                ]
            );
        }else{
            $old_orders = [];
        }
        if($b == 0)
        {
            echo("trade_orders:".count($old_orders)."\n");
            echo("trade_orders_goods:".count($old_orders_goods)."\n");
            return;
        }
        $str = [];
        foreach($old_orders as $k=>$v)
        {
            //替换goods_sn_main
            $new_goods_list = $this->replace_goods_list($v['goods_list'],$old_sn_main,$new_sn_main);
            $str[] = "update trade_orders set goods_list = '{$new_goods_list}' where order_id = '{$v['order_id']}';\n";
        }
        foreach($old_orders_goods as $k=>$v)
        {
            //替换goods_sn_main
            $new_goods_sn = str_replace($old_sn_main."-",$new_sn_main."-",$v['goods_sn']);
            $str[] = "update trade_orders_goods set goods_sn = '{$new_goods_sn}',goods_sn_main = '{$new_sn_main}' where id = {$v['id']};\n";
        }
        if($b == -1){
            while(count($str))
            {
                $tmp_sql = "";
                $i=0;
                foreach($str as $k=>$v)
                {
                    $i++;
                    if($i > $block)
                    {
                        break;
                    }
                    $tmp_sql .= $v;
                    unset($str[$k]);
                }
                echo($tmp_sql);
                ob_flush();
                if(!$tmp_sql)
                {
                    break;
                }
            }
        }
        if($b == 1){
            while(count($str))
            {
                $tmp_sql = "";
                $i=0;
                foreach($str as $k=>$v)
                {
                    $i++;
                    if($i > $block)
                    {
                        break;
                    }
                    $tmp_sql .= $v;
                    unset($str[$k]);
                }
                if(!$tmp_sql)
                {
                    break;
                }
                echo("execute query:".$tmp_sql);
                ob_flush();
                $this->db->query($tmp_sql);
            }
        }
    }
    /**
     * 用新旧goods_sn_main替换goods_list里的字符串
     * @param $goods_list_str
     * @return string
     */
    public function replace_goods_list($goods_list_str,$old_goods_sn,$new_goods_sn)
    {
        $res = "";
        $goods_list_arr = explode("$",$goods_list_str);
        foreach($goods_list_arr as $k=>$v)
        {
            $goods = explode(":",$v);
            if(!empty($goods))
            {
                if($goods[0])
                {
                    if(!empty($res))
                    {
                        $res .= "$";
                    }
                    $res .= str_replace($old_goods_sn."-",$new_goods_sn."-",$goods[0]).":".$goods[1];
                }
            }
        }
        return $res;
    }

    /**
     * mall_supplier，电话字段去掉字符两端字符
     * Ckf
     */
    public function repair_mall_supplier(){
        $lists = $this->db->select('*')->get('mall_supplier')->result_array();
        foreach($lists as $v){
            $phone = trim($v['supplier_phone']);
            $name = trim($v['supplier_name']);
            $tel = trim($v['supplier_tel']);
            $user = trim($v['supplier_user']);
            $address = trim($v['supplier_address']);
            $qq = trim($v['supplier_qq']);
            $ww = trim($v['supplier_ww']);
            $email = trim($v['supplier_email']);
            $link = trim($v['supplier_link']);
            $data = array(
                'supplier_phone' => $phone,
                'supplier_name' => $name,
                'supplier_tel' => $tel,
                'supplier_user' => $user,
                'supplier_address' => $address,
                'supplier_qq' => $qq,
                'supplier_ww' => $ww,
                'supplier_email' => $email,
                'supplier_link' => $link,
            );
            $this->db->where('supplier_id',$v['supplier_id'])->update('mall_supplier', $data);
        }
        echo 'end';
    }
    
    
    /***
     * 补发每周团队分红奖
     * @param 补发时间(时间格式：201703) $year_month
     */
    public function user_week_team_sales($year_month)
    {
    
        $sql = "select b.uid as uid  from users a left join users_store_sale_info_monthly b on a.id = b.uid  where a.user_rank <=3 and a.sale_rank > 0   and b.year_month='".$year_month."' and b.sale_amount >= 7500";
        $query = $this->db->query($sql);
        $query_value = $query->result_array();
        if(!empty($query_value))
        {           
            foreach($query_value as $sult)
            {
                $week_sql = "SELECT week_share_bonus FROM user_comm_stat WHERE uid=".$sult['uid']." limit 1";
                $week_query = $this->db->query($week_sql);
                $week_date = $week_query->row_array();
                $add_status = 0;
                if(empty($week_date))
                {
                    $add_status = 1;
                }
                else 
                {
                    if(empty($week_date['week_share_bonus']) && 0==$week_date['week_share_bonus'])
                    {
                        $add_status = 1;
                    }
                }
                
                if(1==$add_status)
                {
                   
                     $add_sql = "insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
                     ) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b
                     on a.uid=b.id where a.uid = ".$sult['uid']." and a.year_month = '".$year_month."' and b.user_rank in(3,2,1)
                     and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=7500";
                     $this->db->query($add_sql);                     
                }
            }
        }



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

    public function getProportion($uid, $proportion_type) {
        $arrProportionType = array(
            'sale_commissions_proportion' => 1,
            'forced_matrix_proportion' => 2,
            'profit_sharing_proportion' => 3,
        );
       $pres = $this->db->from('profit_sharing_point_proportion')->where('uid', $uid)->where('proportion_type', $arrProportionType[$proportion_type])->get();
        return $pres ? @$pres->row_object()->proportion : 0;
    }

    /**
     * 遍历redis里的独立库存，然后更新到mysql里
     */
    public function update_redis_goods_number_2_mysql()
    {
//        $this->xhprof_start();
        $this->load->model("tb_mall_goods");
        $this->tb_mall_goods->update_redis_goods_number_2_mysql();
//        $this->xhprof_finish("update_redis_goods_number_2_mysql");
    }

    public function update_my_redis_index()
    {
        $this->load->model("tb_empty");
        $this->tb_empty->my_index_refresh();
        exit("finish...");
    }

    /***
     * 每月领导分红奖  判断用户是否满足发奖条件，满足的话， 将其添加到队列中
     */
    public function new_every_month_leader_queue_list()
    {
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(23,1,0,0,"重置",1);
            $this->bonus_plan_control(23,1,time(),0,"每月领导分红用户加入队列",0);
            $this->load->model("o_month_leader_bonus_option");
            $this->o_month_leader_bonus_option->new_month_leader_bonus();
        }        
    }
    
    /**
     * 每月初生成新的月团队组织分红发奖列表  添加队列
     */
    public function new_every_month_team_queue_list()
    {
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(1,1,0,0,"重置",1);
            $this->bonus_plan_control(1,1,time(),0,"月团队组织分红用户加入队列",0);
            $this->load->model("o_month_leader_bonus_option");
            $this->o_month_leader_bonus_option->new_every_month_team_group_sharelist();
        }        
    }

    /**
     * 每月初生成新的周团队分红发奖列表  队列表
     */
    public function new_every_week_team_queue_list()
    {
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(25,1,0,0,"重置",1);
            $this->bonus_plan_control(25,1,time(),0,"周团队分红用户加入队列",0);
            $this->load->model("o_month_leader_bonus_option");
            $this->o_month_leader_bonus_option->new_every_week_share_queue_list();
        }        
    }
    
    // 预发奖
    
    /***
     * 预发放每周团队销售分红    预发奖
     */
    public function grant_pre_every_week_team_bonus()
    {
        $this->bonus_plan_control(25,1,time(),0,"周团队分红用户加入预发队列",0);
        $this->load->model("o_month_leader_bonus_option");       
        $this->o_month_leader_bonus_option->grant_pre_every_week_team_dividend();        
    }
    
    /***
     * 发奖每月团队组织分红奖   预发奖
     */
    public function grant_pre_every_month_team_bonus()
    {
        $this->bonus_plan_control(1,1,time(),0,"月团队组织分红用户加入预发队列",0);
        $this->load->model("o_month_leader_bonus_option");      
        $this->o_month_leader_bonus_option->grant_pre_every_month_team_dividend();
        $this->bonus_plan_control(1,1,0,time(),"月团队组织分红用户加入预发队列完成",0);
    }
    
    /***
     * 发奖每月领导分红奖   预发奖
     */
    public function grant_pre_every_leader_bonus()
    {
        $this->bonus_plan_control(23,1,time(),0,"每月领导分红用户加入预发队列",0);
        $this->load->model("o_month_leader_bonus_option");
        $this->load->model('tb_grant_pre_bonus_state'); //预发奖执行状态
        $this->tb_grant_pre_bonus_state->edit_state(23,0,"");  //预发奖状态初始化
        $this->tb_grant_pre_bonus_state->edit_state(23,2,"");  //预发奖状态初始化
        $this->o_month_leader_bonus_option->pre_monthLeaderSharing_new(); //市场主管
        $this->o_month_leader_bonus_option->pre_monthTopLeaderSharing_new(); //市场总监
        $this->o_month_leader_bonus_option->pre_monthLeader5Sharing_new(); //全球副总裁
        $this->tb_grant_pre_bonus_state->edit_state(23,3,"");  //预发奖状态初始化
        $this->bonus_plan_control(23,1,0,time(),"每月领导分红用户加入预发队列完成",0);
    }
    
    //-->end  预发奖
    /**
     * 发放每周团队组织分红奖
     */
    public function grant_every_week_team_bonus()
    {
        $this->bonus_plan_control(25,1,time(),0,"周团队组织分红实际发奖开始",0);
        $this->load->model("o_month_leader_bonus_option");
        $this->load->model('tb_grant_pre_bonus_state'); //发奖执行状态
        $this->tb_grant_pre_bonus_state->edit_state(25,0,"");  //发奖状态初始化
        $this->tb_grant_pre_bonus_state->edit_state(25,2,"");  //发奖状态初始化
        $this->o_month_leader_bonus_option->grant_every_week_team_dividend();
        $this->tb_grant_pre_bonus_state->edit_state(25,3,"");  //发奖状态初始化
        $this->bonus_plan_control(25,2,0,time(),"周团队组织分红实际发奖完成",0);
    }
    
    /**
     * 发放每月团队组织分红奖
     */
    public function grant_every_month_team_bonus()
    {
        $this->bonus_plan_control(1,1,time(),0,"月团队组织分红实际发奖开始",0);
        $this->load->model("o_month_leader_bonus_option");
        $this->load->model('tb_grant_pre_bonus_state'); //发奖执行状态
        $this->tb_grant_pre_bonus_state->edit_state(1,0,"");  //发奖状态初始化
        $this->tb_grant_pre_bonus_state->edit_state(1,2,"");  //发奖状态初始化
        $this->o_month_leader_bonus_option->grant_every_month_team_dividend();
        $this->tb_grant_pre_bonus_state->edit_state(1,3,"");  //发奖状态初始化
        $this->bonus_plan_control(1,2,0,time(),"月团队组织分红实际发奖完成",0);
    }
    
    /**
     * 发放每月领导分红奖
     */
    public function grant_every_month_leader_bonus()
    {
        $this->bonus_plan_control(23,1,time(),0,"每月领导分红实际发奖开始",0);
        $this->load->model("o_month_leader_bonus_option");
        $this->load->model('tb_grant_pre_bonus_state'); //发奖执行状态
        $this->tb_grant_pre_bonus_state->edit_state(23,0,"");  //发奖状态初始化
        $this->tb_grant_pre_bonus_state->edit_state(23,2,"");  //发奖状态初始化
        $this->o_month_leader_bonus_option->grant_monthLeaderSharing_new(3);
        $this->o_month_leader_bonus_option->grant_monthLeaderSharing_new(4);
        $this->o_month_leader_bonus_option->grant_monthLeaderSharing_new(5);
        $this->tb_grant_pre_bonus_state->edit_state(23,3,"");  //发奖状态初始化
        $this->bonus_plan_control(23,2,0,time(),"每月领导分红实际发奖完成",0);
    }
    
    //发奖
    
    //-->end 发奖
    
    /**
     * 后台预发奖和发奖
     * @param 类型（1：预发奖；2.发奖） $option
     * @param 奖金类型 $item_type
     */
    public function grant_users_bonus($option,$item_type)
    {
        $this->load->model("o_month_leader_bonus_option");
        $this->load->model("tb_system_grant_bonus_logs");
        
        /*$data = array
        (
            'uid' => $uid,
            'item_type' => $item_type,
            'grant_type' => $option,
            'grant_state' => 0            
        );
        $this->tb_system_grant_bonus_logs->add_grant_bonus_logs($data);  */
        switch($option)
        {
            case 1:
                //预发奖
                switch($item_type)
                {
                    case 25:                        
                        //每周团队组织分红
                        $this->o_month_leader_bonus_option->grant_pre_every_week_team_dividend();
                        break;
                    case 1:
                        //每月团队销售分红
                        $this->o_month_leader_bonus_option->grant_pre_every_month_team_dividend();
                        break;
                    case 23:                        
                        //每月领导分红
                        $this->grant_pre_every_leader_bonus();
                        break;
                    case 26:
                        //新会员专项奖
                        $this->new_member_bonus_pre();
                        break;
                }
                break;
            case 2:
                //发奖
                switch($item_type)
                {
                    case 25:
                        //每周团队组织分红
                        $this->grant_every_week_team_bonus();
                        break;
                    case 1:
                        //每月团队销售分红
                        $this->grant_every_month_team_bonus();
                        break;
                    case 23:
                        //每月领导分红
                        $this->grant_every_month_leader_bonus();
                        break;
                }
                break;
        }
        //$data['grant_state'] =1;
       // $this->tb_system_grant_bonus_logs->add_grant_bonus_logs($data);
    }
    
    /***
     * 界面操作后台自动执行预发奖或发奖程序
     */
    public function grant_users_bonus_option()
    {
        $this->load->model('tb_grant_pre_bonus_state'); //发奖执行状态
        $this->load->model("tb_system_grant_bonus_queue_list"); 
        $sql = "select * from system_grant_bonus_queue_list where grant_type = 0 order by priority asc limit 1";
        $query = $this->db->query($sql);
        $query_value = $query->row_array();
        if(!empty($query_value))
        {                     
            $this->tb_grant_pre_bonus_state->edit_state($query_value['item_type'],0,"");  //发奖状态初始化
            //$this->tb_system_grant_bonus_queue_list->edit($query_value['item_type']);
            $this->grant_users_bonus($query_value['pre_type'],$query_value['item_type']);
            $this->tb_system_grant_bonus_queue_list->del($query_value['item_type']);
            $this->tb_grant_pre_bonus_state->edit_state($query_value['item_type'],3,"");
            echo "ok";
        }
        echo "no";
    }
       

    /******************************* 138分红**********************************/

    /**
     * 每月初生成新的138发奖列表
     */
    public function new_138_bonus_list(){
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(2,1,0,0,"重置",1);
            $this->bonus_plan_control(2,1,time(),0,"138用户红加入队列",0);
            $this->load->model('o_cron_138_elite_bonus');
            $this->o_cron_138_elite_bonus->new_138_bonus_list();
        }        
    }

    /**
     *
     * 每日138分红 预发奖
     * @author Able
     */
    public function grant_pre_every_138_bonus(){
        ini_set('memory_limit', '2000M');
        $this->bonus_plan_control(2,1,time(),0,"138用户红加入预发队列",0);
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_cron_138_elite_bonus');
        $this->config->load('config_bonus');
        $yesterdayProfit = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润*/
        $this->o_cron_138_elite_bonus->do138Shar($yesterdayProfit['money']);/*处理发放138分红*/
        $this->bonus_plan_control(2,1,0,time(),"138用户红加入预发队列完成",0);
    }

    /**
     * 发放138分红
     */
    public function grant_every_138_bonus(){
        ini_set('memory_limit', '8192M');
        $this->bonus_plan_control(2,1,time(),0,"138分红实际发奖开始",0);
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->giveOut138Shar(); /*发放138分红*/
        $this->bonus_plan_control(2,2,0,time(),"138分红实际发奖完成",0);
    }

    /**
     * 抽回 138 分红
     */
    public function revulsion_138_grant(){
        ini_set('memory_limit', '5120M');
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->revulsion_138_grant();
    }

    public function batch_138_grant(){
        ini_set('memory_limit', '5120M');
        $this->load->model('o_cron_138_elite_bonus');
        return $this->o_cron_138_elite_bonus->batch_138_grant();
    }

	/******************************* 销售精英分红**********************************/

    /*每月初生成新的销售精英日分红发奖列表*/
    public function new_daily_bonus_elite_list(){
        $this->load->model('tb_bonus_plan_control');
        $check_status = $this->tb_bonus_plan_control->getBonusType(97);
        if($check_status==2)
        {
            $this->bonus_plan_control(24,1,0,0,"重置",1);
            $this->bonus_plan_control(24,1,time(),0,"销售精英用户入队列",0);
            $this->load->model('o_cron_138_elite_bonus');
            $this->o_cron_138_elite_bonus->new_daily_bonus_elite_list();
        }        
    }

    /*添加遗漏数据*/
    public function omit_daily_elite(){
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->omit_daily_elite();
    }


    /*修复销售精英日分红 修复4月1日发奖数据*/
    public function repair_new_daily_bonus_one(){
        $this->load->model('o_cron_138_elite_bonus');
        $create_time_one = date("Ymd", strtotime("-2 day"));
        $create_time = date("Y-m-d", strtotime("-1 day"));
        $msg = "修复4月1日发奖数据";
        $this->o_cron_138_elite_bonus->repair_new_daily_bonus($create_time_one,$create_time,$msg);
    }


    /*修复销售精英日分红 修复4月2日发奖数据*/
    public function repair_new_daily_bonus_two(){
        $this->load->model('o_cron_138_elite_bonus');
        $create_time_two = date("Ymd", strtotime("-1 day"));
        $create_time = date("Y-m-d");
        $msg = "修复4月2日发奖数据";
        $this->o_cron_138_elite_bonus->repair_new_daily_bonus($create_time_two,$create_time,$msg);
    }

    /**
     *  每日销售精英分红 预发奖
     */
    public function grant_pre_every_daily_bonus(){
        $this->bonus_plan_control(24,1,time(),0,"销售精英用户红加入预发队列",0);
        $this->load->model('o_company_money_today_total');
        $this->load->model('o_cron_138_elite_bonus');
        $this->config->load('config_bonus');
        $yesterdayProfit = $this->o_company_money_today_total->get_yesterday_profit();/*统计公司昨天全球销售利润*/
        $this->o_cron_138_elite_bonus->doDailyEliteShar($yesterdayProfit['money']); /*处理精英日分红数据*/
        $this->bonus_plan_control(24,1,0,time(),"销售精英用户红加入预发队列完成",0);
    }

    /**
     * 发放每日销售精英分红
     */
    public function grant_every_daily_bonus(){
        $this->bonus_plan_control(24,1,time(),0,"销售精英实际发奖开始",0);
        ini_set('memory_limit', '8192M');
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->giveOutEliteShar(); /*发放精英日分红*/
        $this->bonus_plan_control(24,2,0,time(),"销售精英实际发奖完成",0);
    }

    /**
     * 批量补发存在于初生成列表中的数据
     */
    public function batch_grant_bonus_elite(){
        ini_set('memory_limit', '5120M');
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->batch_grant_bonus_elite();
    }

    /**
     * 销售精英抽回
     */
    public function revulsion_bonus_elite_grant(){
        ini_set('memory_limit', '1024M');
        $this->load->model('o_cron_138_elite_bonus');
        $this->o_cron_138_elite_bonus->revulsion_bonus_elite_grant();
    }

    /******************************* 销售精英分红结束**********************************/

    /**
     * 导出订单任务启动
     * @param int $b
     */
    public function export_excel($b=0){
        set_time_limit(3000);
        $this->load->model('td_export_excel_tmp');
        $this->load->model('m_admin_helper');
        $data = $this->td_export_excel_tmp->getone();/*查询为处理导出条件*/
        if($data){
            ini_set('memory_limit', '3000M');
            //error_reporting(0);
            $fifter_arr = unserialize($data["fifter_array"]);
            $file_name =  $data["id"];
            $operator_id = $data["operator_id"];
            $admin_id = $data["admin_id"];
            $this->db->where("id",$data["id"])->update("export_order_tmp",array("status"=>1));
            /** 事务开始  **/
            $this->db->trans_begin();
            if($b){echo("trans_begin in line:".__LINE__."\n");ob_flush();}
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            $lists = $this->m_admin_helper->exportOrderReportAjax($fifter_arr,$admin_id);
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            if($b){echo("handle finished in line:".__LINE__."\n");ob_flush();}
            // fout($lists);exit;
            if ($this->db->trans_status() === FALSE){
                if($b){echo("trans_rollback in line:".__LINE__."\n");ob_flush();}
                $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
                $this->db->trans_rollback();
                $this->db->where("id",$data["id"])->update("export_order_tmp",array("status"=>4));
            } else{
                if($b){echo("trans_commit in line:".__LINE__."\n");ob_flush();}
                $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
                $this->db->trans_commit();
                if(empty($lists) || empty($lists['data'])){
                    $this->db->where("id",$data["id"])->update("export_order_tmp",array("status"=>5));//数据为空
                    exit;
                }
            }
            require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';
            require_once APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';
            require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel2007.php';
            require_once APPPATH . 'third_party/PHPExcel/PHPExcel/Writer/Excel5.php';
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            $objExcel = new PHPExcel();
            //设置属性
            $objExcel->getProperties()->setCreator("tps.system");
            $objExcel->setActiveSheetIndex(0);
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            if(in_array($operator_id,array(1,4))){
                $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID','SKU', 'Product Name', 'Country', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
                if($fifter_arr["status"]==88){
                    $fields[] = 'order_status';
                }
                if($b){echo("china_export_excel begin in line:".__LINE__."\n");ob_flush();}
                $this->china_export_excel($objExcel,$lists,$fifter_arr);
                if($b){echo("china_export_excel finished in line:".__LINE__."\n");ob_flush();}
            }
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);

            if(in_array($operator_id,array(2,100))){
                $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type',"");
                if($b){echo("usa_export_excel begin in line:".__LINE__."\n");ob_flush();}
                $this->usa_export_excel($objExcel,$lists);
                if($b){echo("usa_export_excel finished in line:".__LINE__."\n");ob_flush();}
            }
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            if(in_array($operator_id,array(3,200,201))){
                $fields = array('Create Date', 'Customer Name', 'Customer ID', 'Order ID', 'Product Name','Product Num', 'Address', 'Customs Clearance', 'Zip code', 'Phone','Spare phone', 'Tracking No', 'Deliver Time', 'Customer visible', 'System visible', 'ID no', 'ID front', 'ID reverse','order_type');
                if($b){echo("kor_export_excel begin in line:".__LINE__."\n");ob_flush();}
                $this->kor_export_excel($objExcel,$lists);
                if($b){echo("kor_export_excel finished in line:".__LINE__."\n");ob_flush();}
            }
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            //表头
            $objExcel->getActiveSheet()->setCellValue('a1', $fields[0]);
            $objExcel->getActiveSheet()->setCellValue('b1', $fields[1]);
            $objExcel->getActiveSheet()->setCellValue('c1', $fields[2]);
            $objExcel->getActiveSheet()->setCellValue('d1', $fields[3]);
            $objExcel->getActiveSheet()->setCellValue('e1', $fields[4]);
            $objExcel->getActiveSheet()->setCellValue('f1', $fields[5]);
            $objExcel->getActiveSheet()->setCellValue('g1', $fields[6]);
            $objExcel->getActiveSheet()->setCellValue('h1', $fields[7]);
            $objExcel->getActiveSheet()->setCellValue('i1', $fields[8]);
            $objExcel->getActiveSheet()->setCellValue('j1', $fields[9]);
            $objExcel->getActiveSheet()->setCellValue('k1', $fields[10]);
            $objExcel->getActiveSheet()->setCellValue('l1', $fields[11]);
            $objExcel->getActiveSheet()->setCellValue('m1', $fields[12]);
            $objExcel->getActiveSheet()->setCellValue('n1', $fields[13]);
            $objExcel->getActiveSheet()->setCellValue('o1', $fields[14]);
            $objExcel->getActiveSheet()->setCellValue('p1', $fields[15]);
            $objExcel->getActiveSheet()->setCellValue('q1', $fields[16]);
            $objExcel->getActiveSheet()->setCellValue('r1', $fields[17]);
            $objExcel->getActiveSheet()->setCellValue('s1', $fields[18]);
            if(in_array($operator_id,array(1,4)) && $fifter_arr["status"]==88){
                $objExcel->getActiveSheet()->setCellValue('t1', $fields[19]);
            }

            // 高置列的宽度
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

            if(in_array($operator_id,array(1,4))){
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(75);
                $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(75);
            }

            if(in_array($operator_id,array(2,100,3,200,201))){
                $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(75);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(75);
                $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            }
            $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
            $objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);
            $objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50);
            $objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);
            $objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
            $objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
            $objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            ob_end_clean();//清除缓冲区,避免乱码
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            if (!is_dir("img/export_excel/")) {
                mkdir("img/export_excel/", DIR_WRITE_MODE); // 使用最大权限0777创建文件
            }
            $path_file = "img/export_excel/";

            $name = $file_name.".xlsx";
            // fout($name);exit;
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            $objWriter->save($path_file.$name);
            $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
            $this->load->model('m_do_img');
            $path = str_replace("\\","/",dirname(dirname(dirname(__FILE__))))."/".$path_file.$name;
            if($b){echo("upload begin in line:".__LINE__."\n");ob_flush();}
            if($this->m_do_img->upload("upload/temp/export_excel/{$admin_id}/".date('Ymd')."/{$name}",$path)){
                $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
                $this->m_admin_helper->export_excel_debug($data["id"],$path);
                $this->m_admin_helper->export_excel_debug($data["id"],file_exists($path));
                $this->db->close();
                $this->db->initialize();
                $this->db->where("id",$data["id"])->update("export_order_tmp",array("update_path"=> config_item('img_server_url')."/upload/temp/export_excel/{$admin_id}/".date('Ymd')."/{$name}","status"=>2));
                $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
                if(ENVIRONMENT == 'production'){
                    @unlink($path_file.$name);//删除本地文件
                }
//                $this->m_admin_helper->export_excel_debug_clear($data["id"]);
            }else{//上传失败
                $this->db->where("id",$data["id"])->update("export_order_tmp",array("status"=>4));
                $this->m_admin_helper->export_excel_debug($data["id"],__LINE__);
                if(ENVIRONMENT == 'production')
                    @unlink($path_file.$name);//删除本地文件
            }
            if($b){echo("all finished in line:".__LINE__."\n");}
        }
    }

    public function format_time($v)
    {
        if($this->empty_time($v))
        {
            return "";
        }
        return $v;
    }
    public function empty_time($v)
    {
        if(!$v || $v === "0000-00-00 00:00:00")
        {
            return true;
        }
        return false;
    }

    /**
     * 中国订单
     * @param type $objExcel
     * @param type $lists
     */
    function china_export_excel($objExcel,$lists,$fifter_arr){
        $status_map = array(
                1=> '等待付款',
                4 => '等待收货',
                5 => '等待评价',
                6 => '已完成',
        );
        $i = 0 ;
        if ($lists['data']) foreach ($lists['data'] as $k => $v) {
            $u1 = $i +2;
            $phone = $v["phone"];
            $i++;
            $objExcel->getActiveSheet()->setCellValue('a' . $u1, $this->format_time($v["pay_time"]));
            $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
            $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
            $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
            $objExcel->getActiveSheet()->setCellValue('e' . $u1, $v["good_sku_goods"]);
            $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
            $objExcel->getActiveSheet()->setCellValue('f' . $u1, $v["goods_list"]);
            $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
            $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["country_address"]);
            $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["address"]);
            $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["customs_clearance"]);
            $objExcel->getActiveSheet()->setCellValue('j' . $u1, $v["zip_code"]);
            $objExcel->getActiveSheet()->setCellValue('k' . $u1, $phone);
            $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
            $objExcel->getActiveSheet()->setCellValue('m' . $u1, $this->format_time($v["deliver_time"]));
            $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
            $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
            $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
            $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]);
            if($fifter_arr["status"]==88){
                $objExcel->getActiveSheet()->setCellValue('t' . $u1, $status_map[$v["status"]]);
            }
            
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('e' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                    $u1 = $i + 1;
                    $objExcel->getActiveSheet()->setCellValue('e' . $u1, $key);
                    $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['name']);
                    $objExcel->getActiveSheet()->setCellValue('g' . $u1, $item['count']);
                    $objExcel->getActiveSheet()->setCellValue('h' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                    $i++;
            }
        }
    }
    /**
     * 美国订单
     * @param type $objExcel
     * @param type $lists
     */
    function usa_export_excel($objExcel,$lists){
        $wrap = "\n";
        $n = $i = 0;
        if ($lists['data']) foreach ($lists['data'] as $k => $v) {
            /*----------写入内容-------------*/
            $u1 = $i +2;
            if(count($v["goods_name_detail"])>1){
                $new_ul = $u1+count($v["goods_name_detail"])-1;
                $excel_arr = array("a","b","c","d","g","h","i","j","k","l","m","n","o","p","q","r");
                foreach($excel_arr as $ev){
                    $objExcel->getActiveSheet()->mergeCells($ev.$u1.':'.$ev.$new_ul);
                }
                $i = $i+count($v["goods_name_detail"]);
            }else{
                $i++;
            }

            foreach($v["goods_name_detail"] as $gk=>$vv){
                $m = $n + 2;    
                $objExcel->getActiveSheet()->setCellValue('e' . $m, $vv);
                $objExcel->getActiveSheet()->setCellValue('f' . $m, $v["goods_count"][$gk]);
                $n++;
            }
            if(strpos($v["phone"],'/')!== false){
                $phone_arr = explode('/', $v["phone"]);
                $phone = $phone_arr[0];
            }else{
                $phone = $v["phone"]; 
            }

            $objExcel->getActiveSheet()->setCellValue('a' . $u1, $this->format_time($v["pay_time"]));
            $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
            $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
            $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);
            $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
            $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
            $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
            $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
            $objExcel->getActiveSheet()->setCellValue('k' . $u1, $v["freight_info"]);
            $objExcel->getActiveSheet()->setCellValue('l' . $u1, $this->format_time($v["deliver_time"]));
            $objExcel->getActiveSheet()->setCellValue('m' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('o' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
            $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
            $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
            $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["order_type"]);   
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('d' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                    $u1 = $i + 1;
                    $objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
                    $objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
                    $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
                    $objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                    $i++;
            }
        }
    }
    /**
     * 韩国订单
     * @param type $objExcel
     * @param type $lists
     */
    function kor_export_excel($objExcel,$lists){
        $wrap ="\n";
        $i = 0 ;
        if ($lists['data']) foreach ($lists['data'] as $k => $v) {
            /*----------写入内容-------------*/
            $u1 = $i +2;
            $kor_good_list = $kor_good_count = "";
            foreach($v["goods_name_detail"] as $gk=>$vv){
                $kor_good_list .= $vv.$wrap;
                $kor_good_count .= (strpos($vv,$wrap)!== false) ? ($v["goods_count"][$gk].$wrap."".$wrap) : ($v["goods_count"][$gk].$wrap);
            }
            if(strpos($v["phone"],'/')!== false){
                $phone_arr = explode('/', $v["phone"]);
                $phone = $phone_arr[0];
                $spare_phone = $phone_arr[1];
            }else{
                $phone = $v["phone"]; 
                $spare_phone = "";
            }
            $i++;
            $pay_time = $v["pay_time"];
            if($this->empty_time($pay_time))
            {
                $pay_time = "";
            }
            $objExcel->getActiveSheet()->setCellValue('a' . $u1, $pay_time);
            $objExcel->getActiveSheet()->setCellValue('b' . $u1, $v["consignee"]);
            $objExcel->getActiveSheet()->setCellValue('c' . $u1, $v["customer_id"]);
            $objExcel->getActiveSheet()->setCellValue('d' . $u1, $v["order_id"]);

            $objExcel->getActiveSheet()->setCellValue('e' . $u1, $kor_good_list);
            $objExcel->getActiveSheet()->getStyle('e' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行
            $objExcel->getActiveSheet()->setCellValue('f' . $u1, $kor_good_count);
            $objExcel->getActiveSheet()->getStyle('f' . $u1)->getAlignment()->setWrapText(true);//产品信息自动换行

            $objExcel->getActiveSheet()->setCellValue('g' . $u1, $v["address"]);
            $objExcel->getActiveSheet()->setCellValue('h' . $u1, $v["customs_clearance"]);
            $objExcel->getActiveSheet()->setCellValue('i' . $u1, $v["zip_code"]);
            $objExcel->getActiveSheet()->setCellValue('j' . $u1, $phone);
            $objExcel->getActiveSheet()->setCellValue('k' . $u1, $spare_phone);
            $objExcel->getActiveSheet()->setCellValue('l' . $u1, $v["freight_info"]);
            $objExcel->getActiveSheet()->setCellValue('m' . $u1, $this->format_time($v["deliver_time"]));
            $objExcel->getActiveSheet()->setCellValue('n' . $u1, isset($v["cus_remark"]) ? $v["cus_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('o' . $u1, isset($v["sys_remark"]) ? $v["sys_remark"] : "");
            $objExcel->getActiveSheet()->setCellValue('p' . $u1, $v["ID_no"] ? " " . $v["ID_no"] : '');
            $objExcel->getActiveSheet()->setCellValue('q' . $u1, $v["ID_front"] ? config_item('img_server_url') . '/' . $v["ID_front"] : '');
            $objExcel->getActiveSheet()->setCellValue('r' . $u1, $v["ID_reverse"] ? config_item('img_server_url') . '/' . $v["ID_reverse"] : '');
            $objExcel->getActiveSheet()->setCellValue('s' . $u1, $v["order_type"]); 
        }
        $i = $i + 3;
        $objExcel->getActiveSheet()->setCellValue('d' . $i, 'Statistics Number:');
        if ($lists['count_goods']) {
            sortArrByField($lists['count_goods'],'add_time',false);
            foreach ($lists['count_goods'] as $key => $item) {
                    $u1 = $i + 1;
                    $objExcel->getActiveSheet()->setCellValue('d' . $u1, $key);
                    $objExcel->getActiveSheet()->setCellValue('e' . $u1, $item['name']);
                    $objExcel->getActiveSheet()->setCellValue('f' . $u1, $item['count']);
                    $objExcel->getActiveSheet()->setCellValue('g' . $u1, date('Y-m-d H:i:s', $item['add_time']));
                    $i++;
            }
        }
    }
    
    /**
     * 店铺统计
     */
    public function users_store_total()
    {
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $this->load->model('tb_users_level_statistics_total');
        $this->tb_users_level_statistics_total->getTodayUserStortTotal($yesterday); 
    }

    public function repair_daily_bonus_amount()
    {
        $this->o_cron->repair_daily_bonus_amount();
    }

    /**
     * 清楚3天前的发奖日志
     */
    public  function delGrantBonusLogs()
    {
        $d_time = date('Y-m-d',(strtotime(date('Ymd'))-3600*24*3));
        $del_sql = "DELETE FROM grant_bonus_user_logs WHERE create_time < '".$d_time."'";
        $this->db->query($del_sql);
    }

    /**
     * 统计三月份销售额异常的会员
     * CKf
     * 2017/04/21
     */
    public function count_monthly(){

        ini_set ('memory_limit', '2048M');

        $this->load->model('o_pcntl');
        $tol_num = $this->db->count_all_results('daily_bonus_qualified_list');

        $pageSize = 5000;
        $tol_pages = ceil($tol_num / $pageSize);
        for($page = 0;$page <= $tol_pages;$page ++){
            //多线程
            $this->o_pcntl->tps_pcntl_wait('$this->o_cron->count_monthly_pages(\''.$tol_pages.'\',\''.$pageSize.'\',\''.$page.'\');');//用子进程处理每一页
        }
    }

    public function dev_count_mon(){
        $this->load->model('o_pcntl');
        $this->o_cron->count_monthly(1380589027,201704);
    }

    public function cache_data_by_table($table_name="mall_goods",$column="product_id",$start=1,$end=1000000)
    {
        if(!$table_name)
        {
            exit('table_name is required!');
        }
        if(!$column)
        {
            exit('column is required!');
        }
        $this->load->model("tb_".$table_name,"my_model");
        for($i=$start;$i<$end;$i++)
        {
            echo("cache $table_name by column $column is $i\n");
            ob_flush();
            $this->my_model->get_one_auto(["where"=>[$column=>$i]]);
        }
    }

    /**
     * @author: derrick 自动创建cash_log表
     * @date: 2017年5月25日
     * @param: 
     * @reurn: return_type
     */
	public function auto_create_cash_log() {
		$create_table_sql_begin = 'CREATE TABLE IF NOT EXISTS ';
		$create_table_sql_end = ' ( 
				`id` int(11) NOT NULL AUTO_INCREMENT COMMENT "流水ID",
				`uid` int(11) UNSIGNED NOT NULL COMMENT "用户ID",
			  	`item_type` tinyint(4) NOT NULL DEFAULT "0" COMMENT "16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全",
			  	`amount` int(11) NOT NULL DEFAULT 0 COMMENT "表报金额",
			  	`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "创建时间",
			  	`order_id` char(25) NOT NULL DEFAULT "" COMMENT "订单ID",
			  	`related_uid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT "关联用户ID",
			  	`before_amount` int(11) NOT NULL DEFAULT 0 COMMENT "变动前帐户余额",
			  	`after_amount` int(11) NOT NULL DEFAULT 0 COMMENT "变动后帐户余额",
			  	PRIMARY KEY (`id`),
			  	KEY `IDX_create_time` (`create_time`),
			  	KEY `IDX_uid` (`uid`),
			  	KEY `IDX_item_type` (`item_type`),
			  	KEY `IDX_order_id` (`order_id`),
			  	KEY `IDX_related_uid` (`related_uid`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT "资金变动报表";';
		$max_user = $this->db->query('SELECT `id` FROM users ORDER BY ID DESC LIMIT 1')->row_array();
		$max = substr($max_user['id'], 0, 4);
		$date = date('Ym');
		for ($i = $date; $i <= $date+1; $i++) {
			$begin = 1380;
			while ($begin <= $max+1) {
				$this->db->query($create_table_sql_begin.'`cash_account_log_'.$i.'_'.$begin.'`'.$create_table_sql_end);
				$begin++;
			}
		}

		exit;
	}

    /**
     * @author brady.wang
     * @desc 每月底生成下月的积分变动表
     */
    public function create_credit_log_table()
    {
        $nowMonth = date("Ym",time());
        $nextMonth = date("Ym",strtotime("+1 month",time()));
        $month = [$nowMonth,$nextMonth];
        foreach($month as $v) {
            $sql = "CREATE TABLE IF NOT EXISTS `users_credit_log_".$v."` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
              `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前的积分',
              `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后的积分',
              `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
              `child_uid` varchar(25) NOT NULL DEFAULT '' COMMENT '影响该次变动的用户',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分变动日志表'";
            $this->db->query($sql);
        }
        echo "创建积分日志表成功";
    }

	/**
	 * @author: derrick 会员之间转账校验
	 * @date: 2017年6月1日
	 * @param: 
	 * @reurn: return_type
	 */
	public function user_transfer_check() {
		$this->load->model('tb_cash_account_log_x');
	}
	
	
	/**
	 * 生成下单会员队列
	 */
	public function create_order_user_qualified_list()
	{
	    $this->load->model('tb_users_store_sale_info_monthly');
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly(0);
	    
	}
	
	/**
	 * 生成下单会员队列修复昨天的下单会员业绩
	 */
	public function modify_user_monthly_yesterday()
	{
	    $this->load->model('tb_users_store_sale_info_monthly');
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly_yesterday();
	     
	}
		
	/**
	 * 修复下单会员业绩
	 */
	public function modify_order_user_monthly()
	{
	    $this->load->model('tb_users_store_sale_info_monthly');
	    $this->bonus_plan_control(97,1,time(),0,"每天下单会员监控开始",0);
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly_option(1,1);
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly_option(2,1);
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly_option(3,1);
	    $this->tb_users_store_sale_info_monthly->modify_user_monthly_option(4,1);
	    $this->bonus_plan_control(97,2,0,time(),"每天下单会员监控完成",0);
	}
		
	/**
	 * 修复会员业绩后，重新生成新的全球日分红队列
	 */
	public function user_daily_bonus_qualified_list()
	{
	    $this->o_cron->user_daily_bonus_qualified_list();
	}
	
	/**
	 * 修复会员业绩后，重新生成新的138发奖列表
	 */
	public function user_138_bonus_qualified_list(){
	    $this->load->model('o_cron_138_elite_bonus');
	    $this->o_cron_138_elite_bonus->user_138_bonus_qualified_list();
	}
	
	/**
	 * 修复会员业绩后，重新生成新的周团队分红发奖列表  队列表
	 */
	public function user_week_bonus_qualified_list()
	{
	    $this->load->model("o_month_leader_bonus_option");
	    $this->o_month_leader_bonus_option->user_week_bonus_qualified_list();
	}
	
	/**
	 * 修复会员业绩后，重新生成新的每月团队分红发奖列表  队列表
	 */
	public function user_bonus_month_group_share_list()
	{
	    $this->load->model("o_month_leader_bonus_option");
	    $this->o_month_leader_bonus_option->user_every_month_team_group_sharelist();
	}
	
	/**
	 * 修复会员业绩后，重新生成新的每月领导分红发奖列表  队列表
	 */
	public function user_new_month_leader_bonus_list()
	{
	    $this->load->model("o_month_leader_bonus_option");
	    $this->o_month_leader_bonus_option->user_new_month_leader_bonus_list();
	}
	
	/**
	 * 获取未添加发奖队列的会员
	 */
	public function get_new_qualifid_list($type)
	{
	    $this->load->model('tb_users_store_sale_info_monthly');
	    $this->tb_users_store_sale_info_monthly->get_daily_bonus_qualified_list($type);	   
	}

    /**
     * @author brady
     * @desc 每天删除冻结失效的人、 每天凌晨执行
     */
    public function unfrost_user()
    {
        $this->o_cron->unfrost_user();
    }

    /**
     * 修改分红计划状态
     * @param $item_type
     * @param $status
     * @param $exec_time
     * @param $exec_end_time
     * @param $description
     * @param $reset
     */
	public function bonus_plan_control($item_type,$status,$exec_time,$exec_end_time,$description,$reset){
        $this->load->model('tb_bonus_plan_control');
        $param = [
            "status"=>$status,
            "description"=>$description
        ];
        if($reset){
            $param["exec_time"] = 0;
            $param["exec_end_time"] = 0;
        }else{
            if($exec_time>0){
                $param["exec_time"] = $exec_time;
            }
            if($exec_end_time>0){
                $param["exec_end_time"] = $exec_end_time;
            }
        }
        $this->tb_bonus_plan_control->changeExecStatus($item_type,$param);
    }

    /**
     * 检查用户转账异常记录
     * @param int $type 0 统计前一天的记录 1 统计指定日期区间的异常记录
     * @param string $start 开始时间 type为0时不传
     * @param string $end 结束时间 type为0时不传
     */
    public function checkUserTransferLog($type=0, $start='', $end=''){
        ini_set('memory_limit', '512M');
        $this->load->model('tb_user_transfer_account_waring');
        $this->load->model('tb_users_store_sale_info_monthly');
        $this->bonus_plan_control(96,1,time(),0,"每天会员转账监控开始",0);
        if ($type==1) {
            $start = $start=='' ? '2017-06-01' : date('Y-m-01', strtotime($start));
            $end = $end=='' ? date('Y-m-d', strtotime('-1 day')) : date('Y-m-d', strtotime($end));
            while($start <= $end) {
                $month_end = date('Y-m-d', strtotime('+1 month',strtotime($start))-1);
                $month_end = $month_end > $end ? $end : $month_end;
                $this->tb_user_transfer_account_waring->check_user_transfer_account($start, $month_end);
                echo $start.' - '.$month_end."\n";
                $start = date('Y-m-d', strtotime('+1 month',strtotime($start)));
            }
        } else {
            $this->tb_user_transfer_account_waring->check_user_transfer_account();
        }
        $this->bonus_plan_control(96,2,0,time(),"每天会员转账监控完成",0);
    }

    public function ticowong()
    {
        echo("line:".__LINE__."\n");
        $this->load->model("tb_trade_orders");
        $tmp = $this->tb_trade_orders->get_list_auto([
            "select"=>"*",
            "where"=>["order_id"=>"C201706201500018725"],
            "page_size"=>10,
        ]);
        var_dump($tmp);
        echo("line:".__LINE__."\n");
    }

    /**
     * ticowong
     * 2017-06-29
     * 检测指定订单支付类型
     */
    public function check_pay_again()
    {
        echo("start\n");ob_flush();
        $this->load->model("o_pay");
        $pay_methods = ["m_alipay","m_wxpay","m_unionpay","m_usd_unionpay"];
        $orders = ['C201706201501149641','C201706201501145464','C201706201501420785','C201706201501424557','C201706201501424424','N201706201803082024','N201706201906064659','N201706221623552592','N201706222211481354','N201706222212297513','N201706222212471364','N201706222212484382','N201706222212505112','N201706222213092148','N201706241609218985','N201706241616445070','N201706241617061343','N201706251557413868','N201706251804163158','N201706252029568066','N201706252147572471','N201706252156157547','N201706252324167871','N201706260028532701','N201706260735329298','N201706260937472897','N201706261111142421','N201706261134444230','N201706261147571024','N201706261234403716','N201706261247050282','C201706261255417663','N201706261320550285','N201706261321337302','N201706261334275060','N201706261342563525','N201706261353233647','C201706261355341395','N201706261356570231','N201706261359148332','N201706261401328962','N201706261403080513','N201706261409055993','N201706261409206961','C201706261410520623','N201706261411083758','N201706261413326589','N201706261414508527','N201706261415519367','N201706261417292977','N201706261418118110','N201706261418425741','N201706261418423051','N201706261419175727','N201706261419320822','N201706261419495041','N201706261420042117','N201706261420202232','N201706261420223732','N201706261420440440','N201706261420492417','N201706261420549393','N201706261421136689','N201706261421142649','N201706261421155398','N201706261421172048','N201706261422020558','N201706261422030677','N201706261422049497','N201706261422064916','N201706261422077563','N201706261422089771','N201706261422215447','N201706261422244027','N201706261422330895','N201706261422388540','N201706261422404577','N201706261423006479','N201706261423074797','N201706261423131874','N201706261423178153','N201706261423240243','N201706261423353623','N201706261423475746','N201706261423539456','N201706261424039917','N201706261424053342','N201706261424139702','N201706261424181624','N201706261424297001','N201706261424414706','N201706261424465926','N201706261424560443','N201706261425252949','N201706261426578974','N201706261429268665','N201706261429530065','N201706261430198799','N201706261431121229','N201706261434531836','N201706261435497927','N201706261436122136','N201706261437356987','N201706261437417575','N201706261438169026','N201706261438424253','N201706261438499939','N201706261438591642','N201706261439212278','N201706261439459133','N201706261439579712','N201706261440239729','N201706261440341072','N201706261440340648','N201706261440357043','N201706261440454029','N201706261440547991','N201706261441021684','N201706261441051062','N201706261441127475','N201706261441136413','N201706261441190218','N201706261441352690','N201706261441362386','N201706261441454198','N201706261442119525','N201706261442187824','N201706261442367658','N201706261442398983','N201706261442536711','N201706261442533810','N201706261442588557','N201706261443077111','N201706261443087554','N201706261443115326','N201706261443147006','N201706261443156420','N201706261443206714','N201706261443231112','N201706261443254620','N201706261443415900','N201706261443461378','N201706261443514080','N201706261443568520','N201706261443592185','N201706261444054994','N201706261444063857','N201706261444071878','N201706261444110120','N201706261444189648','N201706261444181859','N201706261444208601','N201706261444253790','N201706261444260399','N201706261444331602','N201706261444481020','N201706261444551918','N201706261444558722','N201706261444595053','N201706261445003652','N201706261445240529','N201706261445269526','N201706261445271233','N201706261445327354','N201706261445368909','N201706261445385434','N201706261445483570','N201706261445557701','N201706261445592175','N201706261446045501','N201706261446154098','N201706261446321648','N201706261446438209','N201706261446519131','N201706261446573231','N201706261447077259','N201706261447123830','N201706261447386615','N201706261447527902','N201706261447555951','N201706261448578725','N201706261449260395','N201706261452076894','N201706261455037254','N201706261455520722','N201706261456280720','N201706261458020670','N201706261458063217','N201706261458144425','N201706261458162599','N201706261458260108','N201706261458363961','N201706261458461182','N201706261458599303','N201706261459053447','N201706261459101033','N201706261459127582','N201706261459120596','N201706261459138525','N201706261459212698','N201706261459491951','N201706261459524996','N201706261459538439','N201706261459542730','N201706261459570187','N201706261500017980','N201706261500069536','N201706261500080748','N201706261500502788','N201706261501053977','N201706261501084568','N201706261501319989','N201706261501312907','N201706261501324657','N201706261501414724','N201706261501459750','N201706261501581591','N201706261502039188','N201706261502213185','N201706261502222335','N201706261502280006','N201706261502381252','N201706261502388635','N201706261502520135','N201706261503044071','N201706261503096806','N201706261503156843','N201706261503170380','N201706261503230119','N201706261503264499','N201706261503291805','N201706261503316078','N201706261503357578','N201706261503563285','N201706261503573045','N201706261504153701','N201706261504212105','N201706261504374227','N201706261504466069','N201706261504517510','N201706261505255777','N201706261505270629','N201706261505470775','N201706261506112521','N201706261506210005','N201706261506241979','N201706261506254554','N201706261506448183','N201706261506462013','N201706261506574781','N201706261507013803','N201706261507036133','N201706261507216589','N201706261507250695','N201706261507273833','N201706261507348890','N201706261507355118','N201706261507425919','N201706261507475766','N201706261507478445','N201706261507542547','N201706261507563505','N201706261508034306','N201706261508199973','N201706261508300901','N201706261508364392','N201706261508374763','N201706261508491467','N201706261508505144','N201706261509013713','N201706261509104413','N201706261509121773','N201706261512175906','N201706261514001951','N201706261518527056','N201706261520532544','N201706261521137663','N201706261522088868','N201706261522481333','N201706261523346442','N201706261525222064','N201706261527562610','N201706261528148902','N201706261529433952','N201706261529495821','N201706261530156930','N201706261530225998','N201706261530383575','N201706261533445934','N201706261534205629','N201706261534509922','N201706261535141036','N201706261535508429','N201706261536056727','N201706261536392349','N201706261536496616','N201706261537574630','N201706261538446085','N201706261539273395','N201706261539322877','N201706261540204006','N201706261540265561','N201706261540383335','N201706261540439991','N201706261540489311','N201706261540583354','N201706261541019510','N201706261541106112','N201706261541224594','N201706261541269551','N201706261541325543','N201706261541367627','N201706261541383287','N201706261541401628','N201706261541481736','N201706261541518161','N201706261541583317','N201706261542157087','N201706261542188426','N201706261542293926','N201706261542291470','N201706261542317242','N201706261542362322','N201706261542395309','N201706261542399753','N201706261542425304','N201706261542501485','N201706261542506466','N201706261542526211','N201706261542583650','N201706261543010855','N201706261543214021','N201706261543262297','N201706261543501725','N201706261543572816','N201706261543588851','N201706261544028617','N201706261544081430','N201706261544116452','N201706261544127774','N201706261544130169','N201706261544144266','N201706261544174806','N201706261544234022','N201706261544238699','N201706261544288950','N201706261544327105','N201706261544350742','N201706261544401832','N201706261544416043','N201706261544432304','N201706261544514399','N201706261545062326','N201706261545096265','N201706261545148796','N201706261545144365','N201706261545159826','N201706261545153937','N201706261545171094','N201706261545296590','N201706261545370378','N201706261545447204','N201706261545466978','N201706261545505750','N201706261545561566','N201706261545570102','N201706261546050368','N201706261546138313','N201706261546193815','N201706261546201818','N201706261546246129','N201706261546299289','N201706261546317495','N201706261546372627','N201706261546419763','N201706261546495206','N201706261546496591','N201706261546507386','N201706261547095404','N201706261547250008','N201706261547269610','N201706261547358856','N201706261547389512','N201706261547434450','N201706261547477710','N201706261547483630','N201706261547563154','N201706261548012153','N201706261548050648','N201706261548060511','N201706261548144169','N201706261548152609','N201706261548204892','N201706261548224176','N201706261548268144','N201706261548300091','N201706261548304936','N201706261548395512','N201706261548390248','N201706261548424770','N201706261548436822','N201706261548538466','N201706261548568060','N201706261549008113','N201706261549051145','N201706261549092533','N201706261549172933','N201706261549276497','N201706261549343212','N201706261549390363','N201706261549434929','N201706261549486966','N201706261549591630','N201706261550009162','N201706261550044709','N201706261550194502','N201706261550234629','N201706261550313912','N201706261550397129','N201706261550495405','N201706261551080645','N201706261551122863','N201706261551173232','N201706261551216932','N201706261551216411','C201706261551382527','N201706261551491836','N201706261551543421','N201706261551553376','N201706261552126337','N201706261552133531','N201706261552148245','N201706261552163525','N201706261552184126','C201706261552254580','N201706261552305450','N201706261552407757','N201706261552415879','N201706261552431766','N201706261552445277','N201706261552547150','N201706261553151026','N201706261553156794','N201706261553193188','N201706261553199059','N201706261553200176','N201706261553348270','N201706261553438983','N201706261553468866','N201706261553487026','N201706261554014831','N201706261554057426','N201706261554075160','N201706261554096284','N201706261554103166','N201706261554137517','N201706261554171111','N201706261554184826','N201706261554239470','N201706261554298287','N201706261554314221','N201706261554411896','N201706261554434057','N201706261554489008','N201706261555024269','N201706261555130051','N201706261555145490','N201706261555193770','N201706261555216269','N201706261555280228','N201706261555385564','N201706261555436776','N201706261555545685','N201706261556032913','N201706261556046191','N201706261556102425','N201706261556133640','N201706261556179029','N201706261556265541','N201706261556280051','N201706261556302185','N201706261556336742','N201706261556409860','N201706261556455115','N201706261556465070','N201706261556491685','N201706261556504402','N201706261556529327','N201706261556527542','N201706261556532006','N201706261556569931','N201706261556595149','N201706261557021867','N201706261557020470','N201706261557112420','N201706261557157382','N201706261557316790','N201706261557339766','N201706261557367157','N201706261557409932','N201706261557443707','N201706261557511445','N201706261557544542','N201706261557592078','N201706261558109291','N201706261558138636','N201706261558225483','N201706261558272178','N201706261558365603','N201706261558514271','N201706261559050619','C201706261559162775','C201706261559169939','N201706261559192203','N201706261559316579','N201706261559366952','N201706261559371511','N201706261559501268','N201706261559539447','N201706261559547771','N201706261559587642','N201706261600057488','N201706261600066832','N201706261600091164','N201706261600139199','N201706261600200630','N201706261600264880','N201706261600275939','N201706261600281654','N201706261600289829','N201706261600306227','N201706261600370884','N201706261600403379','N201706261600442396','N201706261600455555','N201706261600544230','N201706261600584892','N201706261600580824','N201706261601013629','N201706261601020588','N201706261601087352','N201706261601122477','N201706261601154186','N201706261601208377','N201706261601224387','N201706261601535939','N201706261601567539','N201706261601584696','N201706261602052991','N201706261602066296','N201706261602082542','N201706261602206392','N201706261602208136','N201706261602344644','N201706261602347024','N201706261602464363','N201706261602514261','N201706261602559146','N201706261602587622','N201706261602597957','N201706261603045856','N201706261603044447','N201706261603067128','N201706261603097086','N201706261603118495','N201706261603128560','N201706261603151837','N201706261603269107','N201706261603374960','N201706261603383144','N201706261603444950','N201706261603473237','N201706261603488890','N201706261603549576','N201706261603587333','N201706261604095690','N201706261604108305','N201706261604114799','N201706261604127313','N201706261604123048','N201706261604131415','N201706261604159455','N201706261604375850','N201706261604429997','N201706261604453651','N201706261604478093','N201706261604487588','C201706261604526728','C201706261604520472','C201706261604529586','C201706261604528950','N201706261604531378','N201706261604580816','N201706261605034112','N201706261605078603','N201706261605081683','N201706261605093048','N201706261605103879','N201706261605119479','N201706261605216616','N201706261605347628','N201706261605376742','N201706261605465646','N201706261605484627','N201706261605527752','N201706261605564511','N201706261605587111','N201706261606004521','N201706261606016700','N201706261606029651','N201706261606056053','N201706261606089897','N201706261606090147','N201706261606158084','N201706261606189709','N201706261606188030','N201706261606242396','N201706261606259373','N201706261606333165','N201706261606331110','N201706261606382172','N201706261606403585','N201706261606404438','N201706261606443325','N201706261606457956','N201706261606471744','N201706261606574560','N201706261606592217','N201706261607037981','N201706261607098922','N201706261607154254','N201706261607161225','N201706261607168725','N201706261607161429','N201706261607262376','N201706261607279568','N201706261607303715','N201706261607306682','N201706261607331650','N201706261607372363','N201706261607394429','N201706261607476615','N201706261607485805','N201706261607503762','N201706261607516002','N201706261607515927','N201706261607514226','N201706261607559002','N201706261607565415','N201706261608014737','N201706261608026841','N201706261608034213','N201706261608044070','N201706261608081679','N201706261608182489','N201706261608211280','N201706261608238995','N201706261608556737','N201706261608561071','N201706261608571142','N201706261608594422','N201706261609005101','N201706261609009963','N201706261609015522','N201706261609132140','N201706261609145414','N201706261609168634','N201706261609165762','N201706261609219132','N201706261609223578','N201706261609264535','N201706261609271376','N201706261609301553','N201706261609338960','N201706261609359482','N201706261609360651','N201706261609382940','N201706261609437170','N201706261609435007','N201706261609481746','N201706261609530800','N201706261609584725','N201706261610008744','N201706261610010679','N201706261610037948','N201706261610094610','N201706261610159030','N201706261610202623','N201706261610235462','N201706261610231948','N201706261610260704','N201706261610288402','N201706261610297466','N201706261610318971','N201706261610321384','N201706261610344858','N201706261610362846','N201706261610386173','N201706261610397628','N201706261610397293','N201706261610413414','N201706261610446311','N201706261610558084','N201706261610560081','N201706261610590687','N201706261611062097','N201706261611084102','N201706261611109242','N201706261611116696','N201706261611113751','N201706261611205868','N201706261611217350','N201706261611245682','N201706261611255812','N201706261611267059','N201706261611304173','N201706261611311539','N201706261611383480','N201706261611408502','N201706261611404166','N201706261611458919','N201706261611507820','N201706261611519248','N201706261611569090','N201706261611570357','N201706261611598467','N201706261612023509','N201706261612113597','N201706261612238113','N201706261612271476','N201706261612295119','N201706261612325217','N201706261612323879','N201706261612335744','N201706261612358542','N201706261612366581','N201706261612457371','N201706261612516516','N201706261612544459','N201706261612584153','N201706261612599897','N201706261613053972','N201706261613059991','N201706261613065137','N201706261613083707','N201706261613185668','N201706261613191620','N201706261613265155','N201706261613262702','N201706261613480711','N201706261613502496','N201706261613524914','N201706261613536471','N201706261613561750','N201706261613583934','N201706261614083222','N201706261614096308','N201706261614112389','N201706261614128347','N201706261614157451','N201706261614163620','N201706261614194018','N201706261614192257','N201706261614227116','N201706261614296345','N201706261614292682','N201706261614362671','N201706261614388071','N201706261614485713','N201706261614526314','N201706261614542737','N201706261614586062','N201706261614593764','N201706261615019849','N201706261615054213','N201706261615086156','N201706261615082044','N201706261615087575','N201706261615159967','N201706261615177907','N201706261615175511','N201706261615186722','N201706261615214176','N201706261615252590','N201706261615345285','N201706261615420840','N201706261615449605','N201706261615458441','N201706261615469231','N201706261615517389','N201706261616025396','N201706261616074536','N201706261616105646','N201706261616118250','N201706261616128941','N201706261616189116','N201706261616202265','N201706261616273567','N201706261616280052','N201706261616280730','N201706261616285591','N201706261616299740','N201706261616341079','N201706261616353890','N201706261616437008','N201706261616437533','N201706261616471828','N201706261616494485','N201706261616515117','N201706261616514495','N201706261616522682','N201706261617110951','N201706261617159294','N201706261617282933','N201706261617290175','N201706261617433279','N201706261617531140','N201706261617599709','N201706261618018474','N201706261618040870','N201706261618040261','N201706261618082126','N201706261618147004','N201706261618154930','N201706261618303483','N201706261618332813','N201706261618456366','N201706261618479320','N201706261618481681','N201706261618532028','N201706261618595176','N201706261619060967','N201706261619093750','N201706261619105581','N201706261619129708','N201706261619169158','N201706261619161973','N201706261619167551','N201706261619178973','N201706261619174925','N201706261619201793','N201706261619347836','N201706261619384392','N201706261619390523','N201706261619414107','N201706261619525055','N201706261619551810','N201706261619599601','N201706261619598422','N201706261620018504','N201706261620048562','N201706261620048257','N201706261620151635','N201706261620162420','N201706261620170403','N201706261620183687','N201706261620186099','N201706261620307042','N201706261620390245','N201706261620402938','N201706261620440505','N201706261620587225','N201706261620582567','N201706261621015973','N201706261621019218','N201706261621051513','N201706261621061760','N201706261621093779','N201706261621120844','N201706261621139951','N201706261621149729','N201706261621206489','N201706261621380095','N201706261621531540','N201706261621532002','N201706261621596839','N201706261621598099','N201706261622018340','N201706261622035015','N201706261622084280','N201706261622209944','N201706261622214883','N201706261622229015','N201706261622285988','N201706261622364660','N201706261622392223','N201706261622413493','N201706261622429341','N201706261622441740','N201706261622519325','N201706261622596806','N201706261622593660','N201706261623029611','N201706261623048151','N201706261623071979','N201706261623196784','N201706261623204445','N201706261623236674','N201706261623242949','N201706261623266986','N201706261623309011','N201706261623310763','N201706261623321081','N201706261623348593','N201706261623376286','N201706261623387164','N201706261623395032','N201706261623396164','N201706261623537974','N201706261624081469','N201706261624095145','N201706261624115807','N201706261624151665','N201706261624201458','N201706261624210794','N201706261624239686','N201706261624231942','N201706261624356349','N201706261624454478','N201706261624472429','N201706261624484285','N201706261624568640','N201706261625001134','N201706261625034451','N201706261625049730','N201706261625079362','N201706261625129783','N201706261625166018','N201706261625384997','N201706261625429269','N201706261625480085','N201706261625524159','N201706261625540000','N201706261625557112','N201706261625599737','N201706261626013085','N201706261626136756','N201706261626135906','N201706261626211282','N201706261626210784','N201706261626253224','N201706261626296930','N201706261626308184','N201706261626302471','N201706261626393269','N201706261626414076','N201706261626420942','N201706261626420601','N201706261626443485','N201706261626505002','N201706261626511686','N201706261626532833','N201706261627050342','N201706261627074557','N201706261627127476','N201706261627139403','N201706261627144011','N201706261627154850','N201706261627339871','N201706261627482561','N201706261627564430','N201706261627587172','N201706261627598133','N201706261628028207','N201706261628171880','N201706261628255141','N201706261628331849','N201706261628357581','N201706261628402642','N201706261628460780','N201706261628509827','N201706261628522215','N201706261629029821','N201706261629075942','N201706261629092720','N201706261629106958','N201706261629249783','N201706261629252276','N201706261629275162','N201706261629292438','N201706261629332873','N201706261629429736','N201706261629467778','N201706261629529678','N201706261629580376','N201706261630000556','N201706261630122961','N201706261630151594','N201706261630188224','N201706261630228141','N201706261630260302','N201706261630319505','N201706261630314263','N201706261630336609','N201706261630450815','N201706261630515643','N201706261630570952','N201706261630579643','N201706261631018234','N201706261631025187','N201706261631037845','N201706261631194102','N201706261631214335','N201706261631296705','N201706261631300825','N201706261631391722','N201706261631445129','N201706261631479856','N201706261631516930','N201706261631563711','N201706261631580952','N201706261631595712','N201706261631597840','N201706261632068857','N201706261632277757','N201706261632418416','N201706261632412187','N201706261632419172','N201706261632425132','N201706261632461874','N201706261632489714','N201706261632536699','N201706261633094050','N201706261633116551','N201706261633244656','N201706261633370486','N201706261633568365','N201706261633566616','N201706261634015816','N201706261634062850','N201706261634318520','N201706261634440711','N201706261634464042','N201706261634512752','N201706261634545301','N201706261634584908','N201706261635280095','N201706261635306985','N201706261635331486','N201706261635389753','N201706261635441907','N201706261635444182','N201706261635474962','N201706261635545549','N201706261635569594','N201706261635570208','N201706261635596571','N201706261636011125','N201706261636114692','N201706261636110088','N201706261636148814','N201706261636232208','N201706261636444230','N201706261636484291','N201706261636499720','N201706261636503696','N201706261636572210','N201706261637156254','N201706261637225836','N201706261637225374','N201706261637234690','N201706261637263983','N201706261637362440','N201706261637366588','N201706261637408780','N201706261637466164','N201706261637545887','N201706261638004580','N201706261638106315','N201706261638163844','N201706261638193048','N201706261638197023','N201706261638233026','N201706261638261315','N201706261638286227','N201706261638379529','N201706261638417768','N201706261638428198','N201706261638463891','N201706261638492143','N201706261638527762','N201706261638579582','N201706261639057671','N201706261639091075','N201706261639108980','N201706261639219306','N201706261639226882','N201706261639359358','N201706261639360884','N201706261639396679','N201706261639406738','N201706261639462033','N201706261639496612','N201706261639490141','N201706261639541427','N201706261639588259','N201706261640211374','N201706261640232964','N201706261640277594','N201706261640352631','N201706261640381273','N201706261640412287','N201706261640506242','N201706261641012548','N201706261641051445','N201706261641052389','N201706261641197663','N201706261641264686','N201706261641268098','N201706261641386210','N201706261641392343','N201706261641409440','N201706261641408485','N201706261641427656','N201706261641487587','N201706261641504157','N201706261642167692','N201706261642223242','N201706261642296434','N201706261642313835','N201706261642352655','N201706261642386321','N201706261642421625','N201706261642477610','N201706261642498634','N201706261642555409','N201706261642572865','N201706261642576012','N201706261643011870','N201706261643118964','N201706261643126398','N201706261643220002','N201706261643318487','N201706261643327712','N201706261643355357','N201706261643376713','N201706261643391004','N201706261643451221','N201706261643514754','N201706261643584699','N201706261644085320','N201706261644090446','N201706261644120923','N201706261644146963','N201706261644268673','N201706261644284489','N201706261644331501','N201706261644418679','N201706261644421930','N201706261644449242','N201706261644460500','N201706261645046497','N201706261645066968','N201706261645073518','N201706261645123010','N201706261645160643','N201706261645293033','N201706261645318568','N201706261645386246','N201706261645431447','N201706261645437925','N201706261645472890','N201706261645566094','N201706261645571275','N201706261646039065','N201706261646036879','N201706261646045354','N201706261646041602','N201706261646149653','N201706261646234548','N201706261646269471','N201706261646353233','N201706261646360875','N201706261646379045','N201706261646398423','N201706261646404232','N201706261646431771','N201706261646445037','N201706261646466666','N201706261646477308','N201706261646553088','N201706261647007552','N201706261647054219','N201706261647076938','N201706261647190524','N201706261647213430','N201706261647278348','N201706261647298657','N201706261647329232','N201706261647372699','N201706261647389413','N201706261647417273','N201706261647421056','N201706261647420822','N201706261647432603','N201706261647496613','N201706261648120551','N201706261648131239','N201706261648135468','N201706261648193080','N201706261648338350','N201706261648330977','N201706261648345765','N201706261648353446','N201706261648393357','N201706261648493482','N201706261648528195','N201706261648598624','N201706261649267730','N201706261649306732','N201706261649360854','N201706261649410328','N201706261649449655','N201706261649546712','N201706261650078484','N201706261650075747','N201706261650074534','N201706261650088634','N201706261650109580','N201706261650106465','N201706261650130351','N201706261650149133','N201706261650195010','N201706261650241380','N201706261650307196','N201706261650330806','N201706261650407894','N201706261650417215','N201706261650504401','N201706261650505293','N201706261650510754','N201706261651010192','N201706261651133879','N201706261651173243','N201706261651202056','N201706261651209027','N201706261651273806','N201706261651384688','N201706261651487013','N201706261651496183','N201706261651514142','N201706261652010996','N201706261652258466','N201706261652303115','N201706261652323681','N201706261652384853','N201706261652400459','N201706261652550894','N201706261653115274','N201706261653113674','N201706261653202736','N201706261653331137','N201706261653346974','N201706261653396110','N201706261653393850','N201706261653422848','N201706261653447575','N201706261653482702','N201706261653488530','N201706261653563241','N201706261654060063','N201706261654327079','N201706261654581621','N201706261655037999','N201706261655025299','N201706261655352836','N201706261655415354','N201706261655530074','N201706261655570331','N201706261656063429','N201706261656113186','N201706261656127878','N201706261656141494','N201706261656184996','N201706261656223399','N201706261656348610','N201706261656369585','N201706261656449464','N201706261656557822','N201706261657124703','N201706261657206014','N201706261657200583','N201706261657403142','N201706261657488461','N201706261658092145','N201706261658197887','N201706261658322033','N201706261658341941','N201706261658580528','N201706261659046704','N201706261659133361','N201706261659144755','N201706261659289253','N201706261659350645','N201706261659522503','N201706261700028025','N201706261700071651','N201706261700102607','N201706261700118247','N201706261700129549','N201706261700248721','N201706261700299498','N201706261700312462','N201706261700388328','N201706261700396867','N201706261700520044','N201706261701021432','N201706261701171644','N201706261701472819','N201706261702007590','N201706261702031217','N201706261702226670','N201706261702271118','N201706261702394755','N201706261702456771','N201706261702536212','N201706261703056854','N201706261703055039','N201706261703099602','N201706261703101576','N201706261703148330','N201706261703197636','N201706261703229236','N201706261703231329','N201706261703238290','N201706261703240562','N201706261703440207','N201706261703502986','N201706261703512354','N201706261703559663','N201706261704040939','N201706261704075393','N201706261704117954','N201706261704218472','N201706261704236558','N201706261704279047','N201706261704314283','N201706261704356881','N201706261704481613','N201706261704514015','N201706261704566106','N201706261704571335','N201706261705045713','N201706261705064865','N201706261705069150','N201706261705089610','N201706261705117380','N201706261705128281','N201706261705161067','N201706261705376169','N201706261705448232','N201706261705463832','N201706261705481172','N201706261705506426','N201706261705504663','N201706261705551224','N201706261706042495','N201706261706349065','N201706261706405918','N201706261706408909','N201706261706412557','N201706261706552580','N201706261707038541','N201706261707089428','N201706261707121322','N201706261707122406','N201706261707171354','N201706261707180038','N201706261707323419','N201706261707402410','N201706261707519888','N201706261707515670','N201706261707536644','N201706261708134265','N201706261708204079','N201706261708222090','N201706261708244787','N201706261708257977','N201706261708362108','N201706261708374221','N201706261708442004','N201706261708480169','N201706261709085681','N201706261709152961','N201706261709162635','N201706261709238837','N201706261709303893','N201706261709305820','N201706261709332121','N201706261709390731','N201706261709477696','N201706261709519485','N201706261709547755','N201706261709555846','N201706261709565503','N201706261710014076','N201706261710105925','N201706261710114306','N201706261710297207','N201706261710331572','N201706261710330387','N201706261710343852','N201706261710345223','N201706261710569056','N201706261710577713','N201706261711086314','N201706261711106899','N201706261711111929','N201706261711157136','N201706261711264558','N201706261711289797','N201706261711351957','N201706261711541426','N201706261712069815','N201706261712168128','N201706261712203202','N201706261712222292','N201706261712269296','N201706261712319683','N201706261712388210','N201706261712413058','N201706261712439122','N201706261712450863','N201706261712521857','N201706261713035532','N201706261713071321','N201706261713156926','N201706261713366513','N201706261713508773','N201706261714088155','N201706261714098423','N201706261714181813','N201706261714243547','N201706261714417181','N201706261714502291','N201706261715297797','N201706261715306039','N201706261715309201','N201706261715355446','N201706261715484963','N201706261715491022','N201706261715555308','N201706261716003548','N201706261716262102','N201706261716378143','N201706261716412057','N201706261716443024','N201706261717147607','N201706261717365319','N201706261717430686','N201706261717559955','N201706261718105349','N201706261718158513','N201706261718163074','N201706261718187763','N201706261718235468','N201706261718283266','N201706261718375741','N201706261718418776','N201706261718430070','N201706261719028855','N201706261719032165','N201706261719063157','N201706261719191334','N201706261719281798','N201706261719296982','N201706261719408946','N201706261719569682','N201706261719577587','N201706261719599715','N201706261720052691','N201706261720068522','N201706261720143426','N201706261720147815','N201706261720204357','N201706261720246781','N201706261720270570','N201706261720302070','N201706261720310356','N201706261720345164','N201706261720511808','N201706261720555100','N201706261721013016','N201706261721113682','N201706261721161559','N201706261721170807','N201706261721173092','N201706261721340101','N201706261721363232','N201706261721415110','N201706261721512280','N201706261721597276','N201706261721594669','N201706261722312623','N201706261722323569','N201706261722519674','N201706261722585451','N201706261723023541','N201706261723036673','N201706261723031311','N201706261723050738','N201706261723118250','N201706261723178828','N201706261723279006','N201706261723328619','N201706261723406646','N201706261725190548','N201706261726560982','N201706261726585998','N201706261727423932','N201706261727552810','N201706261728040706','N201706261728172064','N201706261728182869','N201706261728312462','N201706261728397435','N201706261728408694','N201706261728423772','N201706261728431201','N201706261728510442','N201706261729160269','N201706261729407741','N201706261729444358','N201706261729509938','N201706261730028064','N201706261730058278','N201706261730086753','N201706261730353509','N201706261730366869','N201706261730478352','N201706261730493829','N201706261730553511','N201706261731061182','N201706261731071533','N201706261731172803','N201706261731171994','N201706261731201690','N201706261731254432','N201706261731269434','N201706261731337868','N201706261731387648','N201706261731560251','N201706261732094815','N201706261732131825','N201706261732138165','N201706261732171536','N201706261732253218','N201706261732267854','N201706261732294976','N201706261732454281','N201706261732541307','N201706261732572756','N201706261733024916','N201706261733028192','N201706261733042247','N201706261733238486','N201706261733326856','N201706261733385938','N201706261733434185','N201706261733452990','N201706261733505950','N201706261733556741','N201706261734067910','N201706261734159082','N201706261734250417','N201706261734401174','N201706261734544885','N201706261735021059','N201706261735247665','N201706261735482051','N201706261735543968','N201706261818522228','N201706261821529924','N201706261823324031','N201706261824054534','N201706261824502654','N201706261824533241','N201706261824566183','N201706261825185829','N201706261825336243','N201706270704278468','N201706270710333288','N201706270741409833','N201706270743209728','N201706270754479190','N201706270803136578','N201706270806125238','N201706270808487475','N201706270812041877','N201706270812040536','N201706270814376220','N201706270815502182','N201706270816480310','N201706270819330960','N201706270821147726','N201706270821216335','N201706270821222286','N201706270821508444','N201706270822262105','N201706270823352385','N201706270823376910','N201706270823384169','N201706270823440611','N201706270824072799','N201706270824206411','N201706270824243336','N201706270824523838','N201706270825114559','N201706270825324764','N201706270826055678','N201706270826164199','N201706270826175697','N201706270828315403','N201706270828418604','N201706270828441993','N201706270829313266','N201706270830092931','N201706270830161346','N201706270830450289','N201706270832012657','N201706270832223252','N201706270833001065','N201706270833067514','N201706270833094482','N201706270833255129','N201706270833340673','N201706270833396573','N201706270833509519','N201706270834140460','N201706270834218618','N201706270834272318','N201706270834282763','N201706270834295805','N201706270834312343','N201706270834370248','N201706270834381346','N201706270834391587','N201706270834415098','N201706270835237874','N201706270835382687','N201706270836289598','N201706270836318263','C201706270836515118','C201706270836514242','N201706270837305294','N201706270838224243','N201706270838375649','N201706270838503502','N201706270838506835','N201706270838517254','N201706270839047480','N201706270839370003','N201706270839471454','N201706270840134342','N201706270840199359','N201706270840310031','N201706270841309034','N201706270842114552','C201706270842418694','N201706270843090850','N201706270843282493','N201706270843283899','N201706270843368434','N201706270843410672','N201706270844012546','N201706270844177564','N201706270844252163','N201706270844449574','N201706270845154274','N201706270845171998','N201706270845185499','N201706270845254567','N201706270845337927','N201706270845347314','N201706270845392683','N201706270846051706','N201706270846293416','N201706270846334618','N201706270846368180','N201706270846454965','N201706270846458730','N201706270846513427','N201706270847063083','N201706270847073609','N201706270847111938','N201706270847241129','N201706270847433405','N201706270847587968','N201706270848202877','N201706270848214907','N201706270848412147','N201706270848433424','N201706270848443844','N201706270849235546','N201706270849283034','N201706270849351062','N201706270850121242','N201706270850128442','N201706270850197252','N201706270850229658','N201706270850287361','N201706270850298477','N201706270850503635','N201706270850512268','N201706270851100274','N201706270851288883','N201706270851287477','N201706270851348774','N201706270851510677','N201706270851530774','N201706270851559063','N201706270852078450','N201706270852230138','N201706270852346532','N201706270852372688','N201706270852418246','N201706270852483178','N201706270852595706','N201706270853039499','N201706270853273564','N201706270853326681','N201706270853393274','N201706270854096044','N201706270854325519','N201706270854356712','N201706270854416775','N201706270854561843','N201706270855020631','N201706270855144151','N201706270855267102','N201706270855382922','N201706270855400164','N201706270855468393','N201706270856079904','N201706270856164444','N201706270856166119','N201706270856335125','N201706270856441847','N201706270857007439','N201706270857088052','N201706270857102555','N201706270857140017','N201706270857216512','N201706270857396628','N201706270857405101','N201706270857509782','N201706270857546925','N201706270858217270','N201706270858275577','N201706270858312614','N201706270859048566','N201706270859253326','N201706270859327764','N201706270859332407','N201706270859574739','N201706270900077366','N201706270900150724','N201706270900252865','N201706270900505946','N201706270900511137','N201706270900513913','N201706270900549626','N201706270901029828','N201706270901088416','N201706270901177551','N201706270901186458','N201706270901211459','N201706270901549700','N201706270901589415','N201706270901599664','N201706270902061471','N201706270902200925','N201706270902247012','N201706270902251255','N201706270902394171','N201706270902516456','N201706270902556770','N201706270902592582','N201706270903030656','N201706270903083287','N201706270903371947','N201706270903469926','N201706270903558250','N201706270903598971','N201706270904128925','N201706270904253863','N201706270904306105','N201706270904310936','N201706270904380309','N201706270904490250','N201706270904569942','N201706270905076272','N201706270905108047','N201706270905175090','N201706270905315630','N201706270905320799','N201706270905488383','N201706270905484354','N201706270905572527','N201706270906027832','N201706270906046599','N201706270906141793','N201706270906224612','N201706270906259131','N201706270906565666','N201706270907003795','N201706270907026584','N201706270907081981','N201706270907094916','N201706270907270463','N201706270907365261','N201706270907440907','N201706270908032776','N201706270908053945','N201706270908170889','N201706270908331319','N201706270908541785','N201706270908560246','N201706270909067357','N201706270909375717','N201706270909489573','N201706270910001491','N201706270910082699','N201706270910158365','N201706270910181663','N201706270910194910','N201706270910259071','N201706270910260356','N201706270910286334','N201706270910325816','N201706270910364211','N201706270910391309','N201706270910514204','N201706270910550140','N201706270910596724','N201706270911003118','N201706270911067767','C201706270911081282','N201706270911308230','N201706270911373067','N201706270911520010','N201706270911578779','N201706270912055258','N201706270912263756','N201706270912293236','N201706270912398826','N201706270912536409','N201706270912546542','N201706270912557921','N201706270912587474','N201706270913051956','N201706270913132650','N201706270913207622','N201706270913296898','N201706270913378240','C201706270913410022','N201706270914038442','N201706270914105621','N201706270914319725','N201706270914427662','N201706270914434813','N201706270914521431','N201706270915027512','N201706270915036110','N201706270915134263','N201706270915213695','N201706270915220580','N201706270915266424','N201706270915325521','N201706270915377647','N201706270915394964','N201706270915508549','N201706270915524920','N201706270915525354','N201706270916020392','N201706270916091142','N201706270916101215','N201706270916145313','N201706270916140603','N201706270916212794','N201706270916273765','N201706270916352890','N201706270916355256','N201706270916376869','N201706270916417326','N201706270916445772','N201706270916496737','N201706270916508656','N201706270916568519','N201706270917259896','N201706270917390554','N201706270917416963','N201706270917435741','N201706270917561292','N201706270918003130','N201706270918117682','N201706270918297775','N201706270918400670','N201706270918477438','N201706270918544842','N201706270919081950','N201706270919102164','N201706270919185910','N201706270919198979','N201706270919231732','N201706270919263080','N201706270919335693','N201706270919565503','N201706270920002859','N201706270920003197','N201706270920044331','N201706270920055296','N201706270920108383','N201706270920209775','N201706270920463365','N201706270920539451','N201706270920563534','N201706270920596482','C201706270921082466','N201706270921090874','N201706270921124815','N201706270921211301','N201706270921326221','N201706270921357000','N201706270921359840','N201706270921422224','N201706270921486397','N201706270921514138','N201706270922091752','N201706270922127605','C201706270922139019','N201706270922171925','N201706270922223714','N201706270922247035','N201706270922379354','N201706270922440491','N201706270922461486','N201706270922552382','N201706270923019564','N201706270923058675','N201706270923140522','N201706270923147614','N201706270923223865','N201706270923311350','N201706270923314810','N201706270923331272','N201706270923414540','N201706270923425659','N201706270923514493','N201706270923516275','N201706270923532153','N201706270924083271','N201706270924209755','N201706270924234943','N201706270924259553','N201706270924287655','N201706270924450434','N201706270924586778','N201706270925068270','N201706270925061165','N201706270925152169','N201706270925292444','N201706270925322901','N201706270925332109','N201706270925516341','N201706270926111732','N201706270926132125','N201706270926197828','N201706270926233888','N201706270926252096','N201706270926311769','N201706270926523555','N201706270926590171','N201706270927001885','N201706270927092031','N201706270927178240','C201706270927205553','N201706270927342323','N201706270928088397','N201706270928113689','N201706270928147821','N201706270928233515','N201706270928303775','N201706270928441851','N201706270928522040','N201706270928525594','N201706270928530568','N201706270928580191','N201706270929062864','N201706270929135908','N201706270929167356','N201706270929171718','N201706270929217002','N201706270929300280','N201706270929301727','N201706270929352349','N201706270929368752','N201706270929366137','N201706270929419041','N201706270929436589','N201706270929571077','N201706270930145390','N201706270930208788','N201706270930209534','N201706270930270732','N201706270930300892','N201706270930305200','N201706270930310847','N201706270930419402','N201706270930480100','N201706270930512170','N201706270930569556','N201706270931017471','N201706270931115371','N201706270931110717','N201706270931317198','N201706270931378732','N201706270931393456','N201706270931401288','N201706270931450285','N201706270931478619','N201706270931558303','N201706270932028127','N201706270932054730','N201706270932061061','N201706270932209266','N201706270932253174','N201706270932315304','N201706270932352070','N201706270932407170','N201706270932407266','N201706270932449110','N201706270932446329','N201706270932580547','N201706270933007137','N201706270933008209','C201706270933050060','N201706270933080461','N201706270933130931','N201706270933145781','N201706270933324772','N201706270933349957','N201706270933353387','N201706270933578173','N201706270934115300','N201706270934126657','N201706270934169199','N201706270934212940','N201706270934225572','N201706270934267576','N201706270934290836','N201706270934355762','N201706270934383845','N201706270934492978','N201706270934507201','N201706270935012372','N201706270935031727','N201706270935123043','N201706270935161467','N201706270935213056','N201706270935221024','N201706270935237190','N201706270935310658','N201706270935323331','N201706270935355724','N201706270935404899','N201706270935467559','N201706270935472858','N201706270935554289','N201706270935572096','N201706270935577960','N201706270936001944','N201706270936125289','N201706270936146075','N201706270936212232','N201706270936258715','N201706270936295633','N201706270936325615','C201706270936396736','C201706270936401656','N201706270936459757','N201706270936470412','N201706270936528623','N201706270936512536','N201706270936547043','N201706270936552162','N201706270936543606','N201706270936568333','N201706270936583802','N201706270937001614','N201706270937067114','N201706270937091398','N201706270937156948','N201706270937262381','N201706270937270492','N201706270937329316','N201706270937325776','N201706270937353519','N201706270937366487','N201706270937372998','N201706270937385744','N201706270937399377','N201706270937399963','N201706270937485594','N201706270937563204','N201706270937582362','N201706270938009355','N201706270938042192','N201706270938044810','N201706270938082422','N201706270938085606','N201706270938111593','N201706270938134885','N201706270938175048','N201706270938193074','N201706270938220652','N201706270938246679','N201706270938280415','C201706270938341566','N201706270938357308','C201706270938358506','C201706270938350205','N201706270938375277','N201706270938408150','N201706270938433241','N201706270938517010','N201706270938546677','N201706270939019890','N201706270939048290','N201706270939054968','N201706270939337198','N201706270939421821','N201706270939464301','N201706270939476238','N201706270939487900','N201706270939490989','N201706270939516330','N201706270939576544','N201706270940036455','N201706270940064242','N201706270940093701','N201706270940170517','N201706270940246429','N201706270940255425','N201706270940310845','N201706270940326438','N201706270940414932','N201706270940495159','N201706270940564781','N201706270941119491','N201706270941158502','N201706270941195360','N201706270941192639','N201706270941237231','N201706270941355856','N201706270941364547','N201706270941486970','N201706270941508813','N201706270941521915','N201706270941534809','N201706270941553049','N201706270942059919','N201706270942130025','N201706270942196672','N201706270942285324','N201706270942305098','N201706270942337411','N201706270942369607','N201706270942353518','N201706270942396809','N201706270942406566','N201706270942432428','N201706270942441615','N201706270942522735','N201706270942567506','C201706270943015747','N201706270943068300','N201706270943060550','N201706270943094000','N201706270943126332','N201706270943132337','N201706270943158754','N201706270943320125','N201706270943340183','N201706270943409257','N201706270943437092','N201706270943451265','N201706270943454044','N201706270943539802','N201706270943571891','N201706270944133324','N201706270944148092','N201706270944184448','N201706270944216616','N201706270944258026','N201706270944421340','N201706270944456778','N201706270945047211','N201706270945223480','N201706270945457725','N201706270945494869','N201706270946040947','N201706270946117386','N201706270946141778','C201706270946257720','N201706270946276075','N201706270946290941','N201706270946303100','N201706270946349413','N201706270946386209','N201706270946396854','N201706270946401238','N201706270946440013','N201706270947024408','N201706270947026719','N201706270947101278','N201706270947112026','N201706270947456154','N201706270947462693','N201706270947468293','N201706270947510898','N201706270947528668','C201706270947567589','N201706270947597996','N201706270948018892','N201706270948059600','N201706270948078637','N201706270948077762','N201706270948120772','N201706270948242223','N201706270948251901','N201706270948315910','N201706270948324548','N201706270948428561','N201706270948453786','N201706270948464052','N201706270948528025','N201706270948558601','N201706270948554302','N201706270949137766','N201706270949224238','N201706270949345218','N201706270949343780','N201706270949373791','N201706270949416346','N201706270949431085','N201706270949488174','N201706270949552000','N201706270949562126','N201706270949593567','N201706270950116899','N201706270950196846','N201706270950545053','N201706270951082782','N201706270951280448','N201706270951323119','N201706270951333300','N201706270951367552','N201706270951390416','N201706270951399751','N201706270951397654','N201706270951391627','N201706270951444540','N201706270951483458','N201706270952007206','N201706270952006146','N201706270952020096','N201706270952162969','N201706270952250029','N201706270952314642','N201706270952364465','N201706270952372510','N201706270952405828','N201706270952431906','N201706270952498564','N201706270952495923','N201706270952521854','N201706270952540935','N201706270953041151','N201706270953278293','N201706270953314229','N201706270953345743','N201706270953396945','N201706270953396130','N201706270953396841','N201706270953427778','N201706270953471842','N201706270953475801','N201706270953548214','N201706270953591173','N201706270954007667','N201706270954046189','N201706270954055985','N201706270954076401','C201706270954133249','N201706270954153490','N201706270954160753','N201706270954186550','N201706270954219455','N201706270954246981','N201706270954243418','N201706270954325867','N201706270954332386','N201706270954423731','N201706270954498922','N201706270954514221','N201706270954589754','N201706270955008980','N201706270955018827','N201706270955079132','N201706270955129131','N201706270955131788','N201706270955138888','C201706270955145373','N201706270955182709','N201706270955199664','N201706270955207192','N201706270955202772','N201706270955394412','N201706270955439260','N201706270955454973','N201706270955493847','N201706270955504525','N201706270955538565','N201706270956019552','N201706270956067287','N201706270956080792','N201706270956139945','N201706270956142026','N201706270956223996','N201706270956232859','N201706270956251265','N201706270956425576','N201706270956571805','N201706270957037272','C201706270957064011','N201706270957101631','N201706270957174950','N201706270957201264','N201706270957236135','N201706270957327072','N201706270957334520','N201706270957352458','N201706270957371438','N201706270957400901','C201706270957403629','N201706270957417487','N201706270957476595','C201706270957504092','N201706270957521466','C201706270957573674','C201706270957577556','N201706270957572379','N201706270957596505','N201706270958048584','N201706270958043152','N201706270958171563','N201706270958194062','N201706270958193975','N201706270958265304','N201706270958282238','N201706270958300617','N201706270958346491','N201706270958356507','N201706270958387829','N201706270958402330','C201706270958447357','N201706270958460406','N201706270958542219','N201706270959020571','N201706270959049770','N201706270959092907','N201706270959175680','N201706270959492603','N201706271000003288','N201706271000015402','N201706271000008381','N201706271000092572','N201706271000164558','N201706271000292022','N201706271000324946','N201706271000343223','N201706271000375202','N201706271000399069','N201706271000442930','N201706271000452329','N201706271000499967','N201706271000507973','N201706271001032154','N201706271001060485','N201706271001129055','N201706271001243428','N201706271001351187','N201706271001422198','N201706271001422796','N201706271001421187','N201706271001438724','N201706271001470890','N201706271001488680','N201706271001507318','N201706271001569208','N201706271001583172','N201706271002010008','N201706271002101280','N201706271002121217','N201706271002178427','N201706271002174431','N201706271002183260','N201706271002207293','N201706271002228269','N201706271002257337','N201706271002364832','N201706271002398768','N201706271002481836','N201706271002494138','N201706271002551184','N201706271002575998','N201706271003028007','N201706271003038808','N201706271003049080','N201706271003072346','N201706271003080073','N201706271003108080','N201706271003123089','N201706271003147059','N201706271003171945','N201706271003238754','N201706271003242598','N201706271003353927','N201706271003407066','N201706271003419329','N201706271003403934','N201706271003439657','N201706271003462091','N201706271003474233','N201706271003544985','N201706271003557902','N201706271003563992','N201706271003561954','N201706271003585476','N201706271004040153','N201706271004086424','N201706271004148311','N201706271004186440','N201706271004213460','N201706271004317609','N201706271004356056','N201706271004354224','N201706271004596235','N201706271005126063','N201706271005145132','N201706271005148367','N201706271005176154','N201706271005209721','N201706271005216172','N201706271005261439','N201706271005273990','N201706271005283160','N201706271005291477','N201706271005324048','N201706271005362477','N201706271005376622','N201706271005422428','N201706271005457188','N201706271005477154','N201706271005510170','N201706271005560294','N201706271005575920','N201706271005576628','N201706271006164725','N201706271006179128','N201706271006203434','N201706271006311287','N201706271006365832','N201706271006495784','N201706271006547106','N201706271007044381','N201706271007101999','N201706271007116594','N201706271007230160','N201706271007259408','N201706271007377620','N201706271007398993','N201706271007404204','N201706271007413399','N201706271007582951','N201706271008022519','N201706271008098846','N201706271008114202','N201706271008154507','C201706271008176470','N201706271008201182','N201706271008229363','N201706271008230678','N201706271008273053','N201706271008298371','N201706271008316502','N201706271008330911','N201706271008397181','N201706271008433278','N201706271008489996','N201706271008498748','N201706271008503546','N201706271009049278','N201706271009042602','N201706271009148372','N201706271009157911','N201706271009166213','N201706271009173877','N201706271009205921','N201706271009245058','N201706271009258829','N201706271009366141','N201706271009388662','N201706271009416360','N201706271009455200','N201706271009538080','N201706271009532092','N201706271009575830','N201706271010054191','N201706271010135181','N201706271010231962','N201706271010283171','N201706271010295759','N201706271010342759','N201706271010389540','N201706271010452620','N201706271010483887','N201706271010501053','N201706271010557105','N201706271011014228','N201706271011051606','N201706271011066773','N201706271011078134','N201706271011189768','N201706271011194063','N201706271011216363','N201706271011215749','N201706271011220102','N201706271011302411','N201706271011329979','N201706271011329489','N201706271011338587','N201706271011427112','N201706271011469363','N201706271012064212','N201706271012084594','N201706271012167409','N201706271012272151','N201706271012376175','N201706271012433040','N201706271012468484','N201706271012500153','N201706271012585106','N201706271013048210','N201706271013089843','N201706271013117200','N201706271013134598','N201706271013242170','N201706271013263593','N201706271013292906','N201706271013401567','N201706271013443629','N201706271013460277','N201706271013509734','N201706271013518110','N201706271014081774','N201706271014107215','N201706271014133610','N201706271014217455','N201706271014213627','N201706271014372908','N201706271014380336','N201706271014520721','N201706271014558671','N201706271014588687','N201706271015111480','N201706271015217866','N201706271015248103','N201706271015303366','N201706271015494732','NC20170627101549688','N201706271015519283','N201706271015588229','N201706271015597032','N201706271016005089','N201706271016015549','N201706271016018585','N201706271016047850','N201706271016055789','N201706271016101070','N201706271016129819','N201706271016233939','N201706271016252332','N201706271016266352','N201706271016272120','N201706271016284853','N201706271016312003','N201706271016405413','N201706271016409599','N201706271016505709','N201706271016529402','N201706271016551534','N201706271016590020','N201706271017004153','N201706271017003704','N201706271017031458','N201706271017034686','N201706271017083502','N201706271017315643','N201706271017366621','N201706271017403314','N201706271017496164','N201706271017513863','N201706271017552221','N201706271017572463','N201706271018114641','N201706271018114010','N201706271018217450','N201706271018213267','N201706271018286224','N201706271018425285','N201706271018445769','N201706271018453444','N201706271018472698','N201706271018532052','N201706271018535934','N201706271018551273','N201706271019061306','N201706271019062174','N201706271019175573','N201706271019183093','N201706271019227412','N201706271019305909','N201706271019395519','N201706271019401567','N201706271019420059','N201706271019430380','N201706271019473853','N201706271019545761','N201706271019590203','N201706271020175866','N201706271020256188','N201706271020298430','N201706271020364727','N201706271020397462','N201706271020422523','N201706271020434657','N201706271020529890','N201706271020538902','N201706271020542250','N201706271020545567','N201706271020578287','N201706271020583281','N201706271021173588','N201706271021177463','N201706271021207395','N201706271021231155','N201706271021259134','N201706271021297961','N201706271021307248','N201706271021346652','N201706271021357270','N201706271021370600','N201706271021417654','N201706271021447209','N201706271021496210','N201706271021514707','N201706271021568071','N201706271021563356','N201706271022065097','N201706271022085345','N201706271022111012','N201706271022149650','N201706271022241361','N201706271022241698','N201706271022240532','N201706271022299827','N201706271022338312','N201706271022348052','N201706271022379340','N201706271022399662','N201706271022552568','C201706271023009385','N201706271023040464','N201706271023047024','N201706271023081555','N201706271023206916','N201706271023206984','N201706271023217562','N201706271023210928','N201706271023260122','N201706271023328170','N201706271023423710','N201706271023490246','N201706271023506394','N201706271023589703','N201706271023585000','N201706271024014210','N201706271024013943','N201706271024159897','N201706271024199852','N201706271024255133','N201706271024332511','N201706271024513294','N201706271025024403','N201706271025028127','N201706271025027791','N201706271025053139','N201706271025088251','N201706271025109265','N201706271025158999','N201706271025273236','N201706271025303125','N201706271025338431','N201706271025345805','N201706271025433890','N201706271025552423','N201706271026030063','N201706271026047157','N201706271026168604','N201706271026178814','N201706271026237708','N201706271026291447','N201706271026422271','N201706271026428166','N201706271026429487','N201706271026482475','N201706271026500786','N201706271026530665','N201706271026572787','N201706271026587340','N201706271027016881','N201706271027117739','N201706271027125969','N201706271027231382','N201706271027260698','N201706271027272512','N201706271027247122','N201706271027309035','N201706271027316531','N201706271027320385','N201706271027395317','N201706271027595723','N201706271028006386','N201706271028015469','N201706271028002822','N201706271028031103','N201706271028140857','N201706271028153309','N201706271028174671','N201706271028177992','N201706271028201101','N201706271028216408','N201706271028258486','N201706271028305897','N201706271028385467','N201706271028376408','N201706271028434993','N201706271028471534','N201706271028476156','N201706271028528203','N201706271028535306','N201706271028581221','N201706271029019311','N201706271029015792','N201706271029026254','N201706271029058762','N201706271029163467','N201706271029187050','N201706271029218996','N201706271029245286','N201706271029292302','N201706271029291743','N201706271029407076','N201706271029399694','N201706271029428973','N201706271029498281','N201706271029506950','N201706271029582586','N201706271029593286','N201706271030029018','N201706271030042587','N201706271030053339','N201706271030065412','N201706271030144876','N201706271030150383','N201706271030186221','N201706271030193433','N201706271030196099','N201706271030198744','N201706271030234835','N201706271030326981','N201706271030355144','N201706271030364398','N201706271030479772','N201706271030478295','N201706271030487963','N201706271030518147','N201706271030548491','N201706271031213736','N201706271031238180','N201706271031337426','N201706271031340683','N201706271031356334','N201706271031389478','N201706271031532549','N201706271031589945','N201706271031566954','N201706271031594998','N201706271032048705','N201706271032115150','N201706271032124430','N201706271032143692','N201706271032476214','N201706271032530249','N201706271032558916','N201706271033012406','N201706271033035666','N201706271033068914','N201706271033112864','N201706271033191199','N201706271033277247','N201706271033335300','N201706271033401886','N201706271033435254','N201706271033491139','N201706271033563583','N201706271033573323','N201706271034008971','N201706271034162728','N201706271034140828','N201706271034185752','N201706271034184759','N201706271034165735','N201706271034272953','N201706271034314967','N201706271034325938','N201706271034358007','N201706271034469569','N201706271034508112','N201706271034497136','N201706271035022333','N201706271035036406','N201706271035038776','N201706271035052676','N201706271035068935','N201706271035185585','N201706271035221641','N201706271035249080','N201706271035323746','N201706271035338444','N201706271035381939','N201706271035410404','N201706271035473388','N201706271035516830','N201706271035527674','N201706271035545932','N201706271035562947','N201706271036318973','N201706271036280619','N201706271036441185','N201706271036491026','N201706271036544635','N201706271036546169','N201706271036570052','N201706271037073808','N201706271037078121','N201706271037119548','N201706271037142928','N201706271037128573','N201706271037148400','N201706271037203493','N201706271037209886','N201706271037223950','N201706271037263745','N201706271037289661','N201706271037295432','N201706271037322937','N201706271037379474','N201706271037375217','N201706271037389502','N201706271037430116','N201706271037550448','N201706271038036669','N201706271038096369','N201706271038149959','N201706271038171522','N201706271038186942','N201706271038198749','N201706271038212929','N201706271038452861','N201706271038453575','N201706271038521786','N201706271038514333','N201706271038579388','N201706271038598870','N201706271038594913','N201706271039022298','N201706271039038016','N201706271039226469','N201706271039240460','N201706271039246782','N201706271039253891','N201706271039328543','N201706271039350450','N201706271039375196','N201706271039418764','N201706271039516915','N201706271039584969','N201706271039582938','N201706271040071098','N201706271040123933','N201706271040143661','N201706271040155474','N201706271040163389','N201706271040199523','N201706271040213116','N201706271040262936','N201706271040285932','N201706271040305780','N201706271040342393','N201706271040342320','N201706271040371508','N201706271040437258','N201706271040556461','N201706271041039054','N201706271041050198','N201706271041160051','N201706271041205071','N201706271041276744','N201706271041339546','N201706271041390062','N201706271042042159','N201706271042058394','N201706271042079750','N201706271042139060','N201706271042155066','N201706271042199879','N201706271042242357','N201706271042253079','N201706271042262282','N201706271042292675','N201706271042379117','N201706271042392732','N201706271042464880','N201706271042509157','N201706271042533726','N201706271042592755','N201706271042595815','N201706271043035195','N201706271043090467','N201706271043111698','N201706271043137888','N201706271043280400','N201706271043390521','N201706271043454239','N201706271043476741','N201706271043548465','N201706271044001926','N201706271044043351','N201706271044077605','N201706271044114879','N201706271044129268','N201706271044133771','N201706271044165642','N201706271044166264','N201706271044162687','N201706271044325561','N201706271044355661','N201706271044509235','N201706271044527233','N201706271044558093','N201706271045011830','N201706271045079540','N201706271045183590','N201706271045309747','N201706271045450890','N201706271045533408','N201706271045553567','N201706271046127228','N201706271046134470','N201706271046148293','N201706271046193808','N201706271046327655','N201706271046358004','N201706271046379172','N201706271046385457','N201706271046403101','N201706271046421448','N201706271046425449','N201706271046453022','N201706271046463350','N201706271046485820','N201706271046522703','N201706271046540369','N201706271046551578','N201706271047009653','N201706271047013105','N201706271047065875','N201706271047148399','N201706271047189459','N201706271047260316','N201706271047286471','N201706271047289199','N201706271047375152','N201706271047428697','N201706271047471538','N201706271047479495','N201706271047506908','N201706271048005267','N201706271048026293','N201706271048060088','N201706271048140886','N201706271048152480','N201706271048220990','N201706271048436199','N201706271048435949','N201706271048474493','N201706271048516520','N201706271048563384','N201706271049028610','N201706271049033258','N201706271049131584','N201706271049154244','N201706271049192907','N201706271049203402','N201706271049431179','N201706271049507289','N201706271050020463','N201706271050021735','N201706271050055782','N201706271050105281','N201706271050202118','N201706271050254146','N201706271050268728','N201706271050285541','N201706271050351545','N201706271050406395','N201706271050453164','N201706271050472605','N201706271050479375','N201706271050535927','N201706271050552680','N201706271050581059','N201706271051034722','N201706271051187792','N201706271051252534','N201706271051307518','N201706271051405485','N201706271051477514','N201706271051506221','N201706271051510479','N201706271051522537','N201706271052162398','N201706271052215820','N201706271052389308','N201706271052404440','N201706271052430667','N201706271052480298','N201706271052509684','N201706271052559322','N201706271053059500','N201706271053137713','N201706271053163381','N201706271053194747','N201706271053208770','N201706271053381407','N201706271053453748','N201706271053512314','N201706271054033641','N201706271054075723','N201706271054154342','N201706271054316691','N201706271054466151','N201706271054507816','N201706271054563913','N201706271054571096','N201706271054578648','N201706271054583547','N201706271055008144','N201706271055191671','N201706271055213768','N201706271055258853','N201706271055286566','N201706271055451163','N201706271055502617','N201706271055533986','N201706271056008901','N201706271056018057','N201706271056237918','N201706271056410665','N201706271056443869','N201706271056479702','N201706271056532583','N201706271057004208','N201706271057031392','N201706271057044777','N201706271057141646','N201706271057204819','N201706271057233854','N201706271057307123','N201706271057371683','N201706271057391443','N201706271057453930','N201706271057517240','N201706271057540561','N201706271057552341','N201706271058013688','N201706271058053539','N201706271058222595','N201706271058263718','N201706271058304230','N201706271058394400','N201706271058435196','N201706271058440068','N201706271058449980','N201706271058487755','N201706271058570712','N201706271059009240','N201706271059033896','N201706271059040628','N201706271059081067','N201706271059188992','N201706271059202621','N201706271059219890','N201706271059233826','N201706271059228327','N201706271059220093','N201706271059267922','N201706271059328546','N201706271059359352','N201706271059355332','N201706271059424730','N201706271059498395','N201706271100013337','N201706271100062679','N201706271100098615','N201706271100107962','N201706271100170568','N201706271100309983','N201706271100323856','N201706271100350205','N201706271100423154','N201706271100502493','N201706271100517086','N201706271100573221','N201706271101063385','N201706271101104827','N201706271101124387','N201706271101186354','N201706271101199917','N201706271101319101','N201706271101383471','N201706271101418359','N201706271101484708','N201706271101526529','N201706271102018836','N201706271102028189','N201706271102039885','N201706271102050650','N201706271102067888','N201706271102088597','N201706271102091926','N201706271102096588','N201706271102138002','N201706271102146849','N201706271102152468','N201706271102210790','N201706271102216248','N201706271102319404','N201706271102314623','N201706271102358210','N201706271102361958','N201706271102530857','N201706271102545719','N201706271102597622','N201706271103038814','N201706271103063019','N201706271103118815','N201706271103119922','N201706271103141988','N201706271103284310','N201706271103379984','N201706271103565902','N201706271103574094','N201706271104031112','N201706271104056518','N201706271104064111','N201706271104087552','N201706271104136637','N201706271104146615','N201706271104207196','N201706271104225662','N201706271104237196','N201706271104237251','N201706271104343477','N201706271104377811','N201706271104405575','N201706271104424861','N201706271104467235','N201706271104567239','N201706271105074982','N201706271105108809','N201706271105167192','N201706271105220126','N201706271105268247','N201706271105426345','N201706271105463616','N201706271105558432','N201706271105580542','N201706271106036623','N201706271106139759','N201706271106130264','N201706271106170768','N201706271106273786','N201706271106275638','N201706271106305387','N201706271106398059','N201706271106452227','N201706271106484848','N201706271106481893','N201706271106531311','N201706271106523579','N201706271106549749','N201706271106540954','N201706271106574001','N201706271106578221','N201706271106582244','N201706271107024168','N201706271107069752','N201706271107104255','N201706271107195776','N201706271107391335','N201706271107452298','N201706271107499468','N201706271107496487','N201706271107589513','N201706271108034499','N201706271108090481','N201706271108127099','N201706271108157555','N201706271108171029','N201706271108201347','N201706271108262070','N201706271108277296','N201706271108402958','N201706271108425914','N201706271108556407','N201706271109030863','N201706271109041859','N201706271109056023','N201706271109064938','N201706271109108969','N201706271109141765','N201706271109180230','N201706271109242178','N201706271109257621','N201706271109293679','N201706271109334053','N201706271109332149','N201706271109331541','N201706271109341094','N201706271109435903','N201706271109506670','N201706271109551765','N201706271109583101','N201706271109587808','N201706271109597077','N201706271110009765','N201706271110049605','N201706271110301055','N201706271110395537','N201706271110414934','N201706271110439396','N201706271110440590','N201706271111027831','N201706271111048992','N201706271111071734','N201706271111105235','N201706271111123639','N201706271111279882','N201706271111363138','N201706271111371308','N201706271111476750','N201706271111486594','N201706271111490144','N201706271111541072','N201706271111550643','N201706271112000585','N201706271112036489','N201706271112047596','N201706271112045359','N201706271112074246','N201706271112078034','N201706271112118923','N201706271112152363','N201706271112155971','N201706271112173310','N201706271112273457','N201706271112328665','N201706271112427499','N201706271112468693','N201706271112479184','N201706271112534501','N201706271112532711','N201706271112571035','N201706271113026124','N201706271113040146','N201706271113130747','N201706271113199805','N201706271113203521','N201706271113230569','N201706271113303375','N201706271113372188','N201706271113418331','N201706271113499040','N201706271113509701','N201706271113534944','N201706271113532727','N201706271114004160','N201706271114047962','N201706271114048163','N201706271114069197','N201706271114203853','N201706271114327799','N201706271114385563','N201706271114382630','N201706271114406328','N201706271114489664','N201706271114496265','N201706271114510058','N201706271114532077','N201706271114566819','N201706271115086903','N201706271115115480','N201706271115171427','N201706271115185147','N201706271115210823','N201706271115228424','N201706271115268129','N201706271115294237','N201706271115329975','N201706271115337514','N201706271115357460','N201706271115370082','N201706271115371594','N201706271115397164','N201706271115431245','N201706271115495159','N201706271115495073','N201706271116052100','N201706271116066684','N201706271116100580','N201706271116113561','N201706271116153400','N201706271116174966','N201706271116191447','N201706271116229159','N201706271116343817','N201706271116386062','N201706271116416911','N201706271116497455','N201706271116550338','N201706271116551260','N201706271116550632','N201706271116553677','N201706271116566248','N201706271116573838','N201706271117004582','N201706271117029868','N201706271117035606','N201706271117040083','N201706271117080447','N201706271117236947','N201706271117324885','N201706271117327031','N201706271117419461','N201706271117425840','N201706271117447633','N201706271117519062','N201706271117527911','N201706271117534499','N201706271117558459','N201706271118051061','N201706271118141058','N201706271118170968','N201706271118205965','N201706271118254934','N201706271118378945','N201706271118484792','N201706271118513074','N201706271118569723','N201706271118595387','N201706271118593897','N201706271119057221','N201706271119125135','N201706271119156348','N201706271119160293','N201706271119214998','N201706271119200569','N201706271119299766','N201706271119316922','N201706271119366286','N201706271119406842','N201706271119415471','N201706271119558747','N201706271120009559','N201706271120020110','N201706271120057942','N201706271120224506','N201706271120231660','N201706271120365954','N201706271120379328','N201706271120446012','N201706271120450716','N201706271120464867','N201706271120573892','N201706271121040936','N201706271121049509','N201706271121097604','N201706271121140033','N201706271121180764','N201706271121257150','N201706271121259204','N201706271121278379','N201706271121417458','N201706271121562394','N201706271121598330','N201706271122030770','N201706271122054109','N201706271122166245','N201706271122318879','N201706271122344865','N201706271122355129','N201706271122415422','N201706271122512236','N201706271122596095','N201706271123078646','N201706271123109205','N201706271123115013','N201706271123110844','N201706271123128019','N201706271123171494','N201706271123192778','N201706271123217739','N201706271123367549','N201706271123397945','N201706271123398821','N201706271123472761','N201706271123599421','N201706271124016255','N201706271124025694','N201706271124124750','N201706271124152012','N201706271124173173','N201706271124251130','N201706271124275093','N201706271124304161','N201706271124369391','N201706271124381777','N201706271124438207','N201706271124543592','N201706271124540875','N201706271124558060','N201706271124569833','N201706271125034448','N201706271125208155','N201706271125251314','N201706271125325804','N201706271125338366','N201706271125384118','N201706271125442993','N201706271125466311','N201706271125485346','N201706271125502555','N201706271125568265','N201706271126074023','N201706271126070946','N201706271126139777','N201706271126159962','N201706271126208257','N201706271126217251','N201706271126249022','N201706271126269492','N201706271126311159','N201706271126331050','N201706271126359766','N201706271126439669','N201706271126453990','N201706271126496909','N201706271126516039','N201706271126558423','N201706271126554154','N201706271126568144','N201706271127001695','N201706271127069858','N201706271127127319','N201706271127149312','N201706271127222637','N201706271127263475','N201706271127274901','N201706271127345167','N201706271127361114','N201706271127432234','N201706271127471124','N201706271127497760','N201706271127546766','N201706271127572033','N201706271128061939','N201706271128068638','N201706271128085676','N201706271128085956','N201706271128129908','N201706271128187941','N201706271128216910','N201706271128238335','N201706271128352142','N201706271128363423','N201706271128419400','N201706271128438265','N201706271128521628','N201706271129077992','N201706271129085429','N201706271129115247','N201706271129126080','N201706271129171086','N201706271129213500','N201706271129237795','N201706271129265834','N201706271129463917','N201706271129480599','N201706271129508586','N201706271129579311','N201706271129594731','N201706271130029152','N201706271130057688','N201706271130131970','N201706271130149132','N201706271130145686','N201706271130157746','N201706271130161291','N201706271130277787','N201706271131013936','N201706271131084213','N201706271131105677','N201706271131110509','N201706271131153676','N201706271131163405','N201706271131188334','N201706271131204527','N201706271131313357','N201706271131323436','N201706271131320237','N201706271131342561','N201706271131344838','N201706271131352773','N201706271131376596','N201706271131393447','N201706271131412197','N201706271131445624','N201706271131531309','N201706271131530177','N201706271132078677','N201706271132151959','N201706271132168076','N201706271132228441','N201706271132287833','N201706271132298613','N201706271132297879','N201706271132469081','N201706271132512277','N201706271132517689','N201706271132573158','N201706271132580638','N201706271133017764','N201706271133071558','N201706271133115463','N201706271133135761','N201706271133250711','N201706271133339713','N201706271133365108','N201706271133461355','N201706271133499307','N201706271133561190','N201706271133584752','N201706271134011231','N201706271134077101','N201706271134152419','N201706271134160527','N201706271134334450','N201706271134499327','N201706271134513037','N201706271134555909','N201706271135101685','N201706271135329099','N201706271135376284','N201706271135412451','N201706271135442887','N201706271135576833','N201706271136028242','N201706271136082756','N201706271636327569','N201706272125390433','N201706292135357988'];
        $res = [];
        $i = 0;
        $contents = $this->o_pay->get_logs_orders_notify();
        foreach($orders as $v)
        {
            foreach($pay_methods as $p)
            {
                $tmp = $this->o_pay->check_pay($v,$p,$contents);
                if(isset($res[$v]))
                {
                    if($tmp)
                    {
                        $res[$v] .= ",1";
                    }else{
                        $res[$v] .= ",0";
                    }
                }else{
                    if($tmp)
                    {
                        $res[$v] = ",1";
                    }else{
                        $res[$v] = ",0";
                    }
                }
            }
            echo($v.$res[$v]."\n");ob_flush();
            $i++;
            if($i > 10)
            {
//                break;
            }
        }
        $this->o_pay->redis_set("check_pay_again",serialize($res));
    }

    /**
     * @param int $max
     */
    public function repair_split_order($max=1)
    {
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
        //查订单
        $orders = $this->db->where(["created_at >="=>'2017-06-20 15:00:00'])
//        $orders = $this->db->where(["created_at >="=>'2017-06-28 11:00:00'])
            ->from("trade_orders");
        if($orders)
        {
            $orders = $orders->limit($max,0)->get()->result_array();
            foreach($orders as $k=>$v_order)
            {
                $order_id = $v_order['order_id'];
                //查商品详情
                $order_goodss = $this->db->where(["order_id"=>$order_id])
                    ->from("trade_orders_goods")->get()->result_array();
                if($order_goodss){
                    $this->db->trans_begin();
                    //格式化数据
                    if(!$v_order['deliver_time'])
                    {
                        $v_order['deliver_time'] = '0000-00-00 00:00:00';
                    }
                    if(!$v_order['receive_time'])
                    {
                        $v_order['receive_time'] = '0000-00-00 00:00:00';
                    }
                    if(!$v_order['pay_time'])
                    {
                        $v_order['pay_time'] = '0000-00-00 00:00:00';
                    }
                    //插入商品订单
                    $this->tb_trade_orders->insert_one($v_order);
                    //删除原订单
                    $this->db->where(["order_id"=>$order_id])->from("trade_orders")->delete();
                    //插入商品详情
                    foreach($order_goodss as $k_order_goods =>$v_order_goods)
                    {
                        //格式化产品数据
                        unset($v_order_goods['id']);
                        $this->tb_trade_orders_goods->insert_one($v_order_goods);
                    }
                    //删除原订单商品
                    $this->db->where(["order_id"=>$order_id])->from("trade_orders_goods")->delete();
                    if($this->db->trans_status() == true)
                    {
                        echo(__LINE__.":".$order_id."\n");
                        $this->db->trans_commit();
                    }else{
                        echo(__LINE__.":".$order_id."\n");
                        $this->db->trans_rollback();
                    }
                }

            }
        }
    }

    public function check_wxpay($order_id)
    {
        echo($order_id);
        $this->load->model("o_pay");
        $tmp = $this->o_pay->check_wxpay($order_id);
        var_dump($tmp);
    }

    public function check_alipay($order_id)
    {
        echo($order_id);
        $this->load->model("o_pay");
        $content = $this->o_pay->get_logs_orders_notify();
        $tmp = $this->o_pay->check_alipay($order_id,$content);
        var_dump($tmp);
    }

    public function check_unionpay($order_id)
    {
        echo($order_id);
        $this->load->model("o_pay");
        $content = $this->o_pay->get_logs_orders_notify();
        $tmp = $this->o_pay->check_unionpay($order_id,$content);
        var_dump($tmp);
    }

    public function check_usd_unionpay($order_id)
    {
        echo($order_id);
        $this->load->model("o_pay");
        $content = $this->o_pay->get_logs_orders_notify();
        $tmp = $this->o_pay->check_usd_unionpay($order_id,$content);
        var_dump($tmp);
    }

	    /**
     * 商品点击量从REDIS存到表中
     *@author Baker
     *@date 2017-6-30
     */

    public function add_goods_click() {
        $this->load->model('tb_mall_goods_main');
        for ($i = 1; $i < 2000; $i++) { 
            $goods_list = $this->db->select("click_count,goods_sn_main,goods_id ")->where(['is_on_sale' => 1])->limit(100, ($i-1)*100)->get('mall_goods_main')->result_array();
            if (empty($goods_list)) {
                break;
            }
            foreach ($goods_list as $k => $v) {
                $redis_key = $this->tb_mall_goods_main->click_name.$v['goods_sn_main'];
                $num = intval($this->tb_mall_goods_main->redis_get($redis_key));
                if ($num) {
                    $this->db->update('mall_goods_main', ['click_count' => $num], ['goods_id' => $v['goods_id']]);
                    $this->tb_mall_goods_main->redis_del($redis_key);
                }
            }
        }
    }

    /**
     * 将隔天的redis的日志保存到数据库
     */
    public function save_mall_goods_redis_log()
    {
        $this->load->model("tb_mall_goods");
        $this->tb_mall_goods->save_mall_goods_redis_log();
    }


    /**
     * 抽回新会员重发情况
     */
    public function new_member_retry(){
        ini_set('memory_limit', '5120M');
        $total = 5;
        for($i=3;$i<=$total;$i++){
            $table = "cash_account_log_201707_138".$i;
            $sql ="select p1.uid,p2.proportion,p1.amount,p2.proportion_type,p1.id from $table p1
LEFT JOIN profit_sharing_point_proportion p2 on p1.uid = p2.uid
where item_type=26 and create_time BETWEEN '2017-07-11 10:00:00' and '2017-07-11 12:00:00' GROUP BY uid having count(*)>1";
            $result = $this->db->query($sql)->result_array();

            if(!empty($result)){
                foreach($result as $k=>$v){
                    if($v['proportion_type']==1){
                        $row = $this->db->query("select id,amount from $table where uid = ".$v['uid']." and item_type= 17 and create_time BETWEEN '2017-07-11 10:00:00' and '2017-07-11 12:00:00' GROUP BY uid ")->row_array();
                        if(!empty($row)){
                            $this->db->query("update users set profit_sharing_point=profit_sharing_point-(".$row['amount']."/100) where id =".$v['uid']);
                            $this->db->query("delete from $table where id= ".$row['id']." and item_type =17");
                            $this->m_log->createCronLog('[success]新会员专项奖用户'.$v['uid'].'抽回分红点 '.($row['amount']/100)."删除分红点记录ID为".$row['id']);
                        }
                    }
                    $this->db->query("update user_comm_stat set new_member_bonus=new_member_bonus-".$v['amount']." where uid = ".$v['uid']);
                    $this->db->query("update users set amount=amount-(".$v['amount']."/100) where id =".$v['uid']);
                    $this->db->query("delete from $table where id= ".$v['id']." and item_type =26");
                    $this->m_log->createCronLog('[success]新会员专项奖用户'.$v['uid'].'抽回佣金 '.($v['amount']/100)."删除佣金记录ID为".$v['id']);
                }
            }

        }


    }
}