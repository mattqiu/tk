<?php

class M_order extends CI_Model {

    protected $table = "trade_orders";

    function __construct() {
        $this->load->model('m_erp');
        parent::__construct();
        $this->load->model("tb_trade_orders");
        $this->load->model("tb_trade_orders_goods");
        $this->load->model('tb_empty');
    }

    //得到  客户 订单
    public function getOrderCount($uid) {
        if(use_temporary_rule()){
            return 30;
        }
        return 10;
    }

	/** 店铺的销售总金额 和 总合格订单数 */
	public function getOrderSalesInfo($uid){

		$sales_info = $this->db->select('orders_num,sale_amount')->where('uid',$uid)->get('users_store_sale_info')->row_array();

		if(!$sales_info){
			$sales_info['orders_num'] = 0;
			$sales_info['sale_amount'] = 0;
		}else{
			$sales_info['sale_amount'] = $sales_info['sale_amount'] > 0 ? $sales_info['sale_amount'] / 100 : $sales_info['sale_amount']; //分转化为元
		}

		return $sales_info;
	}

    //上個月 的合格客户 订单  >25
    public function getPassOrderCount($uid) {
        /*if(use_temporary_rule()){
            return TRUE;
        }
        return TRUE;*/
		$month = date('Ym',get_last_timestamp());
		$row = $this->db->where('year_month',$month)->where('uid',$uid)->get('users_store_sale_info_monthly')->row_array();
		if($row /*&& $row['orders_num'] >= 10*/ && $row['sale_amount'] >= 25000){
			return TRUE;
		}else{
			return FALSE;
		}
    }

    //上個月 订单的利润
    public function getOrderSales($uid_arr,$start,$last,$level_cash){

		$total = 0;
		$new_arr = array_chunk($uid_arr,10000);
		unset($uid_arr);

		foreach($new_arr as $item_arr){

			$mallProfit = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('mall_orders as a')->where_in('shopkeeper_id',$item_arr)->where('a.create_time >=',$start)->where('a.create_time <=',$last)->get()->row_object()->totalProfit;
			$mallProfit = $mallProfit?$mallProfit:0;

			$mallProfitOnederect = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('one_direct_orders as a')->where_in('shopkeeper_id',$item_arr)->where('a.create_time >=',$start)->where('a.create_time <=',$last)->get()->row_object()->totalProfit;
			$mallProfitOnederect = $mallProfitOnederect?$mallProfitOnederect:0;

//			$mallProfitTps = $this->db->select('sum(a.order_profit_usd) as totalProfit')->from('trade_orders as a')->where_in('shopkeeper_id',$item_arr)->where('a.pay_time >=',$start)->where('a.pay_time <',$last)->where_in('order_prop',array('0','1'))->where_in('status',array('1','3','4','5','6'))->get()->row_object()->totalProfit;
            $mallProfitTps = $this->tb_trade_orders->get_sum_auto([
                "column"=>"order_profit_usd",
                "where"=>[
                    "shopkeeper_id"=>$item_arr,
                    "pay_time >="=>$start,
                    "pay_time <"=>$last,
                    "order_prop"=>array('0','1'),
                    "status"=>array('1','3','4','5','6'),
                ]
            ])["order_profit_usd"];

			$mallProfitTps = $mallProfitTps?$mallProfitTps/100:0;

			$upgrade_arr = $this->db->select('new_level')->where('level_type',2)->where('(old_level > new_level or (old_level = 4 and new_level = 5)) ')->where_in('uid',$item_arr)->where('create_time >=',$start)->where('create_time <=',$last)->get('users_level_change_log')->result_array();
			$upgrade_amount = 0;
			if($upgrade_arr)foreach($upgrade_arr as $item){
				$upgrade_amount += $level_cash[$item['new_level']]*0.8;
			}
			unset($upgrade_arr);
			unset($item_arr);

			$total += $mallProfit+$mallProfitOnederect+$mallProfitTps+$upgrade_amount;
		}
		unset($new_arr);
		return $total;

    }

    public function getUserStoreInfoFromBase($uid,$month=''){

        $return = array('orders_num'=>0,'sale_amount'=>0);

        if($month){
            $sql = "SELECT order_amount_usd FROM mall_orders where shopkeeper_id=$uid and create_time>='2015-09-01' and create_time<'2015-10-01'";
        }else{
            $sql = "SELECT order_amount_usd FROM mall_orders where shopkeeper_id=$uid";
        }
        $res = $this->db->query($sql)->result_array();
        foreach($res as $item){
            $return['orders_num']++;
            $return['sale_amount']+=($item['order_amount_usd']*100);
        }

        if($month){
            $sql = "SELECT order_amount_usd FROM one_direct_orders where shopkeeper_id=$uid and create_time>='2015-09-01' and create_time<'2015-10-01'";
        }else{
            $sql = "SELECT order_amount_usd FROM one_direct_orders where shopkeeper_id=$uid";
        }
        $res = $this->db->query($sql)->result_array();
        foreach($res as $item){
            $return['orders_num']++;
            $return['sale_amount']+=($item['order_amount_usd']*100);
        }

        if($month){
//            $sql = "SELECT goods_amount_usd FROM trade_orders where shopkeeper_id=$uid and status in(3,4,5,6) and pay_time>='2015-09-01' and pay_time<'2015-10-01'";
            $where = ["shopkeeper_id"=>$uid,"status"=>[3,4,5,6],"pay_time >="=>'2015-09-01',"pay_time <"=>"2015-10-01"];
        }else{
//            $sql = "SELECT goods_amount_usd FROM trade_orders where shopkeeper_id=$uid and status in(3,4,5,6)";
            $where = ["shopkeeper_id"=>$uid,"status"=>[3,4,5,6]];
        }
//        $res = $this->db->query($sql)->result_array();
        $this->load->model("tb_trade_orders");
        $res = $this->tb_trade_orders->get_list("goods_amount_usd",$where);

        foreach($res as $item){
            $return['orders_num']++;
            $return['sale_amount']+=$item['goods_amount_usd'];
        }

        return $return;
    }

    /*取消普通订单，by Terry.*/
    // public function cancelGeneralOrder($orderId){
    //     $this->db->
    // }

    /*订单退款*/
    public function refundOrderToTpsAmount($orderId,$uid,$amount){

        $this->db->where('id', $uid)->set('amount', 'amount+' . $amount, FALSE)->update('users');

        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($uid,22,$amount,0,$orderId);
    }

    public function fixNoCommissionOrder($shopkeeper_id,$order_profit_usd,$order_id){
        /*发放个人店铺销售奖*/
        $this->load->model('M_generation_sales');
        $storeCommissionAmount = tps_money_format($order_profit_usd*0.2);
        $this->M_generation_sales->assignStoreCommission($shopkeeper_id, $storeCommissionAmount, $order_id);

        /*團隊銷售提成獎*/
        $this->load->model('M_overrides', 'm_overrides');
        $this->m_overrides->generationSalesOverrides2($shopkeeper_id,$order_profit_usd,$order_id);
    }
    
    /**
     * 接收沃好商城传递过来的订单信息。
     * @param array $data
     * @return int error_code
     */
    public function saveWohaoMallOrders($data) {
        
        $this->m_log->addInterfaceWalhao($data);

        foreach($data as $key=>$item){
            $data[strtolower(trim($key))] = trim($item);
        }
        
        if(!is_numeric($data['customer_id']) || !is_numeric($data['shopkeeper_id'])){
            return 101;
        }
        if(!$data['order_pay_time']){
            return 101;
        }
        if(!isset($data["order_year_month"]) || (isset($data["order_year_month"]) && empty($data["order_year_month"]))){
            $order_year_month = date("Ym");
        }else{
            if(!is_numeric($data["order_year_month"]) || strlen($data["order_year_month"])!=6){
                return 101;
            }else{
                $order_year_month = $data["order_year_month"];
            }
        }
        $fieldList = array('order_id','order_pay_time','customer_id','shopkeeper_id','order_amount','order_profit','currency');
        foreach($data as $k=>$v){
            if(!in_array($k, $fieldList)){
                unset($data[$k]);
            }
        }
        
        $this->load->model('m_currency');
        $data['order_profit_usd'] = $this->m_currency->exchangeToUSD($data['order_profit'], $data['currency']);
        $data['order_amount_usd'] = $this->m_currency->exchangeToUSD($data['order_amount'], $data['currency']);
        $data['score_year_month'] = $order_year_month;
        
        $res = $this->db->select('order_id,customer_id,shopkeeper_id,order_amount,order_profit,currency')->from('mall_orders')->where('order_id',$data['order_id'])->get()->row_array();
        if(!$res){

            $this->db->trans_start();
            $this->db->insert('mall_orders', $data);
            /*记录到新订单处理队列*/
            $this->load->model('tb_new_order_trigger_queue');
            $this->tb_new_order_trigger_queue->addNewOrderToQueue($data['order_id'],$data['shopkeeper_id'],tps_int_format($data['order_amount_usd']*100),tps_int_format($data['order_profit_usd']*100),$data['score_year_month']);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
                return 103;
            }
            
        }elseif($data['order_amount']!=$res['order_amount'] || $data['order_profit']!=$res['order_profit'] || $data['customer_id']!=$res['customer_id'] || $data['shopkeeper_id']!=$res['shopkeeper_id'] || $data['currency']!=$res['currency']){
            if(!strpos($data['order_id'],'_')){
                $data['order_id'] = $data['order_id'].'_2';
            }else{
                $arr_order_id = explode('_', $data['order_id']);
                $data['order_id'] = $arr_order_id[0].'_'.($arr_order_id[1]+1);
            }
            $this->saveWohaoMallOrders($data);
        }

