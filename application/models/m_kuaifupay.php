<?php
use com\cskj\pay\demo\common\ConfigUtil;
use com\cskj\pay\demo\common\HttpUtils;
use com\cskj\pay\demo\common\SignUtil;
use com\cskj\pay\demo\common\TDESUtil;
use com\cskj\pay\demo\common\XMLUtil;
include APPPATH . 'third_party/kuaifupay/common/ConfigUtil.php';
include APPPATH . 'third_party/kuaifupay/common/SignUtil.php';
include APPPATH . 'third_party/kuaifupay/common/HttpUtils.php';
include APPPATH . 'third_party/kuaifupay/common/TDESUtil.php';
include APPPATH . 'third_party/kuaifupay/common/XMLUtil.php';
class m_kuaifupay extends CI_Model {

    //快付通接口配置信息
    private $__kuaifupay_config;

    public function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    //初始化参数
    private function __init_config() {

        $config = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        //安全检验码，以数字和字母组成的32位字符
        $this->__kuaifupay_config['key'] = $config['kuaifupay_key'];

        //签约支付宝账号或卖家支付宝帐户
        $this->__kuaifupay_config['mchId'] = $config['kuaifupay_account'];

        //服务器异步通知路径
//        $this->__kuaifupay_config['notify_url'] = base_url('respond/do_notify?code='. base64_encode(serialize(__CLASS__)));
        $this->__kuaifupay_config['notify_url'] = 'https://pay.' . get_public_domain() . '/respond/do_notify?code=' . base64_encode(serialize(__CLASS__));
//        $this->__kuaifupay_config['notify_url'] = 'http://cs.tps138.com/respond/do_notify?code=' . base64_encode(serialize(__CLASS__));
        
        //=======================支付宝支付服务地址
//        $this->__kuaifupay_config['kuaifupayUrl']="http://test.kftpay.com.cn:3080/cloud/cloudplatform/api/trade.html";
        $this->__kuaifupay_config['kuaifupayUrl']="https://jhpay.kftpay.com.cn/cloud/cloudplatform/api/trade.html";
    }

    public function get_code($order) {
        /**
         * 只支持人民幣 如果货币相等,且是人民币，直接用order_amount字段支付，不用美金转人民币了
         */
        if ($order['currency'] == $order['view_currency'] && $order['view_currency'] == 'CNY') {
            $amount = $order['order_amount'] / 100;
        } else {
            $amount = $this->m_currency->currency_conversion($order['money'], 'CNY');
        }
        $row = $this->db->select('order_id,pay_url,time')->from('order_pay_url')->where('order_id',$order['order_id'])->get()->row_array();
        if(count($row)){
            if(0<(time()-(strtotime($row['time'])+7200))){
                    $data['msg'] = '订单过期，请重新下单';
            }else{
                    $data['url'] = $row['pay_url'];
            }
            $data['order_id'] = $row['order_id'];
            $data['class']=__CLASS__;
        } else {
            $desKey= $this->__kuaifupay_config['key'];
            $mchId=$this->__kuaifupay_config['mchId'];
            $param=array(
                    'tradeType' => 'cs.pay.submit',
                    'version' => 1.6,
                    'channel' => 'alipayQR',
                    'mchId' => "$mchId",
                    'body' => reset($order['goods_list'])['goods_name'],
                    'outTradeNo' => $order['order_id'],
                    'amount' => $amount,
                    'currency' => 'CNY',
                    'timePaid' => date('YmdHis', time()),
                    'timeExpire' => date('YmdHis', time()+3600*2),
                    'notifyUrl'=>$this->__kuaifupay_config['notify_url'],
            );
            $unSignKeyList = array ("sign");
            $sign = SignUtil::signMD5($param, $unSignKeyList,$desKey);
            $param["sign"] = $sign;
            $jsonStr=json_encode($param);
            $serverPayUrl=$this->__kuaifupay_config['kuaifupayUrl'];
            $httputil = new HttpUtils();
            list ( $return_code, $return_content )  = $httputil->http_post_data($serverPayUrl, $jsonStr);
    //        echo $return_code;
            $respJson=json_decode($return_content,TRUE);
            $respJson['amount']=str_replace(',', '', number_format($respJson['amount'], 2));
//            fout($param);
//            print_r($respJson);
            $respSign = SignUtil::signMD5($respJson, $unSignKeyList,$desKey);
    //        echo $respSign;
    //        exit;
            if($respSign !=  $respJson['sign'])
                {
                    $data['msg'] = $respJson['errCodeDes'];
            }else{
                        $this->db->insert('order_pay_url',array(
                        'order_id'=>$order['order_id'],
                        'pay_url'=>$respJson['codeUrl'],
                        'time'=> date('Y-m-d H:i:s',strtotime($respJson['transTime'])),
                    ));
                    $data['url'] = $respJson['codeUrl'];
            }
            $data['order_id'] = $order['order_id'];
            $data['class']=__CLASS__;
        }
//        fout($data);
        return $this->load->view('mall/wxpay',$data,true);
    }
    
    //确保公网能访问
    public function do_notify() {
        $this->load->model("m_log");
        $this->m_log->createOrdersNotifyLog('kuai1', __CLASS__, file_get_contents("php://input"));
        $return_content=file_get_contents("php://input");
        $respJson=json_decode($return_content,TRUE);
        $respJson['amount']=str_replace(',', '', number_format($respJson['amount'], 2));
        $respSign = SignUtil::signMD5($respJson, array ("sign"),$this->__kuaifupay_config['key']);
        if($respSign == $respJson['sign']){
            $out_trade_no = $respJson['outTradeNo'];
            $trade_no = $respJson['outPayNo'];
            if (!$respJson['resultCode']&&$respJson['status']=='02') {

                $this->load->model('M_order', 'm_order');
                $act = $this->m_order->getPayAction($out_trade_no);
                $this->db->delete('order_pay_url', array('order_id' => $out_trade_no));//删除订单支付
                //验证交易号是否已经处理过
                if ($act['table'] === 'trade_orders') {
                    $this->load->model("tb_trade_orders");
                    $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade_no]);
                }else{
                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                }
                if ($ducu_txn_id) {
                    echo "success";
                    exit;
                }
                //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
                if($act['table'] === 'trade_orders'){
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                }
                if (!$order || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100)) {
                    echo "success";
                    exit;
                }

                $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                echo "success";
                exit;
            }
        }else{
                echo "验签失败";
        }

    }

}
