<?php
/**
    <p>TEST<br>
        373524997-facilitator@qq.com<br>
        https://www.sandbox.paypal.com/cgi-bin/webscr<br>
        www.sandbox.paypal.com</p>

<p>PRO<br>
        danielmmasia@yahoo.com<br>
        https://www.paypal.com/cgi-bin/webscr<br>
        www.paypal.com</p>

define('API_USERNAME', 'Danielmmasia_api1.yahoo.com');
 define('API_PASSWORD', 'YPRGQ2Y6A9TX9KJ5'); //pro
define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31Acn4zQprq1U5uIu-hnh4GWbsUpkH');//pro
 define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp'); //pro


define('API_USERNAME', '373524997-facilitator_api1.qq.com');
define('API_PASSWORD', 'LPFS2UEU5UM5UEK7'); //test
define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AAabJ0AWhsA5oHQhUz7aYJFJZ0mN');//test
define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');

 */
class m_paypal extends CI_Model
{
    //paypal接口配置信息
    public $__paypal_config;

    public function __construct(){
        parent::__construct();
        $this->__init_config();
    }

    private  function __init_config(){
        $config  = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        $this->__paypal_config['business'] = $config['paypal_account'] ;
        $this->__paypal_config['submit_url'] = $config['paypal_submit_url'];
        $this->__paypal_config['paypal_url'] = $config['paypal_host'];
        $this->__paypal_config['paypal_type_name'] = $config['paypal_type_name'];
		$this->__paypal_config['api_url'] = $config['api_url'];
		$this->__paypal_config['USER'] = $config['USER'];
		$this->__paypal_config['PWD'] = $config['PWD'];
		$this->__paypal_config['SIGNATURE'] =  $config['SIGNATURE'];
    }


	public function get_code_mobile($order){

		$amount = $order['money']/100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);
		$params['VERSION'] = '115';
		$params['METHOD'] = 'SetExpressCheckout';
		$params['NOSHIPPING'] = '1';
		$params['RETURNURL'] = base_url('respond/paypal_do_return?code='.base64_encode(serialize(__CLASS__)));
		$params['CANCELURL'] = base_url('respond/do_cancel?code='.base64_encode(serialize(__CLASS__)));
		$params['PAYMENTREQUEST_0_PAYMENTACTION'] = 'sale';
		$params['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
		$params['L_PAYMENTREQUEST_0_NAME0'] = 'TPS Shopping Mall';
		$params['L_PAYMENTREQUEST_0_QTY0'] = '1';
		$params['L_PAYMENTREQUEST_0_AMT0'] = $amount;
		$params['PAYMENTREQUEST_0_ITEMAMT'] = $amount;
		$params['PAYMENTREQUEST_0_AMT'] = $amount;
		$params['PAYMENTREQUEST_0_INVNUM'] = $order['order_sn'];

		$data = self::_curl_get_data($params);

		if(isset($data['ACK']) && $data['ACK']=='Success') {
			$payPalURL = 'https://'.$this->__paypal_config['paypal_url'].'/webscr&cmd=_express-checkout&useraction=commit&token='.$data['TOKEN'];
			return $payPalURL;
		} else {
			return 1006;
		}
	}


	public function get_code($order){
		$amount = $order['money']/100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

		if(strtoupper($this->__paypal_config['paypal_type_name']) === 'NVP'){

			// nvp 接口
			return $this->get_code_nvp($order,$amount);

		}else{
			// ipn 接口
			return $this->get_code_ipn($order,$amount);
		}

	}

