<?php

/**
 * User: Able
 * Date: 2017/3/23
 * Time: 15:12
 */
class o_do_daily_elite extends MY_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->model("tb_error_log");
        $this->load->model("o_bonus");
    }



    /**
     * 每月初生成新的精英日分红发奖列表
     * @return bool
     */
    public function new_daily_bonus_elite_list(){
        /*开始事务*/
        $this->db->trans_begin();

        /*清空上个月拿奖的人员*/
        $this->db->query("truncate table daily_bonus_elite_qualified_list");

        /*筛选出上月推荐人合格的会员并插入发奖列表,同时生成相应的销售额（套餐+零售）筛选人，然后权重字段写入相应套餐销售额*/
        /*把普通订单销售额更新加入到权重字段*/
        $this->db->query("insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight)
                          select a.uid,if(b.sale_amount is null,a.pro_set_amount,cast(a.pro_set_amount as signed)+cast(b.sale_amount as signed)) as total from (select uid,pro_set_amount 
                          from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
                          and pro_set_amount>0 and uid<>1380100217) a 
                          left JOIN  users_store_sale_info_monthly b on (a.uid = b.uid and b.year_month=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m'))"
        );

        /*筛选出上月零售订单合格的会员并插入发奖列表，同时生成相应的销售额（只有零售）*/
        $this->db->query("insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight) select uid,sale_amount 
from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and sale_amount>=25000");

        /*事务结束*/
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data = ['content'=>"每月初生成新的精英日分红发奖列表失败"];
            $this->tb_error_log->add_error_log($data);
            return false;
        }
        else
        {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 每月初生成新的精英日分红发奖列表.');
            return true;
        }

    }



    /**
     * 分页精英日分红插预发表
     * @param $curpage  开始页
     * @param $pagesize  查询数量
     * @param $totalSharAmount
     * @param $totalWeight
     * @return bool
     */
    public function do_daily_elite_shar_page($curpage,$pagesize,$totalSharAmount,$totalWeight){

        $itemStart = ($curpage-1)*$pagesize;

        /*按分页取出要发奖的人员列表插入预发表中*/
        $sql = "insert ignore into grant_pre_sales_executive_bonus (uid,amount,create_time) 
              select a.uid,round($totalSharAmount/$totalWeight*a.bonus_shar_weight),unix_timestamp(now()) from daily_bonus_elite_qualified_list a
              left join users b on a.uid = b.id where b.status = 1
              order by a.uid limit $itemStart,$pagesize";
        $this->db->query($sql);
    }


    /**
     * 发放预发表中的销售精英分红
     * @return mixed
     */
    public function get_elite_shar_grant_pre(){
        $total = $this->db->from("grant_pre_sales_executive_bonus")->force_master()->count_all_results();
        $page = 10000;
        $item_type = 24; //发奖类型
        $num = ceil($total/$page);
        if($num > 0){
            for($i=1;$i<=$num;$i++){
                $itemStart = ($i-1)*$page;
                $list = $this->db->from("grant_pre_sales_executive_bonus")->force_master()->select("uid,amount as money")->limit($page,$itemStart)->get()->result_array();
                if(empty($list)){
                    $data = ['content'=>"没有精英日分红预发数据。"];
                    $this->tb_error_log->add_error_log($data);
                    return false;
                }
                if($this->o_bonus->assign_bonus_batch($list,$item_type)){
                    $this->m_log->createCronLog("[Success] 销售精英分红分发 page: ".$i." total : $num");
                }else{
                    $this->m_log->createCronLog("[fail] 销售精英分红分发失败 page: ".$i." total : $num");
                }
                //多线程
                //$this->o_pcntl->tps_pcntl_wait('$this->o_bonus->assign_bonus_batch(\''.$list.'\',\''.$item_type.'\');');//用子进程处理每一页

            }
            $this->m_log->createCronLog("[Success] 销售精英分红分发成功");
            return false;
        }
        $this->m_log->createCronLog("[fail] 销售精英分红分发失败");
        return false;

    }



    /*
    * 清空销售精英预发表
    */
    public function truncateTable(){
        $this->db->query("truncate table grant_pre_sales_executive_bonus");
    }


    /*添加遗漏数据*/
    public function omit_daily_elite_sql(){

        /*开始事务*/
        $this->db->trans_begin();

        $this->db->query("insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight,qualified_day) 
                          select a.uid,pro_set_amount,DATE_FORMAT(curdate(),'%Y%m%d') from (select uid,pro_set_amount 
                          from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
                          and pro_set_amount>0 and uid<>1380100217) a
                          LEFT JOIN daily_bonus_elite_qualified_list b on a.uid = b.uid where b.bonus_shar_weight is null"
        );

        /*事务结束*/
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $data = ['content'=>"添加遗漏初生成精英日分红发奖列表数据失败"];
            $this->tb_error_log->add_error_log($data);
            return false;
        }
        else
        {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 添加遗漏初生成精英日分红发奖列表数据成功.');
            return true;
        }

    }


    /*补发遗漏精英分红保存uid到遗漏分红表*/
    public function add_miss_bonus($timeDate,$item_type,$itemStart,$pagesize){
        $sql ="insert ignore into miss_bonus (uid,type) select a.uid,$item_type from daily_bonus_elite_qualified_list a
                left join users b on a.uid = b.id where b.status = 1 and a.qualified_day = $timeDate
                order by a.uid limit $itemStart,$pagesize";
        $this->db->query($sql);
        $this->m_log->createCronLog("[Success] 已保存到遗漏分红表");
    }


    /**
     * @add comment brady  补发五月之前的，补发五月之后的不会用到，跟拆表没关系。
     * 获取正常发放的用户ID 和 分红
     * @param $timeDate
     * @param $itemType
     * @return mixed
     */
    public function get_normal_last_month_grant_userId($timeDate,$itemType){
        //某月第一天 查出一个正常的用户
        $firstDate =date('Y-m-01 00:00:00', strtotime($timeDate));
        $endDate =date('Y-m-01 23:59:59', strtotime($timeDate));

        //当前的时间
        $firstCurrent =date('Y-m-d 00:00:00', strtotime($timeDate));
        $endCurrent =date('Y-m-d 23:59:59', strtotime($timeDate));

        $table = "cash_account_log_".date("Ym", strtotime($firstDate));
        $sql = "select uid,amount from $table where item_type=$itemType and create_time BETWEEN '".$firstCurrent."' and '".$endCurrent."' and uid =(";
        $sql.= "select uid from $table
                where item_type=$itemType and amount>0 and create_time BETWEEN '".$firstDate."' and '".$endDate."' 
                ORDER BY amount limit 1";
        $sql.= ") limit 1";
        $data = $this->db->force_master()->query($sql)->row_array();
        return $data;
    }


    /**
     * 获取用户某个时间段的权重
     * @param $uid
     * @param $timeDate
     * @param $flg
     * @return int
     */
    public function get_total_user_weight($uid,$timeDate,$flg){
        if($flg){
            $sale_amount = $this->db->force_master()->query("select bonus_shar_weight from daily_bonus_elite_qualified_list where uid = $uid")->row_array();
            if(!empty($sale_amount)){
                return $sale_amount['bonus_shar_weight'];
            }
        }

        $weightOne = $this->db->force_master()->query("select pro_set_amount from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add('".$timeDate."',interval -1 month),'%Y%m') 
                     and uid<>1380100217 and uid =$uid")->row_array();

        $weightTwo = $this->db->force_master()->query("select sale_amount from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add('".$timeDate."',interval -1 month),'%Y%m') 
                      and uid =$uid")->row_array();

        if(!empty($weightOne) && !empty($weightTwo)){
            return $weightOne['pro_set_amount']+$weightTwo['sale_amount'];
        }elseif(!empty($weightOne) && empty($weightTwo)){
            return $weightOne['pro_set_amount'];
        }elseif(!empty($weightTwo) && empty($weightOne)){
            return $weightTwo['sale_amount'];
        }else{
            return 0;
        }
    }


    /**
     * 每日销售精英分红参数记录
     * @param $totalSharNum
     * @param $totalWeight
     */
    public function add_grant_bonus_elite_every_param($totalSharNum,$totalWeight){
        $sql = "insert into grant_bonus_elite_every_param(total_shar_amount,total_weight) values (
                  $totalSharNum,$totalWeight
                )";
        $this->db->query($sql);
    }



    /**
     * 批量补发销售精英分红
     */
    public function batch_grant_bonus_elite_sql($dateTime,$table){
        $item_type = 24;
        $_row_data = $this->db->query("select total_shar_amount,total_weight from grant_bonus_elite_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') = '$dateTime'")->row_array();

        $total = $this->db->query("select count(1) from daily_bonus_elite_qualified_list a
                                    left join users b on a.uid = b.id 
                                    where uid not in (
                                        select uid from $table 
                                      where DATE_FORMAT(create_time,'%Y-%m-%d') = '$dateTime' and item_type =24 
                                    ) and b.status =1")->row_array();
        $page = 1000;
        $num = (int)$total>=$page?ceil($total/$page):1;

        for($i=0;$i<$num;$i++){
            $sql = "select a.uid,round(".$_row_data['total_shar_amount']."/".$_row_data['total_weight']."*a.bonus_shar_weight) money from daily_bonus_elite_qualified_list a
                left join users b on a.uid = b.id 
                where uid not in (
                  select uid from $table 
                  where DATE_FORMAT(create_time,'%Y-%m-%d') = '$dateTime' and item_type =24 
                ) and b.status =1 limit ".$i*$page.",".$page;
            $result = $this->db->query($sql)->result_array();
            if($this->o_bonus->assign_bonus_batch_fix($result,$item_type,$dateTime)){
                $this->m_log->createCronLog("[Success] 销售精英补发。 page: ".($i+1)." total : $num");
            }else{
                $this->m_log->createCronLog("[fail] 销售精英补发失败。 page: ".($i+1)." total : $num");
            }
        }
        echo 'ok';
    }

    
    /**
     * 抽回分红
     */
    /*public function revulsion_bonus_elite_grant($strId){
        $table = "cash_account_log_".date("Ym", strtotime(date("Y-m-d")));
        $sql = "SELECT uid,sum(amount) as amount from $table where uid in($strId)and item_type=24 GROUP BY uid";
        $res = $this->db->force_master()->query($sql)->result_array();
        foreach ($res as $v){
            $amount = $v['amount'];
            $uid = $v['uid'];
            $this->db->query("update user_comm_stat set daily_bonus_elite=daily_bonus_elite-$amount where uid = $uid");
            $this->db->query("update users set amount=amount-($amount/100) where id = $uid");
            $this->db->query("delete from $table where uid = $uid");
            $this->db->query("delete from daily_bonus_elite_qualified_list where uid = $uid");
            $this->m_log->createCronLog("[Success] 销售精英抽回用户".$v['uid']);
        }
        echo "ok";
    }*/


}