        return 0;
    }

    /**
     * 接收1 direct商城传递过来的订单信息。
     * @param array $data = array (
     *  'order_id' => '381',
     *  'customer_id' => '1380102590',
     *  'shopkeeper_id' => '1380102590',
     *  'order_amount' => '26.99',
     *  'order_profit' => '7',
     *  'currency' => 'USD',
     *)
     * @return int error_code
     */
    public function saveOneDirectMallOrders($data) {

        $this->load->model('tb_users');
        
        /*处理参数和验证参数合法性*/
        $data['order_id'] = isset($data['order_id'])?$data['order_id']:'';
        $data['customer_id'] = isset($data['customer_id'])?(int)$data['customer_id']:0;
        $data['shopkeeper_id'] = isset($data['shopkeeper_id'])?(int)$data['shopkeeper_id']:0;
        $data['order_amount'] = isset($data['order_amount'])?tps_money_format($data['order_amount']):0.00;
        $data['order_profit'] = isset($data['order_profit'])?tps_money_format($data['order_profit']):0.00;
        $data['currency'] = (isset($data['currency']) && in_array($data['currency'],array('USD','CNY')))?$data['currency']:'';
        $vendor = isset($data['vendor'])?$data['vendor']:'';
        switch(trim($vendor)){
            case '1Direct':{
                $data['vendor'] = 1;
                break;
            }
            case 'Incentibuys':{
                $data['vendor'] = 2;
                break;
            }
            case 1:{
                $data['vendor'] = 1;
                break;
            }
            case 2:{
                $data['vendor'] = 2;
                break;
            }
            default:{
                $data['vendor'] = 0;
                break;
            }
        }
        if(!$data['shopkeeper_id'] || !$data['order_id'] || $data['order_amount']<0.01 || !$data['currency'] || !$this->tb_users->getUserInfo($data['shopkeeper_id'])){
            return 101;
        }
        
        /*去掉其他无用参数*/
        $fieldList = array('order_id','customer_id','shopkeeper_id','order_amount','order_profit','currency','vendor');
        foreach($data as $k=>$v){
            if(!in_array($k, $fieldList)){
                unset($data[$k]);
            }
        }

        $this->load->model('m_currency');
        $data['order_profit_usd'] = $this->m_currency->exchangeToUSD($data['order_profit'], $data['currency']);
        $data['order_amount_usd'] = $this->m_currency->exchangeToUSD($data['order_amount'], $data['currency']);
        $data['score_year_month'] = date('Ym');

        $res = $this->db->select('order_id,customer_id,shopkeeper_id,order_amount,order_profit,currency,vendor')->from('one_direct_orders')->where('order_id',$data['order_id'])->get()->row_array();
        if(!$res){
            
            $this->db->trans_start();
            $this->db->insert('one_direct_orders', $data);

            /*记录到新订单处理队列*/
            $this->load->model('tb_new_order_trigger_queue');
            $this->tb_new_order_trigger_queue->addNewOrderToQueue($data['order_id'],$data['shopkeeper_id'],tps_int_format($data['order_amount_usd']*100),tps_int_format($data['order_profit_usd']*100));

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
                return 103;
            }
        }elseif($data['order_amount']!=$res['order_amount'] || $data['order_profit']!=$res['order_profit'] || $data['customer_id']!=$res['customer_id'] || $data['shopkeeper_id']!=$res['shopkeeper_id'] || $data['currency']!=$res['currency'] || $data['vendor']!=$res['vendor']){
            if(!strpos($data['order_id'],'_')){
                $data['order_id'] = $data['order_id'].'_2';
            }else{
                $arr_order_id = explode('_', $data['order_id']);
                $data['order_id'] = $arr_order_id[0].'_'.($arr_order_id[1]+1);
            }
            $this->saveOneDirectMallOrders($data);
        }

        return 0;
    }


	//普通订单
    function mall_general_order_paid($order, $txn_id ,$pay_status = 3){
    	if (empty($order)) {
    		return false;
    	}
        $this->load->model('m_group');
        /* 统计订单付款成功后回调的次数，并根据次数就限定，只允许第一次回调执行。 */
//        $this->db->where('order_id', $order['order_id'])->set('notify_num', 'notify_num+1', FALSE)->update('trade_orders');
        $this->load->model("tb_trade_orders");
//        $this->tb_trade_orders->notify_num_plus($order['order_id']);//改pay_time来判断
        
        $tmp = $this->tb_trade_orders->get_one_auto([
            "select"=>"pay_time",
            "where"=>["order_id"=>$order['order_id']],
            "force_master"=>1
        ]);

        if($tmp['pay_time'] && $tmp['pay_time'] !== "0000-00-00 00:00:00" && $tmp['pay_time'] !== null)
        {
            return false;
        }

        $pay_time = date('Y-m-d H:i:s',time());
        $this->db->trans_begin();
        $order_id = $order['order_id'];
		$sub_orders = array();
		if($order['order_prop'] == '2'){ //拆分單
//			$sub_orders = $this->db->select('order_id,shopkeeper_id,customer_id,goods_amount_usd,order_profit_usd,is_doba_order,status')->where('attach_id',$order['order_id'])->where('order_prop','1')->get('trade_orders')->result_array();
			$sub_orders = $this->tb_trade_orders->get_list_auto([
			    "select"=>"order_id,shopkeeper_id,customer_id,goods_amount_usd,order_profit_usd,is_doba_order,status",
                "where"=>[
                    'attach_id'=>$order['order_id'],
                    'order_prop'=>'1'
                ],

                "force_master"=>1
            ]);
			if($sub_orders)foreach($sub_orders as $sub_order){
				//批量修改子訂單狀態
//				$this->db->update('trade_orders',array('expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),'status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=> $pay_time, 'score_year_month' => date('Ym')),array('order_id'=> $sub_order['order_id']));
                $this->tb_trade_orders->update_one(
                    ['order_id'=> $sub_order['order_id']],
                    [
                        'expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),
                        'status'=>$pay_status,
                        'txn_id'=>$txn_id,
                        'pay_time'=> $pay_time,
                        'score_year_month' => date('Ym')
                    ]
                );
                $insert_data = array();
                $insert_data['oper_type'] = 'modify';
                $insert_data['data']['order_id'] = $sub_order['order_id'];
                $insert_data['data']['status'] = $pay_status;
                $insert_data['data']['pay_time'] = $pay_time;

                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                //库存变动
                if($sub_order['status'] == '98' || $sub_order['status'] == '99'){
//                    $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$sub_order['order_id'])->from('trade_orders_goods')->get()->result_array();
                    $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                        ['order_id'=>$sub_order['order_id']]);
                    $this->m_group->update_goods_number($goods, $sub_order['order_id']);
                }
                /***********查询子订单详情用于户海关接口**********/
                $is_hgs=FALSE;
                $goods_info=$this->db->from('trade_orders_goods'.$this->tb_empty->get_table_ext($sub_order['order_id']))->where('order_id',$sub_order['order_id'])->get()->result_array();
                foreach ($goods_info as $gids) {
                    $gid[]=$gids['goods_sn_main'];
                }
                $is_hg=$this->db->select('is_hg')->from('mall_goods_main')->where_in('goods_sn_main',$gid)->get()->result_array();
                foreach ($is_hg as $hgs) {
                    if($hgs['is_hg']){$is_hgs=TRUE;}
                }
                if($is_hgs){
                    unset($gid);
                    if(isset($is_hgs)){unset($is_hgs);}
                    $order_info=$this->tb_trade_orders->get_one("*",['order_id'=>$sub_order['order_id']]);
                    $order_info['goods_list']=$goods_info;
                    $order_list[]=$order_info;
                }
                /***********查询子订单详情用于户海关接口**********/
			}
			//主訂單的交易號，支付時間
//			$this->db->update('trade_orders',array('txn_id'=>$txn_id,'pay_time'=>date('Y-m-d H:i:s',time()), 'score_year_month' => date('Ym')),array('order_id'=> $order['order_id']));
            $this->tb_trade_orders->update_one(
                ['order_id'=> $order['order_id']],
                ['txn_id'=>$txn_id,'pay_time'=>date('Y-m-d H:i:s',time()), 'score_year_month' => date('Ym')]
            );
		}else{
        	//根据订单号（out_trade_no）更新订单状态
//        	$this->db->update('trade_orders',array('expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),'status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=> $pay_time, 'score_year_month' => date('Ym')),array('order_id'=> $order['order_id']));
            $this->tb_trade_orders->update_one(
                ['order_id'=> $order['order_id']],
                [
                    'expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),
                    'status'=>$pay_status,
                    'txn_id'=>$txn_id,
                    'pay_time'=> $pay_time,
                    'score_year_month' => date('Ym')
                ]
            );
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order['order_id'];
            $insert_data['data']['status'] = $pay_status;
            $insert_data['data']['pay_time'] = $pay_time;

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            //库存变动
            if($order['status'] == '98' || $order['status'] == '99'){
//                $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order['order_id'])->from('trade_orders_goods')->get()->result_array();
                $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                    ['order_id'=>$order['order_id']]);
                $this->m_group->update_goods_number($goods, $order['order_id']);
            }
            
            /***********查询子订单详情用于户海关接口**********/
            $is_hgs=FALSE;
                $goods_info=$this->db->from('trade_orders_goods'.$this->tb_empty->get_table_ext($order['order_id']))->where('order_id',$order['order_id'])->get()->result_array();
                foreach ($goods_info as $gids) {
                    $gid[]=$gids['goods_sn_main'];
                }
                $is_hg=$this->db->select('is_hg')->from('mall_goods_main')->where_in('goods_sn_main',$gid)->get()->result_array();
                foreach ($is_hg as $hgs) {
                    if($hgs['is_hg']){$is_hgs=TRUE;}
                }
                if($is_hgs){
                    unset($gid);unset($is_hgs);
                    $order_info=$this->tb_trade_orders->get_one("*",['order_id'=>$order['order_id']]);
                    $order_info['goods_list']=$goods_info;
                    $order_list[]=$order_info;
                }
                /***********查询子订单详情用于户海关接口**********/
            
		}
        //使用代品券
        if($order['discount_type']=='2') {
            $this->load->model('m_suite_exchange_coupon');
            $this->m_suite_exchange_coupon->useCoupon($order['customer_id'], $order['goods_amount_usd'] / 100);
        }

		/** 如果勾选了收据 插入收据邮件 */
		if(isset($order['need_receipt']) && $order['need_receipt']){
			$this->db->insert('sync_send_receipt_email',array('uid'=>$order['customer_id'],'order_id'=>$order['order_id'],'type'=>0));
		}
