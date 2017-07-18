<?php
/**
 * @author Terry
 */
class tb_cash_account_log_x extends MY_Model {
    protected $slave_flag = false;
    protected $table_info   = "cash_account_log_info";
    protected $table = '';
    function __construct() {
        parent::__construct();

        $db_slave = config_item('db_slave');
        if(!empty($db_slave)) {
            $this->slave_flag = true;
        } else {
            $this->slave_flag = false;
        }
    }

    public function set_table($table)
    {
        $this->table = $table;
//        if (config_item("cash_account_log_temp_write") == true) {
//            $this->table_info = "temp_cash_account_log_info";
//        }
    }

    /**
     * @author brady
     * @desc 检查某个用户某天，是否拿某种分红
     * @param $uid 用户id
     * @param $item_type 6
     * @param $day 2017-05-01
     */
    public function  check_exits_by_day($uid,$item_type,$day)
    {
        $year_month = date("Ym",strtotime($day));
        $table = get_cash_account_log_table($uid,$year_month);
        $this->set_table($table);
        $param = [
            'select_escape'=>'id,amount',
            'where'=>[
                'uid'=>$uid,
                'item_type'=>$item_type,
                'date_format(create_time,"%Y-%m-%d")'=>$day
            ],
            'limit'=>1
        ];
        $res = $this->get($param,false,true,true);
        if(!empty($res) && $res['amount']>0){
            return true;
        }
        else {
            return false;
        }


    }

    /**
     * @author brady
     * @desc 检查某个用户某天，是否拿某种分红
     * @param $uid 用户id
     * @param $item_type 6
     * @param $day 2017-05-01
     */
    public function  check_exits_by_days($uid,$item_type,$day)
    {
        $year_month = date("Ym",strtotime($day));
        $table = get_cash_account_log_table($uid,$year_month);   
        $sql = "select * from ".$table." where uid = ".uid." and item_type=".$item_type." and create_time between '".$day." 00:00:00"."' and '".$day." 00:00:00'";
        $query = $this->db->query($sql)->row_array();
        if(!empty($query)){
            return true;
        }
        else {
            return false;
        }
    
    
    }
    
    /**
     * @author brady.wang
     * @desc  获取cash_account_log_x 用户的所有amount和
     * @param $uid
     * @return float
     */
    public function get_sum_amount_x_by_uid($uid,$type = '')
    {
        //获取年月
        $yearMonths = get_year_months_by_time_period('2016-07-01 00:00:00');
        //查询会员的资金变动
        $sql = "SELECT (";
        foreach($yearMonths as $month){
            $table = get_cash_account_log_table($uid,$month);// m by brady.wang 2017/05/11 分表查询
            if (!empty($type) && is_numeric($type)) {
                $sql .= "(SELECT  ifnull(SUM(amount),0) FROM  ".$table." WHERE uid=$uid AND `item_type` = {$type} ) +";
            } else {
                $sql .= "(SELECT  ifnull(SUM(amount),0) FROM  ".$table." WHERE uid=$uid ) +";
            }

        }
        $sql = rtrim($sql,'+');
        $sql = $sql.") as num";
        $sun_amount1 = $this->db->query("$sql")->row_array();
        return $sun_amount1 = (empty($sun_amount1['num'])?0:$sun_amount1['num']) / 100;
    }

    /**
     * @author brady
     * @desc 获取老资金变动表的用户所有资金金额
     * @param $uid
     * @return int
     */
    public function get_sum_amount_old_by_uid($uid,$type='')
    {
        if (!empty($type) && is_numeric($type)) {
            $sun_amount2 = $this->db->query("SELECT  (ifnull(SUM(amount),0)) as num FROM commission_logs WHERE uid=$uid AND `type` = {$type}")->row_array();
        } else {
            $sun_amount2 = $this->db->query("SELECT  (ifnull(SUM(amount),0)) as num FROM commission_logs WHERE uid=$uid")->row_array();
        }

        return $sun_amount2 = empty($sun_amount2['num'])?0:$sun_amount2['num'];
    }


