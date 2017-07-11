<?php

/**
 * User: Able
 * Date: 2017/4/5
 */
class o_fix_138_elite_commission extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("o_do_daily_elite");
        $this->load->model("o_new_138_bonus");
        $this->load->model('m_profit_sharing');
    }


    /**
     * 138 和 销售精英公共入口
     * @param $uid
     * @param $item_type
     * @param $start
     * @param $end
     */
    public function common_138_elite_interface($uid,$item_type,$start,$end){
        if($item_type == 2){
            $this->o_138_supply_again($uid,$item_type,$start,$end);
        }else if($item_type == 24){
            $this->o_elite_supply_again($uid,$item_type,$start,$end);
        }
    }

    /**
     * 138 和 销售精英公共入口
     * @param $uid
     * @param $item_type
     * @param $start
     * @param $end
     */
    public function common_138_elite_interface_new($uid,$item_type,$start,$end){
        if($item_type == 2){
            $this->o_138_supply_again_new($uid,$item_type,$start,$end);
        }else if($item_type == 24){
            $this->o_elite_supply_again($uid,$item_type,$start,$end);
        }
    }
    /**
     * 138 特殊补发
     * @param $uid
     * @param $item_type
     * @param $start
     * @param $end
     */
    private function o_138_supply_again($uid,$item_type,$start,$end){
        $this->load->model("tb_cash_account_log_x");
        $day_all = $this->getDayM($start, $end);
        $_date_time = strtotime("2017-05-01");
        if(!empty($day_all)) {
            for ($index = 0; $index < count($day_all); $index++) {
                //判断是否发过奖
                if(!$this->tb_cash_account_log_x->check_exits_by_day($uid,$item_type,$day_all[$index])){
                    $this->load->model('o_bonus');
                    if($_date_time<=strtotime($day_all[$index])){
                        $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();
                        //查询发奖参数
                        $sql = "select * from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') ='$day_all[$index]'";
                        $param = $this->db->force_master()->query($sql)->row_array();

                        $sql="select a.uid,round((((".$row["y"]."-c.y)-if(c.x>".$row["x"].",1,0))/".$param['total_shar_num']."*".$param['total_amount_other'].")+".$param['amount_avg'].",2) as money
                                   from users_store_sale_info_monthly a
                                   left join users b on a.uid=b.id
                                   LEFT JOIN user_coordinates c on a.uid = c.user_id
                                   where a.`year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and  b.user_rank in(1,2,3,5) and a.uid = $uid";

                        $result = $this->db->force_master()->query($sql)->row_array();

                        if(!$this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$result['money']]],$item_type,$day_all[$index])){
                            echo json_dump(array('success'=>false,'msg'=>"补发失败，请稍后再试"));
                        }

                    }else{
                        //补发补发获取Y轴一样的用户，返回该用户的分红
                        $money = $this->o_new_138_bonus->get_138_similarity_user($uid,$day_all[$index]);
                        $this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$money]],$item_type,$day_all[$index]);
                    }
                    $this->m_log->createCronLog("[Success] 138补发分红。uid:".$uid.' 补发时间:'.$day_all[$index]);

                }else{
                    $this->m_log->createCronLog("[fail] 138补发分红。uid:".$uid.'已补发过， 补发时间:'.$day_all[$index]);
                    echo json_dump(array('success'=>false,'msg'=>"该用户已补发过！"));
                }

            }
        }
    }
    
    /**
     * 138 特殊补发
     * @param $uid
     * @param $item_type
     * @param $start
     * @param $end
     */
    private function o_138_supply_again_new($uid,$item_type,$start,$end){
        $this->load->model("tb_cash_account_log_x");
        $this->load->model("tb_grant_bonus_user_logs");        
        $day_all = $this->getDayM($start, $end);
        $_date_time = strtotime("2017-05-01");
        if(!empty($day_all)) {
            for ($index = 0; $index < count($day_all); $index++) {
                //判断是否发过奖
                if(!$this->tb_cash_account_log_x->check_exits_by_day($uid,$item_type,$day_all[$index])){
                    $this->load->model('o_bonus');
                    if($_date_time<=strtotime($day_all[$index])){
                        $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();
                        //查询发奖参数
                        $sql = "select * from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') = '$day_all[$index]'";
                        $param = $this->db->force_master()->query($sql)->row_array();
    
                        $sql="select a.uid,round((((".$row["y"]."-c.y)-if(c.x>".$row["x"].",1,0))/".$param['total_shar_num']."*".$param['total_amount_other'].")+".$param['amount_avg'].",2) as money
                                   from users_store_sale_info_monthly a
                                   left join users b on a.uid=b.id
                                   LEFT JOIN user_coordinates c on a.uid = c.user_id
                                   where a.`year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and  b.user_rank in(1,2,3,5) and a.uid = $uid";
                          
                        $result = $this->db->force_master()->query($sql)->row_array();
                        if(!$this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$result['money']]],$item_type,$day_all[$index])){
                            
                            $user_data = array
                            (
                                'uid'  => $uid,
                                'proportion' => 0,
                                'share_point' => 0,
                                'amount' => $result['money'],
                                'bonus' => 0,
                                'type' => 0,
                                'item_type' => $item_type
                            );
                            $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                        }
    
                    }else{
                        //补发补发获取Y轴一样的用户，返回该用户的分红
                        $money = $this->o_new_138_bonus->get_138_similarity_user($uid,$day_all[$index]);
                        $this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$money]],$item_type,$day_all[$index]);
                        $user_data = array
                        (
                            'uid'  => $uid,
                            'proportion' => 0,
                            'share_point' => 0,
                            'amount' => $result['money'],
                            'bonus' => 0,
                            'type' => 1,
                            'item_type' => $item_type
                        );
                        $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                    }                    
                }    
            }
        }
    }


    /**
     * 销售精英 特殊补发
     * @param $uid
     * @param $item_type
     * @param $start
     * @param $end
     */
    private function o_elite_supply_again($uid,$item_type,$start,$end){
        $this->load->model("tb_cash_account_log_x");
        $this->load->model('o_bonus');
        $day_all = $this->getDayM($start, $end);
        $_date_time = strtotime("2017-05-01");
        if(!empty($day_all))
        {
            //检查分红是否发放
            for($index = 0; $index < count($day_all); $index++)
            {
                if(!$this->tb_cash_account_log_x->check_exits_by_day($uid,$item_type,$day_all[$index])){
                    $item_type = 24;
                    if($_date_time<=strtotime($day_all[$index])){
                        $_row_data = $this->db->force_master()->query("select total_shar_amount,total_weight from grant_bonus_elite_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') = '$day_all[$index]'")->row_array();

                        $pro_set_amount = $this->db->force_master()->query("select pro_set_amount 
from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and  uid<>1380100217 and uid=$uid")->row_array();

                        $pro_set_amount = empty($pro_set_amount)?0:$pro_set_amount['pro_set_amount'];

                        $sale_amount = $this->db->force_master()->query("select sale_amount 
from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and uid=$uid")->row_array();

                        $sale_amount = empty($sale_amount)?0:$sale_amount['sale_amount'];
                        $bonus_shar_weight = $pro_set_amount+$sale_amount;
                        $money = round($_row_data['total_shar_amount']/$_row_data['total_weight']*$bonus_shar_weight);
                        
                        if(!$this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$money]],$item_type,$day_all[$index])){
                            echo json_dump(array('success'=>false,'msg'=>"补发失败，请稍后再试"));
                        }

                    }else{
                        //查询一个正常用户的ID 和 分红
                        $rowUid = $this->o_do_daily_elite->get_normal_last_month_grant_userId($day_all[$index],$item_type);

                        //查询一个正常用户的权重
                        $userWeight = $this->o_do_daily_elite->get_total_user_weight($rowUid['uid'],$day_all[$index],0);

                        //获取补发用户的权重
                        $currentWeight = $this->o_do_daily_elite->get_total_user_weight($uid,$day_all[$index],1);

                        $money = round($currentWeight/$userWeight * $rowUid['amount']);

                        $this->o_bonus->assign_bonus_batch_fix([['uid'=>$uid,'money'=>$money]],$item_type,$day_all[$index]);
                    }
                    $this->m_log->createCronLog("[Success] 销售精英补发分红。uid:".$uid.' 补发时间:'.$day_all[$index]);

                }else{
                    $this->m_log->createCronLog("[Success] 销售精英补发分红。uid:".$uid.'已补发过， 补发时间:'.$day_all[$index]);
                    echo json_dump(array('success'=>false,'msg'=>"该用户已补发过！"));
                }
            }
        }


    }

    /**
     * 销售精英返回需要补发的数据
     * @param $uid
     * @param $start
     * @param $end
     */
    public function o_elite_supply_data($uid,$start,$end){
        $day_all = $this->getDayM($start, $end);
        $_date_time = strtotime("2017-05-01");
        $returnData = array();
        if(!empty($day_all))
        {
            for($index = 0; $index < count($day_all); $index++)
            {
                    $item_type = 24;
                    if($_date_time<=strtotime($day_all[$index])){
                        $_row_data = $this->db->force_master()->query("select total_shar_amount,total_weight from grant_bonus_elite_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') = '$day_all[$index]'")->row_array();
                        $pro_set_amount = $this->db->force_master()->query("select pro_set_amount 
from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m')  and  uid<>1380100217 and uid=$uid")->row_array();
                        $pro_set_amount = empty($pro_set_amount)?0:$pro_set_amount['pro_set_amount'];

                        $sale_amount = $this->db->force_master()->query("select sale_amount 
from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and uid=$uid")->row_array();

                        $sale_amount = empty($sale_amount)?0:$sale_amount['sale_amount'];
                        $bonus_shar_weight = $pro_set_amount+$sale_amount;
                        $money = round($_row_data['total_shar_amount']/$_row_data['total_weight']*$bonus_shar_weight);

                    }else{
                        //查询一个正常用户的ID 和 分红
                        $rowUid = $this->o_do_daily_elite->get_normal_last_month_grant_userId($day_all[$index],$item_type);

                        //查询一个正常用户的权重
                        $userWeight = $this->o_do_daily_elite->get_total_user_weight($rowUid['uid'],$day_all[$index],0);

                        //获取补发用户的权重
                        $currentWeight = $this->o_do_daily_elite->get_total_user_weight($uid,$day_all[$index],1);

                        $money = round($currentWeight/$userWeight * $rowUid['amount']);
                    }
                    $returnData[] = ['uid'=>$uid,'money'=>$money,"time"=>$day_all[$index]];
            }
        }

        return $returnData;
    }


    public function o_138_supply_data($uid,$start,$end){
        $this->load->model("tb_cash_account_log_x");
        $day_all = $this->getDayM($start, $end);
        $_date_time = strtotime("2017-05-01");
        if(!empty($day_all)) {
            for ($index = 0; $index < count($day_all); $index++) {
                    $this->load->model('o_bonus');
                    if($_date_time<=strtotime($day_all[$index])){

                        if(strtotime("2017-07-01")<=time()){
                            $row = $this->db->query("select x,y from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y-%m-%d')='$day_all[$index]' GROUP BY DATE_FORMAT(create_time,'%Y%m%d')")->row_array();
                        }else{
                            $row = $this->db->select("x,y")->from("user_coordinates")->force_master()->order_by("id","desc")->limit(1)->get()->row_array();
                        }

                        //查询发奖参数
                        $sql = "select * from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y-%m-%d') = '$day_all[$index]'";
                        $param = $this->db->force_master()->query($sql)->row_array();

                        $sql="select a.uid,round((((".$row["y"]."-c.y)-if(c.x>".$row["x"].",1,0))/".$param['total_shar_num']."*".$param['total_amount_other'].")+".$param['amount_avg'].") as money
                                   from users_store_sale_info_monthly a
                                   left join users b on a.uid=b.id
                                   LEFT JOIN user_coordinates c on a.uid = c.user_id
                                   where a.`year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and  b.user_rank in(1,2,3,5) and a.uid = $uid";

                        $result[]= $this->db->force_master()->query($sql)->row_array();
                    }else{
                        //补发补发获取Y轴一样的用户，返回该用户的分红
                        $amount = $this->o_new_138_bonus->get_138_similarity_user($uid,$day_all[$index]);
                        $result[] = ['uid'=>$uid,'money'=>$amount];
                    }
            }
            return $result;
        }
    }


    /*验证用户是否发放分红*/
   /* private function check_exits($uid,$item_type,$day_all){
        $_date_time = strtotime("2017-05-01");
        $_now_time = strtotime($day_all);
        if($_date_time<=$_now_time){
            $week_team_tb_name = get_cash_account_log_table($uid,date("Ym", strtotime($day_all)));
        }else{
            $week_team_tb_name = "cash_account_log_".date("Ym", strtotime($day_all));
        }
        $sql = "SELECT id,amount FROM ".$week_team_tb_name." WHERE uid=".$uid." AND item_type=".$item_type. " AND  date_format(create_time,'%Y-%m-%d')='".$day_all."' LIMIT 1";
        $return_value = $this->db->query($sql)->row_array();
        if(!empty($return_value) && $return_value['amount']>0){
            return true;
        }
        else {
            return false;
        }
    }*/


    /***
     * 获取某一时间段的日期列表
     * @param 开始时间  $startdate
     * @param 结束时间  $enddate
     * @return multitype:multitype:string
     */
    private function getDayM($startdate,$enddate)
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


    /**
     * 补发获取昨日利润
     * @param $dateTime
     * @return mixed
     */
    public function getYesterdayProfit($dateTime){
        $yesterdayProfit = $this->db->query("select money from company_money_today_total where create_time = DATE_FORMAT(date_add('".$dateTime."',interval -1 day),'%Y%m%d')")->row_array();
        return $yesterdayProfit;
    }

}