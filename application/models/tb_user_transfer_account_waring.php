<?php
/**
 * @author Terry
 */
class tb_user_transfer_account_waring extends MY_Model {
    public $table_name = 'user_transfer_account_waring';
    function __construct() {
        parent::__construct();
    }

	public function user_transfer_account_check($str_time,$end_time,$fx=1)
	{
        $sql = "SELECT `log`.`uid`,`user`.`user_rank` as user_rank FROM (`cash_take_out_logs` AS log) 
            JOIN `users` AS `user` ON `log`.`uid` = `user`.`id` WHERE  
            `log`.`create_time` >= '".$str_time."' AND `log`.`create_time` <= '".$end_time." 23:59:59' AND 
            `log`.`amount` >= 0  GROUP BY `uid` ";

        $query = $this->db->query($sql);
        $ret_value = $query->result_array();
        if(!empty($ret_value))
        {
            
            foreach ($ret_value as $sult)
            {
                //查询每个用户的所有转账记录
                for($year = 2016;$year < 2020;$year++)
                {
                    if($year<=date("Y"))
                    {
                        for($month=1;$month <=12;$month++)
                        {
                            if($year==2016)
                            {
                                if($month<6)
                                {
                                  continue;
                                }                                
                            }
                            if($year>=date("Y")&&$month>date("m"))
                            {
                                continue;
                            }                            
                            $tag = 1;
                            $user_sql="";
                            if($year==2016&&$month>=7)
                            {
                                $tag = 1;
                                $table_name = "cash_account_log_".$year.str_pad($month,2,0,STR_PAD_LEFT);
                                $user_sql = "select uid,amount,ABS(amount) as money, create_time,related_uid from ".$table_name." where uid=".$sult['uid']." and item_type=11 ";
                            }
                            else if($year>2016)
                            {
                                $tag = 1;
                                $table_name = "cash_account_log_".$year.str_pad($month,2,0,STR_PAD_LEFT);
                                $user_sql = "select uid,amount,ABS(amount) as money, create_time,related_uid from ".$table_name." where uid=".$sult['uid']." and item_type=11 ";
                            }
                            else
                            {
                                $tag = 2;
                                $table_name = "commission_logs";
                                $user_sql = "select uid,amount,ABS(amount*100) as money, create_time,pay_user_id as related_uid  from ".$table_name." where uid=".$sult['uid']." and type=11 ";
                            }
                            if($fx == 1)
                            {
                                $user_sql .= " and amount < 0";
                            }
                            else
                            {
                                $user_sql .= " and amount > 0";
                            }
                            
                            $user_query = $this->db->query($user_sql);
                            $user_list = $user_query->result_array();
                            
                            if(!empty($user_list))
                            {                               
                                foreach($user_list as $u_list)
                                {
                                    $check_sql = "";
                                    $add_log_sql = "";
                                    if( $tag == 1)
                                    {
                                        $check_sql = "select uid from ".$table_name." where uid=".$u_list['related_uid']." and ABS(amount)=ABS(".$u_list['amount'].") and related_uid=".$u_list['uid'];
                                        $add_log_sql = "insert into ".$table_name." set uid=".$u_list['related_uid']." ,amount= 0 - ".$u_list['amount'].", item_type = 11,related_uid=".$u_list['uid'].",create_time='".$u_list['create_time']."'";
                                    }
                                    else
                                    {
                                        $check_sql = "select uid from ".$table_name." where uid=".$u_list['related_uid']." and ABS(amount)=ABS(".$u_list['amount'].") and pay_user_id=".$u_list['uid'];
                                        $add_log_sql = "insert into ".$table_name." set uid=".$u_list['related_uid']." ,amount= 0 - ".$u_list['amount'].", type = 11,pay_user_id=".$u_list['uid'].",create_time='".$u_list['create_time']."'";
                                    }                                    
                                    $check_query = $this->db->query($check_sql);
                                    $check_value = $check_query->row_array();
                                   
                                    if(empty($check_value))
                                    {
                                        $log_extis_sql = "select uid from user_transfer_account_waring where transfer_time = '".$u_list['create_time']."' limit 1";
                                        $log_extis_query = $this->db->query($log_extis_sql);
                                        $log_extis_value = $log_extis_query->row_array();
                                        if(empty($log_extis_value))
                                        {                                            
                                            
                                            if($sult['user_rank']==4 || ($u_list['money']/100)>=500)
                                            {
                                                //免费用户或者转账金额大于100美金的转账记录，只做记录，不修复
                                                $log_sql = "insert into user_transfer_account_waring set uid=".$u_list['uid'].",amount=".$u_list['amount'].",relate_uid=".$u_list['related_uid'].",transfer_time='".$u_list['create_time']."',create_time = Now()";
                                                $this->db->query($log_sql);
                                            }
                                            else 
                                            {
                                                $this->db->query($add_log_sql);
                                            }
                                        }                                        
                                    }
                                }
                            }
                        }
                    }
                    else 
                    {
                        break;
                    }
                }
            }            
        }        
	}

