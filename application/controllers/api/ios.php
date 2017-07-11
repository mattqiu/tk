<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class ios extends REST_Controller {

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

    /*验证令牌*/
    private function __checkSign($sign, $token) {

        if ($sign != ios_api_create_sign($token)) {
            $this->response(array('error_code' => 102, 'data' => array()), 200);
        }
    }

    /**
     * test
     * @author Terry
     */
    function test_post() {
        $this->__requestData['res'] = '110';
        $this->response(array('error_code' => 0, 'data' => $this->__requestData), 200);
    }


}
