<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
 * 主动查询订单类
 */

class Active_query extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_alipay');
    }

    //支付宝查询
    public function index() {
        $searchData = $this->uri->segment(5);
        $searchData = isset($searchData) ? $searchData : '';
        print_r($this->m_alipay->alipay_order_query($searchData));
    }

    //银联查询
    public function code_order_query() {
        $this->load->model('m_unionpay');
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        print_r($this->m_unionpay->unionpay_active_query());
    }

    //微信查询
    public function sel_weixin(){
        require_once APPPATH."third_party/wxpay/func/wx_notify.php";
        $this->load->model("m_log");
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_REQUEST);
        $notify = new PayNotifyCallBack();
        $order_id = $this->uri->segment(4);
        $result=$notify->Queryorder_two($order_id);
        fout($result);
    }

    /**
     * 将字符串转换为数组 
     * 
     * @param string $data 字符串 
     * @return array 返回数组格式，如果，data为空，则返回空数组 
     */
    function string2array($data) {
        if ($data == '')
            return array();
        @eval("\$array = $data;");
        return $array;
    }

    //加密
    public function css() {
        include_once APPPATH . 'third_party/AES/AES.php';
        $aes = new aes;
        echo $aes->aes128ecbEncrypt("cy123456");//加密
        echo "<br />";
        echo $aes->aes128ecbHexDecrypt("571BB76551E65CBAB4F73E1604C34684");//解密
    }
    /**
     * 测试事务回滚是否有返回值
     */
    public function asd1(){
        
    }
    function cs($data) {
        $this->load->model('m_debug');
        $this->m_debug->dd_log('1','paypal_masspay');
    }

    public function dev_import()
    {
        $this->_viewData['title'] = lang('admin_trade_order_import');

        parent::index('admin/', 'dev_import');
    }
    /**
     * 导入excel
     */
    public function dev_dr()
    {
        if (isset($_FILES['excelfile2']['type']) == null)
        {
            $result['msg'] = lang('admin_file_format');
            $result['success'] = 0;
            die(json_encode($result));
        }
        $mime = $_FILES['excelfile2']['type'];
        $path = $_FILES['excelfile2']['tmp_name'];

        /* 读取数据 */
        $order_data = readExcel($path, $mime);

        $data = array();
        foreach($order_data as $v){
//            $data['order_id'] = substr($v[0] ,15 ,19);
            $data['order_id'] = substr($v[0] ,18 ,5);
//            $data['content'] = var_export($v[0].substr($v[0] ,14 ,19),1);
//            $data['content'] = substr($v[0] ,35).' 订单ID'.substr($v[0] ,14 ,20);
            $data['content'] = substr($v[0] ,37).' 订单ID'.substr($v[0] ,18 ,5);
//            print_r($data);exit;
            $this->db->insert('admin_import_dev',$data);
        }

        exit;
    }

    public function tx_time(){
        $time = date('Y-m-d H:i:s',time());
        echo $time.'***';
        if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')){
            echo '111';
        }else{
            echo '222';
        }

        //2017-03-01 00:00:00 后升级订单不在发放代品券
        if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')){

        }
    }

}
