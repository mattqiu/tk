	<?php
/**
 * @author Terry
 * @modify tico.wong
 * @modify_data 2017-05-25
 * @desc 扩展了本model，适应了分表并拆表，
 * 对trade_orders表的所有操作，可以当做未分表拆表时的表结构来进行。
 * 但多了一些限制。
 * 限制1：get_one时必传order_id或attach_id
 */
class tb_trade_orders extends MY_Model {
    protected $table_name = "trade_orders";
    //调试输出开关,只允许调试时打开
    protected $DEBUG = true;
    //get_list,get_counts,get_sum时，若不传order_id,attach_id条件时的联表数限制
    protected $max_scan = 12;
    function __construct() {
        parent::__construct();
        //加载拆分的副表模型
        $this->load->model("tb_trade_orders_info");
    }

    /**
     * 查询订货号是否是有效的
     * @return boolean
     * @author Terry
     */
    public function findOne($orderId) {
//        $this->db->from('trade_orders');
//        $status = $this->db->select('status')->where('order_id',$orderId)->get()->row_array();
        $status = $this->get_one("*",["order_id"=>$orderId]);
        return $status;
    }

    public function debug($msg,$start=0)
    {
        if($this->DEBUG || $start)
        {
            $redis_key = "tb_trade_orders:debug:".date("Ymdh");
            $this->redis_lPush($redis_key,$msg);
            if($this->redis_ttl($redis_key) == -1)
            {
                $this->redis_setTimeout($redis_key,60*60);
            }
        }
    }

    /**
     * 获取订单信息
     * @param $order_id
     * @param $fields 需要的字段信息 array('customer_id','paytime'...) (非必需，默认为空数组代表所有字段)
     * @return mixed
     * @author Terry
     */
    public function getOrderInfo($order_id,$fields=array()){

        $fieldsItem = $fields?implode(',', $fields):'*';
//        $res = $this->db->from('trade_orders')->select($fieldsItem)->where('order_id',$order_id)->get();
//        return $res ? $res->row_array() : array();
        return $this->get_one($fieldsItem,['order_id'=>$order_id]);
    }

    /**
     * 更新订单的业绩年月
     */
    public function updateScore_year_month($order_id,$score_year_month){

//        $this->db->where('order_id', $order_id)->set('score_year_month', $score_year_month)->update('trade_orders');
        $this->update_one(['order_id'=>$order_id],['score_year_month'=>$score_year_month]);
    }

	/**
	 * 根据订单 id 更新订单信息
	 */
	public function updateInfoById($id, $attr) {
		$updateData = array();
        if (isset($attr['consignee'])) {
            $updateData['consignee'] = $attr['consignee'];
        }
        if (isset($attr['phone'])) {
            $updateData['phone'] = $attr['phone'];
        }
        if (isset($attr['reserve_num'])) {
            $updateData['reserve_num'] = $attr['reserve_num'];
        }
        if (isset($attr['address'])) {
            $updateData['address'] = $attr['address'];
        }
        if (isset($attr['zip_code'])) {
            $updateData['zip_code'] = $attr['zip_code'];
        }
        if (isset($attr['customs_clearance'])) {
            $updateData['customs_clearance'] = $attr['customs_clearance'];
        }
		if (isset($attr['freight_info'])) {
			$updateData['freight_info'] = $attr['freight_info'];
		}
		if (isset($attr['deliver_time'])) {
			$updateData['deliver_time'] = $attr['deliver_time'];
		}
		if (isset($attr['status'])) {
			$updateData['status'] = $attr['status'];
		}
        //真实运费
        if (isset($attr['deliver_fee_usd'])) {
            $updateData['deliver_fee_usd'] = $attr['deliver_fee_usd'];
        }
        if (isset($attr['score_year_month'])) {
            $updateData['score_year_month'] = $attr['score_year_month'];
        }

//		return $this->db->where('order_id', $id)->update('trade_orders', $updateData);
        return $this->update_one(["order_id"=>$id],$updateData);
	}

	/**
	 * 是否有未支付订单
	 */
	public function is_no_payment($uid){
//		return $this->db->from('trade_orders')->where('customer_id',$uid)->where('status','2')->count_all_results();
		return $this->get_counts(['customer_id'=>$uid,'status'=>'2']);
    }

	/**
	 * 清空現金池支付的通知次數
	 */
	public function update_notify_count($order_id){
//		$this->db->where('order_id',$order_id)->update('trade_orders',array('notify_num'=>0));
		$this->update_one(["order_id"=>$order_id],array('notify_num'=>0));
	}

    /**
     * 获取用户的订单总金额 （含升级的订单+普通零售订单）
     * @author brady
     * @param $users
     */
    public function getUsersOrderAmount($users)
    {
        $this->load->model("m_profit");

        $timePeriod = $this->m_profit->__getLastDayPeriod();
        //商城订单
//        $mallProfitTps = $this->db->select('sum(a.order_amount_usd) as total_amount')
//            ->from('trade_orders as a')
//            ->where('a.pay_time <',$timePeriod['end'])
//            ->where_in('customer_id',$users)
//            ->where_in('order_prop',array('0','2'))
//            ->where_in('status',array('3','4','5','6'))
//            ->get()
//            ->row_object()
//            ->total_amount;
//        $mallProfitTps = $mallProfitTps?$mallProfitTps/100:0;
        $mallProfitTps = $this->get_sum("order_amount_usd",[
            'pay_time <'=>$timePeriod['end'],
            'customer_id'=>$users,
            'order_prop'=>array('0','2'),
            'status'=>array('3','4','5','6'),
        ]);
        $mallProfitTps = isset($mallProfitTps['order_amount_usd'])?$mallProfitTps['order_amount_usd']/100:0;
        return $mallProfitTps;
    }

    /**
     * 获取用户的订单总金额 （含升级的订单+普通零售订单）
     * @author brady
     * @param $users
     */
    public function getUsersOrderAmountArr($users)
    {
        $this->load->model("m_profit");

        $timePeriod = $this->m_profit->__getLastDayPeriod();
        //商城订单
//        $arr = $this->db->select('sum(a.order_amount_usd) as total_amount,customer_id')
//            ->from('trade_orders as a')
//            ->where('a.pay_time <',$timePeriod['end'])
//            ->where_in('customer_id',$users)
//            ->where_in('order_prop',array('0','2'))
//            ->where_in('status',array('3','4','5','6'))
//            ->group_by('customer_id')
//            ->get()
//            ->result_array();
        $arr = $this->get_list_auto([
            "select"=>"sum(a.order_amount_usd) as total_amount,customer_id",
            "where"=>[
                'pay_time <'=>$timePeriod['end'],
                'customer_id'=>$users,
                'order_prop'=>array('0','2'),
                'status'=>array('3','4','5','6'),
            ],
            "group_by"=>["customer_id"],
            "cache"=>0
        ]);
        $new_arr = [];
        if (!empty($arr)) {
            foreach($arr as $v) {
                $new_arr[$v['customer_id']] = $v['total_amount'];
            }
        }
        return $new_arr;
    }

    /**
     * 根据订单ID查询子订单
     * @author: derrick
     * @date: 2017年4月17日
     * @param: @param unknown $order_id
     * @reurn: mixed
     */
    public function find_children_orders_by_order_id($order_id) {
//    	return $this->db->from('trade_orders')->where('attach_id', $order_id)->where('order_prop', '1')->get()->result_array();
    	return $this->get_list("*",['attach_id'=>$order_id,'order_prop'=>'1']);
    }

