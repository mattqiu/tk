<?php
ini_set('date.timezone','Asia/Shanghai');

require_once APPPATH."third_party/wxpay/lib/WxPay.Api.php";
require_once APPPATH."third_party/wxpay/lib/WxPay.Notify.php";
//require_once APPPATH."third_party/wxpay/func/log.php";

//初始化日志
//$logHandler= new CLogFileHandler(APPPATH."/logs/log-2016-06-30.php");
//$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单（流水號）
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	//查询订单(訂單號)
	public function Queryorder_two($out_trade_no)
	{
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
//		if(array_key_exists("return_code", $result)
//			&& array_key_exists("result_code", $result)
//			&& $result["return_code"] == "SUCCESS"
//			&& $result["result_code"] == "SUCCESS")
//		{
//			return true;
//		}
		return $result;
	}
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		$ci = &get_instance();
//		$ci->load->model('m_debug');
//		$ci->m_debug->log('call back');
//        $ci->m_debug->log(json_encode($data));
		//Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();

        $ci->load->model("m_log");
        $ci->m_log->createOrdersNotifyLog('11', "m_wxpay", $data);

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}

		$orderId = $data['out_trade_no'];
		$txn_id = $data['transaction_id'];

		$ci->load->model('M_order','m_order');
		$act = $ci->m_order->getPayAction($orderId);

		if(isset($act['table']) && isset($act['payFunc'])){

//			$order = $ci->db->from($act['table'])->where($act['field'],$orderId)->get()->row_array();
            if($act['table'] === 'trade_orders'){
                $ci->load->model("tb_trade_orders");
                $order = $ci->tb_trade_orders->get_one("*",[$act['field']=>$orderId]);
            }else{
                $order = $ci->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
            }
			$ci->load->model('m_log');
			$ci->m_log->ordersRollbackLog($order[$act['field']],$txn_id);
            if(in_array(substr($order[$act['field']], 0, 1 ), array('L'))){//用于第一路演临时订单
//                            $ci->db->where('order_id',$order[$act['field']])->update('trade_orders',array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$txn_id));
                $ci->load->model('tb_trade_orders');
                $ci->tb_trade_orders->update_one(
                    ['order_id'=>$order[$act['field']]],
                    array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$txn_id)
                );
            }
		}

		//$ci->m_debug->log($msg);

		return true;
	}
}


