<?php
/**
 * @author Terry
 */
class tb_commission_logs extends CI_Model {

    const TIME_NODE_OLD = '201606';
    protected $slave_flag = false;//是否使用从库
    function __construct() {
        parent::__construct();
        $db_slave = config_item('db_slave');
        if(!empty($db_slave)) {
            $this->slave_flag = true;
        } else {
            $this->slave_flag = false;
        }
    }

    /**
     * 获取某个用户转帐给别人的总金额
     */
    public function getUserTransferAmountOld($uid){

        $res = $this->db->select_sum('amount')->from('commission_logs')->where('uid',$uid)->where('type',11)->where('amount <','0')->get()->row_object();
        if($res){
            $return = abs(tps_int_format($res->amount*100));
        }else{
            $return = 0;
        }

        return $return;
    }

    /**
     * 分页获取资金变动报表
     * @param $search array(
        'uid'=>用户id,
        'item_type'=>佣金类型,
        'item_type in'=>佣金类型,
        'order_id'=>订单号,
        'start_time'=>开始时间,
        'end_time'=>开始时间,
        'pay_user_id'=>关联人id,
     )
     */
    public function getCommLogsByPage($search,$page,$leftNum,&$coordinate){
        if ($this->slave_flag) {
            $db = 'db_slave';
        } else {
            $db = "db";
        }
        $this->$db->select('id,uid,type item_type,amount * 100 amount,order_id,create_time,pay_user_id related_uid,related_id')->from('commission_logs');
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $this->$db->where('create_time >=',$v);
            }elseif($k=='end_time'){
                $this->$db->where('create_time <',date('Y-m-d H:i:s',strtotime($v)+86400-1));
            }elseif($k=='item_type'){
                $this->$db->where('type',$v);
            }elseif($k=='item_type in'){
                $this->$db->where_in('type',$v);
            }elseif($k=='related_uid'){
                $this->$db->where('pay_user_id',$v);
            }else{
                $this->$db->where($k,$v);
            }
        }
        $res = $this->$db->order_by("create_time", "desc")->limit($leftNum, ($page - 1) * 10 + $coordinate)->get()->result_array();
        
        if($coordinate>0){
            $coordinate==0;
        }

        return $res;
    }

    public function getCommLogs($search,$select='*',$getOne=false,$sort='asc'){
        if ($this->slave_flag) {
            $db = 'db_slave';
        } else {
            $db = "db";
        }
        if($select=='*'){
            $sql = "select id,uid,type item_type,amount * 100 amount,order_id,create_time,pay_user_id related_uid,'commission_logs' tb_name from commission_logs";
        }else{
            $sql = 'select '.$select.' from commission_logs';
        }
        if (isset($search['not_use_uid']) && $search['not_use_uid'] == true) {
            unset($search['uid']);
            unset($search['not_use_uid']);
        }
        $where = '';
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $where.=' and create_time >="'.$v.'"';
            }elseif($k=='end_time'){
                $where.=' and create_time <"'.$v.'"';
            }elseif($k=='item_type'){
                $where.=' and `type` ='.$v;
            }elseif($k=='item_type in'){
                $where.=' and `type` in('.implode($v,',').')';
            }elseif($k=='related_uid'){
                $where.=' and pay_user_id ='.$v;
            }else{
                //$this->$db->where($k,$v);
                $where.=' and '.$k.' ="'.$v.'"';
            }
        }
        $sql.=$where?' where 1=1'.$where:'';
        $sql.=' order by create_time '.$sort.',id '.$sort;
        if($getOne){
            $res = $this->$db->query($sql)->row_array();
        }else{
            $res = $this->$db->query($sql)->result_array();
        }

        return $res;
    }

    /**
     * 根据检索条件获取资金变动报表纪录数
     * @param $search array(
        'uid'=>用户id,
        'item_type'=>佣金类型,
        'item_type in'=>,
        'order_id'=>订单号,
        'start_time'=>开始时间,
        'end_time'=>开始时间,
        'related_uid'=>关联人id,
     )
     */
    public function getCommLogsNum($search){
        if ($this->slave_flag) {
            $db = 'db_slave';
        } else {
            $db = "db";
        }
        $this->$db->from('commission_logs');
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $this->$db->where('create_time >=',$v);
            }elseif($k=='end_time'){
                $this->$db->where('create_time <',$v);
            }elseif($k=='item_type'){
                $this->$db->where('type',$v);
            }elseif($k=='item_type in'){
                $this->$db->where_in('type',$v);
            }elseif($k=='related_uid'){
                $this->$db->where('pay_user_id',$v);
            }else{
                $this->$db->where($k,$v);
            }
        }

        return $this->$db->count_all_results();
    }

    /**
     * 根据检索条件统计资金变动报表总金额
     * @param $search array(
        'uid'=>用户id,
        'item_type'=>佣金类型,
        'item_type in'=>,
        'amount <'=>,
        'amount >'=>,
        'order_id'=>订单号,
        'start_time'=>开始时间,
        'end_time'=>开始时间,
        'related_uid'=>关联人id,
     )
     */
    public function getCommLogsSumAmount($search){
        if ($this->slave_flag) {
            $db = 'db_slave';
        } else {
            $db = "db";
        }
        $this->$db->select_sum('amount')->from('commission_logs');
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $this->$db->where('create_time >=',$v);
            }elseif($k=='end_time'){
                $this->$db->where('create_time <',$v);
            }elseif($k=='item_type'){
                $this->$db->where('type',$v);
            }elseif($k=='item_type in'){
                $this->$db->where_in('type',$v);
            }elseif($k=='uid in'){
                $this->$db->where_in('uid',$v);
            }elseif($k=='related_uid'){
                $this->$db->where('pay_user_id',$v);
            }elseif($k=='amount <'){
                $this->$db->where('amount <',$v);
            }elseif($k=='amount >'){
                $this->$db->where('amount >',$v);
            }else{
                $this->$db->where($k,$v);
            }
        }
        $res = $this->$db->get()->row_array();
        return $res?tps_int_format($res['amount']*100):0;
    }
    
    /**
     * 判断用户是否拿过某项奖金。
     * @return boolean
     * @author Terry
     */
    public function checkUserCommissionOfType($uid,$typeId,$startTime='',$endTime=''){

        $search = array('uid'=>$uid,'item_type'=>$typeId);
        if($startTime){
            $search['start_time'] = $startTime;
        }
        if($endTime){
            $search['end_time'] = $endTime;
        }

        $this->load->model('o_cash_account');
        $num = $this->o_cash_account->getCashAccountLogNum($search);

        return $num>0?true:false;
    }

    /* 根据时间获取佣金报表(每月,每次查询50条) */
    public function get_commission_info($uid,$time,$type,$pager = 1,$show_num = 50){

        if($pager == 1){
            $start = 0;
        }else{
            $start = ($pager - 1) * $show_num;
        }

        $time = date('Y-m',strtotime($time));
        $cur_time = date('Ym');   //当前时间年月
        $year_month = date('Ym',strtotime($time));  //传递的年月

        //如果传递的参数 > 当前年月,返回空
        if($year_month > $cur_time){
            return array();
        }

        //如果传递的参数 >= 201606,查询对应的佣金表
        if($year_month >= TIME_NODE_OLD){
            $tb_name = get_cash_account_log_table($uid,$year_month);

            if($type == ''){
                $res = $this->db->query("select *,related_uid AS pay_user_id,item_type as type,DATE_FORMAT(create_time,'%Y-%m-%d')AS new_time from $tb_name where uid=$uid and create_time LIKE '$time%' order by create_time desc limit $start,$show_num")->result_array();
            }else{
                $res = $this->db->query("select *,related_uid AS pay_user_id,item_type as type,DATE_FORMAT(create_time,'%Y-%m-%d')AS new_time from $tb_name where uid=$uid and item_type = $type and create_time LIKE '$time%' order by create_time desc limit $start,$show_num")->result_array();
            }

            foreach($res as $k =>$v){
                $res[$k]['amount'] =  $v['amount'] / 100;
            }
            return $res;
        }

        //如果查询的参数 < 201606,查询佣金表
        if($year_month < TIME_NODE_OLD){
            if($type == ''){
                $res = $this->db->query("select *,DATE_FORMAT(create_time,'%Y-%m-%d')AS new_time from commission_logs where uid=$uid and create_time LIKE '$time%' order by create_time desc limit $start,$show_num")->result_array();
            }else{
                $res = $this->db->query("select *,DATE_FORMAT(create_time,'%Y-%m-%d')AS new_time from commission_logs where uid=$uid and type = $type and create_time LIKE '$time%' order by create_time desc limit $start,$show_num")->result_array();
            }
            return $res;
        }
    }

    /* 佣金报表(手机端) */
    public function get_commission_logs_mobile($uid,$time,$type,$pager){
        $this->load->model("m_commission");
        $data = array();

        //个人店铺销售提成、周领导对等、每月杰出店铺、2x5佣金、138佣金、团队销售提成、团队总裁奖
        $res = $this->db->query("
            select (amount_store_commission+
            amount_weekly_Leader_comm+
            amount_monthly_leader_comm+
            personal_commission+
            company_commission+
            team_commission+
            infinite_commission) as total from users where id = $uid;
        ")->row_array();

        //日分红总额
        $day_comm_total = $this->m_commission->getMemberTotalComm($uid,6);
        //月领导分红总额
        $month_leader_comm_total = $this->m_commission->getMemberTotalComm($uid,23);
        //销售精英日分红总额
        $sale_group_comm_total = $this->m_commission->getMemberTotalComm($uid,24);

        $data['total_commission'] = $res['total'] + $day_comm_total +$month_leader_comm_total +$sale_group_comm_total;

        /*当日，当月实时佣金。*/
        $data['day_commission'] = $this->m_commission->getTodayComm($uid);
        $data['monthly_commission'] = $this->m_commission->getCurMonthComm($uid);

        $data['commission_info'] = $this->get_commission_info($uid,$time,$type,$pager);


        return $data;
    }

    public function getCommLogByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month){

        $this->load->model('o_cash_account');
        $beginTime = substr($comm_year_month,0,4).'-'.substr($comm_year_month,4);
        $yearMonthNext = yearMonthAddOne($comm_year_month);
        $endTime = substr($yearMonthNext,0,4).'-'.substr($yearMonthNext,4);

        return $this->o_cash_account->getCashAccountLog(array(
            'uid'=>$uid,
            'item_type'=>$comm_type,
            'start_time'=>$beginTime,
            'end_time'=>$endTime,
        ));

    }

    /**
     * 根据用户id、奖金类型、奖金年月获取该项奖金的金额
     * @author Terry
     */
    public function getAmountByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month){

        $this->load->model('o_cash_account');
        $beginTime = substr($comm_year_month,0,4).'-'.substr($comm_year_month,4);
        $yearMonthNext = yearMonthAddOne($comm_year_month);
        $endTime = substr($yearMonthNext,0,4).'-'.substr($yearMonthNext,4);
        return $this->o_cash_account->getSumAmount(array(
            'uid'=>$uid,
            'item_type'=>$comm_type,
            'start_time'=>$beginTime,
            'end_time'=>$endTime,
        ));
    }
    
    public function get_new_member_bonus_log($uid,$comm_type,$comm_year_month)
    {
    	$year = substr($comm_year_month, 0,4);
    	$month = substr($comm_year_month, -1,2);
    	$new_year = date($year."-".$month);
    	 
    	$next_month_new = date("Ym",strtotime("+1 month",strtotime($new_year)));


        $tb1 = get_cash_account_log_table($uid,$comm_year_month);
        $tb2 = get_cash_account_log_table($uid,$next_month_new);

    	$res1 = $this->db->select("*,'".$tb1."' tb_name",false)->from($tb1)->where(['uid'=>$uid,'item_type'=>$comm_type])->get()->result_array();
    	
    	$res2 = $this->db->select("*,'".$tb2."' tb_name",false)->from($tb2)->where(['uid'=>$uid,'item_type'=>$comm_type])->get()->result_array();
    
    	
    	if (!empty($res2)) {
    		return array_merge($res1,$res2);
    	} else {
    		return $res1;
    	}
    }

    /**
     * 插入记录到资金变动报表
     * @param $data array(
     *   'uid'=>用户id int
     *   'type'=>金额类型 int
     *   'amount'=>金额 decimal(14,2)
     *   'order_id'=>订单号 var（非必需）
     *   'create_time'=>创建时间 timestamp (非必需,默认当前时间)
     *   'pay_user_id'＝>关联的用户id int (非必需)
     *)
     * @return boolean
     * @author Terry
     */
    public function insert($data){
        return $this->db->insert('commission_logs', $data);
    }


	/** 订单是否发放了团队销售佣金
	 * @param $order_id
	 * @return bool TRUE：已发放
	 */
	public function is_grant_cash_order($uid){

        $this->load->model('o_cash_account');
        $num = $this->o_cash_account->getCashAccountLogNum(array(
            'item_type'=>3,
            //'order_id'=>$order_id,
            'uid'=>$uid,
        ));
		return $num>0 ? TRUE : FALSE;
	}

    /* 是否拿过138奖金*/
    public function is_new_member($uid){
        $this->load->model('o_cash_account');
        $res = $this->o_cash_account->getFirstCommLog(array('uid'=>$uid,'item_type'=>2),'desc');
        if($res === false){
            return false;
        }else{
            return true;
        }
    }

}