    /**
     * notify_num自增操作
     * @param $order_id
     * @param int $nums
     */
    public function notify_num_plus($order_id,$nums=1)
    {
        $table_ext = $this->get_table_ext($order_id);
        $this->db->where('order_id', $order_id)->set('notify_num', "notify_num+{$nums}", FALSE)->update('trade_orders'.$table_ext);
    }

    /**
     * @param $order_id
     */
    public function lock_one_trade_orders($order_id)
    {
        $table_ext = $this->get_table_ext($order_id);
        $this->db->query("SELECT order_id FROM `trade_orders{$table_ext}` WHERE `order_id` = '{$order_id}' FOR UPDATE");
    }

    /**
     * @param $order_id_str
     */
    public function lock_batch_trade_orders($order_id_str)
    {
        $order_arr = explode(",",$order_id_str);
        foreach($order_arr as $k=>$v)
        {
            $table_ext = $this->get_table_ext($v);
            $this->db->query("SELECT `order_id` FROM `trade_orders{$table_ext}` WHERE `order_id` = '{$v}' FOR UPDATE");
        }
    }

    /**
     * 取trade_orders_goods为空的trade_orders数据,//todo:://未改分表拆表
     * @return mixed
     */
    public function get_empty_mall_goods_trade_orders()
    {
        $sql = "select ts.order_id,ts.goods_list,ts.customer_id from trade_orders ts 
left join trade_orders_goods tos on ts.order_id=tos.order_id where tos.order_id is null";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 创建trade_orders_xxxx表
     * @param $table_name
     */
    public function create_table($table_name)
    {
        if($this->table_exists($table_name))
        {
            return;
        }
        $sql = "CREATE TABLE `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `order_prop` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单性质,0 普通订单; 1 拆单后的子订单; 2 拆单的子订单所关联的主订单; 3 普通订单合单后的主订单',
  `attach_id` char(19) NOT NULL COMMENT '关联订单ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(11) NOT NULL DEFAULT '0' COMMENT '店主id',
  `area` char(3) NOT NULL DEFAULT '001' COMMENT '区域代码。一般为国家代码，东南亚地区为001，其他地区为000',
  `deliver_time_type` tinyint(4) DEFAULT '1' COMMENT '送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日',
  `expect_deliver_date` date DEFAULT NULL COMMENT '预计发货时间',
  `need_receipt` tinyint(4) DEFAULT '0' COMMENT '是否需要收据。0 不需要；1 需要',
  `currency` char(3) DEFAULT 'USD' COMMENT '币种, USD,RMB',
  `payment_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式。0 未支付；1 选择套装; 2 代品券换购; 105 支付宝；106 银联；107 paypal；108 ewallet；109 银盛；110 余额支付；111 銀聯國際;',
  `currency_rate` decimal(12,6) DEFAULT '1.000000' COMMENT '下单时兑美元汇率',
  `discount_type` tinyint(4) DEFAULT '0' COMMENT '折扣类型。0 无折扣；1 获取代品券，2使用代品券',
  `goods_amount` int(11) NOT NULL DEFAULT '0' COMMENT '商品总金额。单位：分',
  `deliver_fee` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费。单位：分',
  `order_amount` int(11) NOT NULL DEFAULT '0' COMMENT '订单实付金额。单位：分',
  `goods_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '商品金额（美元）。单位：分',
  `deliver_fee_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费（美元）。单位：分',
  `discount_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '折扣金额（美元）。单位：分',
  `order_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单金额（美元）。单位：分',
  `order_profit_usd` int(11) DEFAULT '0' COMMENT '订单利润（美元）。单位：分',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `txn_id` varchar(30) NOT NULL DEFAULT '' COMMENT '交易号',
  `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `store_code` varchar(10) DEFAULT '' COMMENT '仓库简码',
  `deliver_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发货时间',
  `receive_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '收货时间',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '订单状态。1 正在发货中；2 等待付款；3 等待发货；4 等待收货；5 等待评价；6 已完成；90 冻结；97 退货中；98 退货完成；99 订单取消；100 拆分（主单专属）；111 doba异常订单',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `is_export_lock` tinyint(4) DEFAULT '0' COMMENT '导出订单后，锁定，就不能取消订单。0：未锁定，1：锁定',
  `freight_no` varchar(20) DEFAULT '' COMMENT '物流单号',
  `is_doba_order` tinyint(1) DEFAULT '0' COMMENT '订单中是否含有doba产品, 0[没有], 1[有]',
  `doba_supplier_id` varchar(12) DEFAULT NULL COMMENT 'doba店铺ID',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商id',
  `shipper_id` int(11) NOT NULL DEFAULT '0' COMMENT '发货商id',
  `order_type` tinyint(4) NOT NULL DEFAULT '4' COMMENT '订单类型(0=>未定义，1=>选购，2=>升级，3=>代品券，，4=>普通订单, 5=>换货订单)',
  `score_year_month` char(6) NOT NULL COMMENT '业绩月份',
  `order_from` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单来源,1[PC],2[ios],3[android]',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQE_order_id` (`order_id`),
  KEY `INDEX_CREATED_AT` (`created_at`),
  KEY `INDEX_STATUS` (`status`),
  KEY `IDX_ATTACH_ID` (`attach_id`),
  KEY `idx_shopkeeper_id` (`shopkeeper_id`),
  KEY `customer_id` (`customer_id`),
  KEY `area` (`area`),
  KEY `pay_time` (`pay_time`),
  KEY `payment_type` (`payment_type`),
  KEY `deliver_time` (`deliver_time`),
  KEY `txn_id` (`txn_id`),
  KEY `idx_order_prop`(`order_prop`,`order_type`,`pay_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表'";

        $this->db->query($sql);

        //创建表则需要删除缓存
        $redis_key = "SCHEMA:TABLES:*";
        $array_keys = $this->redis_keys($redis_key);
        foreach($array_keys as $v)
        {
            $this->redis_del($v);
        }
    }

    /**
     * 取当前表的所有字段,因为可能分表，
     * 也可能未分表，暂直接根据表名取，这里暂加_tmp代替
     * @param string $table_name
     * @return array
     */
    public function column_list_tmp($table_name)
    {
        $res[] ="id";
        $res[] ="order_id";
        $res[] ="order_prop";
        $res[] ="attach_id";
        $res[] ="customer_id";
        $res[] ="shopkeeper_id";
        $res[] ="area";
        $res[] ="deliver_time_type";
        $res[] ="expect_deliver_date";
        $res[] ="need_receipt";
        $res[] ="currency";
        $res[] ="payment_type";
        $res[] ="currency_rate";
        $res[] ="discount_type";
        $res[] ="goods_amount";
        $res[] ="deliver_fee";
        $res[] ="order_amount";
        $res[] ="goods_amount_usd";
        $res[] ="deliver_fee_usd";
        $res[] ="discount_amount_usd";
        $res[] ="order_amount_usd";
        $res[] ="order_profit_usd";
        $res[] ="created_at";
        $res[] ="txn_id";
        $res[] ="pay_time";
        $res[] ="store_code";
        $res[] ="deliver_time";
        $res[] ="receive_time";
        $res[] ="status";
        $res[] ="updated_at";
        $res[] ="is_export_lock";
        $res[] ="freight_no";
        $res[] ="is_doba_order";
        $res[] ="doba_supplier_id";
        $res[] ="supplier_id";
        $res[] ="shipper_id";
        $res[] ="order_type";
        $res[] ="score_year_month";
        $res[] ="order_from";
        return $res;
    }

    /**
     * 在指定的列上加表名用于区别该列是从哪个表获取，
     * 用于left join 避免字段归属不清问题
     * @param $params
     * @param $table_name
     * @param string $column
     * @return array
     */
    public function plus_table_2_select($params,$table_name,$column="order_id")
    {
        $select_arr = $this->format_select($params);
        $res_select = [];
        foreach($select_arr as $k=>$v)
        {
            if(strrpos($column,$v) > -1)
            {
                $res_select[] = $table_name.".".$v;
            }else{
                $res_select[] = $v;
            }
        }
        return $res_select;
    }

    /**
     * 添加条件到where条件的字段里，以区分表
     * @param $params
     * @param $table_name
     * @param string $column
     * @return mixed
     */
    public function plus_table_2_where($params,$table_name,$column="order_id")
    {
        $res = [];
        foreach($params as $k=>$v)
        {
            if($v != null) {
                //非复杂条件
                if ($column == $k) {
                    $res[$table_name . "." . $k] = $v;
                } else {
                    $res[$k] = $v;
                }
            }else{
                //如果是复杂条件，只替换指定的字段
                $key = str_replace('`'.$column.'`','`'.$table_name."`.".'`'.$column.'`',$k);
                $res[$key] = $v;
            }
        }
        return $res;
    }