    /**
     * @author brady
     * @param $data cash_account_log字段
     * @param string $yearMonth 201705 默认为当前月份
     * @return bool
     */
    public function createCashAccountMonthLogNew($data,$yearMonth=''){
        $uid = $data['uid'];
        $info_data = [];
        if (empty($yearMonth)) {
            $yearMonth = date("Ym");
        }

        if ($yearMonth <= config_item("cash_account_log_cut_table_end")) { //小於拆表前的日期 插入到cash_account_log_month里面
            return $this->db->insert('cash_account_log_'.$yearMonth, $data);
        } else {//大于等于拆表日期的 插入到拆分后的表，并且 插入info表
            //如果配置里面新表旧表要求一起写，那么同时写入
            if (config_item("cash_account_log_write_both") == true) {
            	$old_data = $data;
                unset($old_data['before_amount']);
                unset($old_data['after_amount']);
                $this->db->insert('cash_account_log_'.$yearMonth, $old_data);
            }

            $data['remark'] = isset($data['remark']) ? $data['remark']:'';
            $data['related_id'] = isset($data['related_id']) ? $data['related_id']:'';
            if (!isset($data['before_amount']) && !isset($data['after_amount'])) {
                $this->load->model("m_user");
                $user = $this->m_user->getUserByIdOrEmail($uid);
                $data['after_amount'] = $user['amount']*100;
                $data['before_amount'] = $user['amount'] *100 - $data['amount'];
            }


            $table = get_cash_account_log_table_write($uid,$yearMonth);
            if (!empty($data['remark']) || !empty($data['related_id'])) {
                $info_data = array('related_id'=>$data['related_id'],'remark' => $data['remark']);

            }
            unset($data['remark']);
            unset($data['related_id']);
            $this->db->insert($table, $data);
            $insert_id = $this->db->insert_id();

            if (!empty($info_data)) {
                $table_pre = substr($table,17);
                $info_data['id'] = $table_pre."_".$insert_id;
                $this->db->replace($this->table_info,$info_data);
            }

            if ($insert_id > 0) {
                return true;
            } else {
                return false;
            }

        }

    }

    /**
     * @author brady
     * @desc   批量插入分红表
     * @param   $lists [[uid=>111,money=>10],]
     * @param $table 要插入的表
     * @param  item_type 分红类型
     * @param $create_time 时间 2017-07-01
     */
    public function get_sql_batch($lists,$item_type,$table,$create_time,$old_table = false)
    {
        if ($old_table == true) {
            $sql = "insert into " . $table . "(uid,item_type,amount,create_time) values";
            foreach ($lists as $v) {

                if ($item_type == 17 || $item_type == 28) {
                    $v['money'] = tps_int_format(-1 * $v['commissionToPoint']);
                } else {
                    $v['money'] = ($v['money']) < 0 ? 0 : $v['money'];
                }

                $sql .= "(" . $v['uid'] . "," . $item_type . "," . $v['money'] . ",'" . $create_time . "'),";
            }
        } else {
            $sql = "insert into " . $table . "(uid,item_type,amount,create_time,before_amount,after_amount) values";
            foreach ($lists as $v) {

                if ($item_type == 17 || $item_type == 28) {
                    $v['money'] = tps_int_format(-1 * $v['commissionToPoint']);
                } else {
                    $v['money'] = ($v['money']) < 0 ? 0 : $v['money'];
                }
                if($item_type == 17) {
                    $old_amount = $v['old_amount'];
                }else {
                    $old_amount = 100 * $v['old_amount'];
                }
                $new_amount = $old_amount + $v['money'];
                $sql .= "(" . $v['uid'] . "," . $item_type . "," . $v['money'] . ",'" . $create_time . "',".$old_amount.",".$new_amount."),";
            }
        }

        return $sql = substr($sql, 0, strlen($sql) - 1);
    }

    /**
     * @author: derrick 根据订单ID, 日志类型查询指定表数据
     * @date: 2017年5月6日
     * @param: @param unknown $table_name 表名
     * @param: @param unknown $order_ids 订单IDS
     * @param: @param unknown $item_type 报表类型
     * @reurn: return_type
     */
    public function find_by_order_ids_item_type($table_name, $order_ids, $item_type) {
    	return $this->db->where_in('order_id', is_array($order_ids) ? $order_ids : array($order_ids))->where('item_type', $item_type)->get($table_name)->result_array();
    }
    
