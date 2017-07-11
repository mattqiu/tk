<?php
/**
    <p>//PRO<br>
     'merId'=>'800039253992112',//商户号<br>
 	'md5'=>'84cc7c5c71cf4b508c5fb 82ad1a03300'<br>
 	'name'=>'tps138.com'<br>
    'url'=>'https://pay.veritrans-link.com/epayment/payment,</p>
    <p> //TEST<br>
	'merId'=>'800039253992136',//商户号<br>
	'md5'=>'9c8bb94878214e77a3e7037577bae118'<br>
	'name'=>'tps138.com'<br>
	'url'=>'http://115.28.142.180:8000/epayment/payment,</p>
 */
class m_usd_unionpay extends CI_Model {

	private $__initConfig = array();

    public function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    private function __init_config(){
        $config  = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        $this->__initConfig = $config;
    }

    public function get_code($order) {
        
        include_once APPPATH . 'third_party/hgutil.php';

		$amount  = $order['money'] / 100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

		$para = array(
			"acqID" => "99020344",
			"backURL" =>  base_url('respond/do_notify?code='.base64_encode(serialize(__CLASS__))), //后台通知地址
			"charSet" => "UTF-8",
			"frontURL" => base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__))), //前台通知地址
			"merID" => $this->__initConfig['merID'],
			"merReserve" => "tps138.com",
			"orderAmount" => $amount*100,
			"orderCurrency"=> "USD",
			"orderNum" => $order['order_sn'],
			"paymentSchema" => "UP",
			"signType" => "MD5",
			"transTime" => date('YmdHis',time()),
			"transType" => "PURC",
			"version" => "VER000000001",
		);
		$key = $this->__initConfig['md5_key'];

		$ht = new hgutil($para, $key, "MD5",$this->__initConfig['request_url']);
		//print_r($para);

		$url = $ht->create_url();
		//$url ="Location:".$url;
		//echo $url;
		//header($url);
		$str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img"><span id="loading_span">';
		$str .= lang('jump').'</span></div></div> </div><div style="height:360px;">&nbsp;</div><div  style="display:none;"></div>';
		$str .=  "<script>window.location.href='$url';</script>";

		return $str;
    }

	public function get_code_mobile($order) {

		include_once APPPATH . 'third_party/hgutil.php';

		$amount  = $order['money'] / 100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

		$para = array(
			"acqID" => "99020344",
			"backURL" =>  base_url('respond/do_notify?code='.base64_encode(serialize(__CLASS__))), //后台通知地址
			"charSet" => "UTF-8",
			"frontURL" => base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__))), //前台通知地址
			"merID" => $this->__initConfig['merID'],
			"merReserve" => "tps138.com",
			"orderAmount" => $amount*100,
			"orderCurrency"=> "USD",
			"orderNum" => $order['order_sn'],
			"paymentSchema" => "UP",
			"signType" => "MD5",
			"transTime" => date('YmdHis',time()),
			"transType" => "PURC",
			"version" => "VER000000001",
		);
		$key = $this->__initConfig['md5_key'];

		$ht = new hgutil($para, $key, "MD5",$this->__initConfig['request_url']);

		$url = $ht->create_url();

		return $url;
	}

    public function do_return(){
		include_once APPPATH . 'third_party/hgutil.php';
		$version  = $_GET['version'];
		$charSet  = $_GET['charSet'];
		$transType  = $_GET['transType'];
		$orderNum  = $_GET['orderNum'];
		$orderAmount  = $_GET['orderAmount'];
		$orderCurrency  = $_GET['orderCurrency'];
		$settAmount  = $_GET['settAmount'];
		$settCurrency  = $_GET['settCurrency'];
		$rate  = $_GET['rate'];
		$merReserve  = $_GET['merReserve'];
		$transID  = $_GET['transID'];
		$merID  = $_GET['merID'];
		$acqID  = $_GET['acqID'];
		$paymentSchema  = $_GET['paymentSchema'];
		$RespCode  = $_GET['RespCode'];
		$RespMsg  = $_GET['RespMsg'];
		$transTime  = $_GET['transTime'];
		$GWTime  = $_GET['GWTime'];
		$signType  = $_GET['signType'];
		$signature  = $_GET['signature'];

		$para = array(
			"version" => $version,
			"charSet" => $charSet,
			"transType" => $transType,
			"orderNum" => $orderNum,
			"orderAmount" => $orderAmount,
			"orderCurrency" => $orderCurrency,
			"settAmount" => $settAmount,
			"settCurrency"=> $settCurrency,
			"rate" => $rate,
			"merReserve" => $merReserve,
			"transID" => $transID,
			"merID" => $merID,
			"acqID" => $acqID,
			"paymentSchema" => $paymentSchema,
			"RespCode" => $RespCode,
			"RespMsg" => $RespMsg,
			"transTime" => $transTime,
			"GWTime" => $GWTime,
			"signType" => $signType
		);

		ksort($para);
		reset($para);
		$arg="";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		$key = $this->__initConfig['md5_key'];
		$prestr = substr($arg,0,count($arg)-2);

		$mysign = md5($prestr.$key);

		if($mysign==$signature){
			if($RespCode=="00"){
				$this->load->model('M_order','m_order');
				$act = $this->m_order->getPayAction($orderNum);
				$result['success'] = 1;
				$result['table'] = $act['table'];
				$result['order_id'] = $orderNum;
				if(isset($act['table']) && isset($act['payFunc'])){

					if($act['table'] === 'trade_orders'){
//                        $order = $this->db->from($act['table'])->where($act['field'],$orderNum)->get()->row_array();
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one($act['field'].",order_amount_usd",[$act['field']=>$orderNum]);
						$result['amount'] = $order['order_amount_usd'];
					}else{
                        $order = $this->db->from($act['table'])->where($act['field'],$orderNum)->get()->row_array();
                    }
					$this->m_log->ordersRollbackLog($order[$act['field']],$transID);
					return $result;
				}
			}else{
				$result['success'] = 0;
				$this->m_log->createOrdersLog($orderNum,'USD_UnionPay同步处理交易失败');
				//交易失败  逻辑处理
			}


		}else{
			$result['success'] = 0;
			$this->m_log->createOrdersLog($orderNum,'USD_UnionPay同步处理签证不正确');
			//签证前面不对 逻辑处理
		}
		return $result;
    }

    public function do_notify(){
		include_once APPPATH . 'third_party/hgutil.php';
		$version  = $_GET['version'];
		$charSet  = $_GET['charSet'];
		$transType  = $_GET['transType'];
		$orderNum  = $_GET['orderNum'];
		$orderAmount  = $_GET['orderAmount'];
		$orderCurrency  = $_GET['orderCurrency'];
		$settAmount  = $_GET['settAmount'];
		$settCurrency  = $_GET['settCurrency'];
		$rate  = $_GET['rate'];
		$merReserve  = $_GET['merReserve'];
		$transID  = $_GET['transID'];
		$merID  = $_GET['merID'];
		$acqID  = $_GET['acqID'];
		$paymentSchema  = $_GET['paymentSchema'];
		$RespCode  = $_GET['RespCode'];
		$RespMsg  = $_GET['RespMsg'];
		$transTime  = $_GET['transTime'];
		$GWTime  = $_GET['GWTime'];
		$signType  = $_GET['signType'];
		$signature  = $_GET['signature'];
                $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_GET);
		$para = array(
			"version" => $version,
			"charSet" => $charSet,
			"transType" => $transType,
			"orderNum" => $orderNum,
			"orderAmount" => $orderAmount,
			"orderCurrency" => $orderCurrency,
			"settAmount" => $settAmount,
			"settCurrency"=> $settCurrency,
			"rate" => $rate,
			"merReserve" => $merReserve,
			"transID" => $transID,
			"merID" => $merID,
			"acqID" => $acqID,
			"paymentSchema" => $paymentSchema,
			"RespCode" => $RespCode,
			"RespMsg" => $RespMsg,
			"transTime" => $transTime,
			"GWTime" => $GWTime,
			"signType" => $signType
		);

		ksort($para);
		reset($para);
		$arg="";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		$key = $this->__initConfig['md5_key'];
		$prestr = substr($arg,0,count($arg)-2);

		$mysign = md5($prestr.$key);

		if($mysign==$signature){
			if($RespCode=="00"){
				$this->load->model('M_order','m_order');
				$act = $this->m_order->getPayAction($orderNum);
				$result['success'] = 1;
				$result['table'] = $act['table'];
				$result['order_id'] = $orderNum;
				if(isset($act['table']) && isset($act['payFunc'])){

					if($act['table'] === 'trade_orders'){
//                        $order = $this->db->from($act['table'])->where($act['field'],$orderNum)->get()->row_array();
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one($act['field'].",order_amount_usd",[$act['field']=>$orderNum]);
						$result['amount'] = $order['order_amount_usd'];
					}else{
                        $order = $this->db->from($act['table'])->where($act['field'],$orderNum)->get()->row_array();
                    }
					$this->m_log->ordersRollbackLog($order[$act['field']],$transID);
					exit;
				}
			}else{
				$result['success'] = 0;
				$this->m_log->createOrdersLog($orderNum,'USD_UnionPay同步处理交易失败');
				//交易失败  逻辑处理
			}


		}else{
			$result['success'] = 0;
			$this->m_log->createOrdersLog($orderNum,'USD_UnionPay同步处理签证不正确');
			//签证前面不对 逻辑处理
		}
		exit;
    }

}
