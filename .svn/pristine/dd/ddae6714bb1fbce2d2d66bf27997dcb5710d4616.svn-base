<?php

/**
 * User: Able
 * Date: 2017/3/24
 * Time: 14:46
 */
class o_cron_138_elite_bonus extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->model("td_system_rebate_conf");
        $this->load->model('o_do_daily_elite');
        $this->load->model('o_new_138_bonus');
        $this->load->model('tb_grant_pre_bonus_state');
    }

    /******************************* 138分红**********************************/

    /*处理138分红*/
    public function do138Shar($yesterdayProfit){
        $this->tb_grant_pre_bonus_state->edit_state(2,2,"");  //预发奖状态初始化
        $rowData = $this->td_system_rebate_conf->getBonusData(2); //查询分红百分比
        $totalSharAmount = tps_money_format($yesterdayProfit * $rowData[0]['rate_a']);//根据配置的利润比例算出发放金额

        if($totalSharAmount>0){//-------有金额

            //判断 138临时表
            $_total = $this->o_new_138_bonus->query_138_tmp_count();

            if($_total>0){
                $this->m_log->createCronLog('[false] 138临时表中有历史数据，停止预发。');
                return false;
            }

            $res = $this->o_new_138_bonus->new_138_shar_prepare();
            //sleep(8);
            @$this->db->reconnect();
            $totalNum = $res["totalNum"];/*今天138发奖的总人数*/
            $totalSharNum = $res["totalSharNum"];//今天138人员矩阵底下人数总和

            if($totalNum > 0){//-----有需要发奖的人员
                $this->o_new_138_bonus->truncateTable(); //清空138预发表
                $totalAmountAvg = $totalSharAmount * $rowData[0]['rate_b'];//用来均摊的总利润.
                $amountAvg = tps_money_format($totalAmountAvg/$totalNum);//每个人可以拿到的均摊利润。
                $totalAmountOther = $totalSharAmount-$totalAmountAvg;//用来按照矩阵发放的总利润.

                //记录参数
                $this->o_new_138_bonus->add_grant_bonus_138_every_param($totalSharNum,$totalAmountOther,$amountAvg,$res["x"],$res["y"]);

                /*根据拿奖总人数划分分页,并分页处理发奖。*/
                $pageNum = ceil($totalNum/config_item("bonus")["138Shar"]["pageSize"]);

                for($page=1;$page<=$pageNum;$page++){
                    /*138分红分页插入预存表*/
                    //$this->o_new_138_bonus->do138SharPage(config_item("bonus")["138Shar"]["pageSize"],$amountAvg,$totalAmountOther,$totalSharNum,$page);
                    //多线程
                    $this->o_pcntl->tps_pcntl_wait('$this->o_new_138_bonus->do138SharPage(\''.config_item('bonus')['138Shar']['pageSize'].'\',\''.$amountAvg.'\',\''.$totalAmountOther.'\',\''.$totalSharNum.'\',\''.$page.'\');');//用子进程处理每一页
                }
                $this->m_log->createCronLog('[Success] 138分红插入预存表成功。');
                $this->tb_grant_pre_bonus_state->edit_state(2,3,"");  //预发奖状态初始化
            }else{

                $this->m_log->createCronLog('[false] 今天没有满足138的人。');
            }
        }else{

            $this->m_log->createCronLog('[false] 138发放利润不足。');
        }
    }


    /*发放138分红*/
    public function giveOut138Shar(){
        //判断 138临时表
        $get_status = $this->tb_grant_pre_bonus_state->get_state(2);
        if($get_status['state']!=3){
            $this->m_log->createCronLog('[fail]  138 临时表生成失败 发奖时间：'.date("Y-m-d H:i:s",time()).' 138 发奖未开始！');
            return;
        }
        $this->o_new_138_bonus->get_138_shar_grant_pre();
    }



    /**
     * 每月初生成新的138发奖列表
     */
    public function new_138_bonus_list(){
        $this->o_new_138_bonus->new_138_bonus_list();
    }
    
    /**
     * 修复会员后，生成新的138发奖队列
     */
    public function user_138_bonus_qualified_list(){
        $this->o_new_138_bonus->user_138_bonus_qualified_list();
    }

    /**
     * 为138分红准备数据(正式发放)
     * @return array
     */
    public function new_138_shar_prepare(){
        return $this->o_new_138_bonus->new_138_shar_prepare();
    }


    /**
     * 为138分红准备数据 发放到临时表
     * @return array
     */
    public function new_138_shar_prepare2(){
        return $this->o_new_138_bonus->new_138_shar_prepare2();
    }

    public function revulsion_138_grant(){
        return $this->o_new_138_bonus->revulsion_138_grant();
    }

    public function batch_138_grant(){
        return $this->o_new_138_bonus->batch_138_grant();
    }



    /******************************* 销售精英分红**********************************/


    /*处理精英日分红*/
    public function doDailyEliteShar($yesterdayProfit){
        $this->tb_grant_pre_bonus_state->edit_state(24,2,"");  //预发奖状态初始化
        $this->load->model('tb_daily_bonus_elite_qualified_list');
        $rowData = $this->td_system_rebate_conf->getBonusData(24);//查询分红百分比
        $totalSharAmount = tps_int_format($yesterdayProfit * $rowData[0]['rate_a']);//由配置利润比例算出发放金额
        if($totalSharAmount>0){//-------有金额

            $totalNum = $this->tb_daily_bonus_elite_qualified_list->getDailyEliteSharNum();/*统计今天分红的总人数*/

            if($totalNum > 0){//-----有需要发奖的人员
                $this->o_do_daily_elite->truncateTable();/*清空销售精英预发表*/
                $totalWeight = $this->tb_daily_bonus_elite_qualified_list->statDailyEliteSharTotalWeight();/*统计发奖人员总权重点*/
                //记录参数
                $this->o_do_daily_elite->add_grant_bonus_elite_every_param($totalSharAmount,$totalWeight);

                $pageNum = ceil($totalNum/config_item('bonus')['dailyEliteProfitShar']['pageSize']); /*根据拿奖总人数划分分页,并分页处理发奖。*/
                for($page=1;$page<=$pageNum;$page++){
                    /*精英日分红分页插入预存表*/
                    //$this->o_do_daily_elite->do_daily_elite_shar_page($page,config_item('bonus')['dailyEliteProfitShar']['pageSize'],$totalSharAmount,$totalWeight);
                    //多线程
                    $this->o_pcntl->tps_pcntl_wait('$this->o_do_daily_elite->do_daily_elite_shar_page(\''.$page.'\',\''.config_item('bonus')['dailyEliteProfitShar']['pageSize'].'\',\''.$totalSharAmount.'\',\''.$totalWeight.'\');');//用子进程处理每一页
                }
                $this->m_log->createCronLog('[Success] 精英日分红分页插入预存表成功。');
                $this->tb_grant_pre_bonus_state->edit_state(24,3,"");  //预发奖状态初始化
            }else{

                $this->m_log->createCronLog('[false] 今天没有满足精英日分红的人。');
            }
        }else{

            $this->m_log->createCronLog('[false] 精英日分红发放利润不足。');
        }
    }


    /*发放精英日分红*/
    public function giveOutEliteShar(){
        $this->o_do_daily_elite->get_elite_shar_grant_pre();
    }


    /*每月初生成新的精英日分红发奖列表*/
    public function new_daily_bonus_elite_list(){
        $this->o_do_daily_elite->new_daily_bonus_elite_list();
    }


    /*添加遗漏数据*/
   /* public function omit_daily_elite(){
        $this->o_do_daily_elite->omit_daily_elite_sql();
    }*/


    /*添加遗漏销售精英日分红*/
    public function repair_new_daily_bonus($create_time,$dateTime,$msg){
        $this->load->model('o_bonus');
        $item_type = 24; //发奖类型
        $this->load->model('tb_daily_bonus_elite_qualified_list');
        $pagesize = config_item('bonus')['dailyEliteProfitShar']['pageSize'];

        $sql = "select a.bonus_shar_weight,b.bonus from daily_bonus_elite_qualified_list a
              LEFT JOIN grant_bonus_user_logs b on a.uid = b.uid where a.qualified_day = 0 and item_type=$item_type and create_time like '%" .
            $this->db->escape_like_str($dateTime)."%' order by bonus_shar_weight limit 1";
        $arrRow = $this->db->force_master()->query($sql)->result_array();

        if(!empty($arrRow)){
            $total = $this->db->query("select count(*) as total from daily_bonus_elite_qualified_list a
                            left join users b on a.uid = b.id where b.status = 1 and a.qualified_day = DATE_FORMAT(curdate(),'%Y%m%d')")->row_array();

            $pageNum = ceil($total['total'] / $pagesize);
            $num = $arrRow[0]['bonus_shar_weight'];
            $bonusNum = $arrRow[0]['bonus'];

            for($page=1;$page<=1;$page++){
                $itemStart = ($page-1)*$pagesize;
                $sql = "select a.uid,round(a.bonus_shar_weight/$num*$bonusNum) as money from daily_bonus_elite_qualified_list a
left join users b on a.uid = b.id where b.status = 1 and a.qualified_day = DATE_FORMAT(curdate(),'%Y%m%d') limit $itemStart,$pagesize";
                $rowRul = $this->db->query($sql)->result_array();

                if(empty($rowRul)){
                    $data = ['content'=>"没有遗漏销售精英日分红数据。"];
                    $this->tb_error_log->add_error_log($data);
                    return false;
                }

                if($this->o_bonus->assign_bonus_batch($rowRul,$item_type)){
                    $this->m_log->createCronLog("[Success] 补发精英日分红 page: ".$page." total : $pageNum");
                    $this->o_do_daily_elite->add_miss_bonus(date("Ymd",time()),$item_type,$itemStart,$pagesize); //补发遗漏精英分红保存uid到遗漏分红表
                }else{
                    $this->m_log->createCronLog("[fail] 销补发精英日分红失败 page: ".$page." total : $pageNum");
                }
            }
            $this->m_log->createCronLog('[Success] '.$msg.' 补发精英日分红完成。');
            return true;
        }

        $this->m_log->createCronLog('[false] '.$msg.'补发精英日分红失败。');
        return false;
    }


    /*
     * 批量补发存在于销售精英发奖列表中漏发的数据
     */
   /* public function batch_grant_bonus_elite(){
        $table = "cash_account_log_".date("Ym");
        $dateTime = date("Y-m-d",strtotime("-1 day"));  //需要补发的时间
        $this->o_do_daily_elite->batch_grant_bonus_elite_sql($dateTime,$table);
    }*/


    /**
     * 销售精英抽回
     */
   /* public function revulsion_bonus_elite_grant(){
        $idArr = [
                    1382478405,
                    1382186748,
                    1382180996,
                    1382186690,
                    1380993989,
                    1380162035,
                    1380119851,
                    1381727928,
                    1380100952,
                    1381786075
                ];
        $strId = join(",",$idArr);
        $this->o_do_daily_elite->revulsion_bonus_elite_grant($strId);
    }*/

}