    /**
     * 检查用户转账记录
     * @param string $start_time
     * @param string $end_time
     * @return bool
     * @author carl.zhou
     */
	public function check_user_transfer_account($start_time='', $end_time='') {
        //获取有提现记录的用户id
        if (empty($start_time) && empty($end_time)) {
            $start_time = date('Y-m-d 00:00:00', strtotime('-1 day'));
            $end_time = date('Y-m-d 23:59:59', strtotime('-1 day'));
        } else {
            $start_time = date('Y-m-d 00:00:00', strtotime($start_time));
            $end_time = date('Y-m-d 23:59:59', strtotime($end_time));
        }
        $sql = "SELECT uid from cash_take_out_logs WHERE 1";
        $sql .= " and create_time>='".$start_time."'";
        $sql .= " and create_time<='".$end_time."'";
        $sql .= " group by uid";
        $query = $this->db->query($sql);
        $idList = $query->result_array();

        $num = 0;
        $curr_year = date('Ym');
        foreach($idList as $uidInfo) {
            $uid = $uidInfo['uid'];
            $year_month=201607;
            $year_month=201707;
            while($year_month<=$curr_year) {
                //获取用户在时间段内的所有转账记录
                if ($year_month>=201707) {    //月分表的同时按id分表
                    $sql = "select max(id) as max_id from users limit 1";
                    $res = $this->db->query($sql)->row_array();
                    $max_id = $res['max_id'];
                    $max_pre = substr($max_id, 0, 4);
                    $userList = array();
                    for ($start=1380; $start<=$max_pre; $start++) {
                        $table_name = get_cash_account_log_table($start, $year_month);
                        $user_sql = "select uid,amount,ABS(amount) as money, create_time,related_uid from ".$table_name." where (uid=".$uid." or related_uid=".$uid.") and item_type=11 ";
                        $query = $this->db->query($user_sql);
                        $tempList = $query->result_array();
                        $userList = array_merge($userList, $tempList);
                    }
                } elseif ($year_month>=201607) {    //开始月分表
                    $table_name = get_cash_account_log_table($uid, $year_month);
                    $user_sql = "select uid,amount,ABS(amount) as money, create_time,related_uid from ".$table_name." where (uid=".$uid." or related_uid=".$uid.") and item_type=11 ";
                    $query = $this->db->query($user_sql);
                    $userList = $query->result_array();
                } else {
                    $table_name = "commission_logs";
                    $user_sql = "select uid,amount*100 as amount,ABS(amount*100) as money, create_time,pay_user_id as related_uid from ".$table_name." where (uid=".$uid." or related_uid=".$uid.") and type=11 ";
                    $query = $this->db->query($user_sql);
                    $userList = $query->result_array();
                }
                
                $year_month = date('Ym', strtotime('+1 month',strtotime($year_month.'01')));

                $compare = array();
                foreach($userList as $info) {   //逐条比对转账信息
                    $key = $info['amount']>0 ? $info['uid'].'_'.$info['related_uid'].'_'.$info['money'] : $info['related_uid'].'_'.$info['uid'].'_'.$info['money'];
                    if (array_key_exists($key, $compare)) { //如果存在相应金额的转账记录
                        $resultArray = $compare[$key];
                        $checkFlag = false;
                        foreach ($resultArray as $index=>$val) {
                            if ( ($info['amount'] + $val['amount'])==0 ) {  //找到对应的转账记录后跳出
                                unset($resultArray[$index]);
                                $checkFlag = true;
                                break;
                            }
                        }
                        if (!$checkFlag) {   //没到对应转账记录，即出现多条相同用户间同向同金额的转账
                            $resultArray[] = $info;
                        }
                        if (empty($resultArray)) {
                            unset($compare[$key]);
                        } else {
                            $compare[$key] = $resultArray;
                        }
                    } else {
                        $compare[$key] = array($info);
                    }
                }
                foreach ($compare as $infoList) {
                    foreach ($infoList as $info) {
                        if ($this->checkUserTransferAccountWarningExists($info['uid'], $info['amount'], $info['related_uid'], $info['create_time'])) {
                            continue;
                        }
                        if (!$this->createUserTransferAccountWarning($info['uid'], $info['amount'], $info['related_uid'], $info['create_time'])) {
                            return false;
                        }
                        $num++;
                    }
                }

            }
        }
        echo "create ".$num." logs\n";
    }