    /**
     * @author: derrick 根据当前时间分表写日志
     * @date: 2017年3月25日
     * @param: @param int $uid 用户ID
     * @param: @param int $item_type 资金变动项类型
     * @param: @param int $amount 变动金额
     * @param: @param int $order_id 订单ID
     * @param: @param int $related_uid 关联用户ID
     * @param: @param date $date 分表时间
     * @reurn: return_type
     */
    public function add_log_by_datetime($uid, $item_type, $amount, $order_id, $related_uid = 0, $date = '') {
    	$date = $date ? $date : date('Ym');
    	return $this->db->insert(get_cash_account_log_table_write($uid,$date), array(
    			'uid' => $uid,
    			'item_type' => $item_type,
    			'amount' => $amount,
    			'order_id' => $order_id,
    			'related_uid' => $related_uid,
    	));
    }

    /**         
     * @param: @param int $uid 用户ID
     * @param: @param int $item_type 资金变动项类型
     * @param: @param int $amount 变动金额
     * @param: @param int $order_id 订单ID
     * @param: @param int $related_uid 关联用户ID    
     * @reurn: return_type
     */
    public function add_user_account_commsion_log($uid, $item_type, $amount, $order_id, $related_uid = 0) {     
        return $this->db->insert("commission_logs", array(
            'uid' => $uid,
            'type' => $item_type,
            'amount' => $amount,
            'order_id' => $order_id,
            'related_id' => $related_uid,
        ));
    }
    
    /**
     * @author: derrick 根据订单业绩时间查询某个用户的指定类型的订单日志记录
     * @date: 2017年5月2日
     * @param: @param date $datetime 时间月份 Ym/201705  
     * @param: @param int $uid 用户ID
     * @param: @param string $order_id 订单ID
     * @param: @param int $item_type 日志类型
     * @reurn: return_type
     */
    public function find_by_datetime_uid_order_id_item_type($datetime, $uid, $order_id, $item_type) {
    	return $this->db->get_where($table = get_cash_account_log_table($uid,$datetime), array('uid' => $uid, 'order_id' => $order_id, 'item_type' => $item_type))->result_array();
    }
    