    /**
     * 格式化select
     * @param $params
     * @return array
     */
    public function format_select($params)
    {
        $select_arr = [];
        if(is_string($params))
        {
            $select_arr = explode(",",$params);
        }
        elseif(is_array($params))
        {
            $select_arr = $params;
        }
        foreach($select_arr as $k=>$v)
        {
            //移除开头的空串
            $select_arr[$k] = preg_replace("/^[\\s]+/i","",$v);
            //移除结尾的空串
            $select_arr[$k] = preg_replace("/[\\s]+$/i","",$select_arr[$k]);
        }
        return $select_arr;
    }

    /**
     * 将select条件分入两个不同的表
     * 强烈建议不要使用select*号，要哪个字段就select哪个字段
     * @param $params
     * @return array
     */
    public function split_select($params)
    {
//        if(!$this->get_table_ext())
//        {
////            var_dump("TICOWONG");exit(__FILE__.",".__LINE__."<BR>");
//            //如果未启用分表，直接返回
//            return ["trade_orders"=>$params];
//        }
        $select_arr = $this->format_select($params);
        if(in_array("*",$select_arr))
        {
            return ["trade_orders"=>"*","trade_orders_info"=>"*"];
        }

        $trade_orders_arr = [];
        $trade_orders_table = $this->table_name.$this->get_table_ext();
        $this->create_table($trade_orders_table);//检测数据表，如果没有就创建
        $trade_orders_columns = $this->column_list($trade_orders_table);//取表的所有列

        $trade_orders_info_arr = [];
        $trade_orders_info_table = $this->tb_trade_orders_info->get_table_name().$this->get_table_ext();
        $this->tb_trade_orders_info->create_table($trade_orders_info_table);//检测数据表，如果没有就创建
        $trade_orders_info_columns = $this->tb_trade_orders_info->column_list($trade_orders_info_table);//取表的所有列

        foreach($select_arr as $k=>$v)
        {
            if(array_intersect(explode(" ",$v),$trade_orders_columns))
            {
                $trade_orders_arr[] = $v;
            }
            if(array_intersect(explode(" ",$v),$trade_orders_info_columns))
            {
                $trade_orders_info_arr[] = $v;
            }
        }
        $res = [];
        if($trade_orders_arr)
        {
            $res[$this->table_name] = $trade_orders_arr;
        }
        if($trade_orders_info_arr)
        {
            $res[$this->tb_trade_orders_info->get_table_name()] = $trade_orders_info_arr;
        }
        return $res;
    }

    /**
     * 将复杂条件分解成数组
     * @param $where
     * @return array
     */
    public function handle_complex_where($where)
    {
        $res = [];
        $tmp1 = explode(" or ",$where);
        foreach($tmp1 as $v)
        {
            $tmp2 = explode(" and ",$v);
            if(count($tmp2) == 1)
            {
                $res[] = $v;
            }else{
                foreach($tmp2 as $v2)
                {
                    $res[] = $v2;
                }
            }
        }
        return $res;
    }

    /**
     * 根据params条件将参数条件分入两个不同的表
     * @param $params
     * @return mixed
     */
    public function split_params($params)
    {
        $table_ext = $this->get_table_ext();
//        if(!$table_ext)
//        {
//            //如果未启用分表，直接返回
//            return [$this->table_name=>$params];
//        }
        $select_arr = [];
        if(is_string($params))
        {
            $select_arr = explode(",",$params);
        }
        elseif(is_array($params))
        {
            $select_arr = $params;
        }

        $trade_orders_arr = [];
        $trade_orders_table = $this->table_name.$table_ext;
        $this->create_table($trade_orders_table);//检测数据表，如果没有就创建
        $trade_orders_columns = $this->column_list($trade_orders_table);//取表的所有列

        $trade_orders_info_arr = [];
        $trade_orders_info_table = $this->tb_trade_orders_info->get_table_name().$table_ext;
        $this->tb_trade_orders_info->create_table($trade_orders_info_table);//检测数据表，如果没有就创建
        $trade_orders_info_columns = $this->column_list($trade_orders_info_table);//取表的所有列
//        var_dump($trade_orders_info_table);var_dump($trade_orders_info_columns);exit(__FILE__.",".__LINE__."<BR>");
        foreach($select_arr as $k=>$v)
        {
            //分入trade_orders表的数据
            if(array_intersect(explode(" ",$k),$trade_orders_columns))
            {
                $trade_orders_arr[$k] = $v;
            }
            //分入trade_orders_info表的数据
            if(array_intersect(explode(" ",$k),$trade_orders_info_columns))
            {
                $trade_orders_info_arr[$k] = $v;
            }
            //将传入的复杂的条件分解
            if($v === null)
            {
                $tmp = $this->handle_complex_where($k);
                //判断复杂条件是否全属于trade_orders表
                $is_trade_orders = false;
                foreach($tmp as $kt=>$vt)
                {
                    foreach($trade_orders_columns as $vc)
                    {
                        if(strpos($vt,$vc) > -1)
                        {
                            $is_trade_orders = true;
                            break;
                        }
                    }
                }
                if($is_trade_orders)
                {
                    $trade_orders_arr[$k] = $v;
                }
                //todo::判断复杂条件是否全属于trade_orders_info表
            }
//            else{
//                $this->debug("过滤掉条件：".$k."=>".$v,1);
//            }
        }
        $res = [];
        if($trade_orders_arr)
        {
            $res[$this->table_name] = $trade_orders_arr;
        }
        if($trade_orders_info_arr)
        {
            $res[$this->tb_trade_orders_info->get_table_name()] = $trade_orders_info_arr;
        }
        return $res;
    }

    /**
     * 根据条件获取指定定条件之集
     * @param $params
     * @param string $key
     * @return array
     */
    public function get_params_list($params,$key="order_id")
    {
        $res = [];
        foreach($params as $k=>$v)
        {
            if(strrpos($k,$key) > -1)
            {
                if(is_array($v))
                {
                    foreach($v as $d)
                    {
                        $res[] = $d;
                    }
                }else{
                    if($v === NULL)
                    {
                        //如果传入复杂条件，
                        //形如：["`order_id` = '$order_id' or `attach_id` = '$order_id'"=>null]
                        //分解并取数据
                        preg_match("/{$key}[^=]+=[^']+'(([^'])+)'/i",$k,$match);
                        if(isset($match[1]))
                        {
                            $res[] = $match[1];
                        }
                    }else{
                        $res[] = $v;
                    }
                }
            }
        }
        return $res;
    }