    /**
     * 检查转账异常记录是否存在
     * @param $uid
     * @param $amount
     * @param $relate_uid
     * @param $transfer_time
     * @return bool
     * @author carl.zhou
     */
    public function checkUserTransferAccountWarningExists($uid, $amount, $relate_uid, $transfer_time){
        $uid = intval($uid);
        $amount = intval($amount);
        $relate_uid = intval($relate_uid);
        $data = array(
            'uid' => intval($uid),
            'amount' => intval($amount),
            'relate_uid' => intval($relate_uid),
            'transfer_time' => trim($transfer_time),
        );

        $res = $this->db->from($this->table_name)->where($data)->get()->num_rows();
        return $res>0 ? true : false;
    }

    /**
     * 创建转账异常记录
     * @param $uid
     * @param $amount
     * @param $relate_uid
     * @param $transfer_time
     * @return bool
     * @author carl.zhou
     */
    public function createUserTransferAccountWarning($uid, $amount, $relate_uid, $transfer_time){
        $uid = intval($uid);
        $amount = intval($amount);
        $relate_uid = intval($relate_uid);
        $data = array(
            'uid' => intval($uid),
            'amount' => intval($amount),
            'relate_uid' => intval($relate_uid),
            'transfer_time' => trim($transfer_time),
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert($this->table_name, $data);
        return $res ? true : false;
    }
	
	public function get_waring_all($filter,$perPage = 10)
	{
	    $sql = "select * from user_transfer_account_waring limit ". ($filter['page']-1) * $perPage . " ,".$perPage;	    
	    $query = $this->db->query($sql);
	    return $query->result_array();
	}

    /**
     * 获取异常记录列表
     * @param $where
     * @param int $num
     * @param bool $master
     * @return mixed
     * @author carl.zhou
     */
    public function getUserTransferAccountWarningList($where, $num = 10, $master = false){
        $start = ($where['page'] - 1) * $num;
        $db = $this->getDbPrepare($where, $master);

        $res = $db->limit($num, $start)->get()->result_array();
        return $res;
    }

    /**
     * 获取异常记录数量
     * @param $where
     * @param bool $master
     * @return mixed
     * @author carl.zhou
     */
    public function getUserTransferAccountWarningNum($where,$master = false){
        $db = $this->getDbPrepare($where, $master);
        return $db->count_all_results();
    }

	public function get_waring_total()
	{
	    $sql = "select count(*) as total from user_transfer_account_waring";
	    $query = $this->db->query($sql);
	    return $query->row_array()['total'];
	}

    /**
     * 获取数据库实例
     * @param array $where
     * @param bool $master 是否使用主库 true 是 false 否
     * @return mixed
     */
    private function getDbPrepare($where = [], $master = false){
        $db = $this->db->from($this->table_name);

        if (is_array($where)) {
            if ($master) {
                $db->force_master();
            }

            if (!empty($where['t_stime'])) {
                $db->where('transfer_time >=', date('Y-m-d 00:00:00', strtotime($where['t_stime'])));
            }
            if (!empty($where['t_end'])) {
                $db->where('transfer_time <=', date('Y-m-d 23:59:59', strtotime($where['t_end'])));
            }
            if ($where['fx']==1) {
                $db->where('amount <', 0);
            }
            if ($where['fx']==2) {
                $db->where('amount >', 0);
            }
        }
        if ($master) {
            $db->force_master();
        }
        return $db;
    }
	
}