    public function getCashAccountLogByTbnameAndId($tbname,$id){
        return $this->db->from($tbname)->where('id',$id)->get()->row_array();
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
    public function getCashAccountMonthLogByPage($search,$yearMonth,$page,$page_size){

        $db = 'db';
//        $cut_table_flag = false;
//        if ($yearMonth >= config_item("cash_account_log_cut_table_end")) {
//            $cut_table_flag = true;
//        }
//        if (config_item("cash_account_log_read_new") == false) {
//            //读旧表
//            $cut_table_flag = false;
//        }
        $cut_table_flag = config_item("cash_account_log_read_new");
        $uid = $search['uid'];
        $table = get_cash_account_log_table($uid,$yearMonth);
        $this->db_slave->from($table);
        foreach($search as $k=>$v){

            if($k=='start_time'){
                if($v != substr($yearMonth,0,4)."-".substr($yearMonth,4,2)."-01")
                {
                    $this->db_slave->where('create_time >=',$v);
                }
            }elseif($k=='end_time'){
                $end_time = date('Y-m-d H:i:s',strtotime($v)+86400-1);
                $end_time2 = date('Ym',strtotime($v)+86400);

                if($end_time2 == $yearMonth){
                    $this->db_slave->where('create_time <',$end_time);
                }
            }elseif($k=='item_type in'){
                $this->db_slave->where_in('item_type',$v);
            }else{
                if($k == 'item_type')
                {
                    $v = (int)$v;
                }
                $this->db_slave->where($k,$v);
            }
        }

        //$res = $this->db_slave->order_by("create_time", "desc")->limit($page_size, ($page - 1) * $page_size)->get()->result_array();
        $res = $this->db_slave->order_by("create_time desc,order_id desc")->limit($page_size, ($page - 1) * $page_size)->get()->result_array();
        
        //如果实拆表查询的，还要查询子表数据
       if (!empty($res)) {
           if ($cut_table_flag == true && $yearMonth > config_item("cash_account_log_cut_table_end")) {
               $ids = [];
               foreach($res as $v) {
                   $ids[] = $yearMonth."_".substr($uid,0,4)."_". $v['id'];
                }
               $info_res = $this->db->from($this->table_info)
                   ->where_in('id',$ids)
                   ->limit($page_size)
                   ->get()
                   ->result_array();
               if(!empty($info_res)){
                   foreach($info_res as $vv){
                       $info_res[$vv["id"]] = $vv;
                   }
               }
               foreach($res as $k=>$v) {
                   $id = $v['id'];
                   $new_id = $yearMonth."_".substr($uid,0,4)."_".$id;
                   if(isset($info_res[$new_id]) && !empty($info_res[$new_id])){
                       $res[$k]['related_id'] = $info_res[$new_id]['related_id'];
                       $res[$k]['remark'] = $info_res[$new_id]['remark'];
                   }else{
                       $res[$k]['related_id'] = 0;
                       $res[$k]['remark'] = '';
                   }
                   
                   /* haiya 20170703  旧写法逻辑有错误
                   if (!empty($info_res)) {
                       foreach($info_res as $vv) {
                           $new_id = $yearMonth."_".substr($uid,0,4)."_".$id;
                           if ( $new_id == $vv['id']) {
                               $res[$k]['related_id'] = $vv['related_id'];
                               $res[$k]['remark'] = $vv['remark'];
                           } else {
                               $res[$k]['related_id'] = 0;
                               $res[$k]['remark'] = '';
                           }
                       }
                   }
                    *
                    */

               }

           }
       };
        return $res;
    }

    /**
     * @author  m by brady.wangg
     * @time    2017/05/09
     * @param $search 搜索内容
     * @param $yearMonth 月份
     * @param string $select 查询字段
     * @param bool|false $getOne
     * @param string $sort
     * @return mixed
     */
    public function getCashAccountMonthLog($search,$yearMonth,$select='*',$getOne=false,$sort='asc'){

        $this->load->model("tb_trade_orders");
        if (isset($search['uid'])) {
            $uid = $search['uid'];
        } elseif(isset($search['order_id'])) {
            $order_info = $this->tb_trade_orders->get_one("customer_id",['order_id'=>$search['order_id']]);
            $uid = $order_info['customer_id'];
        } else {
            echo "找不到该订单用户";exit;
        }
        if (isset($search['not_use_uid']) && $search['not_use_uid'] == true) {
            unset($search['uid']);
            unset($search['not_use_uid']);
        }
        $tb_name = get_cash_account_log_table($uid,$yearMonth);
        if($select=='*'){
            $sql = "select *,'".$tb_name."' tb_name from ".$tb_name;
        }else{
            $sql = "select ".$select." from ".$tb_name;
        }
        
        $where = '';
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $where.=' and create_time >="'.$v.'"';
            }elseif($k=='end_time'){
                $where.=' and create_time <"'.$v.'"';
            }elseif($k=='item_type in'){
                $where.=' and item_type in('.implode($v,',').')';
            }else{
                if($k == "item_type")
                {
                    $where.=' and '.$k.' ='.$v;
                }else{
                    $where.=' and '.$k.' ="'.$v.'"';
                }
            }
        }