     //****************************************************
     //*                    重写基类                      *
     //****************************************************

    /**
     * 重写get_one，扩展按月分表并拆表
     * 条件必传order_id,否则不做处理，
     * 若判断是否存在订单请改用get_counts，可不传order_id条件
     * 若传入多个order_id的话只会拿第一个
     * @param string $select
     * @param array $where
     * @param array $or_where
     * @param array $order_by
     * @param int $force_master
     * @param int $cache
     * @return array|bool|mixed
     */
    public function get_one($select="*",$where=[],$or_where=[],$order_by=[],$force_master=1,$cache=1)
    {
        //该表查询强制主库
        $force_master = 1;
        $order_id = 0;
        if($tmp = $this->get_params_list($where))
        {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }elseif($tmp = $this->get_params_list($or_where)) {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }
        if(!$order_id)
        {
            log_message("ERROR","tb_trade_orders's get_one function must have order_id params");
            return [];
        }
        $table_ext = $this->get_table_ext($order_id);
        //过滤掉并不存在的表后缀
        $table_ext = $this->filter_table_ext($table_ext,$this->table_name);
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            extract($defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        //如果是未分表拆表的订单
        if(!$table_ext)
        {
            $this->db->select($select);
            $this->_handle_where($this->db,$where);
            $this->_handle_or_where($this->db,$or_where);
            $this->_handle_order_by($this->db, $order_by);
        }else{
            //如果是已分表拆表的数据
            $select_array = $this->split_select($select);
            if(isset($select_array[$this->table_name])){
                $select = $select_array[$this->table_name];
                $this->db->select($select);
            }

            $where_array = $this->split_params($where);
            if(isset($where_array[$this->table_name])){
                $where = $where_array[$this->table_name];
                $this->_handle_where($this->db,$where);
            }

            $or_where_array = $this->split_params($or_where);
            if(isset($or_where_array[$this->table_name]))
            {
                $or_where = $or_where_array[$this->table_name];
                $this->_handle_or_where($this->db,$or_where);
            }

            $order_by_array = $this->split_params($order_by);
            if(isset($order_by_array[$this->table_name])) {
                $order_by = $order_by_array[$this->table_name];
                $this->_handle_order_by($this->db, $order_by);
            }
        }
        if($force_master)
        {
            $this->db->force_master();
        }
        $res =  $this->db->get($this->table_name.$table_ext);
        if($res)
        {
            $res = $res->row_array();
            $this->debug($this->db->last_query().",".__LINE__);
            //如果是已分表拆表的数据
            if($table_ext) {
                if (isset($select_array[$this->tb_trade_orders_info->get_table_name()])) {
                    if ($res) {
                        $trade_order_info_table_name = $this->tb_trade_orders_info->get_table_name();
                        $tmp_or_where = isset($or_where_array[$trade_order_info_table_name]) ?
                            $or_where_array[$trade_order_info_table_name] : [];
                        $tmp_order_by = isset($order_by_array[$trade_order_info_table_name]) ?
                            $order_by_array[$trade_order_info_table_name] : [];
                        $tmp = $this->tb_trade_orders_info->get_one(
                            $select_array[$trade_order_info_table_name],
                            $where_array[$trade_order_info_table_name],
                            $tmp_or_where,
                            $tmp_order_by,
                            $force_master,
                            $cache
                        );
                        $this->debug($this->db->last_query() . "," . __LINE__);
                        if ($tmp) {
                            $res = array_merge($res, $tmp);
                        }
                    }
                }
            }
        }else{
            log_message("ERROR","no data found in get_one by :".var_export($defined_vars,true));
            $res = false;
        }
        if($cache) {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars, $res);
            if ($cache_data) {
                log_message("ERROR", "get_one_return_from_cache:" . var_export($cache_data, true));
                return $cache_data;
            }
        }
        return $res;
    }

    /**
     * 重写get_list，以扩展按月分表并拆表
     * 建议使用order_id或attach_id，否则将连接较多的数据表
     * 不能传入trade_orders_info表的字段作为group_by字段
     * @param string $select
     * @param array $where
     * @param array $or_where
     * @param int $page_size
     * @param int $page_index
     * @param array $order_by
     * @param int $force_master
     * @param int $cache
     * @param array $group_by
     * @return array|mixed
     */
    public function get_list($select="*",$where=[],$or_where=[],$page_size=1000,$page_index=0,$order_by=[],$force_master=0,$cache=1,$group_by=[])
    {
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            extract($defined_vars);
            if($cache_data)
            {
                $cache_data = $this->primary_key_replace_list($cache_data);
                return $cache_data;
            }
        }
        $order_id = [];
        //取条件中所有的order_id
        if($tmp = $this->get_params_list($where))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        if($tmp = $this->get_params_list($or_where))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        //取条件中所有的attach_id
        if($tmp = $this->get_params_list($where,"attach_id"))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        if($tmp = $this->get_params_list($or_where,"attach_id"))
        {
            $order_id = array_merge($tmp,$order_id);
        }
        //若传入了$order_id或$attach_id，则由这个数据计算所有需要查询的表的后缀
        $table_ext_list = [];
        if($order_id)
        {
            foreach($order_id as $v)
            {
                $ext = $this->get_table_ext($v);
                $this->debug("input:".$v.",ext:".$ext.",".__LINE__);
                if(!in_array($ext,$table_ext_list))
                {
                    $table_ext_list[] = $ext;
                }
            }
            //过滤拼凑出的不存在的表后缀
            $table_ext_list = $this->filter_table_ext($table_ext_list,$this->table_name);
        }
        //将select转换成数组
        $select = $this->format_select($select);

        //分割传入的参数到两个表中
        $select_array = $this->split_select($select);

        $where_array = $this->split_params($where);
//        var_dump($where_array);exit("<br>".__FILE__.",".__LINE__."<BR>");
        $or_where_array = $this->split_params($or_where);
        $order_by_array = $this->split_params($order_by);
        $group_by_array = $this->split_params($group_by);//不能传入trade_orders_info表的字段作为group_by字段

        //如果有order_by，需要加入select中，否则union all后无法排序
        if(isset($order_by_array[$this->table_name]) && $order_by_array[$this->table_name])
        {
            foreach($order_by_array[$this->table_name] as $k=>$v)
            {
                if(!in_array($k,$select_array[$this->table_name]))
                {
                    $select_array[$this->table_name][] = $k;
                }
                if(!in_array($k,$select))
                {
                    $select[] = $k;
                }
            }
        }
        //如果有order_by，需要加入select中，否则union all后无法排序
        if(isset($order_by_array[$this->tb_trade_orders_info->get_table_name()]))
        {
            foreach($order_by_array[$this->tb_trade_orders_info->get_table_name()] as $k=>$v)
            {
                if(!in_array($k,$select_array[$this->tb_trade_orders_info->get_table_name()]))
                {
                    $select_array[$this->tb_trade_orders_info->get_table_name()][] = $k;
                }
                if(!in_array($k,$select))
                {
                    $select[] = $k;
                }
            }
        }
        //如果有group_by，需要加入select中，否则union all后无法排序
        //todo::暂未发现有group_by数据需求

        //判断是否有跨表的字段需要查询
        if(isset($select_array[$this->tb_trade_orders_info->get_table_name()]))
        {
            //若有跨表字段,但又没查order_id字段，则需要先补上,用于拉取跨表数据
            if(isset($select_array[$this->table_name]))
            {
                $select_tmp = $select_array[$this->table_name];
                if(is_string($select_tmp) && strrpos("*",$select_tmp) == -1)
                {
                    $select_array[$this->table_name] .= ",order_id";
                }
                if(is_array($select_tmp) && !in_array("*",$select_tmp))
                {
                    $select_tmp[$this->table_name][] = "order_id";
                }
            }
        }

