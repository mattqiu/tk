<?php

/**
    <p>TEST<br>
    45913748-82cf-42a4-b845-ec87efc5b2a9<br>
    CXwidtL8Vr<br>
    https://testewallet.com/eWalletWS/ws_Adapter.aspx<br>
    https://tps.testewallet.com/MemberLogin.aspx</p>
    <p>PRO<br>
    45913748-82cf-42a4-b845-ec87efc5b2a9<br>
    tnqeBQeeHW<br>
    https://www.i-payout.net/eWalletWS/ws_Adapter.aspx<br>
    https://tps.globalewallet.com/MemberLogin.aspx</p>
 */
class m_ewallet extends CI_Model {

    private $__MerchantGUID ;
	private $__MerchantPassword ;
	private $__eWalletAPIURL ;
	private $__eWalletLOGIN ;

    function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    private  function __init_config(){
        $config  = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        $this->__MerchantGUID = $config['ewallet_key'];
        $this->__MerchantPassword = $config['ewallet_password'];
        $this->__eWalletAPIURL = $config['ewallet_host'];
        $this->__eWalletLOGIN = $config['ewallet_login'];
    }

    public function do_notify(){

        $act = $_POST['act'];						// notification type
        $status_id = $_POST['status_id'];			// transaction status id
        $status_desc = $_POST['status_desc'];		// transaction status description
        $trnx_id = $_POST['trnx_id'];				// merchant reference id (same MerchantReferenceID as it is in eWallet_AddCheckoutItems)
        $log_id = $_POST['log_id'];					// eWallet transaction id
        $hash = $_POST['hash'];						// security hash
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_POST);
        // check a security hash if this notification is valid
        $myHash = strtoupper(sha1($trnx_id . $log_id . $this->__MerchantGUID . $this->__MerchantPassword));
        if($hash == $myHash)
        {
            if($status_id !== 1 && $status_desc !== 'Settled'){ //交易是否成功
                echo 'OK';
                exit;
            }
            $this->load->model('M_order','m_order');
            $my_act = $this->m_order->getPayAction($trnx_id);

            if($act != 'PaymentToMerchant'){
				exit;
            }
            //验证交易号是否已经处理过
//            $ducu_txn_id = $this->db->from($my_act['table'])->where('txn_id',$log_id)->count_all_results();
            //验证交易号是否已经处理过
            if ($my_act['table'] === 'trade_orders') {
                $this->load->model("tb_trade_orders");
                $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$log_id]);
            }else{
                $ducu_txn_id = $this->db->from($my_act['table'])->where('txn_id', $log_id)->count_all_results();
            }
            if($ducu_txn_id){
                echo 'OK';
				exit;
            }
            //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//            $order = $this->db->from($my_act['table'])->where($my_act['field'],$trnx_id)->get()->row_array();
            if($my_act['table'] === 'trade_orders'){
                $this->load->model("tb_trade_orders");
                $order = $this->tb_trade_orders->get_one("*",[$my_act['field']=>$trnx_id]);
            }else{
                $order = $this->db->from($my_act['table'])->where($my_act['field'], $trnx_id)->get()->row_array();
            }
            if($order['txn_id'] && $order['status'] > Order_enum::STATUS_CHECKOUT){
                echo 'OK';
				exit;
            }

			$this->m_log->ordersRollbackLog($order[$act['field']],$log_id);
            echo 'OK';
			exit;
        }
        else
        {
            echo 'invalid notification';
			exit;
        }
    }

    public function do_return(){
		if(isset($_POST['trnx_id'])){
			$orderId = $_POST['trnx_id'];
			$this->load->model('M_order','m_order');
			$this->load->model("tb_trade_orders");
			$act = $this->m_order->getPayAction($orderId);
			$result['success'] = 1;
			$result['table'] = $act['table'];
			if($act['table'] === 'trade_orders'){
//				$order = $this->db->from($act['table'])->where($act['field'],$orderId)->get()->row_array();
				$order = $this->tb_trade_orders->get_one("*",[$act['field']=>$orderId]);
				$result['order_info'] = $order;
				$result['amount'] = $order['order_amount_usd'];
			}
			$result['order_id'] = $orderId;
			return $result;
		}
    }

    /** 注册 eWallet tps用户 */
    public function register_api($user){

        $country = array(1=>'CN',2=>'US',3=>'HK',4=>'KP',5=>'SG',6=>'PH',7=>'MY',8=>'TW',9=>'MX',10=>'CA',11=>'VN',12=>'US');
        // prepare parameters for eWallet HTTP adapter
        $params = array(
            'fn'    			=> 'eWallet_RegisterUser',
            'MerchantGUID'		=> $this->__MerchantGUID,
            'MerchantPassword'	=> $this->__MerchantPassword,
            'UserName'			=> $user['ewallet_name'],
            'FirstName'			=> $user['name'],
            'LastName'			=> '--',
            'CompanyName'		=> '',
            'Address1'			=> $user['address'],
            'Address2'			=> '',
            'City'				=> '',
            'State'				=> '',
            'ZipCode'			=> '',
            'Country2xFormat'	=> isset($country['country_id']) ? $country['country_id'] : 'US',
            'PhoneNumber'		=> '',
            'CellPhoneNumber'	=> $user['mobile'],
            'EmailAddress'		=> $user['email'],
            'SSN'				=> '123-45-6789',
            'CompanyTaxID'		=> '',
            'GovernmentID'		=> '',
            'MilitaryID'		=> '',
            'PassportNumber'	=> '',
            'DriversLicense'	=> '',
            'DateOfBirth'		=> '9/18/1945',
            'WebsitePassword'	=> rangePassword(),
            'DefaultCurrency'	=> 'USD'
        );

        return $this->curl_post($params);
    }

    public function get_code($order){

        $amount  = $order['money'] / 100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

        // prepare parameters for eWallet HTTP adapter
        $arrItems_to_checkout = array(
            array(
                // regular payment
                'Amount'				=> $amount,
                'CurrencyCode'			=> 'USD',
                'ItemDescription'		=> 'Buy Mall',
                'MerchantReferenceID'	=> $order['order_sn'],
                'UserReturnURL'			=> base_url('respond/do_return?code='.base64_encode(serialize(__CLASS__))), // only usefull for instant payments like from eWallet or credit card
                'MustComplete'			=> 'false'
            )
        );

        $arrItems = '';

        // convert arrays into a proper formatted string
        foreach($arrItems_to_checkout as $value)
        {
            $arrItems .= '[' . http_build_query($value) . ']';
        }

        $params = array(
            'fn'    			=> 'eWallet_AddCheckoutItems',
            'MerchantGUID'		=> $this->__MerchantGUID,
            'MerchantPassword'	=> $this->__MerchantPassword,
            'UserName'			=> $order['ewallet_name'],
            'arrItems'			=> $arrItems,
            'CurrencyCode'		=> 'USD',
            'ret_params'        => 'month_fee'
        );

        $result =  $this->curl_post($params);
        if($result['success'] == 1){
            $str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img"><span id="loading_span">';
            $str .= lang('jump').'</span></div></div> </div><div style="height:360px;">&nbsp;</div><div  style="display:none;"></div>';
            $str .=  "<script>window.location.href='$this->__eWalletLOGIN';</script>";
        }else{
            $str = "error: ". $result['msg'];
        }
        return $str;
    }
    public function curl_post($params){

        // make a POST request to eWallet HTTP adapter
        $ch = curl_init($this->__eWalletAPIURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ips_response = curl_exec($ch);
        curl_close($ch);

        $response =''; $m_Code =''; $m_Text = '';
        // parse return
        parse_str($ips_response);
        parse_str($response);

        if($m_Code == 'NO_ERROR'){ // SUCCESS
            $result['success'] =  1;
        } else {// FAILED
            $result['success'] =  0;
            $result['msg'] =  $m_Text;
        }
        return $result;
    }

    function do_load(){
        // prepare parameters for eWallet HTTP adapter
        $accounts_to_load = array(
            /*array(
                'UserName'	=> '373524997@qq.com',
                'Amount'	=> '1000.00',
                'Comments'	=> 'commissions for pay period 1',
                'MerchantReferenceID' => 'REFID0001'
            ),*/
            array(
                'UserName'	=> 'john.he4@ecosko.com',
                'Amount'	=> '2000',
                'Comments'	=> 'tps test by john.he',
                'MerchantReferenceID' => 'MyDatabaseTransactionRef_100111'
            )
        );

        $arrAccounts = '';

        // convert arrays into a proper formatted string
        foreach($accounts_to_load as $value)
        {
            $arrAccounts .= '[' . http_build_query($value) . ']';
        }

        //echo $arrAccounts;

        $params = array(
            'fn'    			=> 'eWallet_Load',
            'MerchantGUID'		=> $this->__MerchantGUID,
            'MerchantPassword'	=> $this->__MerchantPassword,
            'PartnerBatchID'	=> 'my_batch_id_13122',
            'PoolID'			=> '',
            'arrAccounts'		=> $arrAccounts,
            'CurrencyCode'		=> 'USD'
        );
        return $this->curl_post($params);

    }
}
