<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class pay extends REST_Controller {

    function __construct() {
        parent::__construct();

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
//        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
//        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
//        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }

//    private function __checkSign($sign, $token) {
//        if ($sign != api_create_sign($token)) {
//            $this->response(array('error_code' => 102, 'data' => array()), 200);
//        }
//    }

    /**
     * 用户账户激活
     */
    function unionpay_post() {
        
        $requestData = $this->input->post();
        $this->response(array('error_code' => 0, 'data' => $requestData), 200);
    }
}