        //判断跨不同结构的表的select问题
        if(in_array("",$table_ext_list) && count($table_ext_list) > 1)
        {
            if(isset($select_array[$this->table_name]))
            {
                $select_tmp = $select_array[$this->table_name];
                if(is_array($select_tmp))
                {
                    if(in_array("*",$select_tmp))
                        exit("todo:://跨不同规格的表进行查询，不允许select * 操作。");

                }elseif(is_string($select_tmp))
                {
                    if(strrpos("*",$select_tmp) > -1)
                        exit("todo:://跨不同规格的表进行查询，不允许select * 操作。");
                }
            }
        }

        //如果传了order_id或attch_id，可推出需要查的表
        if($table_ext_list)
        {
            $sql = "";
            foreach($table_ext_list as $v)
            {
                $need_join_table = false;
                if(isset($select_array[$this->tb_trade_orders_info->get_table_name()]) or
                    isset($where_array[$this->tb_trade_orders_info->get_table_name()]) or
                    isset($or_where_array[$this->tb_trade_orders_info->get_table_name()]) or
                    isset($order_by_array[$this->tb_trade_orders_info->get_table_name()]))
                {
                    $need_join_table = true;
                }
                //如果是有后缀的表
                if($v != "")
                {
                    if(isset($select_array[$this->table_name])){
                        $select_tmp = $select_array[$this->table_name];
                        //为left join 准备区别order_id
                        if($need_join_table){
                            $select_tmp = $this->plus_table_2_select($select_tmp, $this->table_name.$v, "order_id");
                        }
                        $this->db->select($select_tmp);
                    }
                    if(isset($where_array[$this->table_name])){
                        $where_tmp = $where_array[$this->table_name];
                        //为left join 准备区别order_id
                        if($need_join_table){
                            $where_tmp = $this->plus_table_2_where($where_tmp,$this->table_name.$v,"order_id");
                        }
                        $this->_handle_where($this->db,$where_tmp);
//                        var_dump("TICOWONG");exit;
                    }
                    if(isset($or_where_array[$this->table_name])){
                        $or_where_tmp = $or_where_array[$this->table_name];
                        //为left join 准备区别order_id
                        if($need_join_table){
                            $or_where_tmp = $this->plus_table_2_where($or_where_tmp,$this->table_name.$v,"order_id");
                        }
                        $this->_handle_or_where($this->db,$or_where_tmp);
                    }
                    //如果有传入第二个表的字段，需要连接第二个表
                    if($need_join_table)
                    {
                        $this->db->join(
                            $this->tb_trade_orders_info->get_table_name() . $v,
                            $this->tb_trade_orders_info->get_table_name() . $v . ".order_id=".$this->table_name."$v.order_id",
                            "left"
                        );
                    }
                    $tmp = $this->db->get_compiled_select($this->table_name.$v,true);
                    if($sql)
                    {
                        $sql .= " union all \n";
                    }
                    $sql .= $tmp;
                }else{
                    //如果是没后缀的旧表
                    $this->db->select($select);
                    $this->_handle_where($this->db,$where);
                    $this->_handle_or_where($this->db,$or_where);
                    $tmp = $this->db->get_compiled_select($this->table_name.$v,true);
                    if($sql)
                    {
                        $sql .= " union all \n";
                    }
                    $sql .= $tmp;
                }
            }
            //处理传入的参数order_by
            if(isset($order_by_array[$this->table_name])) {
                $order_by = $order_by_array[$this->table_name];
                if($order_by)
                {
                    $sql .= " order by ";
                }
                $order_index = 0;
                foreach($order_by as $k=>$v)
                {
                    if(!$v)
                    {
                        $v = "desc";
                    }
                    if($order_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k." ".$v;
                    $order_index += 1;
                }
            }
            //处理传入的group_by参数
            if(isset($group_by_array[$this->table_name])) {
                $group_by = $group_by_array[$this->table_name];
                if($group_by)
                {
                    $sql .= " group by ";
                }
                $group_by_index = 0;
                foreach($group_by as $k=>$v)
                {
                    if($group_by_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k;
                    $group_by_index += 1;
                }
            }

            if($page_size)
            {
                $sql .= " limit ".$page_index.",".$page_size;
            }
            $this->debug($sql.",".__LINE__);
            $trade_order_res = $this->db->query($sql)->result_array();
            //如果有主表数据
            if($trade_order_res)
            {
                //而且查询了副表数据,如果是数组要大于1，如果是字符串*号也要查副表数据
                if((isset($select_array[$this->tb_trade_orders_info->get_table_name()]) &&
                    count($select_array[$this->tb_trade_orders_info->get_table_name()]) > 1) ||
                    ( isset($select_array[$this->tb_trade_orders_info->get_table_name()]) &&
                        $select_array[$this->tb_trade_orders_info->get_table_name()] == "*"))
                {
                    $trade_order_info_table_name = $this->tb_trade_orders_info->get_table_name();
                    //根据order_id拿出所有需要查询的副表数据
//                    $order_ids = array_column($trade_order_res,"order_id");
                    foreach($trade_order_res as $k=>$v)
                    {
                        $order_ids[] = $v["order_id"];
                    }

                    $sql = "";
                    foreach($table_ext_list as $v)
                    {
                        if(isset($select_array[$trade_order_info_table_name])){
                            $select = $select_array[$trade_order_info_table_name];
                            $this->db->select($select);
                        }
                        $this->db->where_in("order_id",$order_ids);
                        if($v)
                        {
                            //如果是分表的，在分表里查询
                            $tmp = $this->db->get_compiled_select($trade_order_info_table_name.$v,true);
                        }else{
                            //如果不是分表的，依旧在主表查询
                            $tmp = $this->db->get_compiled_select($this->table_name.$v,true);
                        }
                        if($sql)
                        {
                            $sql .= " union all \n";
                        }
                        $sql .= $tmp;
                    }
                    $this->debug($sql.",".__LINE__);
                    $trade_order_info_res = $this->db->query($sql)->result_array();
                    //合并分表查询的数据到主表
                    foreach($trade_order_res as $k=>$v)
                    {
                        foreach($trade_order_info_res as $ki=>$vi)
                        {
                            if($vi['order_id'] == $v['order_id'])
                            {
                                $trade_order_res[$k] = array_merge($v,$vi);
                                unset($trade_order_info_res[$ki]);
                                continue;
                            }
                        }
                    }
                }
            }else{
                $this->debug($sql.",".__LINE__,1);//强制记录日志
            }
            return $trade_order_res;
        }
        else
        {
            //如果没传入order_id或attch_id，则需要遍历所有分表
            $table_list = $this->table_list("trade_orders(_[0-9]+)?$");
            $now_scan = 0;
            $sql = "";

            foreach($table_list as $v)
            {
                if($now_scan >= $this->max_scan)
                {
                    //最大月份的union all数限制，默认只联一年内的数据
                    break;
                }
                if($v != "trade_orders")
                {
                    //如果查询的是分表数据，而且查询了副表数据
                    if(isset($select_array[$this->tb_trade_orders_info->get_table_name()]) &&
                        count($select_array[$this->tb_trade_orders_info->get_table_name()]) > 1) {
                        //如果有left join where跟or_where条件需要指定order_id是哪个表的字段
                        $where = $this->plus_table_2_where($where,$v,"order_id");
                        $or_where = $this->plus_table_2_where($or_where,$v,"order_id");

                        $this->_handle_where($this->db,$where);
                        $this->_handle_or_where($this->db,$or_where);

                        //为left join 准备区别order_id
                        $tmp_select = $this->plus_table_2_select($select, $v, "order_id");
                        $this->db->select($tmp_select);
                        preg_match("/(_([0-9]+)?)$/", $v, $ext_arr);
                        if (isset($ext_arr[2])) {
                            $this->db->join(
                                $this->tb_trade_orders_info->get_table_name() . "_" . $ext_arr[2],
                                $this->tb_trade_orders_info->get_table_name() . "_" . $ext_arr[2] . ".order_id=$v.order_id",
                                "left"
                            );
                        }
                    }else{
                        $this->_handle_where($this->db,$where);
                        $this->_handle_or_where($this->db,$or_where);
                        //直接查分表数据不连接副表数据
                        $this->db->select($select);
                    }
                }else{
                    $this->_handle_where($this->db,$where);
                    $this->_handle_or_where($this->db,$or_where);
                    $this->db->select($select);
                }
                $tmp = $this->db->get_compiled_select($v,true);

                if($sql)
                {
                    $sql .= " union all \n";
                }
                $sql .= $tmp;
                $now_scan += 1;
            }

            if(isset($order_by_array[$this->table_name])) {
                $order_by = $order_by_array[$this->table_name];
                if($order_by)
                {
                    $sql .= " order by ";
                }
                $order_index = 0;
                foreach($order_by as $k=>$v)
                {
                    if(!$v)
                    {
                        $v = "desc";
                    }
                    if($order_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k." ".$v;
                    $order_index += 1;
                }
            }
            if(isset($group_by_array[$this->table_name])) {
                $group_by = $group_by_array[$this->table_name];
                if($group_by)
                {
                    $sql .= " group by ";
                }
                $group_by_index = 0;
                foreach($group_by as $k=>$v)
                {
                    if($group_by_index > 0)
                    {
                        $sql .= ",";
                    }
                    $sql .= $k;
                    $group_by_index += 1;
                }
            }
            if($page_size)
            {
                $sql .= " limit ".$page_index.",".$page_size;
            }
            $this->debug($sql.",".__LINE__);
            $tmp = $this->db->query($sql);
            $trade_order_res = [];
            if($tmp)
            {
                $trade_order_res =  $tmp->result_array();
            }else{
                log_message("ERROR",$sql);
                $this->debug($sql.",".__LINE__,1);//强制记录日志
            }
            return $trade_order_res;
        }
    }

    /**
     * 重写update_one，以扩展按月分表并拆表的更新
     * 注意：只有传入order_id条件才能更新拆表trader_orders_info表的数据
     * @param array $where
     * @param array $data
     * @param array $or_where
     * @param int $cache
     * @return mixed
     */
    public function update_one($where=[],$data=[],$or_where=[],$cache=1)
    {
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            log_message("ERROR","更新数据却未传入需要更新的数据，影响表:".$this->table_name);
            return -1;
        }
        if(1 > count($where))
        {
            log_message("ERROR","未传入条件恐怕会更新整表而不是更新一条的数据，影响表:".$this->table_name);
            return -2;
        }
        $order_id = 0;
        if($tmp = $this->get_params_list($where))
        {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }elseif($tmp = $this->get_params_list($or_where)) {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }elseif($tmp = $this->get_params_list($where,"attach_id")) {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }elseif($tmp = $this->get_params_list($or_where,"attach_id")) {
            //若传入多个order_id的话只会拿第一个
            $order_id = $tmp[0];
        }

        if(!$order_id)
        {
//            var_dump("tb_trade_orders's update_one function must have order_id or attach_id params");exit(__FILE__.",".__LINE__."<BR>");
            log_message("ERROR","tb_trade_orders's update_one function must have order_id or attach_id params");
            return -3;
        }
        $table_ext = $this->get_table_ext($order_id);

        $this->debug("input:".$order_id.",ext:".$table_ext.",".__LINE__);

        $where_array = $this->split_params($where);
        if(isset($where_array[$this->table_name])){
            $where = $where_array[$this->table_name];
            $this->_handle_where($this->db,$where);
        }

        $or_where_array = $this->split_params($or_where);
        if(isset($or_where_array[$this->table_name]))
        {
            $or_where = $or_where_array[$this->table_name];
            $this->_handle_or_where($this->db,$or_where);
        }

        $this->db->limit(1);//限制一条

        $data_array = $this->split_params($data);

        if($data_array[$this->table_name])
        {
            $res =  $this->db->update($this->table_name.$table_ext, $data_array[$this->table_name]);
            $this->debug("res:".$res.",||".$this->db->last_query()."||,".__FILE__.",".__LINE__."<BR>");
        }else{
            $this->db->get_compiled_select($this->table_name.$table_ext,true);
            $res = 0;
        }

        if(isset($where_array[$this->tb_trade_orders_info->get_table_name()]))
        {
            $table_info = $this->tb_trade_orders_info->get_table_name();
            if(isset($where_array[$table_info])){
                $where = $where_array[$table_info];
                $this->_handle_where($this->db,$where);
            }
            if(isset($or_where_array[$table_info]))
            {
                $or_where = $or_where_array[$table_info];
                $this->_handle_or_where($this->db,$or_where);
            }

            $this->db->limit(1);//限制一条

            if($table_ext) //如果是分表数据
            {
                //如果更新了分表数据
                if(isset($data_array[$table_info]) && $data_array[$table_info]){
                    $res =  $this->db->update($table_info.$table_ext, $data_array[$table_info]);
                    $this->debug($this->db->last_query().",".__FILE__.",".__LINE__."<BR>");
                }else{
                    //重置条件，否则会污染$this->db
                    $this->db->get_compiled_select($table_info.$table_ext,true);
                }
            }else{//非分表数据依旧更新主表
                //如果有待更新的数据
                if(isset($data_array[$table_info]) && $data_array[$table_info]) {
                    $res = $this->db->update($this->table_name . $table_ext, $data_array[$table_info]);
                    $this->debug($this->db->last_query().",".__FILE__.",".__LINE__."<BR>");
                }else{
                    //重置条件，否则会污染$this->db
                    $this->db->get_compiled_select($table_info.$table_ext,true);
                }
            }
        }

        if($cache)
        {
            $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
        }
        return $res;
    }

    /**
     * 重写update_batch，以扩展按月分表并拆表,的更新
     * 注意：只有传入order_id条件才能更新trader_orders_info表的数据
     * @param array $where
     * @param array $data
     * @param array $or_where
     * @param int $cache
     * @param string $index
     * @return CI_DB_active_record|CI_DB_result|int
     */
    public function update_batch($where=[],$data=[],$or_where=[],$cache=1,$index="")
    {
        $defined_vars = get_defined_vars();
        if($cache) {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            log_message("ERROR","更新数据却未传入需要更新的数据，影响表:".$this->table_name);
            return -1;
        }
        if($index)
        {
            $res = 0;
            foreach($data as $k=>$v)
            {
                if(isset($v[$index]))
                {
                    $tmp_where = [$index=>$v[$index]];
                    unset($v[$index]);
                    $tmp_data = $v;
                    //使用update_one自动适应分表拆表更新
                    $res += $this->update_one($tmp_where,$tmp_data);
                }
            }
        }
        else
        {
            if (1 > count($where)) {
                log_message("ERROR", "未传入条件恐怕会更新整表而不是更新一批的数据，影响表:" . $this->table_name);
                return -2;
            }

            $order_id = [];
//            if($tmp = $this->get_params_list($where,"order_id"))
//            {
//                //若传入多个order_id的话只会拿第一个，用于获取表名后缀,涉及到多表操作请传入index进行批量更新
//                $order_id = $tmp[0];
//            }elseif($tmp = $this->get_params_list($or_where,"order_id")) {
//                //若传入多个order_id的话只会拿第一个，用于获取表名后缀,涉及到多表操作请传入index进行批量更新
//                $order_id = $tmp[0];
//            }elseif($tmp = $this->get_params_list($where,"attach_id")) {
//                //若传入多个order_id的话只会拿第一个，用于获取表名后缀,涉及到多表操作请传入index进行批量更新
//                $order_id = $tmp[0];
//            }elseif($tmp = $this->get_params_list($or_where,"attach_id")) {
//                //若传入多个order_id的话只会拿第一个，用于获取表名后缀,涉及到多表操作请传入index进行批量更新
//                $order_id = $tmp[0];
//            }
            //取所有order_id跟attach_id用于定位数据表
            if($tmp = $this->get_params_list($where,"order_id")){
                $order_id = array_merge($order_id,$tmp);
            }
            if($tmp = $this->get_params_list($or_where,"order_id")) {
                $order_id = array_merge($order_id,$tmp);
            }
            if($tmp = $this->get_params_list($where,"attach_id")) {
                $order_id = array_merge($order_id,$tmp);
            }
            if($tmp = $this->get_params_list($or_where,"attach_id")) {
                $order_id = array_merge($order_id,$tmp);
            }
            if(!$order_id)
            {
                log_message("ERROR","tb_trade_orders's update_batch function must have order_id or attach_id params");
//                exit("tb_trade_orders's update_batch function must have order_id or attach_id params");
                return -3;
            }

            $table_exts = [];
            foreach($order_id as $v)
            {
                $tmp = $this->get_table_ext($v);
                if(!in_array($tmp,$table_exts))
                {
                    $table_exts[] = $tmp;
                }
            }

            $where_array = $this->split_params($where);
            $or_where_array = $this->split_params($or_where);
            $data_array = $this->split_params($data);
            $res = "";

            foreach($table_exts as $table_ext) {
                if (isset($where_array[$this->table_name])) {
                    $where_tmp = $where_array[$this->table_name];
                    $this->_handle_where($this->db, $where_tmp);
                }

                if (isset($or_where_array[$this->table_name])) {
                    $or_where_tmp = $or_where_array[$this->table_name];
                    $this->_handle_or_where($this->db, $or_where_tmp);
                }
                //必须有条件才给予更新,并且要有更新的数据
                if ((isset($where_array[$this->table_name]) || isset($or_where_array[$this->table_name]))
                    && isset($data_array[$this->table_name])
                ) {
                    $res += $this->db->update($this->table_name . $table_ext, $data_array[$this->table_name]);
//                    var_dump($this->db->last_query());
                } else {
                    //重置条件，否则会污染$this->db
                    $this->db->get_compiled_select($this->table_name . $table_ext, true);
                }

                //如果副表有传入where条件
                //副表必须要有条件，并且传入了需要更新的数据才能更新
                $table_info = $this->tb_trade_orders_info->get_table_name();
                if ((isset($where_array[$table_info]) || isset($or_where_array[$table_info]))
                    && $data_array[$table_info]
                ) {
                    if (isset($where_array[$table_info])) {
                        $where_tmp = $where_array[$table_info];
                        $this->_handle_where($this->db, $where_tmp);
                    }
                    if (isset($or_where_array[$table_info])) {
                        $or_where_tmp = $or_where_array[$table_info];
                        $this->_handle_or_where($this->db, $or_where_tmp);
                    }
                    if ($table_ext) {
                        //如果是分表数据
                        $res2 = $this->db->update($table_info . $table_ext, $data_array[$table_info]);
//                        var_dump($this->db->last_query());
                    } else {
                        //非分表数据依旧更新主表
                        $res2 = $this->db->update($this->table_name . $table_ext, $data_array[$table_info]);
//                        var_dump($this->db->last_query());
                    }
                }
            }
        }

        if($cache) {
            $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
        }

        return $res;
    }

    /**
     * 重写get_counts以适应分表并拆表
     * 传入的条件请不要跨表，即，如果传了trade_orders表的条件，请不传trade_orders_info的条件，
     * 注意：若传了trade_orders_info表的条件，请不要传trade_orders表的条件
     * @param array $where
     * @param array $or_where
     * @param int $force_master
     * @return int|string
     */
    public function get_counts($where=[],$or_where=[],$force_master=0){
        $tmp_order_1 = $this->get_params_list($where);
        $tmp_order_2 = $this->get_params_list($or_where);
        $tmp_orders = array_merge($tmp_order_1,$tmp_order_2);
        $tmp_attach_id_1 = $this->get_params_list($where,"attach_id");
        $tmp_orders = array_merge($tmp_orders,$tmp_attach_id_1);
        $tmp_attach_id_2 = $this->get_params_list($or_where,"attach_id");
        $tmp_orders = array_merge($tmp_orders,$tmp_attach_id_2);

        if($tmp_orders)//若有传入order_id或attach_id作为条件
        {
            //若有传入order_id
            if(count($tmp_orders) == 1)
            {
                //若只传入了一个order_id
                $table_ext = $this->get_table_ext($tmp_orders[0]);
                //过滤掉并不存在的表后缀
                $table_ext = $this->filter_table_ext($table_ext,$this->table_name);
                $this->db->from($this->table_name.$table_ext);

                $where_array = $this->split_params($where);
                if(isset($where_array[$this->table_name])){
                    $where = $where_array[$this->table_name];
                    $this->_handle_where($this->db,$where);
                }

                $or_where_array = $this->split_params($or_where);
                if(isset($or_where_array[$this->table_name]))
                {
                    $or_where = $or_where_array[$this->table_name];
                    $this->_handle_or_where($this->db,$or_where);
                }

                if($force_master)
                {
                    $this->db->force_master();
                }
                if($this->db)
                {
                    $tmp =  $this->db->count_all_results();
                    $this->debug($this->db->last_query().",".__LINE__);
                }else{
                    log_message("ERROR","tb_trade_orders get_counts no data found by:".var_export(get_defined_vars(),true));
                    $tmp = 0;
                }
                return $tmp;
            }else{
                //若传入了多个order_id
                //todo.全局搜索发现并没有地方传入多个order_id或attach_id
            }
        }else{
            //若没有传入order_id或attach_id作为条件
            //遍历所有分表并求和,返回较大的之和
            $total_trade_orders = 0;
            $total_trade_orders_info = 0;
            $where_array = $this->split_params($where);
            $or_where_array = $this->split_params($or_where);
            if(isset($where_array[$this->table_name]) || isset($or_where_array[$this->table_name]))
            {
                //如果传入了trade_orders表的条件
                $trade_order_table_list = $this->table_list("trade_orders(_[0-9]+)?$");
                $now_scan = 0;
                foreach($trade_order_table_list as $v)
                {
                    if($now_scan >= $this->max_scan)
                    {
                        break;
                    }
                    $this->db->from($v);
                    $this->_handle_where($this->db,$where_array[$this->table_name]);
                    if(isset($or_where_array[$this->table_name]))
                    {
                        $or_where = $or_where_array[$this->table_name];
                        $this->_handle_or_where($this->db,$or_where);
                    }
                    if($force_master)
                    {
                        $this->db->force_master();
                    }
                    if($this->db)
                    {
                        $tmp = $this->db->count_all_results();
                        $this->debug($this->db->last_query().",".__LINE__);
                    }else{
                        $tmp = 0;
                    }
                    $now_scan += 1;
                    $total_trade_orders = bcadd($total_trade_orders,$tmp);
                }
            }
            if(isset($where[$this->tb_trade_orders_info->get_table_name()]))
            {
                //如果传入了trade_orders_info表的条件
                $trade_order_info_table_list = $this->table_list("trade_orders_goods(_[0-9]+)?$");
                $now_scan = 0;
                foreach($trade_order_info_table_list as $v) {
                    if($now_scan >= $this->max_scan)
                    {
                        break;
                    }
                    $this->db->from($v);
                    $this->_handle_where($this->db, $where_array[$this->tb_trade_orders_info->get_table_name()]);
                    if(isset($or_where_array[$this->table_name]))
                    {
                        $or_where = $or_where_array[$this->tb_trade_orders_info->get_table_name()];
                        $this->_handle_or_where($this->db,$or_where);
                    }
                    if ($force_master) {
                        $this->db->force_master();
                    }
                    $tmp = $this->db->count_all_results();
                    $this->debug($this->db->last_query().",".__LINE__);
                    $total_trade_orders_info = bcadd($total_trade_orders_info, $tmp);
                    $now_scan += 1;
                }
            }
            if($total_trade_orders >= $total_trade_orders_info)
            {
                return $total_trade_orders;
            }else{
                return $total_trade_orders_info;
            }
        }
    }

    /**
     * 重写get_sum以适应分表并拆表
     * 全局搜索后发现几乎无order_id参数传入，只能遍历分表
     * 注意：条件只能传入trade_orders表的，否则将忽略
     * @param string $column
     * @param array $where
     * @param array $or_where
     * @param int $force_master
     * @return mixed
     */
    public function get_sum($column="",$where=[],$or_where=[],$force_master=0)
    {
        if(!$column)
        {
            return false;
        }

        $res_sum = 0;
        $where_array = $this->split_params($where);
        $or_where_array = $this->split_params($or_where);
        $trade_orders_list = $this->table_list("trade_orders(_[0-9]+)?$");
        $now_scan = 0;
        foreach($trade_orders_list as $v)
        {
            if($now_scan >= $this->max_scan)
            {
                break;
            }
            $this->db->from($v);
            $this->db->select_sum($column);

            if(isset($where_array[$this->table_name])){
                $where = $where_array[$this->table_name];
                $this->_handle_where($this->db,$where);
            }

            if(isset($or_where_array[$this->table_name]))
            {
                $or_where = $or_where_array[$this->table_name];
                $this->_handle_or_where($this->db,$or_where);
            }

            if($force_master)
            {
                $this->db->force_master();
            }
            if($this->db)
            {
                $tmp = $this->db->get();
                if($tmp){
                    $res_sum = bcadd($res_sum,$tmp->row_array()[$column],2);
                    $this->debug($this->db->last_query().",".__LINE__);
                }
            }
            $now_scan += 1;
        }

        return $res_sum;
    }

    /**
     * 重写insert_one，以适应分表并拆表
     * 插入数据请传入所有字段的数据，会自动分别插入到对应的拆表及分表里。
     * 注意：多插入的字段会被过滤。
     * @param $data
     * @param int $cache
     * @return int|mixed
     */
    public function insert_one($data,$cache=1)
    {
        $defined_vars = get_defined_vars();
        $this->debug("insert_one:".var_export($defined_vars,true));
        if($cache) {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }
        if(1 > count($data))
        {
            log_message("ERROR","插入数据却未传入数据，准备操作的表:".$this->table_name);
            return -1;
        }
        if(!is_array($data))
        {
            log_message("ERROR","插入数据应传入数组，准备操作的表:".$this->table_name);
            return -2;
        }
        $data_arr = $this->split_params($data);
        if(isset($data_arr[$this->tb_trade_orders_info->get_table_name()])){
            $this->db->trans_start();
        }
        $this->create_table($this->table_name.$this->get_table_ext());
        $this->db->insert($this->table_name.$this->get_table_ext(), $data_arr[$this->get_table_name()]);
        $this->debug($this->db->last_query());
        $res =  $this->db->insert_id();
        if(isset($data_arr[$this->tb_trade_orders_info->get_table_name()])){
            $this->tb_trade_orders_info->insert_one($data_arr[$this->tb_trade_orders_info->get_table_name()]);
            $this->debug($this->db->last_query().",".__LINE__);
            $res2 =  $this->db->insert_id();
            if($this->db->trans_status())
            {
                $this->debug("trade_orders id:".$res.",trade_orders_info id:".$res2.",".__FILE__.",".__LINE__.",<br>");
                $this->db->trans_commit();
            }else{
                return 0;
            }
        }
        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }
        return $res;
    }

