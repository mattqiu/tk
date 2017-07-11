<?php

/**
 * Class o_pay
 */
class o_pay extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 将字符串转换为数组
     *
     * @param string $data 字符串
     * @return array 返回数组格式，如果，data为空，则返回空数组
     */
    function string2array($data) {
        $array = [];
        if ($data == '')
            return array();
        @eval("\$array = $data;");
        return $array;
    }

    public function pay_methods()
    {
        return array('m_wxpay', 'm_alipay', 'm_unionpay', 'm_paypal', 'm_ewallet', 'm_yspay', 'm_usd_unionpay');
    }

    /**
     * 检测订单是否已使用某支付方式支付
     * @param $order_id
     * @param $pay_method
     * @return bool
     */
    public function check_pay($order_id,$pay_method,$contents)
    {
        switch ($pay_method) {
            case 'm_alipay':
                return $this->check_alipay($order_id,$contents);
            case 'm_unionpay':
                return $this->check_unionpay($order_id,$contents);
            case 'm_usd_unionpay':
                return $this->check_usd_unionpay($order_id,$contents);
            case 'm_wxpay':
                return $this->check_wxpay($order_id,$contents);
            default:
                return false;
        }
    }

    public function check_usd_unionpay($order_id,$contents)
    {
        foreach($contents as $v)
        {
            if($v['payment_type'] == "m_usd_unionpay")
            {
                $tmp=$this->string2array($v['content']);
                if($tmp['RespCode']=='00' && $tmp['orderNum'] == $order_id){
                    return true;
                }
            }
        }
        return false;
    }

    public function check_unionpay($order_id,$contents)
    {
        foreach($contents as $v)
        {
            if($v['payment_type'] == "m_unionpay")
            {
                $tmp=$this->string2array($v['content']);
                if($tmp['respCode']=='00' && $tmp['orderId'] == $order_id){
                    return true;
                }
            }
        }
        return false;
    }

    public function check_alipay($order_id,$contents)
    {
        foreach($contents as $v)
        {
            if($v['payment_type'] == "m_alipay")
            {
                $tmp=$this->string2array($v['content']);
                if($tmp['trade_status']=='TRADE_SUCCESS' && $tmp['out_trade_no'] == $order_id){
                    return true;
                }
            }
        }
        return false;
    }

    public function check_wxpay($order_id,$contents="")
    {
        require_once APPPATH."third_party/wxpay/func/wx_notify.php";
        $notify = new PayNotifyCallBack();
        $result=$notify->Queryorder_two($order_id);
        if(isset($result["result_code"]) && $result['result_code'] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    /**
     * 根据订单ID取回调日志
     * @param $order_id
     * @return mixed
     */
    public function get_logs_orders_notify()
    {
        $data = $this->db->select('payment_type,content')
            ->from('logs_orders_notify')
            ->where('type', 11)
            ->where('create_time >', '2017-06-26 00:00:00')
            ->where('create_time <', '2017-07-01 00:00:00')
            ->where_in('payment_type', $this->pay_methods())
            ->get()->result_array();
        echo('get all_logs_orders from db.'."\n");
//        echo($this->db->last_query()."\n");
        return $data;
    }

}
