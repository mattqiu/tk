<?php
/**
 *  手机端支付的业务处理类
 * @author john
 */
class o_payment extends CI_Model {

	private $__alipay_config = array();

    function __construct() {
        parent::__construct();

		$this->__alipay_config = array(
			'partner'=>'2088911308895646',
			'sign_type'=>strtoupper('RSA'),
			'input_charset'=>strtolower('utf-8'),
			'cacert'=>APPPATH.'third_party/alipay/cacert.pem',
			'transport'=>(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')?'https':'http',
		);

		$config  = $this->m_global->getPaymentConfig(strtolower('m_unionpay'));
		$config_array = array(
			'merId'=>$config['unionpay_merId'],//商户号
			'SDK_SIGN_CERT_PATH'=>$config['unionpay_pfxpath'],
			'SDK_SIGN_CERT_PWD'=>$config['unionpay_pfxpassword'],
			'SDK_ENCRYPT_CERT_PATH'=>$config['unionpay_certpath'],
			'SDK_FRONT_TRANS_URL'=>$config['unionpay_host'],
		);
		$this->config->set_item('unionpay',$config_array);
    }

	public function m_alipay_get_code($order){

		/**
		 * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
		 */
		if($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY'){
			$amount = $order['order_amount'] / 100;
		}else{
			$this->load->model('M_currency','m_currency');
			$amount  = $this->m_currency->currency_conversion($order['money'],'CNY');
		}

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '￥'.$amount;
		$this->m_log->createOrdersNotifyLog('1','m_alipay',$order);
		$this->m_global->update_paid_amount($order);

		include_once APPPATH . 'third_party/alipay/alipay_submit.class.php';

		$parameter = array(
			'service' => "mobile.securitypay.pay",
			'partner' => "2088911308895646",
			'_input_charset' => "utf-8",
			'notify_url' => base_url('respond/mobile_do_notify?code='. base64_encode(serialize('m_alipay'))),//回调地址
			'out_trade_no' => $order['order_sn'],//商户订单号
			"subject"    =>"TPS商城购物",//订单名称
			'payment_type' => 1,//支付类型
			'seller_id' => "2567583700@qq.com",//支付宝账号
			'total_fee' => $amount,//$amount,//必填,付款金额
			"body"    => "TPS商城购物",//必填,订单描述
			'it_b_pay' => "2d",
		);

		$alipaySubmit = new AlipaySubmit($this->__alipay_config);
		$request_string = $alipaySubmit->buildRequestParaToString($parameter);
		return $request_string;
	}

	//确保公网能访问
	public function m_alipay_do_notify() {
		if(empty($_POST)){
			return FALSE;
		}$this->m_log->createOrdersNotifyLog('11', __CLASS__, $_POST);
		unset($_POST['code']);

		include_once APPPATH . 'third_party/alipay/alipay_notify.class.php';
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($this->__alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result){

			$out_trade_no = $_POST['out_trade_no'];
			$trade_no = $_POST['trade_no'];
			$trade_status = $_POST['trade_status'];
			$seller_email = $_POST['seller_email'];
			$total_fee = $_POST['total_fee'];
			//$custom = $_POST['extra_common_param'];

			if($trade_status=='TRADE_FINISHED'||$trade_status=='TRADE_SUCCESS'){

				$this->load->model('M_order','m_order');
				$this->load->model('m_log');
				$act = $this->m_order->getPayAction($out_trade_no);

//				$ducu_txn_id = $this->db->from($act['table'])->where('txn_id',$trade_no)->count_all_results();
                //验证交易号是否已经处理过
                if ($act['table'] === 'trade_orders') {
                    $this->load->model("tb_trade_orders");
                    $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade_no]);
                }else{
                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                }
				if($ducu_txn_id){
					echo "success";
					exit;
				}

				//先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//				$order = $this->db->from($act['table'])->where($act['field'],$out_trade_no)->get()->row_array();
                if($act['table'] === 'trade_orders'){
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                }
				if (!$order || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100))
				{
					echo "success";
					exit;
				}

				$this->m_log->ordersRollbackLog($order[$act['field']],$trade_no);
				echo "success";
				exit;
			}
		}else {
			//验证失败
			echo "fail";
		}
	}

	public function m_unionpay_get_code($order){

		/**
		 * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
		 */
		if($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY'){
			$amount = $order['order_amount'] / 100;
		}else{
			$this->load->model('M_currency','m_currency');
			$amount  = $this->m_currency->currency_conversion($order['money'],'CNY');
		}

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '￥'.$amount;
		$this->m_log->createOrdersNotifyLog('1','m_unionpay',$order);
		$this->m_global->update_paid_amount($order);

		$unionpayConfig = config_item('mobile_unionpay_test');

		$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')?'https':'http';

		$params = array(
			'version' => '5.0.0',                 //版本号
			'encoding' => 'utf-8',				  //编码方式
			'txnType' => '01',				      //交易类型
			'txnSubType' => '01',				  //交易子类
			'bizType' => '000201',				  //业务类型
			'frontUrl' =>base_url('respond/do_return?code='.base64_encode(serialize('m_unionpay'))), //前台通知地址
			//'backUrl' => $http.'://www.'.get_public_domain().'/respond/mobile_do_notify?code='.base64_encode(serialize('m_unionpay')), //后台通知地址
			'backUrl' => base_url('respond/mobile_do_notify?code='.base64_encode(serialize('m_unionpay'))), //后台通知地址
			'signMethod' => '01',	              //签名方法
			'channelType' => '08',	              //渠道类型，07-PC，08-手机
			'accessType' => '0',		          //接入类型
			'currencyCode' => '156',	          //交易币种，境内商户固定156
			'merId' => $unionpayConfig['merId'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
			'orderId' => $order['order_sn'],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
			'txnTime' => date('YmdHis',time()),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
			'txnAmt' => $amount*100,//$amount*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
	// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		);

		require_once APPPATH . 'third_party/mobileUnionpay/acp_service.php';

		AcpService::sign ( $params ); // 签名

		$result_arr = AcpService::post ($params,$unionpayConfig['SDK_App_Request_Url']);
		if(count($result_arr)<=0) {
			return 1003;//没收到200应答的情况
		}
		if (!AcpService::validate ($result_arr) ){
			return 1004;//应答报文验签失败
		}
		if ($result_arr["respCode"] == "00"){
			return $result_arr['tn'];
		} else {
			return 1005;//respCode 失败
		}

	}

	public function m_unionpay_do_notify(){

		if (isset ( $_POST ['signature'] ) ) {

			require_once APPPATH . 'third_party/mobileUnionpay/acp_service.php';

			$orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
			$respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功

			$this->m_log->createOrdersLog($orderId,$respCode.'--Unverified');

			if(AcpService::validate( $_POST ) && $respCode=='00'){

				$this->m_log->createOrdersLog($orderId,$respCode.'--Verified');

				$txn_id = $_POST ['queryId'];

				$this->load->model('M_order','m_order');
				$act = $this->m_order->getPayAction($orderId);

				if(isset($act['table']) && isset($act['payFunc'])){

//					$order = $this->db->from($act['table'])->where($act['field'],$orderId)->get()->row_array();
                    if($act['table'] === 'trade_orders'){
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$orderId]);
                    }else{
                        $order = $this->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
                    }
					$this->m_log->ordersRollbackLog($order[$act['field']],$txn_id);
					exit;
				}

				//如果卡号我们业务配了会返回且配了需要加密的话，请按此方法解密
				//if(array_key_exists ("accNo", $_POST)){
				//	$accNo = decryptData($_POST["accNo"]);
				//}
			}else{
				$this->m_log->createOrdersLog($orderId,'验签失败');
			}
		} else {
			$this->m_log->createOrdersLog('106','签名为空');
		}
	}

	public function m_paypal_get_code($order){
		$this->load->model('m_paypal');
		return $this->m_paypal->get_code_mobile($order);
	}

	public function m_usd_unionpay_get_code($order){
		$this->load->model('m_usd_unionpay');
		return $this->m_usd_unionpay->get_code_mobile($order);
	}

	public function m_amount_get_code($order){
		//$order['order_id'] = $data['order_id']; //兼容 mall_general_order_paid函數的字段
		$this->load->model('m_order');
		$order_type = $this->m_order->get_order_type($order['order_id']);
		if($order_type){
			//產品套裝
			$payFunc = 'mall_order_paid';
		}else{
			//普通訂單
			$payFunc = 'mall_general_order_paid';
		}
		if($payFunc === 'mall_order_paid'){ //  兼容以前的功能
			$order['join_fee'] = $order_type['amount'];
			$order['level'] = $order_type['level'];
		}

		if($order['notify_num'] != 0 ){
			$this->load->model('tb_trade_orders');
			$this->tb_trade_orders->update_notify_count($order['order_id']);
		}

		$status = $this->m_order->$payFunc($order,'TPS'.$order['order_id'], Order_enum::STATUS_SHIPPING);
		if($status === TRUE){
			return 0;
		}else{
			return 1111;	//Amount pay failure';
		}
	}

}