//                $huilv=  $this->db->select("currency_rate")->where('order_id',$order['order_id'])->from('trade_orders')->get()->row_array();
		$huilv = $this->tb_trade_orders->get_one("currency_rate",['order_id'=>$order['order_id']]);
        if($txn_id === 'TPS'.$order['order_id']){ //如果是余额支付
            $amount = $order['money']/100;
            $this->db->where('id',$order['customer_id'])->set('amount','amount-'.$amount,FALSE)->update('users');
			/** 支付方式 */
			$this->load->model('m_trade');
			$this->m_trade->update_order_payment($order,'110');
			/** 資額變動 */
			$this->load->model('m_commission');
			$this->m_commission->commissionLogs($order['customer_id'],20,-1*$amount,0,$order['order_id']);
		}

		if(empty($sub_orders)){

			/*如果不是doba订单,产生新订单时，触发奖金制度相关动作。*/
			if($order['is_doba_order'] != '1') {

                /*记录到新订单处理队列*/
                $this->load->model('tb_new_order_trigger_queue');
                $this->tb_new_order_trigger_queue->addNewOrderToQueue($order['order_id'],$order['shopkeeper_id'],$order['goods_amount_usd'],$order['order_profit_usd']);
			}

			//如果是doba订单,改成已付款
			if($order['is_doba_order'] == '1')
			{
				$order_id = $order['order_id'];
				$this->db->query("update trade_order_doba_log set status = 1,pay_time = '$pay_time' WHERE order_id ='$order_id' ");
			}
		}else{
			/** 拆分單 */
			foreach($sub_orders as $sub_order){

				if($sub_order['is_doba_order'] != '1') {

                    /*记录到新订单处理队列*/
                    $this->load->model('tb_new_order_trigger_queue');
                    $this->tb_new_order_trigger_queue->addNewOrderToQueue($sub_order['order_id'],$sub_order['shopkeeper_id'],$sub_order['goods_amount_usd'],$sub_order['order_profit_usd']);
				}

				//如果是doba订单,改成已付款
				if($sub_order['is_doba_order'] == '1')
				{
					$order_id = $sub_order['order_id'];
					$pay_time = date('Y-m-d H:i:s',time());
					$this->db->query("update trade_order_doba_log set status = 1,pay_time = '$pay_time' WHERE order_id ='$order_id' ");
				}
			}
		}
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
			$this->m_log->createOrdersLog($order_id,'商城普通訂單回滾了');
			if($txn_id === 'TPS'.$order_id){ //如果是余额支付 notify_num 重置成0
//				$this->db->where('order_id', $order['order_id'])->set('notify_num', '0', FALSE)->update('trade_orders');
				$this->tb_trade_orders->update_one(['order_id'=>$order_id],["pay_time"=>"0000-00-00 00:00:00"]);
			}
            return FALSE;
        }
        else
        {
            if(isset($order_list)){
                $this->m_erp->wd_erp_new_push($order_list);
            }
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /**  修改升级訂單狀態
     * @param $order
     * @param $txn_id 交易ID
     * @param int $pay_status
     */
    function order_paid($order, $txn_id ,$pay_status = 2 , $is_grant_generation = true){

        $this->load->model('m_coupons');
        $this->load->model('m_user');

        $userInfo = current($this->m_user->getInfo($order['uid']));

        /* 统计订单付款成功后回调的次数，并根据次数就限定，只允许第一次回调执行。 */
        $this->db->where('order_sn', $order['order_sn'])->set('notify_num', 'notify_num+1', FALSE)->update('user_upgrade_order');
        if ($this->db->select('notify_num')->from('user_upgrade_order')->where('order_sn', $order['order_sn'])->get()->row_object()->notify_num > 1) {
            return;
        }

        try{
            $this->db->trans_begin();

			if($is_grant_generation){
				//團隊銷售提成獎
				$this->load->model('M_overrides', 'm_overrides');
				$this->m_overrides->generationSalesOverrides2($order['uid'],$order['join_fee'] * 0.8);
			}

			/** 统计直推人数量　*/
			$this->load->model('m_referrals_count');
			$this->m_referrals_count->join_referrals_count($userInfo['parent_id'],$userInfo['user_rank'],$order['level'],$userInfo['id']);

            //根据订单号（out_trade_no）更新订单状态
            $this->db->update('user_upgrade_order',array('status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>date('Y-m-d H:i:s',time())),array('order_sn'=> $order['order_sn']));

            //更新用戶等級、狀態。
//            $this->db->update('users',array('is_choose'=>1,'user_rank'=>$order['level'],'status'=>1,'store_qualified'=>1,'upgrade_time'=>date('Y-m-d H:i:s')),array('id'=> $order['uid']));
            $this->load->model("tb_users");
            $this->tb_users->modify_user_rank(array('id'=> $order['uid']),array('is_choose'=>1,'user_rank'=>$order['level'],'status'=>1,'store_qualified'=>1,'upgrade_time'=>date('Y-m-d H:i:s')));
            $this->m_user->addUserLevelChangeLog($order['uid'],$userInfo['user_rank'],$order['level'],LEVEL_TYPE_STORE);
            $this->m_user->addInfoToWohaoSyncQueue($order['uid'],array(11));
            $this->m_user->rewardMemSharingPoint($order['uid'],$order['join_fee']);//用户奖励分红点。
            $this->m_user->updateIntrMemMonth($userInfo,$order['level']);//统计用户每月推荐会员数
			$this->db->query("delete from users_month_fee_fail_info where uid=".$order['uid']); //移除扣月费失败记录

			/***用户升级成功->进入2*5,138matrix***/
			$this->load->model('m_forced_matrix');
			$this->m_forced_matrix->save_user_for_138($order['uid']);
			$this->m_forced_matrix->userSort2x5($order['uid']);

            /*对于没有拿过138奖金的,如果这个月满足了条件，则立刻加入138合格列表*/
            $this->load->model('m_forced_matrix');
            $this->m_forced_matrix->join_qualified_for_138($order['uid']);

            //插入訂單log記錄
            $logs = array(
                'uid'=>$order['uid'],
                'upgrade_rank'=>$order['level'],
                'create_time'=>time()
            );
            $this->db->insert('user_upgrade_log',$logs);

            /*用户从免费到付费会员触发的动作。*/
            if ($order['before_level'] == 4) {

                $this->m_coupons->giveMonthlyFeeCoupon($order['uid'],3);//给新加盟的会员发放3张月费券。
				$this->m_user->addInfoToWohaoSyncQueue($order['uid'],array(0));
                $this->m_user->freeToFeeAffectParent($order['uid']);//职称的变动。
                $this->m_user->dayProfitShareForNewMem($userInfo);//当新加入的会员满足日分红时，立即加入日分红用户表开始分红。
				if( $userInfo['month_fee_rank'] == 4 ){ /** 没有升级过月费　*/
					$this->db->update('users',array('first_monthly_fee_level'=>$order['level'],'month_fee_date'=>(int) date('d'),'upgrade_month_fee_time'=>date('Y-m-d H:i:s')),array('id'=> $order['uid']));
				}
			}
			if($userInfo['month_fee_rank'] != 4 && $order['level']  <= $userInfo['month_fee_rank'] ){
				$this->db->update('users',array('month_fee_rank'=>4),array('id'=> $order['uid']));
			}

            $newUserInfo = current($this->m_user->getInfo($userInfo['id']));

            /*更新用户等级后检查其是否满足了周领导对等奖，如果是第一次满足，则立即加入周领导对等奖。*/
            $this->load->model('tb_commission_logs');
            $this->load->model('tb_users_store_sale_info_monthly');
            if( $newUserInfo['user_rank']==1 && $newUserInfo['sale_rank']>=2 && !$this->tb_commission_logs->checkUserCommissionOfType($order['uid'],7) ){
                $newMonthSaleAmount = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($order['uid'],date('Ym'));
                if($newMonthSaleAmount>=7500 && date('Ym')<=201610){
                    $this->db->replace('week_leader_members', array(
                        'uid' => $order['uid'],
                    ));
                }
            }

            if ($this->db->trans_status() === FALSE)
            {
	            if ($order['before_level'] == 4) {
		        	$this->load->model('o_queen');
// 		        	$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
	        	}
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }

        }catch (Exception $e){
        	if ($order['before_level'] == 4) {
	        	$this->load->model('o_queen');
// 	        	$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
        	}
            $this->db->trans_rollback();
        }
    }

    /** 商城的产品套装 */
    function mall_order_paid($order, $txn_id ,$pay_status = 3){
    	
    	if (empty($order)) {
    		return false;
    	}
        $pay_time = date('Y-m-d H:i:s',time());
        $this->load->model('m_coupons');
        $this->load->model('m_user');
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $userInfo = current($this->m_user->getInfo($order['customer_id']));

        $tmp = $this->tb_trade_orders->get_one_auto([
            "select"=>"pay_time",
            "where"=>["order_id"=>$order['order_id']],
            "force_master"=>1
        ]);

        if($tmp['pay_time'] && $tmp['pay_time'] !== "0000-00-00 00:00:00" && $tmp['pay_time'] !== null)
        {
            return false;
        }

        if($order['order_type'] != 5) {
            /** 检测重复升级的订单  升级等级 === 用户本来的等级 */
            if (isset($order['level']) && $order['level'] == $userInfo['user_rank']) {
                $this->m_log->createOrdersLog($order['order_id'], '商城产品套装訂單重复升级了');
                return false;
            }
       }

       $order_id=$order['order_id'];
       $user_id=$order['customer_id'];
       if (empty($order_id) || empty($user_id)) {
       		return false;
       }
        try{
            $this->db->trans_begin();
            $this->load->model('m_suite_exchange_coupon');

            /***店铺升级付款成功后，发放代品券***/
            $this->load->model('m_group');
            $sql="select * from temp_save_coupons where order_id='$order_id' and user_id=$user_id";
            $res=$this->db->query($sql)->result();

            if(!empty($res)){
                $this->load->model('m_suite_exchange_coupon');
                foreach($res as $coupons){
                    $this->m_suite_exchange_coupon->add($user_id,$coupons->coupons_value,$coupons->coupons_num);
                }
            }
            $this->db->query("delete from temp_save_coupons where user_id=$user_id and order_id='$order_id' ");

            //團隊銷售提成獎
            $this->load->model('M_overrides', 'm_overrides');
			$or_mon = ($order['order_amount_usd'] - $order['deliver_fee_usd']) / 100;
            $this->m_overrides->generationSalesOverrides2($order['customer_id'], $or_mon * 0.8, $order['order_id']);

			/*用户从免费到付费会员触发的动作。*/
            if ($userInfo['user_rank'] == 4) {
                
                $this->m_coupons->giveMonthlyFeeCoupon($order['customer_id'],3);//给新加盟的会员发放3张月费券。
				$this->m_user->addInfoToWohaoSyncQueue($order['customer_id'],array(0));
                $this->m_user->freeToFeeAffectParent($order['customer_id']);//职称的变动。
				if( $userInfo['month_fee_rank'] == 4 ){ /** 没有升级过月费　*/
					$this->db->update('users',array('first_monthly_fee_level'=>$order['level'],'month_fee_date'=>(int) date('d'),'upgrade_month_fee_time'=>date('Y-m-d H:i:s')),array('id'=> $order['customer_id']));
				}
			}

            if($order['order_prop'] == '2'){ //拆分單
                $sub_orders = $this->tb_trade_orders->get_list_auto([
                   "select"=>"order_id,shopkeeper_id,customer_id,goods_amount_usd,order_profit_usd,status",
                    "where"=>[
                        'attach_id'=>$order['order_id'],
                        'order_prop'=>'1'
                    ],
                    "force_master"=>1,
                ]);

                if($sub_orders)foreach($sub_orders as $sub_order){
					//批量修改子訂單狀態
//					$res = $this->db->update('trade_orders',array('expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),'status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>$pay_time),array('order_id'=> $sub_order['order_id']));
                    $res = $this->tb_trade_orders->update_one([
                            'order_id'=> $sub_order['order_id']
                        ],
                        [
                            'expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),
                            'status'=>$pay_status,
                            'txn_id'=>$txn_id,
                            'pay_time'=>$pay_time
                        ]);

                    if($res){
                        //修改成功插入到trade_orders_log
                        $this->m_trade->add_order_log($sub_order['order_id'],110,'修改商城的产品套装订单状态status:'.$pay_status,0);

                        //订单同步erp
                        $insert_data = array();
                        $insert_data['oper_type'] = 'modify';
                        $insert_data['data']['order_id'] = $sub_order['order_id'];
                        $insert_data['data']['status'] = $pay_status;
                        $insert_data['data']['txn_id'] = $txn_id;
                        $insert_data['data']['pay_time'] = $pay_time;

                        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                        //库存变动
                        if($sub_order['status'] == '98' || $sub_order['status'] == '99'){
//                            $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$sub_order['order_id'])->from('trade_orders_goods')->get()->result_array();
                            $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                                ['order_id'=>$sub_order['order_id']]);
                            $this->m_group->update_goods_number($goods, $sub_order['order_id']);
                        }
                    }
				}

                                /***********查询子订单详情用于户海关接口**********/
                                $is_hgs=FALSE;
                $goods_info=$this->db->from('trade_orders_goods'.$this->tb_empty->get_table_ext($sub_order['order_id']))->where('order_id',$sub_order['order_id'])->get()->result_array();
                foreach ($goods_info as $gids) {
                    $gid[]=$gids['goods_sn_main'];
                }
                $is_hg=$this->db->select('is_hg')->from('mall_goods_main')->where_in('goods_sn_main',$gid)->get()->result_array();
                foreach ($is_hg as $hgs) {
                    if($hgs['is_hg']){$is_hgs=TRUE;}
                }
                if($is_hgs){
                    unset($gid);unset($is_hgs);
                    $order_info=$this->tb_trade_orders->get_one("*",['order_id'=>$sub_order['order_id']]);
                    $order_info['goods_list']=$goods_info;
                    $order_list[]=$order_info;
                }
                /***********查询子订单详情用于户海关接口**********/
                                
				//主訂單的交易號，支付時間
//				$this->db->update('trade_orders',array('txn_id'=>$txn_id,'pay_time'=>$pay_time),array('order_id'=> $order['order_id']));
                $this->tb_trade_orders->update_one(['order_id'=> $order['order_id']],
                    ['txn_id'=>$txn_id,'pay_time'=>$pay_time]);
            }
			else{
				//根据订单号（out_trade_no）更新订单状态
//                $res = $this->db->update('trade_orders',array('expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),'status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>$pay_time),array('order_id'=> $order['order_id']));
                $res = $this->tb_trade_orders->update_one(
                    ['order_id'=> $order['order_id']],
                    [
                        'expect_deliver_date'=>date('Y-m-d',strtotime('+'.config_item('expect_deliver_date').' days')),
                        'status'=>$pay_status,
                        'txn_id'=>$txn_id,
                        'pay_time'=>$pay_time
                    ]);
                if($res) {
                    //插入到trade_orders_log
                    $this->m_trade->add_order_log($order['order_id'],110,'修改商城的产品套装订单状态status:'.$pay_status,0);

                    //订单同步到erp
                    $insert_data = array();
                    $insert_data['oper_type'] = 'modify';
                    $insert_data['data']['order_id'] = $order['order_id'];
                    $insert_data['data']['status'] = $pay_status;
                    $insert_data['data']['txn_id'] = $txn_id;
                    $insert_data['data']['pay_time'] = $pay_time;

                    $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                    //库存变动
                    if($order['status'] == '98' || $order['status'] == '99'){
//                        $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order['order_id'])->from('trade_orders_goods')->get()->result_array();
                        $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                            ['order_id'=>$order['order_id']]);
                        $this->m_group->update_goods_number($goods, $order['order_id']);
                    }
                }
                
                /***********查询子订单详情用于户海关接口**********/
                $is_hgs=FALSE;
                $goods_info=$this->db->from('trade_orders_goods'.$this->tb_empty->get_table_ext($order['order_id']))->where('order_id',$order['order_id'])->get()->result_array();
                foreach ($goods_info as $gids) {
                    $gid[]=$gids['goods_sn_main'];
                }
                $is_hg=$this->db->select('is_hg')->from('mall_goods_main')->where_in('goods_sn_main',$gid)->get()->result_array();
                foreach ($is_hg as $hgs) {
                    if($hgs['is_hg']){$is_hgs=TRUE;}
                }
                if($is_hgs){
                    unset($gid);unset($is_hgs);
                    $order_info=$this->tb_trade_orders->get_one("*",['order_id'=>$order['order_id']]);
                    $order_info['goods_list']=$goods_info;
                    $order_list[]=$order_info;
                }
                /***********查询子订单详情用于户海关接口**********/
                
			}

            //修改换货原订单的状态
            if($order['order_type'] == 5) {
                $strlen = strlen($order['remark']);
                $tp = strpos($order['remark'],"#");  //limit之前的字符长度
                $p_order_id = substr($order['remark'],-$strlen,$tp);  //从头开始截取到指字符位置。
                $order_info = $this->tb_trade_orders->getOrderInfo($p_order_id, array('status'));
                if($order_info){
                    //原订单状态退货完成--改成取消
//                    $this->db->where('order_id',$p_order_id)->update('trade_orders',array('status'=> Order_enum::STATUS_CANCEL));
                    $this->tb_trade_orders->update_one(['order_id'=>$p_order_id],array('status'=> Order_enum::STATUS_CANCEL));

                    //订单同步到erp
                    $insert_data = array();
                    $insert_data['oper_type'] = 'modify';
                    $insert_data['data']['order_id'] = $p_order_id;
                    $insert_data['data']['status'] = Order_enum::STATUS_CANCEL;

                    //记录到trade_order_remark_record
                    $this->db->insert('trade_order_remark_record',array(
                        'order_id'=>$p_order_id,
                        'type'=>'1',
                        'remark'=>'换货订单:'.$order_id,
                        'admin_id'=> 0,
                        'operator'=>'system'
                    ));
                    //备注同步到erp
                    $insert_data_remark = array();
                    $insert_data_remark['oper_type'] = 'remark';
                    $insert_data_remark['data']['order_id'] = $p_order_id;
                    $insert_data_remark['data']['remark'] = '换货订单:'.$order_id;
                    $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
                    $insert_data_remark['data']['recorder'] = 0; //操作人
                    $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

                    $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注
                    $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
                }
            }
            if($order['order_type'] != 5) {
                if ($userInfo['month_fee_rank'] != 4 && $order['level'] <= $userInfo['month_fee_rank']) {
                    $this->db->update('users', array('month_fee_rank' => 4), array('id' => $order['customer_id']));
                }

                //更新用戶等級、狀態。
                $this->load->model("tb_users");

                $this->tb_users->modify_user_rank(array('id'=> $order['customer_id']),array('is_choose'=>1,'user_rank'=>$order['level'],'status'=>1,'store_qualified'=>1,'upgrade_time'=>date('Y-m-d H:i:s')));
                $this->m_user->addUserLevelChangeLog($order['customer_id'],$userInfo['user_rank'],$order['level'],LEVEL_TYPE_STORE);
                $this->m_user->addInfoToWohaoSyncQueue($order['customer_id'],array(11));
                $this->m_user->rewardMemSharingPoint($order['customer_id'],$order['join_fee']);//用户奖励分红点。
                $this->m_user->updateIntrMemMonth($userInfo,$order['level']);//统计用户每月推荐会员数
                $this->db->query("delete from users_month_fee_fail_info where uid=".$order['customer_id']); //移除扣月费失败记录

                $this->load->model('m_forced_matrix');
                $this->m_forced_matrix->save_user_for_138($order['customer_id']);

                /*对于没有拿过138奖金的,如果这个月满足了条件，则立刻加入138合格列表*/
                $this->load->model('m_forced_matrix');
                $this->m_forced_matrix->join_qualified_for_138($order['customer_id']);
                //插入訂單log記錄
                $logs = array(
                    'uid' => $order['customer_id'],
                    'upgrade_rank' => $order['level'],
                    'create_time' => time()
                );
                $this->db->insert('user_upgrade_log', $logs);

                $newUserInfo = current($this->m_user->getInfo($userInfo['id']));

                /*用户从免费到付费会员触发的动作。*/
                if ($userInfo['user_rank'] == 4) {
                    $this->m_user->dayProfitShareForNewMem($userInfo);//当新加入的会员满足日分红时，立即加入日分红用户表开始分红。
                }
            }

			/** 如果勾选了收据 插入收据邮件 */
			if(isset($order['need_receipt']) && $order['need_receipt']){
				$this->db->insert('sync_send_receipt_email',array('uid'=>$order['customer_id'],'order_id'=>$order['order_id'],'type'=>0));
			}

			if($txn_id === 'TPS'.$order['order_id']){ //如果是余额支付

				$amount = $order['money']/100;

				$this->db->where('id',$order['customer_id'])->set('amount','amount-'.$amount,FALSE)->update('users');
				/** 支付方式 */
				$this->load->model('m_trade');
				$this->m_trade->update_order_payment($order,'110');

				/** 資額變動 升级费用*/
				$this->load->model('m_commission');
				$this->m_commission->commissionLogs($order['customer_id'],13,-1*$amount,0,$order['order_id']);
			}

			if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
				$this->m_log->createOrdersLog($order['order_id'],'商城产品套装訂單回滾了');
				if($txn_id === 'TPS'.$order['order_id']){
                    $this->tb_trade_orders->update_one(['order_id'=>$order['order_id']],['notify_num'=>"0000-00-00 00:00:00"]);
				}
	            if ($userInfo['user_rank'] == 4) {
		            $this->load->model('o_queen');
// 		            $this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
	            }
                return FALSE;
            }
            else
            {
                if(isset($order_list)){
                    $this->m_erp->wd_erp_new_push($order_list);
                }
                $this->db->trans_commit();
                return TRUE;
            }

        }catch (Exception $e){

            $this->db->trans_rollback();
            $this->m_log->createOrdersLog($order['order_id'],$e->getMessage());
            if ($userInfo['user_rank'] == 4) {
	            $this->load->model('o_queen');
// 	            $this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
            }
            return FALSE;
        }
    }

    /*免费手动升级店铺等级，不发放佣金，不给予发货，仅仅等级变动*/
    function upgradeLevelFree($uid,$level){

        $this->load->model('m_user');
        $userInfo = current($this->m_user->getInfo($uid));

        if($userInfo['month_fee_rank']>$level){
            return false;
        }
        
        try {
            $this->db->trans_begin();
            
//            $this->db->update('users',array('user_rank'=>$level,'upgrade_time'=>date('Y-m-d H:i:s')),array('id'=> $uid));
            $this->load->model("tb_users");
            $this->tb_users->modify_user_rank(array('id'=> $uid),array('user_rank'=>$level,'upgrade_time'=>date('Y-m-d H:i:s')));
            $this->m_user->addUserLevelChangeLog($uid,$userInfo['user_rank'],$level,LEVEL_TYPE_STORE);
            $this->m_user->updateIntrMemMonth($userInfo,$level);//统计用户每月推荐会员数

            /*用户从免费到付费会员触发的动作。*/
            if ($userInfo['user_rank'] == 4) {
                $this->m_user->freeToFeeAffectParent($uid);//职称的变动。
                $this->m_user->dayProfitShareForNewMem($userInfo);//当新加入的会员满足日分红时，立即加入日分红用户表开始分红。
            }

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                return TRUE;
            } else {
            	if ($userInfo['user_rank'] == 4) {
            		$this->load->model('o_queen');
//             		$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
            	}
                throw new Exception('error');
            }
        } catch (Exception $e) {
        	if ($userInfo['user_rank'] == 4) {
        		$this->load->model('o_queen');
//         		$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
        	}
            $this->db->trans_rollback();
            return false;
        }
    }

    
    /**
     * 一次性升级月费和加盟费
     * @param type $order
     * @param type $txn_id
     * @param type $pay_status
     */
    function order_paid_all($order, $txn_id ,$pay_status = 2){
        
        $this->load->model('m_user');
        $userInfo = current($this->m_user->getInfo($order['uid']));
        
        /* 统计订单付款成功后回调的次数，并根据次数就限定，只允许第一次回调执行。 */
        $this->db->where('order_sn', $order['order_sn'])->set('notify_num', 'notify_num+1', FALSE)->update('user_upgrade_order');
        if ($this->db->select('notify_num')->from('user_upgrade_order')->where('order_sn', $order['order_sn'])->get()->row_object()->notify_num > 1) {
            return;
        }

        try{
            $this->db->trans_begin();

            //根据订单号（out_trade_no）更新订单状态
            $this->db->update('user_upgrade_order',array('status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>date('Y-m-d H:i:s',time())),array('order_sn'=> $order['order_sn']));
            
            if ($userInfo['month_fee_rank'] > $order['level']) {
                $this->db->update('users', array('month_fee_date' => (int) date('d'), 'month_fee_rank' => $order['level'], 'upgrade_month_fee_time' => date('Y-m-d H:i:s'),'status'=>1), array('id' => $order['uid']));
                $this->m_user->addUserLevelChangeLog($order['uid'],$userInfo['month_fee_rank'],$order['level'],LEVEL_TYPE_MONTHLY_FEE);
                $this->m_user->removeMonthFeeLevelChange($order['uid']);
                $this->db->query("delete from users_month_fee_fail_info where uid=".$order['uid']);

                if($userInfo['month_fee_rank']==4){
					$this->db->insert('users_coupon_monthfee',array(
						'uid'=>$order['uid'],
						'coupon_level'=>$order['level'],
					));
					$this->m_user->addInfoToWohaoSyncQueue($order['uid'],array(0));
				}

                /***用户交月费成功->进入matrix->计算佣金***/
                $this->load->model('m_forced_matrix');
                $this->m_forced_matrix->memberFromFreeToPaid($order['uid'], 1);
                //if(!config_item('enter_138_new_rule')) {
                    //$this->m_forced_matrix->save_user_for_138($order['uid']);
                //}
            }
            
            //團隊銷售提成獎
            $this->load->model('M_overrides', 'm_overrides');
            $this->m_overrides->generationSalesOverrides2($order['uid'],$order['join_fee'] * 0.8);
            
            //更新用戶等級。
//            $this->db->update('users',array('user_rank'=>$order['level'],'store_qualified'=>1,'upgrade_time'=>date('Y-m-d H:i:s')),array('id'=> $order['uid']));
            $this->load->model("tb_users");
            $this->tb_users->modify_user_rank(array('id'=> $order['uid']),array('user_rank'=>$order['level'],'store_qualified'=>1,'upgrade_time'=>date('Y-m-d H:i:s')));
            $this->m_user->addUserLevelChangeLog($order['uid'],$userInfo['user_rank'],$order['level'],LEVEL_TYPE_STORE);
            $this->m_user->addInfoToWohaoSyncQueue($order['uid'],array(11));
            $this->m_user->rewardMemSharingPoint($order['uid'],$order['join_fee']);//用户奖励分红点。
            $this->m_user->updateIntrMemMonth($userInfo,$order['level']);//统计用户每月推荐会员数

            //插入訂單log記錄
            $logs = array(
                'uid'=>$order['uid'],
                'upgrade_rank'=>$order['level'],
                'create_time'=>time()
            );
            $this->db->insert('user_upgrade_log',$logs);

            /*用户从免费到付费会员触发的动作。*/
            if ($order['before_level'] == 4) {
                $this->m_user->freeToFeeAffectParent($order['uid']);//职称的变动。
                $this->m_user->dayProfitShareForNewMem($userInfo);//当新加入的会员满足日分红时，立即加入日分红用户表开始分红。
            }

            if ($this->db->trans_status() === FALSE)
            {
            	if ($order['before_level'] == 4) {
            		$this->load->model('o_queen');
//             		$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
            	}
                $this->db->trans_rollback();
                $this->m_log->createOrdersLog($order['order_sn'],'訂單回滾了');
            }
            else
            {
                $this->db->trans_commit();
            }

        }catch (Exception $e){
        	if ($order['before_level'] == 4) {
        		$this->load->model('o_queen');
//         		$this->o_queen->en_queen(o_queen::QUEEN_USER_RANK_TITLE, $order['uid']);
        	}
            $this->db->trans_rollback();
        }
    }
    
    /**  充值月费訂單狀態
     * @param $order
     * @param $txn_id 交易ID
     * @param int $pay_status
     */
    public function month_fee_order_paid($order, $txn_id ,$pay_status = 2){
        try{

            $this->load->model('m_coupons');
            //验证訂單是否已经处理过
            $check_order = $this->db->select('status')->from('user_month_fee_order')->where('order_sn',$order['order_sn'])->get()->row_array();
            $duplication_add_log = $this->db->from('user_month_fee_log')->where('order_id',$order['id'])->count_all_results();
            if($check_order['status'] == 2 || $duplication_add_log){
                return false;
            }
            $this->db->trans_begin();

            $pay_time = date('Y-m-d H:i:s',time());
            $this->load->model('m_commission');
            $this->load->model('m_user');
            $user = current($this->m_user->getInfo($order['uid']));
            $monthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($order['uid']);
            $this->m_commission->monthFeeChangeLog($order['uid'],$user['month_fee_pool'],$user['month_fee_pool'] + $order['usd_money'],$order['usd_money'],$pay_time,1,$monthlyFeeCouponNum,$monthlyFeeCouponNum,0);

            $this->db->update('user_month_fee_order',array('status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>$pay_time),array('order_sn'=> $order['order_sn']));
            //更新用戶月费池
            $this->db->where('id',$order['uid'])->set('month_fee_pool','month_fee_pool +'. $order['usd_money'], FALSE)->update('users');

            $this->m_user->checkMonthFeeGap($order['uid']);//检查是否欠月费。

            $add_monthly_fee = array(
                'uid'=>$order['uid'],
                'order_id'=>$order['id'],
                'money'=>$order['usd_money'],
                'payment'=>$order['payment'],
                'create_time'=>$pay_time,
            );
            $this->db->insert('user_month_fee_log',$add_monthly_fee);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->m_log->createOrdersLog($order['order_sn'],'充值月费訂單回滾了');
				return false;
            }
            else
            {
                $this->db->trans_commit();
				return true;
            }

        }catch (Exception $e){

            $this->db->trans_rollback();
			return false;
        }
    }


    /**  購買月費等級
     * @param $order
     * @param $txn_id 交易ID
     * @param int $pay_status
     */
    public function upgrade_month_order_paid($order, $txn_id ,$pay_status = 2){
		return TRUE;
		
        $this->load->model('m_user');

        /* 统计订单付款成功后回调的次数，并根据次数就限定，只允许第一次回调执行。 */
        $this->db->where('order_sn', $order['order_sn'])->set('notify_num', 'notify_num+1', FALSE)->update('user_upgrade_month_order');
        if ($this->db->select('notify_num')->from('user_upgrade_month_order')->where('order_sn', $order['order_sn'])->get()->row_object()->notify_num > 1) {
            return false;
        }
		$oldUserInfo = current($this->m_user->getInfo($order['uid']));
        try{
            $this->db->trans_begin();

			if($order['level'] == $oldUserInfo['month_fee_rank']){
				/*$this->m_log->createOrdersLog($order['order_sn'],'重复升级月费');
				$this->db->where('id', $order['uid'])->set('amount', 'amount+' . $order['usd_money'], FALSE)->update('users');

				$this->load->model('m_commission');
				$this->m_commission->commissionLogs($order['uid'],9,$order['usd_money'],0,$order['order_sn']);*/
				return false;
			}

            $this->db->update('user_upgrade_month_order',array('status'=>$pay_status,'txn_id'=>$txn_id,'pay_time'=>date('Y-m-d H:i:s',time())),array('order_sn'=> $order['order_sn']));
            $this->db->update('users',array('month_fee_date'=>(int)date('d'),'month_fee_rank'=>$order['level'],'upgrade_month_fee_time'=>date('Y-m-d H:i:s'),'status'=>1,'store_qualified'=>1),array('id'=> $order['uid']));
            $this->m_user->addUserLevelChangeLog($order['uid'],$oldUserInfo['month_fee_rank'],$order['level'],LEVEL_TYPE_MONTHLY_FEE);
            $this->m_user->removeMonthFeeLevelChange($order['uid']);
            $this->db->query("delete from users_month_fee_fail_info where uid=".$order['uid']);

            if($oldUserInfo['month_fee_rank']==4){
                $this->db->insert('users_coupon_monthfee',array(
                    'uid'=>$order['uid'],
                    'coupon_level'=>$order['level'],
                ));
                $this->m_user->addInfoToWohaoSyncQueue($order['uid'],array(0));
            }

            /***用户交月费成功->进入matrix->计算佣金***/
            $this->load->model('m_forced_matrix');
            $this->m_forced_matrix->memberFromFreeToPaid($order['uid'],1);
            //if(!config_item('enter_138_new_rule')) {
                //$this->m_forced_matrix->save_user_for_138($order['uid']);
            //}

			if($txn_id === 'TPS'.$order['order_sn']){ //如果是余额支付

				$this->db->where('id', $order['uid'])->set('amount','amount-'.$order['usd_money'],FALSE)->update('users');
				$this->load->model('m_commission');
				$this->m_commission->commissionLogs($order['uid'],13,-1*$order['usd_money']);
			}

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->m_log->createOrdersLog($order['order_sn'],'升级月费訂單回滾了');
				return false;
            }
            else
            {
                $this->db->trans_commit();
				return true;
            }

        }catch (Exception $e){

            $this->db->trans_rollback();
			return false;
        }
    }
    
    /**
     * 手动升级月费等级
     * @param type $uid
     * @param type $level
     */
    public function uptrade_month_level_manually($uid,$level,$adminId=''){
        
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        $before_level = $userInfoBefore['month_fee_rank'];
        if($level>=$before_level){
            return FALSE;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $month_fee = $arrJoinFeeAndMonthFee[$level]['month_fee'];
        $order = array(
            'order_sn' => get_order_sn('UM'),
            'uid' => $uid,
            'money' => $month_fee,
            'usd_money' => $month_fee,
            'level' => $level,
            'payment' => 'manually (admin id:'.$adminId.')',
            'create_time' => time()
        );
        $this->db->insert('user_upgrade_month_order', $order);
        $this->upgrade_month_order_paid($order, '');
        return TRUE;
    }

    /**
     * 手动升级店铺等级
     * @param int $uid
     * @param string $level
     * @param string $adminId
     * @param bool $is_grant_generation
	 * @return	bool
     */
	public function uptradeStoreLevelManually($uid,$level='',$adminId='',$is_grant_generation=true,$is_certificate=true){
        
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        if(!$level){
            $level = $userInfoBefore['month_fee_rank'];
        }
        $before_level = $userInfoBefore['user_rank'];
        if($level>=$before_level && $level!=5){ //銅級除外，因為銅級5
            return FALSE;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
		/**
		 * 　如果不是免费店铺，查看是否是１.１号之前升级的。如果是，得到当时升级店铺的费用，计算升級差价
		 */
		/*$old_join_fee = $this->m_user->upgrade_before_1_1_amount($uid,$before_level);
		if($old_join_fee !== FALSE){
			$arrJoinFeeAndMonthFee[$before_level]['join_fee'] = $old_join_fee;
		}*/

        $join_fee = $arrJoinFeeAndMonthFee[$level]['join_fee']-$arrJoinFeeAndMonthFee[$before_level]['join_fee'];
        $order = array(
            'order_sn' => get_order_sn('U'),
            'uid' => $uid,
            'money' => $join_fee,
            'join_fee' => $join_fee,
            'before_level' => $before_level,
            'level' => $level,
            'payment' => 'manually (admin id:'.$adminId.')',
            'create_time' => time()
        );
        $this->db->insert('user_upgrade_order', $order);

        //2017-03-01 00:00:00 后升级订单不在发放代品券
        if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')) {
            if ($is_certificate) {
                //增加对应金额的代品券
                $this->load->model('m_suite_exchange_coupon');
                $this->m_suite_exchange_coupon->add_voucher($uid, $join_fee);
            }
        }
        $this->order_paid($order, '', 2 ,$is_grant_generation);
        return TRUE;
    }

    /**
     * 用现金池金额升级
     * @param type $uid
     * @param type $level
     */
    public function uptradeAllLevelByAmount($uid,$level){
        
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        $before_level = $userInfoBefore['user_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['join_fee']-$arrJoinFeeAndMonthFee[$before_level]['join_fee'];
        $allMoney = $join_fee;
        if($level<$userInfoBefore['month_fee_rank']){
            $month_fee= $arrJoinFeeAndMonthFee[$level]['month_fee'];
            $allMoney+=$month_fee;
        }
        if($userInfoBefore['amount']<$allMoney){
            return 1030;
        }
        $this->db->where('id', $uid)->set('amount','amount-'.$allMoney,FALSE)->update('users');
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($uid,13,'-'.$allMoney);

        if($allMoney>$join_fee){
            $order = array(
                'order_sn' => get_order_sn('UM'),
                'uid' => $uid,
                'money' => $month_fee,
                'usd_money' => $month_fee,
                'level' => $level,
                'payment' => 'tps_amount',
                'create_time' => time()
            );
            $this->db->insert('user_upgrade_month_order', $order);
            $this->upgrade_month_order_paid($order, '');
        }
        $order = array(
            'order_sn' => get_order_sn('U'),
            'uid' => $uid,
            'money' => $join_fee,
            'join_fee' => $join_fee,
            'before_level' => $before_level,
            'level' => $level,
            'payment' => 'tps_amount',
            'create_time' => time()
        );
        $this->db->insert('user_upgrade_order', $order);
        $this->order_paid($order, '');
        
        return 0;
    }

    /**
     * 用现金池金额升级月费等级
     * @param type $uid
     * @param type $level
     */
    public function uptradeMonthlyLevelByAmount($uid,$level){
        
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        $before_level = $userInfoBefore['month_fee_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['month_fee'];
        if($userInfoBefore['amount']<$join_fee){
            return 1030;
        }

        $order = array(
            'uid' => $uid,
            'money' => $join_fee,
            'usd_money' => $join_fee,
            'level' => $level,
            'payment' => 'tps_amount',
            'create_time' => time()
        );
		do{
			$order['order_sn'] = get_order_sn('UM'); //获取新订单号
			$count =  $this->db->from('user_upgrade_month_order')->where('order_sn',$order['order_sn'])->count_all_results();
		}
		while ($count > 0); //如果是订单号重复则重新生成订单

        $this->db->insert('user_upgrade_month_order', $order);

		if($this->db->insert_id()){
			$this->upgrade_month_order_paid($order, 'TPS'.$order['order_sn']);
		}else{
			return 103;
		}
        return 0;
    }

    /**
     * 用现金池金额升级店铺等级
     * @param type $uid
     * @param type $level
     */
    public function uptradeStoreLevelByAmount($uid,$level=''){
        
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        if(!$level){
            $level = $userInfoBefore['month_fee_rank'];
        }
        $before_level = $userInfoBefore['user_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['join_fee']-$arrJoinFeeAndMonthFee[$before_level]['join_fee'];
        if($userInfoBefore['amount']<$join_fee){
            return 1030;
        }
        $this->db->where('id', $uid)->set('amount','amount-'.$join_fee,FALSE)->update('users');
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($uid,13,'-'.$join_fee);
        $order = array(
            'order_sn' => get_order_sn('U'),
            'uid' => $uid,
            'money' => $join_fee,
            'join_fee' => $join_fee,
            'before_level' => $before_level,
            'level' => $level,
            'payment' => 'tps_amount',
            'create_time' => time()
        );
        $this->db->insert('user_upgrade_order', $order);
        $this->order_paid($order, '');
        return 0;
    }

    public function upgradeAllLevelByAmountCheck($uid,$level){
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        $before_level = $userInfoBefore['user_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['join_fee']-$arrJoinFeeAndMonthFee[$before_level]['join_fee'];
        if($level<$userInfoBefore['month_fee_rank']){
            $join_fee += $arrJoinFeeAndMonthFee[$level]['month_fee'];
        }
        if($userInfoBefore['amount']<$join_fee){
            return 1030;
        }
        return 0;
    }

    public function upgradeStoreLevelByAmountCheck($uid,$level=''){
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        if(!$level){
            $level = $userInfoBefore['month_fee_rank'];
        }
        $before_level = $userInfoBefore['user_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['join_fee']-$arrJoinFeeAndMonthFee[$before_level]['join_fee'];
        if($userInfoBefore['amount']<$join_fee){
            return 1030;
        }
        return 0;
    }

    public function upgradeMonthlyLevelByAmountCheck($uid,$level){
        $this->load->model('m_user');
        $userInfoBefore = current($this->m_user->getInfo($uid));
        $before_level = $userInfoBefore['month_fee_rank'];
        if($level>=$before_level){
            return 1031;
        }
        $arrJoinFeeAndMonthFee = $this->m_user->getJoinFeeAndMonthFee();
        $join_fee = $arrJoinFeeAndMonthFee[$level]['month_fee'];
        if($userInfoBefore['amount']<$join_fee){
            return 1030;
        }
        return 0;
    }
    
    //創建升級訂單
    public function createUpgradeOrder($data,$userInfo,$payment='paypal'){

        $this->load->model('M_overrides', 'm_overrides');
        $order = array(
            'uid'=>$userInfo['id'],
            'money'=>$data['amount'],
            'status'=>0,
            'join_fee'=>$this->m_overrides->getUpgradeProfit($data['level'], $userInfo['user_rank'],$userInfo['id']),
            'create_time'=>$data['time'],
            'level'=>$data['level'],
            'before_level'=>$userInfo['user_rank'],
            'payment'=>$payment
        );

        do{
            $order['order_sn'] = get_order_sn('U'); //获取新订单号
            $count =  $this->db->from('user_upgrade_order')->where('order_sn',$order['order_sn'])->count_all_results();
        }
        while ($count > 0); //如果是订单号重复则重新生成订单

        $exe_count = 0 ;
        do{
            //生成订单
            $this->db->insert('user_upgrade_order',$order);
            $is_insert =  $this->db->from('user_upgrade_order')->where('order_sn',$order['order_sn'])->count_all_results();
            $exe_count = $exe_count + 1;
            if($exe_count > 2 && $is_insert === 0 ){
                $this->m_log->createOrdersLog($order['uid'],'生成产品套装订单失败。');
                redirect('ucenter/member_upgrade');exit;
            }
        } while ($is_insert === 0); /** 生成订单失败，继续执行，执行3次后，刷新本页面，并记录插入失败log */

        return $order;
    }
    
    public function createUpallOrder($data,$userInfo,$payment='paypal'){

        $this->load->model('M_overrides', 'm_overrides');
        $order = array(
            'uid'=>$userInfo['id'],
            'money'=>$data['upall_amount'],
            'status'=>0,
            'join_fee'=>$this->m_overrides->getUpgradeProfit($data['upall_level'], $userInfo['user_rank'],$userInfo['id']),
            'create_time'=>$data['time'],
            'level'=>$data['upall_level'],
            'before_level'=>$userInfo['user_rank'],
            'payment'=>$payment
        );

        do{
            $order['order_sn'] = get_order_sn('UA'); //获取新订单号
            $count =  $this->db->from('user_upgrade_order')->where('order_sn',$order['order_sn'])->count_all_results();
        }
        while ($count > 0); //如果是订单号重复则重新生成订单

        $exe_count = 0 ;
        do{
            //生成订单
            $this->db->insert('user_upgrade_order',$order);
            $is_insert =  $this->db->from('user_upgrade_order')->where('order_sn',$order['order_sn'])->count_all_results();
            $exe_count = $exe_count + 1;
            if($exe_count > 2 && $is_insert === 0 ){
                $this->m_log->createOrdersLog($order['uid'],'生成一次性升级订单失败。');
                redirect('ucenter/member_upgrade');exit;
            }
        } while ($is_insert === 0); /** 生成订单失败，继续执行，执行3次后，刷新本页面，并记录插入失败log */

        return $order;
    }

    //创建購買月份等級订单
    public function createUpgradeMonthFeeOrder($data,$uid,$payment='paypal'){

        $order = array(
            'uid'=>$uid,
            'money'=>$data['amount'],
            'level'=>$data['level'],
            'status'=>0,
            'create_time'=>$data['time'],
            'payment'=>$payment,
            'usd_money'=>$data['usd_money'],
        );

        do{
            $order['order_sn'] = get_order_sn('UM'); //获取新订单号
            $count =  $this->db->from('user_upgrade_month_order')->where('order_sn',$order['order_sn'])->count_all_results();
        }
        while ($count > 0); //如果是订单号重复则重新生成订单

        $exe_count = 0 ;
        do{
            //生成订单
            $this->db->insert('user_upgrade_month_order',$order);
            $is_insert =  $this->db->from('user_upgrade_month_order')->where('order_sn',$order['order_sn'])->count_all_results();
            $exe_count = $exe_count + 1;
            if($exe_count > 2 && $is_insert === 0 ){
                $this->m_log->createOrdersLog($order['uid'],'生成升级月费订单失败。');
                redirect('ucenter/member_upgrade');exit;
            }
        } while ($is_insert === 0); /** 生成订单失败，继续执行，执行3次后，刷新本页面，并记录插入失败log */

        return $order;
    }

    //创建充值月份订单
    public function createMonthFeeOrder($data,$userInfo,$payment='paypal'){

        $order = array(
            'uid'=>$userInfo['id'],
            'money'=>$data['amount'],
            'status'=>0,
            'create_time'=>$data['time'],
            'payment'=>$payment,
            'usd_money' => $data['usd_money']
        );

        do{
            $order['order_sn'] = get_order_sn('M'); //获取新订单号
            $count =  $this->db->from('user_month_fee_order')->where('order_sn',$order['order_sn'])->count_all_results();
        }
        while ($count > 0); //如果是订单号重复则重新生成订单

        $exe_count = 0 ;
        do{
            //生成订单
            $this->db->insert('user_month_fee_order',$order);
            $is_insert =  $this->db->from('user_month_fee_order')->where('order_sn',$order['order_sn'])->count_all_results();
            $exe_count = $exe_count + 1;
            if($exe_count > 2 && $is_insert === 0 ){
                $this->m_log->createOrdersLog($order['uid'],'生成月费订单失败。');
                redirect('ucenter/commission');exit;
            }
        } while ($is_insert === 0); /** 生成订单失败，继续执行，执行3次后，刷新本页面，并记录插入失败log */

        return $order;
    }

    public function getMyOrders($filter, $page = false, $perPage = 10) {
        $this->db_slave->from('mall_orders');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db_slave->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db_slave->where('create_time <=', $v);
                continue;
            }
            $this->db_slave->where($k, $v);
        }
        return $this->db_slave->order_by("order_pay_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }
    
    public function getMyOrdersTotalRows($filter) {
        $this->db_slave->from('mall_orders');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db_slave->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db_slave->where('create_time <=', $v);
                continue;
            }
            $this->db_slave->where($k, $v);
        }
        return $this->db_slave->count_all_results();
    }

	public function getDirectOrders($filter, $page = false, $perPage = 10) {
        $this->db_slave->from('one_direct_orders');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db_slave->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db_slave->where('create_time <=', $v);
                continue;
            }
            $this->db_slave->where($k, $v);
        }
        return $this->db_slave->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }

    public function getDirectTotalRows($filter) {
        $this->db_slave->from('one_direct_orders');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db_slave->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db_slave->where('create_time <=', $v);
                continue;
            }
            $this->db_slave->where($k, $v);
        }
        return $this->db_slave->count_all_results();
    }

    public function filterOrders4Auto($filter,&$params_4_auto){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            if ($k == 'order_input') {
//                $this->db_slave->where(array('order_id'=>$v));
                $params_4_auto["where"]['order_id'] = $v;
                continue;
            }
            if ($k == 'start') {
//                $this->db_slave->where('created_at >=', strtotime($v));
                $params_4_auto["where"]['created_at >='] = strtotime($v);
                continue;
            }
            if ($k == 'end') {
//                $this->db_slave->where('created_at <=', strtotime($v));
                $params_4_auto["where"]['created_at <='] = strtotime($v);
                continue;
            }
            if($k == 'month'){
                $first = date('Y-m-01', strtotime($v));
                $last = date('Y-m-t 23:59:59', strtotime($v));
//                $this->db_slave->where('created_at >=', $first);
                $params_4_auto["where"]['created_at >='] = $first;
//                $this->db_slave->where('created_at <=', $last);
                $params_4_auto["where"]['created_at <='] = $last;
            }


            if($k !== 'order_by' && $k !== 'month'){
//                $this->db_slave->where($k, $v);
                $params_4_auto["where"][$k] = $v;
            }
            if($k === 'order_by'){
                $orderby = explode('-',$v);
                if(empty($orderby[1])){
                    $orderby[1] = 'desc';
                }
//                $this->db_slave->order_by($orderby[0],$orderby[1]);
                $params_4_auto["order_by"][$orderby[0]] = $orderby[1];
                continue;
            }
        }
    }

    //请使用filterOrders4Auto代替
	public function filterOrders($filter,$uid){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
            if ($k == 'order_input') {
                //$this->db->where("order_id like '%$v%' or goods_list like '%$v%'");
                //m by brady.wang 去掉goods_list搜索,并且不要模糊搜索
                $this->db_slave->where(array('order_id'=>$v));
                continue;
            }
			if ($k == 'start') {
				$this->db_slave->where('created_at >=', strtotime($v));
				continue;
			}
			if ($k == 'end') {
				$this->db_slave->where('created_at <=', strtotime($v));
				continue;
			}
			if($k == 'month'){
				$first = date('Y-m-01', strtotime($v));
				$last = date('Y-m-t 23:59:59', strtotime($v));
				$this->db_slave->where('created_at >=', $first);
				$this->db_slave->where('created_at <=', $last);
			}


			if($k !== 'order_by' && $k !== 'month'){
				$this->db_slave->where($k, $v);
			}
			if($k === 'order_by'){
				$orderby = explode('-',$v);
				if(empty($orderby[1])){
					$orderby[1] = 'desc';
				}
				$this->db_slave->order_by($orderby[0],$orderby[1]);
				continue;
			}
		}
	}

	public function getOrders($filter,$fields,$uid,$perPage = 10) {
//        $this->db_slave->select('remark,order_type,order_id,supplier_id,order_amount_usd,customer_id,order_prop,created_at,pay_time,score_year_month,status,order_profit_usd,goods_amount_usd,txn_id')
//            ->from('trade_orders')->where($fields,$uid)->where('order_prop <>','1');
//		if($fields === 'customer_id'){
//			$this->db_slave->where('shopkeeper_id !=',$uid);
//		}
//       	$this->filterOrders($filter,$uid);
//        return $this->db_slave->order_by("created_at", "desc")->order_by("pay_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();

        $this->load->model("tb_trade_orders");
        $select = 'remark,order_type,order_id,supplier_id,order_amount_usd,customer_id,order_prop,
        created_at,pay_time,score_year_month,status,order_profit_usd,goods_amount_usd,txn_id';
        $where = [$fields=>$uid,'order_prop <>'=>'1'];
        if($fields === 'customer_id'){
            $where['shopkeeper_id !='] = $uid;
        }
        $order_by = ["created_at"=>"desc","pay_time"=>"desc"];
        $params_auto = [
            "select"=>$select,
            "where"=>$where,
            "order_by"=>$order_by,
            "page_size"=>$perPage,
            "page_index"=>($filter['page'] - 1) * $perPage
        ];
        $this->filterOrders4Auto($filter,$params_auto);
        return $this->tb_trade_orders->get_list_auto($params_auto);
    }

	public function getFiveOrders($uid){
//		return $this->db->from('trade_orders')->where('customer_id',$uid)->order_by('order_id','DESC')->limit(5)->get()->result_array();
		return $this->tb_trade_orders->get_list_auto([
		   "where"=>['customer_id'=>$uid],
            "order_by"=>["order_id"=>"desc"],
            "page_size"=>5
        ]);
	}

	public function getOrderStatusCount($status,$filter,$filed,$uid){
//		$filter['status'] = $status;
//		$this->db_slave->from('trade_orders');
//		$this->filterOrders($filter,$uid);
//		$tmp = $this->db->count_all_results();
//		var_dump($tmp);
		$this->load->model("tb_trade_orders");
		$where = ["status"=>$status];
		$param_auto = ["where"=>$where];
		$this->filterOrders4Auto($filter,$param_auto);
		return $this->tb_trade_orders->get_counts_auto($param_auto);
	}

    /**
     * 获取订单总行数
     * @param $filter
     * @param $fields
     * @param $uid
     * @return mixed
     */
    public function getOrdersRows($filter,$fields,$uid) {
        $this->load->model("tb_trade_orders");
        $where = [$fields=>$uid,"order_prop <>"=>'1'];
        if($fields === 'customer_id'){
            $where['shopkeeper_id !='] = $uid;
        }
        $param_auto = ["where"=>$where];
        $this->filterOrders4Auto($filter,$param_auto);
        $res =  $this->tb_trade_orders->get_counts_auto($param_auto);
        return $res;
    }

    /** 確認普通訂單還是產品套裝訂單 trade_orders_type*/
    function get_order_type($order_sn){
        //强制查询主库，$this->db->force_master(),CKf 2017/05/25 11:39
        $row = $this->db->force_master()->where('order_id',$order_sn)->get('trade_orders_type')->row_array();
        return $row;
    }

    /** 升級月費，或充值月 或 商城普通訂單 或 商城產品套裝 */
    function getPayAction($order_sn){

        $act = array('table'=>'','payFunc'=>'','field'=>'');
        $first = substr( $order_sn, 0, 1 );
        if($first == 'M'){
            $table = 'user_month_fee_order'; //充值月份
            $payFunc = 'month_fee_order_paid';
            $field = 'order_sn';
        }else if($first == 'U'){
            $second = substr( $order_sn, 0, 2 );
            if($second == 'UM'){
                $table = 'user_upgrade_month_order'; //購買月份等級
                $payFunc = 'upgrade_month_order_paid';
                $field = 'order_sn';
            }else{
				$table = 'user_upgrade_order'; //升级操作
				$payFunc = '';
				$field = 'order_sn';
			}
        }else{ //商城訂單（區分是產品套裝還是普通訂單）
            $order_type = $this->get_order_type($order_sn);
            if($order_type){
                $table = 'trade_orders'; //產品套裝
                $payFunc = 'mall_order_paid';
                $field = 'order_id';
            }else{
                $table = 'trade_orders'; //普通訂單
                $payFunc = 'mall_general_order_paid';
                $field = 'order_id';
            }
            $act['order_type'] = $order_type;
        }
         $act['table'] = $table;
         $act['payFunc'] = $payFunc;
         $act['field'] = $field;
         return $act;
    }

	/** 补充韩国订单信息 */
	public function update_korea_order($data){
		$order_id = $data['order_id'];
//		$order = $this->db->from('trade_orders')->where('order_id',$order_id)->get()->row_array();
		$order = $this->tb_trade_orders->get_one_auto("*",['order_id'=>$order_id]);
		if($order['area'] == '410' && (preg_match('/[a-zA-Z]{3,}/', $order['address']) || !$order['zip_code'] || !$order['customs_clearance'] ) || $order['is_export_lock'] == 0 ){
			unset($data['order_id']);
			if($order['order_prop'] == '2'){
//				$this->db->where('attach_id',$order_id)->update('trade_orders',$data);
				$this->tb_trade_orders->update_batch(['attach_id'=>$order_id],$data);
			}else{
//				$this->db->where('order_id',$order_id)->update('trade_orders',$data);
                $this->tb_trade_orders->update_batch(['order_id'=>$order_id],$data);
			}
			return array('success'=>1,'msg'=>'');
		}else{
			return array('success'=>0,'msg'=>lang('admin_order_lock'));
		}
	}

	/** 检测订单产品是否下架，库存数 */
	public function test_order_product($order_id){
		$cur_language_id = get_cookie('curLan_id', true);
		if (empty($cur_language_id)) {
			$cur_language_id = 1;
		}
		$result['success'] = 1;
//		$goods = $this->db->where('order_id',$order_id)->get('trade_orders_goods')->result_array();
        $goods = $this->tb_trade_orders_goods->get_list("*",
            ['order_id'=>$order_id]);
		if($goods){
			foreach($goods as $item){
				// 商品信息
				$goods_main_info = $this->db
					->select('is_on_sale')
					->where('goods_sn_main', $item['goods_sn_main'])
					->where('language_id', $cur_language_id)
					->get('mall_goods_main')
					->row_array();
				if($goods_main_info && $goods_main_info['is_on_sale'] == 0){
					$result['success'] = 0;
					$result['msg'] = $item['goods_name'].'<br>'.lang('goods_sold_out');
					break;
				}
				/*$goods_info_item = $this->db->select("goods_number")
					->where("goods_sn", $item['goods_sn'])
					->where('language_id',$cur_language_id)
					->get("mall_goods")
					->row_array();
				if ($goods_info_item && $item['goods_number'] > $goods_info_item['goods_number']){
					$result['success'] = 0;
					$result['msg'] = $item['goods_name'].'<br>'.lang('checkout_order_low_stocks');
					break;
				}*/
			}
		}else{
			$result['success'] = 0;
			$result['msg'] = lang('order_not_exits');
		}
		return $result;
	}

	/** 检测订单中心的操作 当前区域和收货区域是否一致 */
	public function check_location($order_id,$location_id){
        $order_id = empty($order_id) ? '' : strval($order_id);
//		$row = $this->db->select('area')->where('order_id',$order_id)->get('trade_orders')->row_array();
        $row = $this->tb_trade_orders->get_one("area",["order_id"=>$order_id]);
		if(!$row) return true;

		/** 这些都是其他区域 */
		if(in_array($row['area'],array('158','096','360','446','458'))){
			$row['area'] = '000';
		}

		$is_true = $row['area'] == $location_id ? true : false ;

		return array('flag'=>$is_true,'area'=>$row['area']);
	}
    /**
     * 查询用户是否下单过，删除免费店铺时候做判断
     * Ckf
     * return int
     * 20161101
     */
    public function  getUserOrderNum($uid){
//        $this->db->from('trade_orders');
//        $this->db->where('customer_id', $uid);
//        $row = $this->db->count_all_results();
        $row = $this->tb_trade_orders->get_counts(['customer_id'=>$uid]);
        return $row;
    }

    /**
     * @author brady.wang
     * @function 用户下单后自己修改地址
     * @param $data [array]更新数据
     * @param $order_id [int] 订单id
     * @return bool
     */
    public  function update_order_address($data, $order_id)
    {
        if (empty($data)) {
            return false;
        }
//        $res = $this->db->update('trade_orders',$data,['order_id'=>$order_id]);
//        $affected_rows = $this->db->affected_rows();
        $affected_rows = $this->tb_trade_orders->update_one(['order_id'=>$order_id],$data);
        if ($affected_rows >0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @function 根据条件查找
     * @param array $params 查询条件
     * @param bool|false $get_rows 是否查询行数
     * @param bool|false $get_one 是否只返回一行
     * @return array
     */
    public function get_by_where(array $params, $get_rows = false,$get_one=false)
    {
        $this->db->from($this->table);
        if (isset($params['select'])) {
            if (isset($params['select_escape'])){
                $this->db->select($params['select'], false);
            }else{
                $this->db->select($params['select']);
            }
        }
        if (isset($params['where']) && is_array($params['where'])) {
            $this->db->where($params['where']);
        }

        if (isset($params['join'])){
            foreach ($params['join'] as $item){
                $this->db->join($item['table'], $item['where'], $item['type']);
            }
        }


        if (isset($params['group'])) {
            $this->db->group_by($params['group']);
        }
        if (isset($params['order'])) {
            $this->db->order_by($params['order']);
        }
        $result = $this->db->get();
        if (!$get_one) {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->result_array() : array());
        } else {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->row_array() : array());
        }
    }

    //验证商品是否是冬季北方六省不能发货
    public function check_goods_sn_delivery($data,$new_arr,$addressobj)
    {
        $arr = [
            'error' => false,
            'msg' => ''
        ];

        $not_deliever_district = config_item('not_deliever_district');
        //不支持的商品
        $not_deliever_goods = config_item('not_deliever_goods');
        $addr_id = intval($data['address_id']);
        //获取地址详情

        $params = [
            'select' => 'id,addr_lv2',
            'where' => [
                'id' => $addr_id
            ],
            'limit' => '1'
        ];
        $address_info = $addressobj->get($params, false, true);
        if (!empty($addr_id)) {
            if (in_array($address_info['addr_lv2'], $not_deliever_district)) {
                //区域验证
                $goods_sn_list = [];
                $not_deliever_arr = [];
                foreach ($new_arr as $k=> $v) {
                    if (in_array($k, $not_deliever_goods)) {
                        $not_deliever_arr[] = $k;
                    }
                }

                if (count($not_deliever_arr) > 0) {
                    $arr['error'] = true;
                    $not_deliever_str = join(',', $not_deliever_arr);
                    $msg = lang("area_cannot_reach_1") . $not_deliever_str . lang('area_cannot_reach_2');
                    $arr['msg'] = $msg;

                }

            } else {
                $arr['error'] = false;
            }
        } else {
            $msg = lang('get_user_address_error');
            $arr['error'] = true;
            $arr['msg'] = $msg;
        }

        return $arr;

    }

    public function check_goods_sn_delivery_4order($data)
    {
        $arr = [
            'error' => false,
            'msg' => ''
        ];
        $not_deliever_district = config_item('not_deliever_district');
        //不支持的商品

        $not_deliever_goods = config_item('not_deliever_goods');
        $addr_id = intval($data['addr_id']);
        //获取地址详情
        $this->load->model("M_address", 'm_address');
        $params = [
            'select' => 'id,addr_lv2',
            'where' => [
                'id' => $addr_id
            ],
            'limit' => '1'
        ];
        $address_info = $this->m_address->get($params, false, true);

        if (!empty($addr_id)) {
            if (in_array($address_info['addr_lv2'], $not_deliever_district)) {
                //区域验证
                $goods_sn_list = [];
                if (!empty($data['deliver'])) {
                    foreach ($data['deliver'] as $v) {
                        if (!empty($v['list'])) {
                            foreach ($v['list'] as $vv) {
                                $goods_sn_list[] = $vv['goods_sn'];
                            }
                        }
                    }
                }

                $not_deliever_arr = [];
                foreach ($goods_sn_list as $v) {
                    if (in_array($v, $not_deliever_goods)) {
                        $not_deliever_arr[] = $v;
                    }
                }

                if (count($not_deliever_arr) > 0) {
                    $arr['error'] = true;
                    $not_deliever_str = join(',', $not_deliever_arr);
                    $msg = lang("area_cannot_reach_1") . $not_deliever_str . lang('area_cannot_reach_2');
                    $arr['msg'] = $msg;

                }

            } else {
                $arr['error'] = false;
            }
        } else {
            $msg = lang('get_user_address_error');
            $arr['error'] = true;
            $arr['msg'] = $msg;
        }
        return $arr;
    }

    //临时增加，生成报名订单
    public function sign_up_order($id) {
        $this->load->model("tb_exchange_rate");
//        $res = $this->db->select('rate')->from('exchange_rate')->where('currency', 'CNY')->get()->row_array();
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>['currency'=>'CNY']]);
        $this->load->model('m_trade');
        $this->load->model('m_split_order');
        $this->load->model('m_currency');
        $this->load->model('tb_mvp_list');
        //初始化
        $component_id=$this->m_split_order->create_component_id('S');
        $num=100000;
        $main_insert_attr['order_prop'] = '0';
        $main_insert_attr['order_id'] = $component_id;
        $main_insert_attr['attach_id'] = $component_id;
        $main_insert_attr['customer_id'] = $id;
        $main_insert_attr['area'] = '001';
        $main_insert_attr['status'] = '2';
        $main_insert_attr['goods_list'] = '00000000-1:1';
        $main_insert_attr['currency'] = 'CNY';
        $main_insert_attr['currency_rate'] = $res['rate'];
        $main_insert_attr['goods_amount'] = $num;
        $main_insert_attr['goods_amount_usd'] = $num/$res['rate'];
        $main_insert_attr['order_amount'] = $num;
        $main_insert_attr['order_amount_usd'] = $num/$res['rate'];
        $main_insert_attr['order_profit_usd'] = 0;
        $main_insert_attr['discount_amount_usd'] = 0;
        $main_insert_attr['deliver_fee_usd'] = 1;
        
        $this->db->trans_begin();//事务开始
        $this->tb_mvp_list->add_mvp($id,$component_id);
