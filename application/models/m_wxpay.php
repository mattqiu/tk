<?php
/**
 * 微信支付
 */
class m_wxpay extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    private function __init_config(){
       // $config  = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
    }

    public function get_code($order) {

		/**
		 * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
		 */
		if($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY'){
			$amount = $order['order_amount'] / 100;
		}else{
			$amount  = $this->m_currency->currency_conversion($order['money'],'CNY');
		}

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '￥'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

		require_once APPPATH."third_party/wxpay/lib/WxPay.Api.php";
		require_once APPPATH. "third_party/wxpay/func/WxPay.NativePay.php";

		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("TPS商城购物");
		$input->SetAttach("TPS");
		//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetOut_trade_no($order['order_sn']);
		$input->SetTotal_fee($amount*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 86400));
		$input->SetGoods_tag("TPS");
		$input->SetNotify_url(base_url('respond/wx_do_notify'));
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($order['order_sn']);
		$result = $notify->GetPayUrl($input);

		if($result['result_code'] == 'FAIL'){
			$data['msg'] = $result['err_code_des'];
		}else{
			$data['url'] = $result["code_url"];
		}
		$data['order_id'] = $order['order_sn'];
                /*********************************记录香港微信订单的参数**********************************/
                if($order['currency']=='HKD'){
                    $this->load->model('m_debug');
                    $this->m_debug->dd_log(array($order,$input,$result),'香港微信订单',$order['order_id']);
                }
                /*******************************************************************/
		return $this->load->view('mall/wxpay',$data,true);
	}

	public function do_return($orderId){

		$this->load->model('M_order','m_order');
		$act = $this->m_order->getPayAction($orderId);
		$result['success'] = 1;
		$result['table'] = $act['table'];
		if($act['table'] === 'trade_orders'){
//			$order = $this->db->from($act['table'])->where($act['field'],$orderId)->get()->row_array();
			$this->load->model("tb_trade_orders");
			$order = $this->tb_trade_orders->get_one("order_amount_usd,phone,txn_id",[$act['field']=>$orderId]);
			$result['amount'] = $order['order_amount_usd'];
		}
		$result['order_id'] = $orderId;
                $result['phone'] = $order['phone'];
                $result['txn_id'] = $order['txn_id'];
		return $result;

	}

	public function do_notify(){
		require_once APPPATH."third_party/wxpay/func/wx_notify.php";
		$this->load->model("m_log");
		$this->m_log->createOrdersNotifyLog('11', __CLASS__, $_REQUEST);
		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
	}

}