    protected  function get_code_nvp($order,$amount){

		$params['VERSION'] = '115';
		$params['METHOD'] = 'SetExpressCheckout';
		$params['NOSHIPPING'] = '1';
		$params['RETURNURL'] = base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__)));
		$params['CANCELURL'] = base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__)));
		$params['PAYMENTREQUEST_0_PAYMENTACTION'] = 'sale';
		$params['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
//		$params['L_PAYMENTREQUEST_0_NAME0'] = 'TPS Shopping Mall';
//		$params['L_PAYMENTREQUEST_0_QTY0'] = '1';
//		$params['L_PAYMENTREQUEST_0_AMT0'] = $amount;
		$params['PAYMENTREQUEST_0_INVNUM'] = $order['order_sn'];
                //*******************************地址
                //国家code对应的国家ID
		$_country_code_loc = array_flip(array('CN'=>'156','KR'=>'410','HK'=>'344','US'=>'840'));
                $params['ADDROVERRIDE'] = 1;
                $params['LOCALCODE'] = $_country_code_loc[$order['country']];
                $params['PAYMENTREQUEST_0_SHIPTOSTREET'] = explode(' ', $order['address'])[3].' '.explode(' ', $order['address'])[4];
                $params['PAYMENTREQUEST_0_SHIPTOSTREET2'] = explode(' ', $order['address'])[3].' '.explode(' ', $order['address'])[4];
                $params['PAYMENTREQUEST_0_SHIPTOCITY'] = explode(' ', $order['address'])[2];
                $params['PAYMENTREQUEST_0_SHIPTOSTATE'] = explode(' ', $order['address'])[1];
                $params['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $_country_code_loc[$order['country']];
                $params['PAYMENTREQUEST_0_SHIPTOZIP'] = $order['address2']['zip_code'];
                //*******************************商品详情
                if($order['discount_type']=='2'){//2使用代品券'
                    $kk=1;
                    $params['L_PAYMENTREQUEST_0_NAME'.$kk] = 'Commodities vouchers';
                    $params['L_PAYMENTREQUEST_0_NUMBER'.$kk] = '95625646-X';//此编号随意编造的,目的是为了代品券虚拟商品编号看起来真实
                    $params['L_PAYMENTREQUEST_0_DESC'.$kk] = '';
                    $params['L_PAYMENTREQUEST_0_AMT'.$kk] = $order['order_amount']/100;
                    $params['L_PAYMENTREQUEST_0_QTY'.$kk] = 1;
                    $zj=$order['order_amount']/100;
                    $amount=$zj;
                }else{
                    $zj=0;
                    foreach ($order['goods_list'] as $k=> $value) {
                        $params['L_PAYMENTREQUEST_0_NAME'.$k] = $value['goods_name'];
                        $params['L_PAYMENTREQUEST_0_NUMBER'.$k] = $value['goods_sn'];
                        $params['L_PAYMENTREQUEST_0_DESC'.$k] = $value['goods_attr'];
                        $params['L_PAYMENTREQUEST_0_AMT'.$k] = $value['goods_price'];
                        $params['L_PAYMENTREQUEST_0_QTY'.$k] = $value['goods_number'];
                        $jg=$value['goods_price']*$value['goods_number'];
                        $zj+=$jg;$kk=$k;
                    }
                    if ($order['discount_type']=='1') {//1 获取代品券
                        $kk=$kk+1;
                        $params['L_PAYMENTREQUEST_0_NAME'.$kk] = 'Commodities vouchers';
                        $params['L_PAYMENTREQUEST_0_NUMBER'.$kk] = '95625646-X';//此编号随意编造的,目的是为了代品券虚拟商品编号看起来真实
                        $params['L_PAYMENTREQUEST_0_DESC'.$kk] = '';
                        $params['L_PAYMENTREQUEST_0_AMT'.$kk] = $order['discount_amount_usd']/100;
                        $params['L_PAYMENTREQUEST_0_QTY'.$kk] = 1;
                        $zj+=$order['discount_amount_usd']/100;
                    }
                }
                //******************************新增结束
                $params['PAYMENTREQUEST_0_ITEMAMT'] = $zj;//所有商品总金额（不包含运费）
                $params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $order['deliver_fee_usd']/100;//运费
		$params['PAYMENTREQUEST_0_AMT'] = $amount;//订单总金额
		$data = self::_curl_get_data($params);
		if(isset($data['ACK']) && $data['ACK']=='Success') {

			$payPalURL = 'https://'.$this->__paypal_config['paypal_url'].'/webscr&cmd=_express-checkout&useraction=commit&token='.$data['TOKEN'];
			$str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img">';
			$str .= lang('jump').'</span></div></div> </div><div style="height:260px;"></div>';
			$str  .= '<script>$(function(){ location.href="'.$payPalURL.'"});</script> ';

			return $str;

		} else {
			$error_info = isset($data['L_LONGMESSAGE0']) ? $data['L_ERRORCODE0'] .' '. $data['L_LONGMESSAGE0'] : '';
            $str = '<div class="paypal_loading"><div class="paypal_loading_div" >';
            $str .= "Connection PayPal failed :".$error_info.'</div> </div><div style="height:260px;"></div>';
            return $str;
		}

    }

	public function get_code_ipn($order,$amount){

		$def_url = '<form action="'.$this->__paypal_config['submit_url'].'" method="post" id="paypal_pay">';
		$def_url .= '<input type="hidden" name="item_name" value="TPS Shopping Mall">';
		//$def_url .= '<input type="hidden" name="custom" value="1">';
		$def_url .= '<input type="hidden" name="quantity" value="1">';
		$def_url .= '<input type="hidden" name="cmd" value="_xclick">';
		$def_url .= '<input type="hidden" name="business" value="'.$this->__paypal_config['business'].'">';
		//$def_url .= '<input type="hidden" name="cancel_return" value="'.base_url('ucenter/upgrade_level').'">';
		$def_url .= '<input type="hidden" name="amount" value="'.$amount.'">';
		$def_url .= '<input type="hidden" name="currency_code" value="USD">';
		$def_url .= '<input type="hidden" name="return" value="'.base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__))).'">';
		$def_url .= '<input type="hidden" name="notify_url" value="'.base_url('respond/do_notify?code='.base64_encode(serialize(__CLASS__))).'">';
		$def_url .= '<input type="hidden" name="no_note" value="">';
		$def_url .= '<input type="hidden" name="no_shipping" value="1">';
		$def_url .= '<input type="hidden" name="rm" value="2">';
		$def_url  .='<input type="hidden" name="invoice" value="'.$order['order_sn'].'">' ;
		//$def_url .= '<input type="submit" value="Add Cart"/>';
		$def_url .= '</form>';

		$str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img">';
		$str .= lang('jump').'</div></div> </div><div style="height:260px;">&nbsp;</div>';
		$str  .= $def_url.'<script>$(function(){document.forms["paypal_pay"].submit();});</script>';

		return $str;

	}

	protected  function  _curl_get_data($params){

		$params['USER'] = $this->__paypal_config['USER'];
		$params['PWD'] = $this->__paypal_config['PWD'];
		$params['SIGNATURE'] = $this->__paypal_config['SIGNATURE'];

		$options = array(
			CURLOPT_URL	=> $this->__paypal_config['api_url'],
			CURLOPT_VERBOSE => 1,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_SSLVERSION => 'CURL_SSLVERSION_TLSv1_2',
			CURLOPT_RETURNTRANSFER => 1
		);
		$options[CURLOPT_POST] = true;
		$options[CURLOPT_POSTFIELDS] = http_build_query($params);

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);

        if(!$result) return $result;

		$result = urldecode($result);
		$result = explode('&', $result);
		$data = array();
		foreach($result as $key=>$val) {
			$tmp2 = explode("=", $val);
			$data[$tmp2[0]] = $tmp2[1];
		}
		return $data;
	}

	public function  do_return(){

		if(strtoupper($this->__paypal_config['paypal_type_name']) === 'NVP'){

			// nvp 接口
			return $this->do_return_nvp();

		}else{
			// ipn 接口
			return $this->do_return_ipn();
		}
	}

    public function do_return_nvp(){

		if(!isset($_GET['PayerID'])) return false;


		$params['VERSION'] = '115';
		$params['METHOD'] = 'GetExpressCheckoutDetails';
		$params['TOKEN'] = $_GET['token'];

		$details = self::_curl_get_data($params);

		$this->m_log->createOrdersLog($details['INVNUM'],'PayPal(nvp)-GetExpressCheckoutDetails');

		if($details['ACK']=='Success' || $details['ACK']=='SuccessWithWarning') {

			/** 扣款之前，判定订单是否已经在交易队列里面 */
			$count = $this->db->from('logs_orders_rollback')->where('order_id',$details['INVNUM'])->count_all_results();
			if($count > 0){
				$order_id = $details['INVNUM'];
				$this->load->model('M_order','m_order');
				$act = $this->m_order->getPayAction($order_id);
				$result['success'] = 1;
				$result['table'] = $act['table'];
				if($act['table'] === 'trade_orders'){
//					$order = $this->db->from($act['table'])->where($act['field'],$order_id)->get()->row_array();
					$this->load->model("tb_trade_orders");
					$order = $this->tb_trade_orders->get_one("order_amount_usd",[$act['field']=>$order_id]);
					$result['amount'] = $order['order_amount_usd'];
				}
				$result['order_id'] = $order_id;
				return $result;
			}

			$params['VERSION'] = $details['VERSION'];
			$params['METHOD'] = 'DoExpressCheckoutPayment';
			$params['TOKEN'] = $details['TOKEN'];
			$params['PAYERID'] = $details['PAYERID'];
			$params['PAYMENTREQUEST_0_PAYMENTACTION'] = 'sale';
			$params['PAYMENTREQUEST_0_CURRENCYCODE'] = $details['PAYMENTREQUEST_0_CURRENCYCODE'];
                        /*************/
                        for ($x=0; $x<=1000; $x++) {
                            if(isset($details['L_NAME'.$x])&&isset($details['L_NUMBER'.$x])&&isset($details['L_QTY'.$x])&&isset($details['L_TAXAMT'.$x])&&isset($details['L_AMT'.$x])){
                                $params['L_PAYMENTREQUEST_0_NAME'.$x] = $details['L_NAME'.$x];
                                $params['L_PAYMENTREQUEST_0_NUMBER'.$x] = $details['L_NUMBER'.$x];
                                $params['L_PAYMENTREQUEST_0_QTY'.$x] = $details['L_QTY'.$x];
                                $params['L_PAYMENTREQUEST_0_TAXAMT'.$x] = $details['L_TAXAMT'.$x];
                                $params['L_PAYMENTREQUEST_0_AMT'.$x] = $details['L_AMT'.$x];
                            }  else {
                                break;
                            }
                          } 
                        /*************/
			$params['PAYMENTREQUEST_0_ITEMAMT'] = $details['PAYMENTREQUEST_0_ITEMAMT'];
			$params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $details['PAYMENTREQUEST_0_SHIPPINGAMT'];
			$params['PAYMENTREQUEST_0_AMT'] = $details['PAYMENTREQUEST_0_AMT'];
			$is_payment = self::_curl_get_data($params);

			if($is_payment['ACK']=='Success' || $is_payment['ACK']=='SuccessWithWarning') {

				//$params['VERSION'] = '115';
				//$params['METHOD'] = 'GetTransactionDetails';
				//$params['TRANSACTIONID'] = $is_payment['PAYMENTINFO_0_TRANSACTIONID'];
				//$is_success = self::_curl_get_data($params);

				$this->m_log->createOrdersLog($details['INVNUM'],'PayPal(nvp)-DoExpressCheckoutPayment');

				$txn_id = $is_payment['PAYMENTINFO_0_TRANSACTIONID'];
				$order_id = $details['INVNUM'];
				$this->m_log->ordersRollbackLog($order_id,$txn_id);

				$this->load->model('M_order','m_order');
				$act = $this->m_order->getPayAction($order_id);
				$result['success'] = 1;
				$result['table'] = $act['table'];
				if($act['table'] === 'trade_orders'){
//					$order = $this->db->from($act['table'])->where($act['field'],$order_id)->get()->row_array();
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("order_amount_usd",[$act['field']=>$order_id]);
					$result['amount'] = $order['order_amount_usd'];
				}
				$result['order_id'] = $order_id;
				return $result;

			}
		}
    }

	public function do_return_ipn(){

		if(empty($_POST)){
			return false;
		}

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header ="POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Host: ".$this->__paypal_config['paypal_url']."\r\n";
		$header.="Content-Type:application/x-www-form-urlencoded\r\n";
		$header.="Content-Length:".strlen($req)."\r\n\r\n";

		$fp = fsockopen ("ssl://".$this->__paypal_config['paypal_url'], 443, $errno, $errstr, 30); // 沙盒用


		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$order_sn = $_POST['invoice'];
		$this->m_log->createOrdersLog($order_sn,$payment_status.'--Unverified');

		if (!$fp)
		{
			$this->m_log->createOrdersLog($order_sn,$payment_status.'--!$fp');
			fclose($fp);
			return false;
		}
		else
		{
			fputs($fp, $header . $req);
			while (!feof($fp))
			{
				$res = fgets($fp, 1024);

				if (strcmp($res, 'VERIFIED') == 0)
				{
					$this->load->model('M_order','m_order');
					$act = $this->m_order->getPayAction($order_sn);

					$result['success'] = 0 ;
					$result['table'] = $act['table'];
					$result['order_id'] = $order_sn;

					$this->m_log->createOrdersLog($order_sn,$payment_status.'--Verified');

					if ($payment_status != 'Completed' && $payment_status != 'Pending')
					{
						fclose($fp);
						$result['msg'] = lang('no_order');
						return $result;
					}

					//先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//					$order = $this->db->from($act['table'])->where($act['field'],$order_sn)->get()->row_array();
                    if($act['table'] === 'trade_orders'){
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$order_sn]);
                    }else{
                        $order = $this->db->from($act['table'])->where($act['field'], $order_sn)->get()->row_array();
                    }
					if($act['table'] === 'trade_orders'){
						$result['amount'] = $order['order_amount_usd'];
                        //验证交易号是否已经处理过
//                        $ducu_txn_id = $this->db->from($act['table'])->where('txn_id',$txn_id)->count_all_results();
                        $this->load->model("tb_trade_orders");
                        $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$txn_id]);
					}else{
                        //验证交易号是否已经处理过
                        $ducu_txn_id = $this->db->from($act['table'])->where('txn_id',$txn_id)->count_all_results();
                    }
					if($ducu_txn_id){
						$result['success'] = 1;
						fclose($fp);
						return false;
					}
					if($order['txn_id'] /*|| ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status']!=100)*/){
						$result['success'] = 1;
						fclose($fp);
						return $result;
					}
					if (strtolower($receiver_email) !== strtolower($this->__paypal_config['business']))
					{
						$this->m_log->createOrdersLog($order_sn,'收款賬戶不一致');
						fclose($fp);
						$result['msg'] = lang('no_email');
						return $result;
					}

					if ('USD' != $payment_currency)
					{
						$this->m_log->createOrdersLog($order_sn,'currency不一致');
						fclose($fp);
						$result['msg'] = lang('no_currency');
						return $result;
					}

					$this->m_log->ordersRollbackLog($order[$act['field']],$txn_id);
					$result['success'] = 1;
					fclose($fp);
					return $result;
				}
				elseif (strcmp($res, 'INVALID') == 0)
				{
					$this->m_log->createOrdersLog($order_sn,$payment_status.'--INVALID');
					fclose($fp);
					return false;
				}
			}
		}
	}

    public function do_notify(){

        if(empty($_POST)){
            return false;
        }
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_POST);
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header ="POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Host: ".$this->__paypal_config['paypal_url']."\r\n";
        $header.="Content-Type:application/x-www-form-urlencoded\r\n";
        $header.="Content-Length:".strlen($req)."\r\n\r\n";

        $fp = fsockopen ("ssl://".$this->__paypal_config['paypal_url'], 443, $errno, $errstr, 30); // 沙盒用

        //$item_name = $_POST['item_name'];
        //$item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        //$custom = $_POST['custom'];
        $order_sn = $_POST['invoice'];
        //$memo = empty($_POST['memo']) ? '' : $_POST['memo'];
		$this->m_log->createOrdersLog($order_sn,$payment_status.'--Unverified');

        if (!$fp)
        {
			$this->m_log->createOrdersLog($order_sn,$payment_status.'--!$fp');
            fclose($fp);
            return false;
        }
        else
        {
            fputs($fp, $header . $req);
            while (!feof($fp))
            {
                $res = fgets($fp, 1024);

                if (strcmp($res, 'VERIFIED') == 0)
                {

					/** 如果訂單狀態是Reversed,Refunded 記錄到 mall_orders_paypal_refund */
					if($payment_status == 'Reversed' || $payment_status == 'Refunded'){
						$parent_txn_id = $_POST['parent_txn_id'];
						$reason_code = $_POST['reason_code'];
						$name = $_POST['first_name'].$_POST['last_name'];
						$payer_email = $_POST['payer_email'];
						$payment_date = $_POST['payment_date'];
						$payment_type = $payment_status == 'Reversed' ? 'Reversal' : 'Refund';
						$this->load->model('m_admin_user');
						$this->m_admin_user->insertPayPalRefund($order_sn,$payer_email,$parent_txn_id,$payment_amount,$reason_code,$payment_date,$name,$payment_type);
						exit;
					}

                    $this->load->model('M_order','m_order');
                    $act = $this->m_order->getPayAction($order_sn);

					$this->m_log->createOrdersLog($order_sn,$payment_status.'--Verified');

                    if ($payment_status != 'Completed' && $payment_status != 'Pending')
                    {
                        fclose($fp);
                        exit;
                    }
                    //验证交易号是否已经处理过
//                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id',$txn_id)->count_all_results();
                    if ($act['table'] === 'trade_orders') {
                        $this->load->model("tb_trade_orders");
                        $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$txn_id]);
                    }else{
                        $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $txn_id)->count_all_results();
                    }
                    if($ducu_txn_id){
                        fclose($fp);
						exit;
                    }
                    if (strtolower($receiver_email) !== strtolower($this->__paypal_config['business']))
                    {
                        $this->m_log->createOrdersLog($order_sn,'收款賬戶不一致');
                        fclose($fp);
						exit;
                    }
                    //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//                    $order = $this->db->from($act['table'])->where($act['field'],$order_sn)->get()->row_array();
                    if($act['table'] === 'trade_orders'){
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$order_sn]);
                    }else{
                        $order = $this->db->from($act['table'])->where($act['field'], $order_sn)->get()->row_array();
                    }
                    if (!$order /*|| ($order['status'] > 2 && $order['status'] != 100)*/)
                    {
                        fclose($fp);
						exit;
                    }
                    if ('USD' != $payment_currency)
                    {
                        $this->m_log->createOrdersLog($order_sn,'currency不一致');
                        fclose($fp);
						exit;
                    }

					$this->m_log->ordersRollbackLog($order[$act['field']],$txn_id);
                    fclose($fp);
					exit;
                }
                elseif (strcmp($res, 'INVALID') == 0)
                {
					$this->m_log->createOrdersLog($order_sn,$payment_status.'--INVALID');
                    fclose($fp);
                    return false;
                }
            }
        }
    }
    //大宗付款提交
    public function submit_masspay($order) {
        $row =  $this->db->select('card_number,actual_amount,id,uid')->where('batch_num',$order)->where('status','2')->get('cash_take_out_logs')->result_array();
        if(!count($row)){
            return FALSE;
        }
        $params['VERSION'] = '115';
        $params['METHOD'] = 'MassPay';
        $params['RECEIVERTYPE'] = 'EmailAddress';
        $params['CURRENCYCODE'] = 'USD';
        foreach ($row as $k => $value) {
            $params['L_EMAIL' . $k] = $value['card_number'];
            $params['L_AMT' . $k] = $value['actual_amount'];
            $params['L_UNIQUEID' . $k] = $value['id'];
            $params['L_NOTE' . $k] = $value['uid'];
        }
        /*****************记录*********************/
        $adminUserInfoSeri = filter_input(INPUT_COOKIE, 'adminUserInfo');
        $cookieAdminUnSeri = unserialize($adminUserInfoSeri);
        $this->load->model('m_debug');
        $this->m_debug->dd_log($cookieAdminUnSeri,'大宗付款:'.$order);
        /**************************************/
        $data = self::_curl_get_data($params);
        $this->m_debug->dd_log($data,'大宗付款2:'.$order);
        if(isset($data['ACK']) && $data['ACK']=='Success') {
            $this->db->where('id', $order)->update('cash_paypal_take_out_batch_tb', array('status' => 2,'process_time'=>date("Y-m-d H:i:s",time())));
                return TRUE;
        } else {
                return FALSE;
        }
    }

    //大宗付款异步
    /*
     *   Completed:支付已经处理,不管���最初是一个单方面的付款
     *   Failed:支付失败,因为贝宝余额不足。
     *   Returned:当一个无人认领的付款仍然无人认领的超过30天,它返回给发送者。
     *   Reversed:贝宝已经扭转了事务。
     *   Unclaimed:这是无人认领的单方面支付。
     *   Pending:付款是等待,因为它是在进行符合政府法规。评审将完成并支付状态将在72小时内更新。
     *   Blocked:这个付款被由于违反政府法规。
     */
    public function get_masspay() {
//        $this->load->model('m_debug');
        if (empty($_POST)) {
            return false;
        }
        $this->m_log->createCronLog($_POST);
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Host: " . $this->__paypal_config['paypal_url'] . "\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n";
        $header .= "Connection: close\r\n\r\n";

        $fp = fsockopen("ssl://" . $this->__paypal_config['paypal_url'], 443, $errno, $errstr, 30); // 沙盒用
        if (!$fp) {
            fclose($fp);
            return false;
        } else {
            fputs($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets($fp, 1024);
                if (strcmp(trim($res), 'VERIFIED') == 0) {//成功
                    /****************记录普通订单退款记录******************/
                    if(($_POST['payment_status']=='Refunded'||$_POST['payment_status']=='Partially-Refunded')&&isset($_POST['invoice'])&&isset($_POST['txn_id'])){
                        $this->db->insert('paypal_pending_log',array('order_id'=>$_POST['invoice'],'txn_id'=>$_POST['txn_id']));
                        fclose($fp);
                        exit;
                    }
                    //***************************************************处理大宗付款结果
                    //重组POST数据为二维数组
                    foreach ($_POST as $y => $lue) {
                        if(is_int((int)trim(strrchr($y, '_'),'_'))&&(int)trim(strrchr($y, '_'),'_')){
                            $arra['content'][trim(strrchr($y, '_'),'_')][rtrim($y,strrchr($y, '_'))]=$lue;
                        }  else {
                            $arra[$y]=$lue;
                        }
                    }
                    $paypal_error_code = config_item('paypal_error_code');
                    $failure = $success = 0;
                    foreach ($arra['content'] as $k=> $value) {
                        $wh[$k]['id']=$value['unique_id'];
                        $del[]=$value['unique_id'];
                        $insert[$k]['id']=$value['unique_id'];
                        $insert[$k]['trade_no']=$value['masspay_txn_id'];
                        if($value['status']=='Completed'){
                            $wh[$k]['status']=1;
                            $success ++;
                        }elseif ($value['status']=='Failed'||$value['status']=='Blocked') {
                            $wh[$k]['status']=3;
                            $failure ++;
                        }  else {
                            $wh[$k]['status']=2;
                        }
                        if(isset($value['reason_code'])){$wh[$k]['check_info']=lang($paypal_error_code[$value['reason_code']]);}
                    }
                    $goods = $this->db->select("batch_num")->from('cash_take_out_logs')->where('id',end($wh)['id'])->get()->row_array();
                    $this->db->trans_begin();
                    $this->db->where('id', $goods["batch_num"])->update('cash_paypal_take_out_batch_tb', array('status' => 3,'process_time'=>date("Y-m-d H:i:s",time()),'success'=>$success,'failure'=>$failure));
                    $this->db->update_batch('cash_take_out_logs', $wh, 'id');
                    $this->db->where_in('id',$del)->delete('mass_pay_trade_no');
                    $this->db->insert_batch('mass_pay_trade_no', $insert);
                    if ($this->db->trans_status() === FALSE)
                    {
                        $this->db->trans_rollback();
                        return false;
                    }
                    else
                    {
                        $this->db->trans_commit();
                        return TRUE;
                    }
                    //***************************************************
                    fclose($fp);
                    exit;
                } elseif (strcmp(trim($res), 'INVALID') == 0) {//失败
//                    $this->m_debug->dd_log($_POST,'驗簽失敗');
                    fclose($fp);
                    return false;
                }
            }
        }
    }
}
