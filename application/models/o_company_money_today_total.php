<?php

/**
 * 用户昨天全球利润
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/22
 * Time: 14:21
 */
class o_company_money_today_total extends MY_Model
{
	protected $table = "company_money_today_total";
	protected $table_name = "company_money_today_total";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取全球利润 已统计查库 否则统计
	 * @return array Array
	 *    (
	 *    [id] => 2
	 *    [money] => 174809  昨天总利润
	 *    [mall_orders] => 0 沃好
	 *    [one_direct_orders] => 0 美国
	 *    [trade_orders] => 145200 tps
	 *    [walmart_orders] => 29609 沃尔玛
	 *    [create_time] => 20170321 时间
	 *    )
	 */
	public function get_yesterday_profit()
	{
		$create_time = date("Ymd", strtotime("-1 day"));
		$param = [
			"select" => 'id,money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time',
			"where" => [
				'create_time' => $create_time
			]
		];
//        $res = $this->get($param, false, true);
        $res = $this->get_one_auto($param);
		if (!empty($res)) {
			return $res;
		} else {
			return $this->get_yesterday_profit_by_db();
		}
	}

	/**
	 * 获取某一天的功能
	 */
	public function get_profit_some_day($create_time)
	{
		$year = substr($create_time,0,4);
		$month = substr($create_time,4,2);
		$day = substr($create_time,6,2);
		$time = $year."-".$month."-".$day;

		$param = [
				"select" => 'id,money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time',
				"where" => [
						'create_time' => $create_time
				]
		];
//		$res = $this->get($param, false, true,true);
		$res = $this->get_one_auto($param);
		if (!empty($res)) {
			return $res;
		} else {
			return $this->get_some_day_profit_by_db($time);

		}
	}