//        $this->db->insert('trade_orders', $main_insert_attr);
        $this->tb_trade_orders->insert_one($main_insert_attr);
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return $component_id;
        }
    }
    //临时增加，生成直播费订单
    public function live_up_order($data) {
        $this->load->model("tb_exchange_rate");
//        $res = $this->db->select('rate')->from('exchange_rate')->where('currency', 'CNY')->get()->row_array();
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>['currency'=>'CNY']]);
        $this->load->model('m_trade');
        $this->load->model('m_split_order');
        $this->load->model('m_currency');
        $this->load->model('tb_mvp_list');
        //初始化
        $component_id=$this->m_split_order->create_component_id('L');
        $num=200;//金额，单位：分
        $phone=$data['orderNo'];
        $id=138666;
        $main_insert_attr['order_prop'] = '0';
        $main_insert_attr['order_id'] = $component_id;
        $main_insert_attr['attach_id'] = $component_id;
        $main_insert_attr['customer_id'] = $id;
        $main_insert_attr['phone'] = $phone;
        $main_insert_attr['area'] = '001';
        $main_insert_attr['status'] = '2';
        $main_insert_attr['goods_list'] = '00000000-1:1';
        $main_insert_attr['currency'] = 'CNY';
        $main_insert_attr['currency_rate'] = $res['rate'];
        $main_insert_attr['goods_amount'] = $num;
        $main_insert_attr['goods_amount_usd'] = $num/$res['rate'];
        $main_insert_attr['order_amount'] = $num;
        $main_insert_attr['order_amount_usd'] = $num/$res['rate'];
        $main_insert_attr['order_profit_usd'] = 0;
        $main_insert_attr['discount_amount_usd'] = 0;
        $main_insert_attr['deliver_fee_usd'] = 1;
        
        $this->db->trans_begin();//事务开始
        $this->tb_mvp_list->add_mvp($id,$component_id);
