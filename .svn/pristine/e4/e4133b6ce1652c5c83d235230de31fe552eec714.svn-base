<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class wohao extends REST_Controller {

    private $__requestData = array();

    function __construct() {
        parent::__construct();
        $this->load->model('m_debug');
        $this->load->model('m_log');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
//        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
//        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
//        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
        $this->__requestData = $this->input->post();
        $this->__checkSign($this->__requestData['sign'], $this->__requestData['token']);//Check the sign.

    }

    private function __checkSign($sign, $token) {
        if ($sign != api_create_sign($token)) {
            $this->response(array('error_code' => 102, 'data' => array()), 200);
        }
    }

    /**
     * 用户信息同步(新增/修改)接口－tps138接收沃好的同步
     * @author Terry
     */
    function memberSync_post() {

        $this->load->model('m_user');
        $res = $this->m_user->memberSyncFromWohao($this->__requestData);
        $this->response($res, 200);
    }

    /*订单同步接口－tps138接收沃好的同步*/
    function orderSync_post() {
        if(!config_item('wohao_api_status')){
            $this->response(array('error_code' => 100, 'data' => array()), 200);
        }
        $this->__requestData['order_id'] = trim($this->__requestData['order_id']);
        //echo $this->__requestData['order_id'];exit;
        if(!$this->__requestData['order_id'] || substr($this->__requestData['order_id'],0,3)=='TKD' || substr($this->__requestData['order_id'],0,5)=='WLTHD'){
            $this->response(array('error_code' => 101, 'data' => array()), 200);
        }else{
            $this->__requestData['order_id'] = 'W-'.$this->__requestData['order_id'];
        }

        $this->load->model('m_order');
        $error_code = $this->m_order->saveWohaoMallOrders($this->__requestData);
        $this->response(array('error_code' => $error_code, 'data' => array()), 200);
    }

    /**
     * 沃好取消订单接口
     * @author Terry
     */
    public function orderCancel_post(){
        $this->load->model('o_order_cancel');
        $this->load->model('tb_mall_orders');
        if($this->__requestData['order_id']){
            //查询订单是否存在，状态是否为已完成
            $orderStatus = $this->tb_mall_orders->getWhOrderStatus($this->__requestData['order_id']);
            if($orderStatus==1){
                $this->tb_mall_orders->updateWhOrderStatus($this->__requestData['order_id'],2);//更改订单的状态为 取消状态
                $this->o_order_cancel->preWithdrawOfOrder($this->__requestData['order_id'],'wh');//抽回佣金
                $error_code = 0;
            }else{
                $error_code = 100;
            }
        }else{

            $error_code = 101;
        }
        $this->response(array('error_code' => $error_code, 'data' => array()), 200);
    }

}