        $sql.=$where?' where 1=1'.$where:'';
        $sql.=' order by create_time '.$sort.',id '.$sort;
        if($getOne){
            $res = $this->db_slave->query($sql)->row_array();
        }else{
            $res = $this->db_slave->query($sql)->result_array();
        }
        return $res;
    }

    /**
     * 根据检索条件获取资金变动报表纪录数
     * @param $search array(
        'uid'=>用户id,
        'item_type'=>佣金类型,
        'item_type in'=>array(),
        'order_id'=>订单号,
        'start_time'=>开始时间,
        'end_time'=>开始时间,
        'related_uid'=>关联人id,
     )
     */
    public function getCashAccountMonthLogNum($search,$yearMonth){
        $this->load->model("tb_trade_orders");
        if (isset($search['uid'])) {
            $uid = $search['uid'];
        } elseif(isset($search['order_id'])) {
            $order_info = $this->tb_trade_orders->get_one("customer_id",['order_id'=>$search['order_id']]);
            $uid = $order_info['customer_id'];
        } else {
            echo "找不到该订单用户";exit;
        }
        $tb_name = get_cash_account_log_table($uid,$yearMonth);
        $db_slave = config_item('db_slave');
        if(!empty($db_slave)) {
            $db = 'db_slave';
        } else {
            $db = 'db';
        }
        $this->db_slave->from($tb_name);
        foreach($search as $k=>$v){

            if($k=='start_time'){
                $this->db_slave->where('create_time >=',$v);
            }elseif($k=='end_time'){
                $this->db_slave->where('create_time <',date('Y-m-d H:i:s',strtotime($v)+86400-1));
            }elseif($k=='item_type in'){
                $this->db_slave->where_in('item_type',$v);
            }else{
                $this->db_slave->where($k,$v);
            }
        }

        return $this->db_slave->count_all_results();
    }



    public function getCommByDate($uid,$commItemId,$curDate){

        $timestamp = strtotime($curDate);
        $yearMonth = date('Ym',$timestamp);
        $end_time = date('Y-m-d',$timestamp+86400);
        $commItemId = (int)$commItemId; 
        $table = get_cash_account_log_table($uid,$yearMonth); // M by brady.wang 拆分表
        $res = $this->db_slave->select('amount,create_time')->from($table)
            ->where('uid',$uid)
            ->where('item_type',$commItemId)
            ->where('create_time >',$curDate)
            ->where('create_time <',$end_time)
            ->get()->row_array();

        return $res;
    }



    /**
     * 根据检索条件统计资金变动报表总金额
     * @param $search array(
        'uid'=>用户id,
        'item_type'=>佣金类型,
        'item_type in'=>array(),
        'amount <'=>,
        'amount >'=>,
        'order_id'=>订单号,
        'start_time'=>开始时间,
        'end_time'=>开始时间,
        'related_uid'=>关联人id,
     )
     */
    public function getCashAccountMonthLogSumAmount($search,$yearMonth){
        $db = $this->getDbPrepare($search,$yearMonth);
        $res = $db->select_sum('amount')->get()->row_array();
        return $res?$res['amount']:0;
    }
    
    public function getSumAmountGroupByType($search) {
        $data = array();
        $uid = intval($search['uid']);
        $curMonth = $search['curMonth'];
        $table = get_cash_account_log_table($uid,$curMonth);
        $sql = "select item_type, sum(amount) as amount from ".$table." where uid=$uid and item_type in(1,2,3,4,5,6,7,8,23,24,25,26,27) group by item_type ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
               $k = $row->item_type;
               $data[$k] = $row->amount;
              
           }
        } 
        
        return $data;
        
    }

    /**
     * 根据检索条件统计资金变动报表详情
     * @param $search
     * @param $yearMonth
     * @return mixed
     */
    public function getCashAccountLogBatch($search, $yearMonth){
        $db = $this->getDbPrepare($search,$yearMonth);
        $fields = ['uid','item_type','amount','create_time'];
        return $db->select($fields)->get()->result_array();
    }

    /**
     * 获取周奖金总数，提前id分组
     * @param $ids
     * @param $types
     * @param $date
     * @return int
     */
    public function getWeekSumAmount($ids, $types, $date){
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $id_array = array();
        foreach ($ids as $id) {
            $pre = substr($id,0,4);
            $id_array[$pre][] = $id;
        }
        $total = 0;
        foreach ($id_array as $array) {
            $total += $this->getWeekSumAmountAct($array, $types, $date);
        }
        return $total;
    }

    /**
     * 获取周奖金总数，实际获取金额
     * @param $ids
     * @param $types
     * @param $date
     * @return int
     */
    public function getWeekSumAmountAct($ids, $types, $date){
        $start_table = date('Ym', strtotime($date));
        $end_table = date('Ym', strtotime('+6 day', strtotime($date)));

        $condition = $this->getWeekLogCondition($ids, $types, $date);

        $amount = $this->getCashAccountMonthLogSumAmount($condition, $start_table);
        //跨表的话需要再取一次
        if ($start_table !== $end_table) {
            $amount += $this->getCashAccountMonthLogSumAmount($condition, $end_table);
        }
        return intval($amount);
    }

    /**
     * 获取一周内用户的奖金详情
     * @param $ids
     * @param $types
     * @param $date
     * @return int
     */
    public function getCashAccountLogList($ids, $types, $date){
        $start_table = date('Ym', strtotime($date));
        $end_table = date('Ym', strtotime('+6 day', strtotime($date)));

        $condition = $this->getWeekLogCondition($ids, $types, $date);

        $list = $this->getCashAccountLogBatch($condition, $start_table);
        //跨表的话需要再取一次
        if ($start_table !== $end_table) {
            $listTemp = $this->getCashAccountLogBatch($condition, $end_table);
            $list = array_merge($list, $listTemp);
        }
        return $list;
    }

    /**
     * 获取一周内奖金数据的条件
     * @param $ids
     * @param $types
     * @param $date
     * @return array
     */
    private function getWeekLogCondition($ids, $types, $date){
        $time = strtotime($date);
        $start = date('Y-m-d 00:00:00', $time);
        $end = date('Y-m-d', strtotime('+6 day', $time));

        $condition = array(
            'start_time' => $start,
            'end_time' => date('Y-m-d 00:00:00', strtotime('+1 day',strtotime($end))),
            'uid in' => $ids,
            'item_type in' => $types,
        );
        return $condition;
    }

    /**
     * 获取加入筛选条件后的db实例
     * @param $search
     * @param $yearMonth
     * @return mixed
     */
    private function getDbPrepare($search, $yearMonth){
        if(isset($search['uid'])) {
            $uid_one = $search['uid'];
        } elseif(isset($search['uid in'])) {
            $uid_one = $search['uid in'][0];
            foreach($search['uid in'] as $v) {
                if (substr($uid_one,0,4) !== substr($v,0,4)) {
                    echo "请传递用户id一致的id过来";exit;
                }
            }
        }
        $table = get_cash_account_log_table($uid_one,$yearMonth);

        $this->db_slave->from($table);
        if (is_array($search)) {
            foreach($search as $k=>$v){
                if (is_array($v) && empty($v)) {
                    continue;
                }
                if($k=='start_time'){
                    $this->db_slave->where('create_time >=',$v);
                }elseif($k=='end_time'){
                    $this->db_slave->where('create_time <',$v);
                }elseif($k=='item_type in'){
                    $this->db_slave->where_in('item_type',$v);
                }
                elseif($k=='uid in'){
                    $this->db_slave->where_in('uid',$v);
                }
                elseif($k=='amount <'){
                    $this->db_slave->where('amount <',$v);
                }elseif($k=='amount >'){
                    $this->db_slave->where('amount >',$v);
                }else{
                    $this->db_slave->where($k,$v);
                }
            }
        }
        return $this->db_slave;
    }


    /**
     * @author brady.wang
     * @time  2017/05/09
     * 获取某个用户转帐给别人的总金额 拆分表后新方法
     * @param 用户id $uid
     * @param 时间 $oldLastYearMonth
     */
    public function getUserTransferAmountNewBranch($uid,$oldLastYearMonth){
        $return = 0;
        $yearMonth = yearMonthAddOne($oldLastYearMonth);

        $curYearMonth = date('Ym');
        while ($yearMonth<=$curYearMonth){
            $table_new = get_cash_account_log_table($uid,$yearMonth);
            $res = $this->db_slave->select_sum('amount')->from($table_new)
                ->where('uid',$uid)->where('item_type',11)->where('amount <','0')
                ->get()->row_object();
            if($res){
                $return += abs($res->amount);
            }
            $yearMonth = yearMonthAddOne($yearMonth);
        }

        return $return;
    }

    /**
     * @author brady.wang
     * @desc 统计某个用户某个月的各项奖金总和 admin后台用到  (201606以后的统计)
     * SELECT item_type,SUM(amount)/100 as sum_amount FROM cash_account_log_201705 where item_type in(5,3,6,1,8,2,7,23,4,24,25,26) and uid=1381595612 group by item_type
     * @time 2017/05/11
     */
    public function get_sum_bonus_by_item($uid,$select,$year_month,$item_type = array())
    {
        $table = get_cash_account_log_table($uid,$year_month);
        $res = $this->db_slave->from($table)
            ->select($select)
            ->where_in('item_type',$item_type)
            ->where('uid',$uid)
            ->group_by("item_type")
            ->get()
            ->result_array();
        return $res;
    }

    public function get_sum_by_type_uid($uid,$item_type,$year_month)
    {
        $table1 = get_cash_account_log_table($uid,$year_month);
        $param =  [
            'select_escape'=>'sum(amount) as amount',
            'where'=>[
                'uid'=>$uid,
                'item_type'=>$item_type
            ],
            'limit'=>1
        ];
        $table = get_cash_account_log_table($uid,$year_month);

        $this->tb_cash_account_log_x->set_table($table);
        $res = $this->tb_cash_account_log_x->get($param,false,true,true);
        return $amount =  $res?$res['amount']:0;
    }

    /**
     * @author brady
     * @desc 获取用户每月的各项奖金总和  获取的是201606月之前的
     * $sql = "SELECT type as item_type,SUM(amount) as sum_amount FROM commission_logs where type in({$item_str}) and uid={$user["id"]} and create_time>='{$start_time}' and  create_time <='{$end_time}' group by type";
     * @param $uid
     * @param $select
     * @param $year_month
     * @param array $item_type
     * @return mixed
     * @time 2017/05/11
     */
    public function get_sum_bonus_by_item_201606($uid,$select,$year_month,$item_type = array())
    {
        $table = "commission_logs";
        $year = substr($year_month,0,4);
        $month = substr($year_month,-1,2);
        $start_time = date("Y-m-01 00:00:00",strtotime($year."-".$month));
        $end_time = date("Y-m-31 23:59:59",strtotime($year."-".$month));
        $res = $this->db_slave->from($table)
            ->select($select)
            ->where('create_time >="'.$start_time.'" and create_time <="'.$end_time.'"')
            ->where('uid',$uid)
            ->where_in('type',$item_type)
            ->group_by("type")
            ->get()
            ->result_array();
        return $res;
    }

    /***
     * 用户发奖记录
     * @param 用户id $uid
     * @param 发奖类型  $item_type
     */
    public function getUserCashAccountLogs($uid,$item_type)
    {
        $data_array = array();         
        for($i=2016;$i<2050;$i++)
        {
            for($j=1;$j<=12;$j++)
            {
                $ymd_nb = $i.sprintf( "%02d",$j);
                if($ymd_nb >= 201607)
                {                    
                    if($ymd_nb>date('Ym'))
                    {
                        break 2;
                    }
                    else
                    {
                        $cash_query_all = array();
                        $cash_tb = get_cash_account_log_table($uid,$ymd_nb); //m by brady.wang 拆分表

                        $cash_sql = "select item_type,(amount/100) as amount,create_time from ".$cash_tb." where uid = ".$uid;  
                        if(!empty($item_type))
                        {
                            $cash_sql .= " and item_type = ".$item_type;
                        }
                        $cash_query = $this->db->query($cash_sql);
                        $cash_query_all = $cash_query->result_array();
                        if(!empty($cash_query_all))
                        {
                            $data_array[] = array
                            (
                                'cash_query_all' => $cash_query_all
                            );
                        }                                              
                    }                    
                }
            }            
        }
        $str=array();
        foreach($data_array as $sult)
        {
            if(!empty($sult['cash_query_all']))
            {
                $str += $sult['cash_query_all'];
            }            
        }

       return array_merge($data_array,$str);      
    }


    
    /**
     * 2016-8 月之前的日志记录
     * @param 用户id $uid
     * @param 发奖类型 $item_type
     */
    public function getUserCashAccountSincesLogs($uid,$item_type)
    {
        $sql = "select type as item_type,amount,create_time from commission_logs where uid=".$uid;
        if(!empty($item_type))
        {
            $sql .= " and type=".$item_type;
        }
        $query = $this->db->query($sql);
        $query_all = $query->result_array();
        return $query_all;
    }
    
}

   

