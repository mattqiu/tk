<?php
/**
 *
  <p>//TEST<br>
    hxj123456<br>
    香港汤普森电子商务有限公司<br>
    hxj123456.pfx<br>
    123456<br>
    businessgate.cer<br>
    113.106.160.201:889</p>
   <p>//PRO<br>
    Uhan_sm3801<br>
    Tps<br>
    Uhan_sm3801.pfx<br>
    138716<br>
    businessgate.cer<br>
    pay.ysepay.com</p>
 */
class m_yspay extends CI_Model{

    private $__yspay_object;

    public function __construct(){
        parent::__construct();
        $this->__init_config();
    }

    private function __init_config(){
        $config  = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        include_once APPPATH.'third_party/yspay/ysepay_service.php';
        $this->__yspay_object = new ysepay_service($config);
    }

    public function get_code($order){

		/**
		 * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
		 */
		/*if($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY'){
			$amount = $order['order_amount'] / 100;
		}else{
			$amount  = $this->m_currency->currency_conversion($order['money'],'CNY');
		}*/
		$amount = $order['money']/100;

		/** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
		$order['paid_amount'] =  '$'.$amount;
		$this->m_log->createOrdersNotifyLog('1',__CLASS__,$order);
		$this->m_global->update_paid_amount($order);

        //获取参数
        $input=array(
            "BankType"=>"",
            "BankAccountType"=>"",
            "date"=>date('Ymd'),
            "orderid"=>$order['order_sn'],
            "busicode"=>'01000010',
            "amount"=>$amount*100,//單位是分
            "ordernote"=>lang('payment_note_'),
            "banktype"=>'',
            "bankaccounttype"=>'',
            'cur'=>$order['currency']
        );
        $return = $this->__yspay_object->S3001_ysepay($input);
        $url = $return['url'];
        if ($return['success'] == 1){

            $str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img"><span id="loading_span">';
            $str .= lang('jump').'</span></div></div> </div><div style="height:360px;">&nbsp;</div><div  style="display:none;"></div>';
            $str .=  "<script>window.location.href='$url';</script>";

        } else {
            $str = "error: ". $return['success'] .$return['url'] .$return['msg'];
        }
        return $str;
    }

    public function do_return(){
        if(isset($_REQUEST['Msg'])){
            $msg=$_REQUEST['Msg'];
            $trnx_id = explode('|',base64_decode($msg))[0];
            $this->load->model('M_order','m_order');
            $act = $this->m_order->getPayAction($trnx_id);
            $result['success'] = 1;
            $result['table'] = $act['table'];
            if($act['table'] === 'trade_orders'){
//                $order = $this->db->from($act['table'])->where($act['field'],$trnx_id)->get()->row_array();
                $this->load->model("tb_trade_orders");
                $order = $this->tb_trade_orders->get_one("order_amount_usd",[$act['field']=>$trnx_id]);
                $result['amount'] = $order['order_amount_usd'];
            }
			$result['order_id'] = $trnx_id;
            return $result;
        }
    }

    //确保公网能访问
    public function do_notify() {
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_REQUEST);
        $unsign=$this->__yspay_object->unsign_crypt(array("check"=>$_REQUEST['check'],"msg"=>$_REQUEST['msg']));
        preg_match("/\<OrderId\>(.*)\<\/OrderId\>/i",$unsign['data'],$orderid);
        file_put_contents("Response/R3501/".$orderid[1].".txt",$unsign['data']);
        preg_match("/\<Code\>(.*)\<\/Code\>/i",$unsign['data'],$con);
        preg_match("/\<TradeSN\>(.*)\<\/TradeSN\>/i",$unsign['data'],$trade);
        preg_match("/\<Amount\>(.*)\<\/Amount\>/i",$unsign['data'],$payment_amount);
        preg_match("/\<Cur\>(.*)\<\/Cur\>/i",$unsign['data'],$cur);

        if($con[1]==="0000"){

            $this->load->model('M_order','m_order');
            $act = $this->m_order->getPayAction($orderid[1]);

            header("Content-type: text/html; charset=GBK");

            //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//            $order = $this->db->from($act['table'])->where($act['field'],$orderid[1])->get()->row_array();
            if($act['table'] === 'trade_orders'){
                $this->load->model("tb_trade_orders");
                $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$orderid[1]]);
            }else{
                $order = $this->db->from($act['table'])->where($act['field'], $orderid[1])->get()->row_array();
            }
            //验证交易号是否已经处理过
//            $ducu_txn_id = $this->db->from($act['table'])->where('txn_id',$trade[1])->count_all_results();
            //验证交易号是否已经处理过
            if ($act['table'] === 'trade_orders') {
                $this->load->model("tb_trade_orders");
                $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade[1]]);
            }else{
                $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade[1])->count_all_results();
            }
            if($ducu_txn_id){
                echo base64_encode(iconv("UTF-8","GBK//IGNORE","0000|success"));
				exit;
            }

            if (!$order || $order['status'] > Order_enum::STATUS_CHECKOUT  && $order['status']!=100)
            {
				exit;
            }

			$this->m_log->ordersRollbackLog($order[$act['field']],$trade[1]);
            echo base64_encode(iconv("UTF-8","GBK//IGNORE","0000|success"));
            exit;
        }else{

            echo base64_encode(iconv("UTF-8","GBK//IGNORE","fail"));
            exit;
        }
    }

}