//        $this->db->insert('trade_orders', $main_insert_attr);
        $this->tb_trade_orders->insert_one($main_insert_attr);
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return $component_id;
        }
    }

    //m by brady.wang 冬季不发货商品校验
    public function goods_undeliver_check($attr,$new_arr)
    {
        $start_time = strtotime(config_item('not_deliever_goods_start'));
        $end_time = strtotime(config_item('not_deliever_goods_end'));
        $this->load->model("M_address", 'm_address');
        $addressobj = $this->m_address;
        $now = time();
        if ($now > $start_time && $now < $end_time) {
            $check_res = $this->check_goods_sn_delivery($attr,$new_arr,$addressobj);
            if ($check_res['error'] == true) {
                $ret_data['code'] = 204;
                $ret_data['msg'] = $check_res['msg'];
                echo json_encode($ret_data);
                exit;
            }
        }
    }

    //m by brady.wang 冬季不发货商品校验1
    public function goods_undeliver_check_4order1($attr)
    {
        $start_time = strtotime(config_item('not_deliever_goods_start'));
        $end_time = strtotime(config_item('not_deliever_goods_end'));
        $now = time();
        if ($now > $start_time && $now < $end_time) {
            $check_res = $this->check_goods_sn_delivery_4order($attr);
            if ($check_res['error'] == true) {
                $ret_data['code'] = 204;
                $ret_data['msg'] = $check_res['msg']." with not_deliever_goods_start";
                echo json_encode($ret_data);
                exit;
            }
        }
        //m by brady.wang 冬季不发货商品校验 end
    }

    //m by brady.wang 冬季不发货商品校验2
    public function goods_undeliver_check_4order2($addr,$new_arr)
    {
        $start_time = strtotime(config_item('not_deliever_goods_start'));
        $end_time = strtotime(config_item('not_deliever_goods_end'));
        $this->load->model("M_order",'m_order');
        $this->load->model("M_address", 'm_address');
        $addressobj = $this->m_address;
        $now = time();
        if ($now > $start_time && $now < $end_time) {
            $not_deliever_district = config_item('not_deliever_district');
            //不支持的商品
            $not_deliever_goods = config_item('not_deliever_goods');
            if (in_array($addr['addr_lv2'], $not_deliever_district)) {
                //区域验证
                $goods_sn_list = [];
                $not_deliever_arr = [];
                foreach ($new_arr as $k=> $v) {
                    if (in_array($v, $not_deliever_goods)) {
                        $not_deliever_arr[] = $v;
                    }
                }
                if (count($not_deliever_arr) > 0) {
                    $arr['error'] = true;
                    $not_deliever_str = join(',', $not_deliever_arr);
                    $msg = lang("area_cannot_reach_1") . $not_deliever_str . lang('area_cannot_reach_2');
                    $arr['msg'] = $msg;
                    $ret_data['msg'] = lang("area_cannot_reach_1") . $not_deliever_str . lang('area_cannot_reach_2');
                    return json_encode($ret_data);
                    exit;
                }
            }
        }
    }

}