    /**
     * 全局并未发现使用该函数，暂不处理
     * @param $data
     * @param int $cache
     * @return CI_DB_active_record|CI_DB_result|int|mixed
     */
    public function insert_batch($data,$cache=1)
    {
        exit("tb_trade_orders insert_batch is todo.".__FILE__.",".__LINE__."<BR>");
        $defined_vars = get_defined_vars();

        if($cache)
        {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        if(1 > count($data))
        {
            log_message("ERROR","批量插入数据却未传入数据，准备操作的表:".$this->table_name);
            return -1;
        }
        if(!is_array($data))
        {
            log_message("ERROR","批量插入数据应传入数组，准备操作的表:".$this->table_name);
            return -2;
        }
        $res =  $this->db->insert_batch($this->table_name, $data);

        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        return $res;
    }

    /**
     * delete orders is denied!
     * @param array $where
     * @param array $or_where
     * @param int $cache
     * @return int|mixed
     */
    public function delete_one($where=[],$or_where=[],$cache=1)
    {
        exit("delete orders is denied!".__FILE__.",".__LINE__."<BR>");
        $defined_vars = get_defined_vars();

        if($cache)
        {
            $cache_data = $this->_hook_function("before_" . __FUNCTION__, $defined_vars);
            if ($cache_data)
            {
                return $cache_data;
            }
        }

        if(1 > count($where))
        {
            log_message("ERROR","未传入条件恐怕会删除整表的数据，影响表:".$this->table_name);
            return -2;
        }

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        $this->db->limit(1);//限制一条数据
        $this->db->delete($this->table_name);

        $res = $this->db->affected_rows();

        if($cache)
        {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }

        return $res;
    }

    /**
     * delete orders is denied!
     * @param array $where
     * @param array $or_where
     * @param int $cache
     * @return int|mixed
     */
    public function delete_batch($where=[],$or_where=[],$cache=1)
    {
        exit("delete orders is denied!".__FILE__.",".__LINE__."<BR>");
        $defined_vars = get_defined_vars();
        if($cache)
        {
            $cache_data = $this->_hook_function("before_".__FUNCTION__,$defined_vars);
            if($cache_data)
            {
                return $cache_data;
            }
        }
        if(1 > count($where))
        {
            log_message("ERROR","未传入条件恐怕会删除整表的数据，影响表:".$this->table_name);
            return -2;
        }

        $this->_handle_where($this->db,$where);
        $this->_handle_or_where($this->db,$or_where);

        $this->db->delete($this->table_name);
        $res = $this->db->affected_rows();

        if($cache) {
            $cache_data = $this->_hook_function("after_" . __FUNCTION__, $defined_vars);
            if ($cache_data) {
                return $cache_data;
            }
        }

        return $res;
    }

}
