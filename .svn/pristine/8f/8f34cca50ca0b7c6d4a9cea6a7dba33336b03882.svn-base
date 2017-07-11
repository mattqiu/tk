<?php

/**
  <p>//PRO<br>
  'merId'=>'824440357320037',//商户号<br>
  'SDK_SIGN_CERT_PATH'=>'PRO_700000000000001_acp.pfx',<br>
  'SDK_SIGN_CERT_PWD'=>'138138',<br>
  'SDK_ENCRYPT_CERT_PATH'=>'pro_public.cer',<br>
  'SDK_FRONT_TRANS_URL'=>'https://gateway.95516.com/gateway/api/frontTransReq.do',</p>
  <p> //TEST<br>
  'merId'=>'777290058113353',//商户号<br>
  'SDK_SIGN_CERT_PATH'=>'PM_700000000000001_acp.pfx',<br>
  'SDK_SIGN_CERT_PWD'=>'000000',<br>
  'SDK_ENCRYPT_CERT_PATH'=>'pro_public.cer',<br>
  'SDK_FRONT_TRANS_URL'=>'https://101.231.204.80:5000/gateway/api/frontTransReq.do',</p>
 */
class m_unionpay extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    private function __init_config() {
        $config = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        $config_array = array(
            'merId' => $config['unionpay_merId'], //商户号
            'SDK_SIGN_CERT_PATH' => $config['unionpay_pfxpath'],
            'SDK_SIGN_CERT_PWD' => $config['unionpay_pfxpassword'],
            'SDK_ENCRYPT_CERT_PATH' => $config['unionpay_certpath'],
            'SDK_FRONT_TRANS_URL' => $config['unionpay_host'],
        );
        $this->config->set_item('unionpay', $config_array);
    }

    //银联付款
    public function get_code($order) {

        include_once APPPATH . 'third_party/unionpay/common.php';
        include_once APPPATH . 'third_party/unionpay/SDKConfig.php';
        include_once APPPATH . 'third_party/unionpay/secureUtil.php';
        include_once APPPATH . 'third_party/unionpay/log.class.php';

        //$unionpayConfig = get_unionpay_config();
        $unionpayConfig = config_item('unionpay');

        /**
         * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
         */
        if ($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY') {
            $amount = $order['order_amount'] / 100;
        } else {
            $amount = $this->m_currency->currency_conversion($order['money'], 'CNY');
        }

        /** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
        $order['paid_amount'] = '￥' . $amount;
        $this->m_log->createOrdersNotifyLog('1', __CLASS__, $order);
        $this->m_global->update_paid_amount($order);

        // 初始化日志
        $params = array(
            'version' => '5.0.0', //版本号
            'encoding' => 'utf-8', //编码方式
            'certId' => getSignCertId(), //证书ID
            'txnType' => '01', //交易类型
            'txnSubType' => '01', //交易子类
            'bizType' => '000201', //业务类型
            'frontUrl' => base_url('respond/do_return?code=' . base64_encode(serialize(__CLASS__))), //前台通知地址
            //'backUrl' => base_url('respond/do_notify?code='.base64_encode(serialize(__CLASS__))), //后台通知地址
            'backUrl' => 'https://mall.' . get_public_domain() . '/respond/do_notify?code=' . base64_encode(serialize(__CLASS__)), //后台通知地址
            'signMethod' => '01', //签名方法
            'channelType' => '07', //渠道类型，07-PC，08-手机
            'accessType' => '0', //接入类型
            'merId' => $unionpayConfig['merId'], //商户代码，请改自己的测试商户号
            'orderId' => $order['order_sn'], //商户订单号
            'txnTime' => date('YmdHis', time()), //订单发送时间
            'txnAmt' => $amount * 100, //交易金额，单位分
            'currencyCode' => '156', //交易币种
            'defaultPayType' => '0001', //默认支付方式
                //'orderDesc' => '订单描述',  //订单描述，网关支付和wap支付暂时不起作用
                // 'reqReserved' => $type, //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );

        // 签名
        sign($params);

        // 前台请求地址
        $front_uri = $unionpayConfig['SDK_FRONT_TRANS_URL'];
        $html_form = create_html($params, $front_uri);

        $str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img">';
        $str .= lang('jump') . '</div></div> </div><div style="height:360px;"></div>';
        $str .= $html_form;

        return $str;
    }

    //银联同步
    public function do_return() {
        if (isset($_POST['orderId'])) {
            $orderId = $_POST['orderId'];
            $this->load->model('M_order', 'm_order');
            $act = $this->m_order->getPayAction($orderId);
            $result['success'] = 1;
            $result['table'] = $act['table'];
            if ($act['table'] === 'trade_orders') {
//                $order = $this->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
                $this->load->model("tb_trade_orders");
                $order = $this->tb_trade_orders->get_one("order_amount_usd",[$act['field']=>$orderId]);
                $result['amount'] = $order['order_amount_usd'];
            }
            $result['order_id'] = $orderId;
            return $result;
        }
    }

    //银联异步
    public function do_notify() {
        include_once APPPATH . 'third_party/unionpay/common.php';
        include_once APPPATH . 'third_party/unionpay/secureUtil.php';
        $data = $this->input->post();
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $data);
        if (isset($data['signature']) && verify($data) && $data['respCode'] == '00') {
            $orderId = $data['orderId'];
            $txn_id = $data['queryId'];

            $this->load->model('M_order', 'm_order');
            $act = $this->m_order->getPayAction($orderId);

            if (isset($act['table']) && isset($act['payFunc'])) {

//                $order = $this->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
                if($act['table'] === 'trade_orders'){
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$orderId]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
                }
                $this->m_log->ordersRollbackLog($order[$act['field']], $txn_id);
                exit;
            }
        }
    }

    //银联查询单笔订单状态（接口，不含业务逻辑）
    public function code_order_query($order) {
        include_once APPPATH . 'third_party/unionpay/sdk/acp_service.php';
        include_once APPPATH . 'third_party/unionpay/secureUtil.php';
        $unionpayConfig = config_item('unionpay');
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => '5.0.0', //版本号
            'encoding' => 'utf-8', //编码方式
            'signMethod' => '01', //签名方法
            'txnType' => '00', //交易类型
            'txnSubType' => '00', //交易子类
            'bizType' => '000000', //业务类型
            'accessType' => '0', //接入类型
            'channelType' => '07', //渠道类型
            //TODO 以下信息需要填写
            'orderId' => $order["orderId"], //请修改被查询的交易的订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数
            'merId' => $unionpayConfig["merId"], //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'txnTime' => $order["txnTime"], //请修改被查询的交易的订单发送时间，格式为YYYYMMDDhhmmss，此处默认取demo演示页面传递的参数
        );

        com\unionpay\acp\sdk\AcpService::sign($params); // 签名
        $url = com\unionpay\acp\sdk\SDK_SINGLE_QUERY_URL;

        $result_arr = com\unionpay\acp\sdk\AcpService::post($params, $url);
        if (count($result_arr) <= 0) { //没收到200应答的情况
            return FALSE;
        }
        if (verify($result_arr)) {//验签之后，返回数据
            return $result_arr;
        }
    }

    /*
     * 定时查询银联单笔未完成交易，成功则改为待发货
     */

    public function unionpay_active_query() {
        exit("function exit:".__FILE__.",".__LINE__."<BR>");
//        $time = date('Y-m-d H:i:s', time() - 300);
//        $sql = "SELECT `a`.`order_id`,`a`.`created_at`,`a`.`attach_id`,`a`.`order_type`,`a`.`created_at`,`b`.`num`
//FROM (`trade_orders` AS a)
//LEFT JOIN `order_query_num` AS b ON `a`.`order_id` = `b`.`order_id`
//WHERE `a`.`created_at` <='$time'  AND `a`.`payment_type` = '106'
//AND (`b`.`num` <= 3 OR `b`.`num` IS NULL) AND `a`.`status` = '2' LIMIT 1000";
//        $order_list = $this->db->query($sql)->result_array();
//        foreach ($order_list as $value) {
//            $order_id['orderId'] = $value['order_id'];
//            $order_id['txnTime'] = date('YmdHis', strtotime($value['created_at']));
//            //*******************************判断类型
//            if ($value['order_type'] === '2' && substr($value['order_id'], 0, 1) === 'C') {//升级订单，不处理C字开头的子订单
//                $order_id['orderId'] = $value['attach_id'];
//            }
//            //*******************************计数
//            if ($value['num']) {
//                $this->db->where('order_id', $value['order_id'])->update('order_query_num', array('num' => $value['num'] + 1));
//            } else {
//                $this->db->insert('order_query_num', array('order_id' => $value['order_id']));
//            }
//            //*******************************计数结束
//            $result_arr = $this->code_order_query($order_id); //调接口，去银联查询
//            unset($result_arr['signature']);
//            if (isset($result_arr['queryId']) && $result_arr["respCode"] == "00" && isset($result_arr["origRespCode"]) && $result_arr["origRespCode"] == "00") {
//                $txn_id=$result_arr['queryId'];
//                $orderId=$result_arr['orderId'];
//
//                $this->load->model('M_order', 'm_order');
//                $act = $this->m_order->getPayAction($orderId);
//                if (isset($act['table']) && isset($act['payFunc'])) {
//                    $order = $this->db->from($act['table'])->where($act['field'], $orderId)->get()->row_array();
//                    $this->m_log->ordersRollbackLog($order[$act['field']], $txn_id);
//                }
//            }
//        }
    }

}