	/**
	 * 本周日期
	 * @param 某个星期的某一个时间戳，默认为当前时间
	 * @param 是否返回时间戳，否则返回时间格式
	 * @return Ambigous <>
	 */
	function this_monday($timestamp=0,$is_return_timestamp=true){
		static $cache ;
		$id = $timestamp.$is_return_timestamp;
		if(!isset($cache[$id])){
			if(!$timestamp) $timestamp = time();
			$monday_date = date('Ymd', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400));
			if($is_return_timestamp){
				$cache[$id] = strtotime($monday_date);
			}else{
				$cache[$id] = $monday_date;
			}
		}
		return $cache[$id];

	}


	/**
	 * 上周一
	 * @param number 某个星期的某一个时间戳，默认为当前时间
	 * @param string 是否返回时间戳，否则返回时间格式
	 * @return Ambigous <>
	 */
	function last_monday($timestamp=0,$is_return_timestamp=true){
		static $cache ;
		$id = $timestamp.$is_return_timestamp;
		if(!isset($cache[$id])){
			if(!$timestamp) $timestamp = time();
			$thismonday = $this->this_monday($timestamp) - /*7*86400*/604800;
			if($is_return_timestamp){
				$cache[$id] = $thismonday;
			}else{
				$cache[$id] = date('Ymd',$thismonday);
			}
		}
		return $cache[$id];
	}

	/***
	 * 上一周利润统计
	 * @return mixed :|multitype:number
	 */
	public function get_last_week_profit()
	{
		$last_week = $this->last_monday(0,false); //上周一
		$week = $this->this_monday(0,false); //本周一

		$param = [
			"select" => 'sum(money) as money,sum(mall_orders) as mall_orders,sum(one_direct_orders) as one_direct_orders,sum(trade_orders) as trade_orders,sum(walmart_orders) as walmart_orders',
			"where" => [
				'create_time >='=> $last_week,
				'create_time <'=> $week
			]
		];
//        $res = $this->get($param, false, true);
        $res = $this->get_one_auto($param);

		if (!empty($res)) {
			return $res;
		} else {
			return null;
		}
	}

			
	/***
	 * 某一时间段统计
	 * @param unknown $last_week
	 * @param unknown $week
	 */
	public function get_last_week_profit_pare($last_week,$week)
	{	   
	    $param = [
	        "select" => 'sum(money) as money,sum(mall_orders) as mall_orders,sum(one_direct_orders) as one_direct_orders,sum(trade_orders) as trade_orders,sum(walmart_orders) as walmart_orders',
	        "where" => [
	            'create_time >='=> $last_week,
	            'create_time <'=> $week
	        ]
	    ];

//        $res = $this->get($param, false, true);
        $res = $this->get_one_auto($param);
	
	    if (!empty($res)) {
	        return $res;
	    } else {
	        return null;
	    }
	}
	
	/***
	 * 获取某一月的销售额
	 * @param unknown $last_week
	 * @param unknown $week
	 */
	public function get_month_profit_pare($stime)
	{
	     
	    $param = [
	        "select" => 'money,mall_orders,one_direct_orders,trade_orders,walmart_orders',
	        "where" => [
	            'create_time ='=> $stime	           
	        ]
	    ];
//	    $res = $this->get($param, false, true);;
        $res = $this->get_one_auto($param, false, true);
	
	    if (!empty($res)) {
	        return $res;
	    } else {
	        return null;
	    }
	}



	/**
	 * @author brady
	 * @description 通过数据库获取昨天全球利润
	 */
	public function get_yesterday_profit_by_db()
	{
		$this->load->model("m_profit");
		$timePeriod = $this->m_profit->__getLastDayPeriod();
		//沃好订单利润
		$mallProfit = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('mall_orders as a')->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->get()->row_object()->totalProfit;
		$mallProfit = $mallProfit ? $mallProfit : 0;

		//美国订单利润
		$mallProfitOnederect = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('one_direct_orders as a')->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->get()->row_object()->totalProfit;
		$mallProfitOnederect = $mallProfitOnederect ? $mallProfitOnederect : 0;

		//tps订单利润
//		$mallProfitTps = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('trade_orders as a')->where('a.pay_time >=', $timePeriod['start'])->where('a.pay_time <', $timePeriod['end'])->where_in('order_prop', array('0', '2'))->where_in('status', array('3', '4', '5', '6'))->get()->row_object()->totalProfit;
//		$mallProfitTps = $mallProfitTps ? $mallProfitTps / 100 : 0;
        $this->load->model("tb_trade_orders");
        $mallProfitTps = $this->tb_trade_orders->get_sum_auto([
            "column"=>"order_profit_usd",
            "where"=>[
                "pay_time >="=>$timePeriod['start'],
                "pay_time <"=>$timePeriod['end'],
                "order_prop"=>array('0', '2'),
                "status"=>array('3', '4', '5', '6'),
            ]
        ]);
        $mallProfitTps = $mallProfitTps ? $mallProfitTps / 100 : 0;

		//手动升级订单利润 废弃
		//$tpsProductMoney = $this->db->query("select sum(a.join_fee) as totalMoney from user_upgrade_order a where a.pay_time>='" . $timePeriod['start'] . "' and a.pay_time<'" . $timePeriod['end'] . "' and a.status=2")->row_object()->totalMoney;
		//$tpsProductMoney = $tpsProductMoney ? $tpsProductMoney : 0;

		//沃尔玛订单利润
		$mallProfitWalmart = $this->db->select('sum(a.order_profit_usd  ) as totalProfit')->from("walmart_orders as a ")->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->where(array('status' => 1))->get()->row_object()->totalProfit;
		$mallProfitWalmart = $mallProfitWalmart ? $mallProfitWalmart : 0;
		$data = array(
			'money' => tps_money_format($mallProfit + $mallProfitOnederect + $mallProfitTps + $mallProfitWalmart) * 100,
			'mall_orders' => $mallProfit * 100,
			'one_direct_orders' => $mallProfitOnederect * 100,
			'trade_orders' => $mallProfitTps * 100,
			'walmart_orders' => $mallProfitWalmart * 100
		);
		return $data;
	}

	public function get_some_day_profit_by_db($time)
	{
		$start = date("Y-m-d",strtotime($time));
		$end = date("Y-m-d",strtotime($time)+3600*24);

		$timePeriod = array('start'=>$start,'end'=>$end);
		//沃好订单利润
		$mallProfit = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('mall_orders as a')->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->get()->row_object()->totalProfit;
		$mallProfit = $mallProfit ? $mallProfit : 0;

		//美国订单利润
		$mallProfitOnederect = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('one_direct_orders as a')->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->get()->row_object()->totalProfit;
		$mallProfitOnederect = $mallProfitOnederect ? $mallProfitOnederect : 0;

		//tps订单利润
		$mallProfitTps = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('trade_orders as a')->where('a.pay_time >=', $timePeriod['start'])->where('a.pay_time <', $timePeriod['end'])->where_in('order_prop', array('0', '2'))->where_in('status', array('3', '4', '5', '6'))->get()->row_object()->totalProfit;
		$mallProfitTps = $mallProfitTps ? $mallProfitTps / 100 : 0;

		//手动升级订单利润 废弃
		//$tpsProductMoney = $this->db->query("select sum(a.join_fee) as totalMoney from user_upgrade_order a where a.pay_time>='" . $timePeriod['start'] . "' and a.pay_time<'" . $timePeriod['end'] . "' and a.status=2")->row_object()->totalMoney;
		//$tpsProductMoney = $tpsProductMoney ? $tpsProductMoney : 0;

		//沃尔玛订单利润
		$mallProfitWalmart = $this->db->select('sum(a.order_profit_usd  ) as totalProfit')->from("walmart_orders as a ")->where('a.create_time >=', $timePeriod['start'])->where('a.create_time <', $timePeriod['end'])->where(array('status' => 1))->get()->row_object()->totalProfit;
		$mallProfitWalmart = $mallProfitWalmart ? $mallProfitWalmart : 0;
		$data = array(
				'money' => tps_money_format($mallProfit + $mallProfitOnederect + $mallProfitTps + $mallProfitWalmart) * 100,
				'mall_orders' => $mallProfit * 100,
				'one_direct_orders' => $mallProfitOnederect * 100,
				'trade_orders' => $mallProfitTps * 100,
				'walmart_orders' => $mallProfitWalmart * 100
		);
		$data['create_time'] = date("Ymd",strtotime($start));
		$this->db->query("insert ignore into company_money_today_total(money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time) values({$data['money']},
{$data['mall_orders']},{$data['one_direct_orders']},{$data['trade_orders']},{$data['walmart_orders']},{$data['create_time']})");
		return $data;
	}

	/**
	 * @author brady
	 * @description 统计昨天全球利润 定时任务跑
	 */
	public function calc_company_yesterday_profit()
	{
		$create_time = date("Ymd", strtotime("-1 day"));
		$data = $this->get_yesterday_profit_by_db();
		$param = [
			"select" => 'id',
			"where" => [
				'create_time' => $create_time
			]
		];
		$res = $this->get($param, false, true);
		if (empty($res)) {
			$data['create_time'] = $create_time;
			$insert_id = $this->add($data);
			if ($insert_id > 0) {
				$this->m_log->createCronLog('[success] 统计昨天全球利润成功：' . json_encode($data));
			} else {
				$this->m_log->createCronLog('[failed] 统计昨天全球利润失败：' . json_encode($data));
			}
		} else {
			$this->m_log->createCronLog('[success] 昨天全球利润已结统计过：' . json_encode($data));
		}
	}

	/**
	 * @author brady
	 * @description 统计每月的利润
	 */
	public function calc_profit_month()
	{
		$m = date("m");
		if ($m == '03') {
			$create_time = date("Y02",time());
		} else {
			$create_time = date("Ym",strtotime("-1 month")); //本月
		}

		//查询是否已经存在了本月的数据
		$exists = $this->get([
			'select'=>"id",
			'where'=>[
				'create_time'=>$create_time
			]
		],false,true);


		$month = $this->getlastMonthDays($create_time);
		$month_start = $month['0'];
		$month_end = $month['1'];

		$params = [
			"select" => 'id,money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time',
			'where'=>[
				'create_time >='=>(int)$month_start,
				'create_time <='=>(int)$month_end
			]
		];
		$res = $this->get($params);
		if (empty($res)) {
			$this->m_log->createCronLog('[fail] 统计月利润获取数据失败：');
		} else {
			$_new_money = $_new_mall_orders  = $_new_one_direct_orders = $_new_trade_orders = $_new_walmart_orders = 0;

			foreach($res as $v) {
				$_new_money += $v['money'];
				$_new_mall_orders += $v['mall_orders'];
				$_new_one_direct_orders += $v['one_direct_orders'];
				$_new_trade_orders += $v['trade_orders'];
				$_new_walmart_orders += $v['walmart_orders'];
			}
			$data = array(
				"money"=>$_new_money,
				"mall_orders"=>$_new_mall_orders,
				"one_direct_orders"=>$_new_one_direct_orders,
				"trade_orders"=>$_new_trade_orders,
				"walmart_orders"=>$_new_walmart_orders,
				'create_time'=>$create_time
			);

			if (!empty($exists)) {
				$data['id'] = $exists['id'];
				$this->db->replace($this->table_name,$data);
			} else {
				$this->db->insert($this->table_name,$data);
			}
			$affected_rows = $this->db->affected_rows();
			if ($affected_rows <= 0) {
				$this->m_log->createCronLog('[failed] 统计月利润失败：' . json_encode($data));
			} else {
				$this->m_log->createCronLog('[success] 统计月利润成功：' . json_encode($data));
			}

		}

	}

	/**
	 * @author brady
	 * @param $date 当前月的日期
	 * @return array  上个月的第一天和最后一天
	 */
	public function getlastMonthDays($date)
	{
		$timestamp = strtotime($date);
		$firstday = date('Ym01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
		$lastday = date('Ymd', strtotime("$firstday +1 month -1 day"));
		return array($firstday, $lastday);
	}

	/**
	 * @author brady
	 * @description 获取上个月的总利润
	 */
	public function get_last_month_profit()
	{
		$m = date("m");
		if ($m == '03') {
			$create_time = date("Y02",time());
		} else {
			$create_time = date("Ym",strtotime("-1 month")); //本月
		}
		$month = $this->getlastMonthDays($create_time);
		$month_start = $month['0'];
		$month_end = $month['1'];

		//查询是否已经存在了本月的数据
		$exists = $this->get([
			'select'=>"id,money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time",
			'where'=>[
				'create_time'=>$create_time
			]
		],false,true);



		if (empty($exists)) {
			$params = [
				"select" => 'id,money,mall_orders,one_direct_orders,trade_orders,walmart_orders,create_time',
				'where'=>[
					'create_time >='=>(int)$month_start,
					'create_time <='=>(int)$month_end
				]
			];
			$res = $this->get($params);

			$_new_money = $_new_mall_orders  = $_new_one_direct_orders = $_new_trade_orders = $_new_walmart_orders = 0;

			foreach($res as $v) {
				$_new_money += $v['money'];
				$_new_mall_orders += $v['mall_orders'];
				$_new_one_direct_orders += $v['one_direct_orders'];
				$_new_trade_orders += $v['trade_orders'];
				$_new_walmart_orders += $v['walmart_orders'];
			}
			$data = array(
				"money"=>$_new_money,
				"mall_orders"=>$_new_mall_orders,
				"one_direct_orders"=>$_new_one_direct_orders,
				"trade_orders"=>$_new_trade_orders,
				"walmart_orders"=>$_new_walmart_orders,
				'create_time'=>$create_time
			);
			return $data;
		} else {
			return $exists;
		}
	}
}