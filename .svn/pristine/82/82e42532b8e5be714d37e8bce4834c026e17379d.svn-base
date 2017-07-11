<?php

/**
 * User: Able
 * Date: 2017/3/23
 * Time: 9:44
 */
class o_new_138_bonus extends MY_Model
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 每月初生成新的138发奖列表
     */
    public function new_138_bonus_list(){
        /*开始事务*/
        $this->db->trans_begin();
        /*清空上个月拿奖的人员*/
        $this->db->query("truncate table user_qualified_for_138");

        /*创建临时表，筛选出本月拿奖人员并插入发奖列表（上月订单合格的会员）*/
        /*$this->db->query("CREATE TEMPORARY TABLE tmp_user_qualified_table  select a.uid,b.user_rank,a.sale_amount from
                          users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`=
                          DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=5000 and b.user_rank in(1,2,3,5)");*/

        /*创建临时表，取出X,Y数据插入*/
        /*$this->db->query("CREATE TEMPORARY TABLE tmp_bonus_table select a.uid,a.user_rank,a.sale_amount,c.x,c.y from tmp_user_qualified_table a
                          LEFT JOIN user_coordinates c on  a.uid = c.user_id");*/

        /*更新合格表中的x,y*/
        //$this->db->query("insert ignore into user_qualified_for_138(user_id,user_rank,sale_amount,x,y) select * from tmp_bonus_table");
        $this->db->query("insert ignore into user_qualified_for_138(user_id,user_rank,sale_amount,x,y) 
        select a.uid,b.user_rank,a.sale_amount,c.x,c.y 
        from users_store_sale_info_monthly a 
        left join users b on a.uid=b.id 
        LEFT JOIN user_coordinates c on a.uid = c.user_id
        where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=5000 and b.user_rank in(1,2,3,5)");


        /*事务结束*/
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[Fail] 每月初生成新的138发奖列表.');
            return false;
        }
        else
        {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 每月初生成新的138发奖列表.');
            return true;
        }
    }

    /**
     * 修复会员业绩后，生成新的138日分红奖队列
     */
    public function user_138_bonus_qualified_list(){
        /*开始事务*/
        $this->db->trans_begin();
        /*清空上个月拿奖的人员*/
        $this->db->query("truncate user_138_bonus_qualified_list");    
      
        $this->db->query("insert ignore into user_138_bonus_qualified_list(user_id,user_rank,sale_amount,x,y)
        select a.uid,b.user_rank,a.sale_amount,c.x,c.y
        from users_store_sale_info_monthly a
        left join users b on a.uid=b.id
        LEFT JOIN user_coordinates c on a.uid = c.user_id
        where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=5000 and b.user_rank in(1,2,3,5)");
    
    
        /*事务结束*/
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->m_log->createCronLog('[Fail] 每月初生成新的138发奖列表.');
            return false;
        }
        else
        {
            $this->db->trans_commit();
            $this->m_log->createCronLog('[Success] 每月初生成新的138发奖列表.');
            return true;
        }
    }
    

    /**
     * 为138分红准备数据(正式发放)
     * @return array
     */
    public function new_138_shar_prepare(){
        /*统计138分红总人数*/
        $totalNum = $this->db->from("user_qualified_for_138")->force_master()->count_all_results();

        /*获取138矩阵的最后一个坐标*/
        $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();

        /*根据矩阵最后一个坐标计算每个id矩阵底下的人数，并将数据存入临时表*/
        $this->db->query("insert ignore into 138_grant_tmp(uid,num_share) select a.user_id,(".$row["y"]."-a.y)-if(a.x>".$row["x"].",1,0) from user_qualified_for_138 a
            left join users b on a.user_id = b.id where b.status = 1
         ");


        /*统计发奖临时表中所有人底下人数的总和*/
        $totalSharNum = $this->db->select_sum("num_share")->from("138_grant_tmp")->force_master()->get()->row_array();
        $data = ["totalNum"=>$totalNum,"totalSharNum"=>$totalSharNum['num_share'],"x"=>$row['x'],"y"=>$row['y']];
        return $data;
    }


    /**
     * 为138分红准备数据 发放到临时表
     * @return array
     */
    public function new_138_shar_prepare2(){
        /*统计138分红总人数*/
        $totalNum = $this->db->from("user_qualified_for_138")->force_master()->count_all_results();

        /*获取138矩阵的最后一个坐标*/
        $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();

        /*根据矩阵最后一个坐标计算每个id矩阵底下的人数，并将数据存入临时表*/
        $this->db->query("insert ignore into 138_grant_tmp2(uid,num_share) select user_id,(".$row["y"]."-y)-if(x>".$row["x"].",1,0) from user_qualified_for_138");

        /*统计发奖临时表中所有人底下人数的总和*/
        $totalSharNum = $this->db->select_sum("num_share")->from("138_grant_tmp2")->force_master()->get()->row_array();
        $data = ["totalNum"=>$totalNum,"totalSharNum"=>$totalSharNum['num_share'],"x"=>$row['x'],"y"=>$row['y']];
        return $data;
    }



    /*分页发放138 预存表*/
    public function do138SharPage($pageSize,$averageMoney,$totalAmountOther,$totalSharNum,$page=0){

        $itemStart = ($page-1)*$pageSize;
        //查出符合的用户插入预发表
        $sql = "insert ignore into grant_pre_138_bonus (uid,amount,create_time) 
                select uid,round((num_share/$totalSharNum*$totalAmountOther),2)+$averageMoney,unix_timestamp(now()) from 138_grant_tmp
                limit $itemStart,$pageSize ";
        $this->db->query($sql);
    }


    /*
     * 清空138预发表
     */
    public function truncateTable(){
        $this->db->query("truncate table grant_pre_138_bonus");
    }



    /**
     * 发放138分红
     * @return mixed
     */
    public function get_138_shar_grant_pre(){

        $totalNum = $this->db->from("grant_pre_138_bonus")->force_master()->count_all_results();
        $tmp_total = $this->query_138_tmp_count();
        if($totalNum != $tmp_total){
            $this->m_log->createCronLog("[fail] 138数据不匹配,停止实际发奖");
            return false;
        }

        $page = 10000;
        $item_type = 2; //发奖类型
        $total = $this->db->from("grant_pre_138_bonus")->force_master()->count_all_results();
        $num = (int)$total>=$page?ceil($total/$page):1;
        @$this->db->reconnect();
        if($num >0){
            for($i=0;$i<$num;$i++){
                $sql = "select uid,amount as money from grant_pre_138_bonus limit ".$i*$page.",".$page;
                $list = $this->db->force_master()->query($sql)->result_array();
                if(empty($list)){
                    $data = ['content'=>"没有138分红预发数据。"];
                    $this->tb_error_log->add_error_log($data);
                    return false;
                }
                if($this->o_bonus->assign_bonus_batch($list,$item_type)){
                    $this->m_log->createCronLog("[Success] 138分红分发。 page: ".($i+1)." total : $num");
                    $this->delete_138_grant_tmp($list);
                }else{
                    $this->m_log->createCronLog("[fail] 138分红分发失败。 page: ".($i+1)." total : $num");
                }
                //多线程
                //$this->o_pcntl->tps_pcntl_wait('$this->o_bonus->assign_bonus_batch(\''.$list.'\',\''.$item_type.'\');');//用子进程处理每一页

            }
            $this->m_log->createCronLog("[Success] 138分红分发放完成");
            return true;

        }
        $this->m_log->createCronLog("[fail] 138分红分发放失败");
        return false;
    }


    /**
     *  补发获取Y轴一样的用户，返回该用户的分红
     * @param $uid
     * @return array
     */
    public function get_138_similarity_user($uid,$dateTime){
        $table = get_cash_account_log_table($uid,date("Ym", strtotime($dateTime)));
        //$table = "cash_account_log_".date("Ym", strtotime($dateTime));
        $sql ="select a.amount from $table a left join user_coordinates b on a.uid = b.user_id
        where item_type =2 and date_format(a.create_time,'%Y-%m-%d') = '".$dateTime."'
        and b.y=(
            select c.y from users_store_sale_info_monthly a left join user_coordinates c on a.uid = c.user_id where a.uid=$uid limit 1
        ) limit 1";
       $row = $this->db->force_master()->query($sql)->row_array();
       return $row['amount'];

    }

    /**
     * 计算出补发用户的分红
     * @param $uid
     * @param $totalSharNum
     * @param $totalAmountOther
     * @param $averageMoney
     * @return mixed
     */
    public function get_user_138_fix_bonus($uid,$totalSharNum,$totalAmountOther,$averageMoney){
        $sql="select a.uid,round((a.num_share/$totalSharNum*$totalAmountOther),2)+$averageMoney as amount from 138_grant_tmp a 
              left join users b on a.uid = b.id where a.uid=$uid and b.status = 1";
        $amount = $this->db->force_master()->query($sql)->row_array();
        return $amount;
    }


    /**
     * 每日138分红参数记录
     * @param $totalSharNum
     * @param $totalAmountOther
     * @param $averageMoney
     * @param $x
     * @param $y
     */
    public function add_grant_bonus_138_every_param($totalSharNum,$totalAmountOther,$averageMoney,$x,$y){
        $sql = "insert into grant_bonus_138_every_param(total_shar_num,total_amount_other,amount_avg,x,y) values (
                  $totalSharNum,$totalAmountOther,$averageMoney,$x,$y
                )";
        $this->db->query($sql);
    }


    /**
     * 删除138_grant_tmp表中分红用户
     */
    public function delete_138_grant_tmp($list){
        $arrId = [];
        foreach($list as $k=>$v){
            $arrId[] = $v['uid'];
        }
        $str = implode(",",$arrId);
        $sql = "delete from 138_grant_tmp where uid in ($str)";
        $this->db->query($sql);
    }


    /**
     * 138 抽回分红
     */
  /*  public function revulsion_138_grant(){
        $table = "cash_account_log_".date("Ym", strtotime(date("Y-m-d")));
        //查询不满足的用户
        $sql = "select uid,amount from $table where DATE_FORMAT(create_time,'%Y%m%d') = DATE_FORMAT(curdate(),'%Y%m%d') and item_type=2 and uid in (
                  select uid from 138_grant_tmp where uid not in (select user_id from user_qualified_for_138)
                )";
        $res = $this->db->force_master()->query($sql)->result_array();
        foreach ($res as $v){
            $amount = $v['amount'];
            $uid = $v['uid'];
            $this->db->query("update user_comm_stat set 138_bonus=138_bonus-$amount where uid = $uid");
            // $this->db->query("update user_comm_stat_month set 138_bonus=138_bonus-$amount where uid = $uid and create_time = DATE_FORMAT(curdate(),'%Y%m')");
            $this->db->query("update users set amount=amount-($amount/100) where id = $uid");
            $this->db->query("delete from $table where uid = $uid");
            $this->db->query("delete from 138_grant_tmp where uid = $uid");
            $this->m_log->createCronLog("[Success] 138抽回用户".$v['uid']);
        }
        echo "ok";
    }*/


    /**
     * 批量补138分红
     */
   /* public function batch_138_grant(){
        $item_type = 2;
        $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();
        //查询发奖参数
        $sql = "select * from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y%m%d') = '20170501'";
        $param = $this->db->force_master()->query($sql)->row_array();

        $table = "cash_account_log_".date("Ym", strtotime(date("Y-m-d")));

        $sql="select count(1) as total from user_qualified_for_138 a
	LEFT JOIN cash_account_log_201705 b on (a.user_id=b.uid and b.item_type =2)
	where b.uid is null";
        $total = $this->db->force_master()->query($sql)->row_array();
        $page = 10000;
        $num = (int)$total['total']>=$page?ceil($total['total']/$page):1;
        if($num >0){
            for($i=0;$i<$num;$i++){
                $sql="select a.user_id as uid,round((((".$row["y"]."-y)-if(x>".$row["x"].",1,0))/".$param['total_shar_num']."*".$param['total_amount_other'].")+".$param['amount_avg'].",2) as money
                from user_qualified_for_138 a
                LEFT JOIN $table b on (a.user_id=b.uid and b.item_type =2)
                where b.uid is null  limit ".$i*$page.",".$page;
                $result = $this->db->force_master()->query($sql)->result_array();
                if($this->o_bonus->assign_bonus_batch($result,$item_type)){
                    $this->m_log->createCronLog("[Success] 138 补发。 page: ".($i+1)." total : $num");
                    $this->delete_138_grant_tmp($result);
                }else{
                    $this->m_log->createCronLog("[fail] 138 补发失败。 page: ".($i+1)." total : $num");
                }
            }
        }

    }*/

    /**
     * 查询138_grant_tmp 是否被清空
     */
    public function query_138_tmp_count(){
        $totalNum = $this->db->from("138_grant_tmp")->force_master()->count_all_results();
        return $totalNum;
    }


}