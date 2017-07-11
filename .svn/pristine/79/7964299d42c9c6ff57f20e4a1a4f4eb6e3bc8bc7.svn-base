<?php

/**
  <p>//PRO<br>
  2567583700@qq.com<br>
  mfcw6srf20vi7teopsjmolgeju4wjcu2<br>
  2088911308895646</p>

 */
class m_alipay extends CI_Model {

    //支付宝接口配置信息
    private $__alipay_config;

    public function __construct() {
        parent::__construct();
        $this->__init_config();
    }

    //初始化参数
    private function __init_config() {

        $config = $this->m_global->getPaymentConfig(strtolower(__CLASS__));
        //合作身份者id，以2088开头的16位纯数字
        $this->__alipay_config['partner'] = $config['alipay_partner'];

        //安全检验码，以数字和字母组成的32位字符
        $this->__alipay_config['key'] = $config['alipay_key'];

        //签约支付宝账号或卖家支付宝帐户
        $this->__alipay_config['seller_email'] = $config['alipay_account'];

        //服务器异步通知路径
        //$this->__alipay_config['notify_url'] = base_url('respond/do_notify?code='. base64_encode(serialize(__CLASS__)));
        $this->__alipay_config['notify_url'] = 'https://mall.' . get_public_domain() . '/respond/do_notify?code=' . base64_encode(serialize(__CLASS__));

        //页面跳转路径
        $this->__alipay_config['return_url'] = base_url('respond/do_return?code=' . base64_encode(serialize(__CLASS__)));

        //签名方式 不需修改
        $this->__alipay_config['sign_type'] = strtoupper('MD5');

        //字符编码格式 目前支持 gbk 或 utf-8
        $this->__alipay_config['input_charset'] = strtolower('utf-8');

        //ca证书路径地址，用于curl中ssl校验,请保证cacert.pem文件在当前文件夹目录中
        $this->__alipay_config['cacert'] = APPPATH . 'third_party/alipay/cacert.pem';//线上证书位置
//        $this->__alipay_config['cacert'] = getcwd() . '\\application/third_party/alipay/cacert.pem';//本地windows环境位置

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $this->__alipay_config['transport'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
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

        /** 推送支付接口的订单日志 and 更新订单推送的实付金额 */
        $order['paid_amount'] = '￥' . $amount;
        $this->m_log->createOrdersNotifyLog('1', __CLASS__, $order);
        $this->m_global->update_paid_amount($order);

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($this->__alipay_config['partner']),
            "payment_type" => '1',
            "notify_url" => trim($this->__alipay_config['notify_url']),
            "return_url" => trim($this->__alipay_config['return_url']),
            "seller_email" => trim($this->__alipay_config['seller_email']), //支付宝帐户,
            "out_trade_no" => $order['order_sn'], //商户订单号
            "subject" => lang('payment_note_'), //订单名称
            "body" => lang('payment_note_'), //必填,订单描述
            "total_fee" => $amount, //必填,付款金额
            //"show_url"    =>  base_url('ucenter/member_upgrade'),//商品展示地址
            "anti_phishing_key" => '', //防钓鱼时间戳
            "exter_invoke_ip" => $this->input->ip_address(), //客户端的IP地址
            "extra_common_param" => '', //自定義參數
            "_input_charset" => trim(strtolower($this->__alipay_config['input_charset']))
        );

        include_once APPPATH . 'third_party/alipay/alipay_submit.class.php';
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->__alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "submit");
        $str = '<div class="paypal_loading"><div class="paypal_loading_div" ><div class="paypal_loading_img"><span id="loading_span">';
        $str .= lang('jump') . '</span></div></div> </div><div style="height:360px;">&nbsp;</div><div  style="display:none;">';
        $str .= $html_text . '</div>';

        return $str;
    }

    public function do_return() {
        if (empty($_GET)) {
            return FALSE;
        }
        /** 去掉參數code */
        unset($_GET['code']);
        include_once APPPATH . 'third_party/alipay/alipay_notify.class.php';
        $alipayNotify = new AlipayNotify($this->__alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        if ($verify_result) {//判断sign是否正确验证成功
            //商户订单号
            $out_trade_no = $this->input->get('out_trade_no');
            //支付宝交易号
            $trade_no = $this->input->get('trade_no');
            //交易状态
            $trade_status = $this->input->get('trade_status');
            //收钱的email
            $seller_email = $this->input->get('seller_email');
            $total_fee = $this->input->get('total_fee');
//                if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {//含有交易結束後，二次通知
            if ($trade_status == 'TRADE_SUCCESS') {

                $result['success'] = 0;
                $this->load->model('M_order', 'm_order');
                $act = $this->m_order->getPayAction($out_trade_no);
                $result['table'] = $act['table'];
                $result['order_id'] = $out_trade_no;

                //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
                if ($act['table'] === 'trade_orders') {
//                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                    $result['amount'] = $order['order_amount_usd'];
                    //验证交易号是否已经处理过
//                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                    $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade_no]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                    //验证交易号是否已经处理过
                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                }
                if ($ducu_txn_id) {
                    $result['success'] = 1;
                    return $result;
                }
                if (!$order /* || $order['money'] != $total_fee */) {
                    $result['msg'] = lang('no_amount');
                    return $result;
                }
                if ($order['txn_id'] && $order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100) {
                    $result['success'] = 1;
                    return $result;
                }

                //判断支付宝的email是不是公司的email
                if ($seller_email != $this->__alipay_config['seller_email']) {
                    $this->m_log->createOrdersLog($out_trade_no, '收款賬戶不一致');
                    $result['msg'] = lang('no_email');
                    return $result;
                }

                $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                $result['success'] = 1;
                return $result;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    //确保公网能访问
    public function do_notify() {
        if (empty($_POST)) {
            return FALSE;
        }
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_POST);
        unset($_POST['code']);
        include_once APPPATH . 'third_party/alipay/alipay_notify.class.php';
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->__alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result) {

            $out_trade_no = $_POST['out_trade_no'];
            $trade_no = $_POST['trade_no'];
            $trade_status = $_POST['trade_status'];
            $seller_email = $_POST['seller_email'];
            $total_fee = $_POST['total_fee'];
            //$custom = $_POST['extra_common_param'];
//                if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {//含有交易結束後，二次通知
            if ($trade_status == 'TRADE_SUCCESS') {

                $this->load->model('M_order', 'm_order');
                $act = $this->m_order->getPayAction($out_trade_no);

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

                //判断支付宝的email是不是公司的email
                if ($seller_email != $this->__alipay_config['seller_email']) {
                    $this->m_log->createOrdersLog($out_trade_no, '收款賬戶不一致');
                    echo "fail";
                    exit;
                }

                //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
                if($act['table'] === 'trade_orders'){
                    $this->load->model("tb_trade_orders");
                    $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                }else{
                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                }
                if (!$order /* || $order['money'] != $total_fee */ || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100)) {
                    echo "success";
                    exit;
                }

                $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                echo "success";
                exit;
            }
        } else {
            //验证失败
            echo "fail";
        }
    }

    /**
     * 功能：批量付款到支付宝账户有密接口
     */
    public function batch_trans_get_code($batch_info) {

        include_once APPPATH . 'third_party/alipay/alipay_submit.class.php';

        //服务器异步通知页面路径
        $notify_url = base_url('respond/do_batch_trans_notify');
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        //付款账号
        $email = trim($this->__alipay_config['seller_email']);
        //必填
        //付款账户名
        $account_name = "深圳前海云集品电子商务有限公司";
        //必填，个人支付宝账号是真实姓名公司支付宝账号是公司名称
        //付款当天日期
        $pay_date = date("Ymd");
        //必填，格式：年[4位]月[2位]日[2位]，如：20100801
        //批次号
        $batch_no = $batch_info['batch_no'];
        //必填，格式：当天日期[8位]+序列号[3至16位]，如：201008010000001
        //付款总金额
        $batch_fee = $batch_info['batch_fee'];
        ;
        //必填，即参数detail_data的值中所有金额的总和
        //付款笔数
        $batch_num = $batch_info['batch_num'];
        ;
        //必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）


        /**
         * 得到提现批次号的所有记录，拼接特定的字符
         */
        //付款详细数据
        $detail_data = $batch_info['detail_data'];
        //必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "batch_trans_notify",
            "partner" => trim($this->__alipay_config['partner']),
            "notify_url" => $notify_url,
            "email" => $email,
            "account_name" => $account_name,
            "pay_date" => $pay_date,
            "batch_no" => $batch_no,
            "batch_fee" => $batch_fee,
            "batch_num" => $batch_num,
            "detail_data" => $detail_data,
            "_input_charset" => trim(strtolower($this->__alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->__alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "");
        return $html_text;
    }

    /**
     * 功能：批量付款到支付宝账户异步通知处理
     */
    public function do_batch_trans_notify() {

        require_once APPPATH . 'third_party/alipay/alipay_notify.class.php';

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->__alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        $this->m_log->createCronLog($_POST);

        if ($verify_result) {//验证成功
            //判断是否在商户网站中已经做过了这次通知返回的处理
            $batch_no = $_POST['batch_no'];
            $notify_time = $_POST['notify_time'];
            $this->load->model('tb_cash_take_out_logs');
            $batch_info = $this->tb_cash_take_out_logs->get_batch_process_info($batch_no);
            if (!$batch_info || $batch_info['status'] == 3) {//如果有做过处理，那么不执行商户的业务程序
                echo "success";
                exit;
            }

            $this->db->trans_start();

            //如果没有做过处理，那么执行商户的业务程序
            $success_details = $_POST['success_details'];
            $success = array();
            $fail = array();
            if (!empty($success_details)) {
                $suArray = explode('|', $success_details);
                foreach ($suArray as $item) {
                    $item_arr = explode('^', $item);
                    if (!$item_arr[0])
                        continue;
                    $success[] = array(
                        'id' => $item_arr[0],
                        'status' => 1,
                        'process_num' => $item_arr[6],
                        'check_time' => $notify_time,
                    );
                }
                $this->db->update_batch('cash_take_out_logs', $success, 'id');
            }

            $fail_details = $_POST['fail_details'];
            if (!empty($fail_details)) {
                $faArray = explode('|', $fail_details);
                foreach ($faArray as $item) {
                    $item_arr = explode('^', $item);
                    if (!$item_arr[0])
                        continue;
                    $fail[] = array(
                        'id' => $item_arr[0],
                        'status' => 3,
                        'check_info' => lang($item_arr[5])?lang($item_arr[5]):$item_arr[5],
                        'process_num' => $item_arr[6],
                        'check_time' => $notify_time,
                    );
                }
                $this->db->update_batch('cash_take_out_logs', $fail, 'id');
            }

            $this->db->where('batch_num', $batch_no)->update('cash_take_out_batch_tb', array(
                'success' => count($success),
                'failure' => count($fail),
                'process_time' => $notify_time,
                'status' => 3,
            ));

            $this->db->trans_complete();

            if ($this->db->trans_status() == TRUE) {
                echo "success";
                $this->m_log->createCronLog($batch_no . '提现批次处理[success]');
            } else {
                echo "fail";
                $this->m_log->createCronLog($batch_no . '提现批次处理[fail]');
            }
        } else {
            echo "fail";
            $this->m_log->createCronLog('verify_result提现验证[fail]');
        }
    }

/**
     * 功能：批量退出转账到支付宝
     */

    public function batch_trans_get_code_after($batch_info) {

        include_once APPPATH . 'third_party/alipay/alipay_submit.class.php';

        //服务器异步通知页面路径
        $notify_url = base_url('respond/do_batch_trans_notify_after');
        //$notify_url =  "http://cs.tps138.com:81/respond/do_batch_trans_notify_after";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        //付款账号
        $email = trim($this->__alipay_config['seller_email']);
        //必填
        //付款账户名
        $account_name = "深圳前海云集品电子商务有限公司";
        //必填，个人支付宝账号是真实姓名公司支付宝账号是公司名称
        //付款当天日期
        $pay_date = date("Ymd");
        //必填，格式：年[4位]月[2位]日[2位]，如：20100801
        //批次号
        $batch_no = $batch_info['batch_no'];
        //必填，格式：当天日期[8位]+序列号[3至16位]，如：201008010000001
        //付款总金额
        $batch_fee = $batch_info['batch_fee'];
        ;
        //必填，即参数detail_data的值中所有金额的总和
        //付款笔数
        $batch_num = $batch_info['batch_num'];
        ;
        //必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）


        /**
         * 得到提现批次号的所有记录，拼接特定的字符
         */
        //付款详细数据
        $detail_data = $batch_info['detail_data'];
        //必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "batch_trans_notify",
            "partner" => trim($this->__alipay_config['partner']),
            "notify_url" => $notify_url,
            "email" => $email,
            "account_name" => $account_name,
            "pay_date" => $pay_date,
            "batch_no" => $batch_no,
            "batch_fee" => $batch_fee,
            "batch_num" => $batch_num,
            "detail_data" => $detail_data,
            "_input_charset" => trim(strtolower($this->__alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->__alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "");
        return $html_text;
    }

    /**
     * 功能：批量退出转账到支付宝（异步）
     */
    public function do_batch_trans_notify_after() {

        require_once APPPATH . 'third_party/alipay/alipay_notify.class.php';

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->__alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        $this->m_log->createCronLog($_POST);

        if ($verify_result) {//验证成功
            //判断是否在商户网站中已经做过了这次通知返回的处理
            $batch_no = $_POST['batch_no'];
            $notify_time = $_POST['notify_time'];
            $this->load->model('tb_admin_after_sale_batch');
            $batch_info = $this->tb_admin_after_sale_batch->get_batch_process_info($batch_no);
            if (!$batch_info || $batch_info['status'] == 3) {//如果有做过处理，那么不执行商户的业务程序
                echo "success";
                exit;
            }

            $this->db->trans_start();

            //如果没有做过处理，那么执行商户的业务程序
            $success_details = $_POST['success_details'];
            $success = array();
            $fail = array();
            if (!empty($success_details)) {
                $suArray = explode('|', $success_details);
                foreach ($suArray as $item) {
                    $item_arr = explode('^', $item);
                    if (!$item_arr[0])
                        continue;
                    $success[] = array(
                        'as_id' => $item_arr[0],
                        'status' => 3,
                    );
                }
                $this->db->update_batch('admin_after_sale_order', $success, 'as_id');
            }

            $fail_details = $_POST['fail_details'];
            $this->load->model('m_log');
            if (!empty($fail_details)) {
                $faArray = explode('|', $fail_details);
                foreach ($faArray as $item) {
                    $item_arr = explode('^', $item);
                    if (!$item_arr[0])
                        continue;
                    $fail[] = array(
                        'as_id' => $item_arr[0],
                        'status' => 5,
                        'batch_id'=>NULL,
                    );
                    $this->m_log->admin_after_sale_remark($item_arr[0], 18, lang($item_arr[5]));
                }
                $this->db->update_batch('admin_after_sale_order', $fail, 'as_id');
            }

            $this->db->where('batch_num', $batch_no)->update('admin_after_sale_batch', array(
                'success' => count($success),
                'failure' => count($fail),
                'process_time' => $notify_time,
                'status' => 3,
            ));

            $this->db->trans_complete();

            if ($this->db->trans_status() == TRUE) {
                echo "success";
                $this->m_log->createCronLog($batch_no . '批量退出批次处理[success]');
            } else {
                echo "fail";
                $this->m_log->createCronLog($batch_no . '批量退出批次处理[fail]');
            }
        } else {
            echo "fail";
            $this->m_log->createCronLog('verify_result批量退出验证[fail]');
        }
    }

    /*
     * 支付宝查询单笔订单状态（接口，不含业务逻辑）
     */
    public function alipay_order_query($order) {
        include_once APPPATH . 'third_party/alipay/alipay_submit.class.php';
        //支付宝交易号
        $trade_no = '';
        //支付宝交易号与商户网站订单号不能同时为空
        //商户订单号
        $out_trade_no = $order;
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "single_trade_query",
            "partner" => trim($this->__alipay_config['partner']),
            "trade_no" => $trade_no,
            "out_trade_no" => $out_trade_no,
            "_input_charset" => trim(strtolower($this->__alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->__alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
        return json_decode(json_encode((array) simplexml_load_string($html_text)), true);//解析XML，返回数组
    }
    
    /*
     * 定时查询支付宝单笔未完成交易，成功则改为待发货
     */
    public function ailapy_active_query() {
        $time=  date('Y-m-d H:i:s',time()-300);
        $sql="SELECT `a`.`order_id`,`a`.`attach_id`,`a`.`order_type`,`a`.`created_at`,`b`.`num` 
FROM (`trade_orders` AS a) LEFT JOIN `order_query_num` AS b ON `a`.`order_id` = `b`.`order_id` 
WHERE `a`.`created_at` <='$time'  AND `a`.`payment_type` = '105' 
AND (`b`.`num` <= 3 OR `b`.`num` IS NULL) AND `a`.`status` = '2' LIMIT 1000";
        $order_list = $this->db->query($sql)->result_array();
        foreach ($order_list as $value) {
            $order_id=$value['order_id'];
            //*******************************判断类型
            if($value['order_type']==='2' && substr($value['order_id'], 0, 1 )==='C'){//升级订单，不处理C字开头的子订单
                $order_id=$value['attach_id'];
            }
            //*******************************计数
            if($value['num']){
                $this->db->where('order_id',$value['order_id'])->update('order_query_num',array('num'=>$value['num']+1));
            }  else {
                $this->db->insert('order_query_num',array('order_id'=>$value['order_id']));
            }
            //*******************************计数结束
            $is=  $this->alipay_order_query($order_id);//调接口，去支付宝查询
//            $this->load->model('m_debug');
//            $this->m_debug->log($is);
            if(isset($is['response']['trade']['trade_status']) && str_replace(' ','',$is['response']['trade']['trade_status'])=='TRADE_SUCCESS'){//trade_status等于TRADE_SUCCESS为成功
                $trade_no=$is['response']['trade']['trade_no'];
                $out_trade_no=$is['response']['trade']['out_trade_no'];
                $seller_email=$is['response']['trade']['seller_email'];
//                $this->m_debug->log('进入支付宝成功队列的订单：'.$out_trade_no);
                $this->load->model('M_order', 'm_order');
                $act = $this->m_order->getPayAction($out_trade_no);
                //验证交易号是否已经处理过
                if($act['table'] === 'trade_orders'){
                    $this->load->model("tb_trade_orders");
                    $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade_no]);
                }else{
                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                }
                if ($ducu_txn_id) {
                    continue;
                }
                //判断支付宝的email是不是公司的email
                if ($seller_email != $this->__alipay_config['seller_email']) {
                    $this->m_log->createOrdersLog($out_trade_no, '收款賬戶不一致');
                    continue;
                }
                //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
                $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                if (!$order /* || $order['money'] != $total_fee */ || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100)) {
                    continue;
                }
                $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                continue;
            }
        }
    }
    
    /**
     * WAP版支付宝付款接口，唤起支付宝APP付款
     */
    public function get_codemobile($order) {
        header("Content-type: text/html; charset=utf-8");
        require_once APPPATH . 'third_party/alipay/alipay/wappay/service/AlipayTradeService.php';
        require_once APPPATH . 'third_party/alipay/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
        require APPPATH . 'third_party/alipay/alipay/wappay/config.php';
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
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_sn'];
        //订单名称，必填
        $subject = lang('payment_note_');
        //付款金额，必填
        $total_amount = $amount;
        //商品描述，可空
        $body = '';
        //超时时间
        $timeout_express="1m";
        $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $payResponse = new AlipayTradeService($config);
        $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    }
    /**
     * WAP版支付宝付款接口回调，同步
     */
    public function do_return_mobile() {
        require_once APPPATH . 'third_party/alipay/alipay/wappay/config.php';
        require_once APPPATH . 'third_party/alipay/alipay/wappay/service/AlipayTradeService.php';
        $arr=$_GET;
        unset($arr['code']);
        $alipaySevice = new AlipayTradeService($config); 
        $results = $alipaySevice->check($arr);
        if($results) {//验证成功
                //商户订单号
                $out_trade_no = $this->input->get('out_trade_no');
                //支付宝交易号
                $trade_no = $this->input->get('trade_no');
                if (isset($out_trade_no)) {
                    $result['success'] = 0;
                    $this->load->model('M_order', 'm_order');
                    $act = $this->m_order->getPayAction($out_trade_no);
                    $result['table'] = $act['table'];
                    $result['order_id'] = $out_trade_no;
                    //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
                    if ($act['table'] === 'trade_orders') {
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                    }else{
                        $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                    }
                    $result['amount'] = $order['order_amount_usd'];
                    $result['phone'] = $order['phone'];
                    //验证交易号是否已经处理过
                    if ($act['table'] === 'trade_orders') {
                        $this->load->model("tb_trade_orders");
                        $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$trade_no]);
                    }else{
                        $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $trade_no)->count_all_results();
                    }
                    if ($ducu_txn_id) {
                        $result['success'] = 1;
                        return $result;
                    }

                    if (!$order /* || $order['money'] != $total_fee */) {
                        $result['msg'] = lang('no_amount');
                        return $result;
                    }
                    if ($order['txn_id'] && $order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100) {
                        $result['success'] = 1;
                        return $result;
                    }
                    $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                    if(in_array(substr($order[$act['field']], 0, 1 ), array('L'))){//用于第一路演接口
//                        $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$trade_no));
                        $this->load->model("tb_trade_orders");
                        $this->tb_trade_orders->update_one(["order_id"=>$order['order_id']],
                            array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$trade_no));
                    }
                    $result['success'] = 1;
                    return $result;
                } else {
                    if(in_array(substr($out_trade_no, 0, 1 ), array('L'))){//用于第一路演接口
                    }
                    return FALSE;
                }
        }
        else {
            //验证失败
            return FALSE;
        }
    }
    /**
     * WAP版支付宝付款接口回调，异步
     */
    public function do_notify_mobile() {
        require_once APPPATH . 'third_party/alipay/alipay/wappay/config.php';
        require_once APPPATH . 'third_party/alipay/alipay/wappay/service/AlipayTradeService.php';
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_POST);
        $arr=$_POST;
        unset($_POST['code']);unset($arr['code']);
        $alipaySevice = new AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        if($result) {

                $out_trade_no = $_POST['out_trade_no'];
                $trade_no = $_POST['trade_no'];
                $trade_status = $_POST['trade_status'];
                $seller_email = $_POST['seller_email'];
//                $total_fee = $_POST['total_fee'];
                //$custom = $_POST['extra_common_param'];
//                if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {//含有交易結束後，二次通知
                if ($trade_status == 'TRADE_SUCCESS') {

                    $this->load->model('M_order', 'm_order');
                    $act = $this->m_order->getPayAction($out_trade_no);

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

                    //判断支付宝的email是不是公司的email
                    if ($seller_email != $this->__alipay_config['seller_email']) {
                        $this->m_log->createOrdersLog($out_trade_no, '收款賬戶不一致');
                        echo "fail";
                        exit;
                    }

                    //先要进行订单号和价格的查询比对，ok之后再去修改order，修改pay的状态
//                    $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                    if($act['table'] === 'trade_orders'){
                        $this->load->model("tb_trade_orders");
                        $order = $this->tb_trade_orders->get_one("*",[$act['field']=>$out_trade_no]);
                    }else{
                        $order = $this->db->from($act['table'])->where($act['field'], $out_trade_no)->get()->row_array();
                    }
                    if (!$order /* || $order['money'] != $total_fee */ || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != 100)) {
                        echo "success";
                        exit;
                    }

                    $this->m_log->ordersRollbackLog($order[$act['field']], $trade_no);
                    if(in_array(substr($order[$act['field']], 0, 1 ), array('L'))){//用于第一路演接口
//                        $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$trade_no));
                        $this->load->model("tb_trade_orders");
                        $this->tb_trade_orders->update_one(['order_id'=>$order['order_id']],
                            array('status'=>'6','pay_time'=>date('Y-m-d H:i:s',time()),'txn_id'=>$trade_no));
                    }
                    echo "success";
                    exit;
                }
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
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
    /**
     * 6.20修复已支付的订单
     */
    public function repair_order() {
        $this->load->model('m_debug');
        $data = $this->db->select('payment_type,content')->from('logs_orders_notify')->where('type', 11)->where('create_time >', '2017-06-20 15:00:00')->where_in('payment_type', array('m_wxpay', 'm_alipay', 'm_unionpay', 'm_paypal', 'm_ewallet', 'm_yspay', 'm_usd_unionpay'))->get()->result_array();
//        fout($this->db->last_query());exit;
        foreach ($data as $key => $value) {
            $content=$this->string2array($value['content']);
            switch ($value['payment_type']) {
                case 'm_alipay':
                    if($content['trade_status']=='TRADE_SUCCESS'){
                        $this->load->model('M_order', 'm_order');
                        $act = $this->m_order->getPayAction($content['out_trade_no']);
                        //验证交易号是否已经处理过
                        if ($act['table'] === 'trade_orders') {
                            $this->load->model("tb_trade_orders");
                            $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$content['trade_no']]);
                        }else{
                            $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $content['trade_no'])->count_all_results();
                        }
                        if ($ducu_txn_id) {
                            break;
                        }echo $content['out_trade_no'];
                        $this->m_log->ordersRollbackLog($content['out_trade_no'], $content['trade_no']);
                        $this->m_debug->dd_log($content['out_trade_no'],$content['trade_no'],'session_cookie');
                        break;
                    }
                case 'm_unionpay':
                    if($content['respCode']=='00'){
                        $this->load->model('M_order', 'm_order');
                        $act = $this->m_order->getPayAction($content['orderId']);
                        //验证交易号是否已经处理过
                        if ($act['table'] === 'trade_orders') {
                            $this->load->model("tb_trade_orders");
                            $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$content['queryId']]);
                        }else{
                            $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $content['queryId'])->count_all_results();
                        }
                        if ($ducu_txn_id) {
                            break;
                        }
                        echo $content['orderId'];
                        $this->m_log->ordersRollbackLog($content['orderId'], $content['queryId']);
                        $this->m_debug->dd_log($content['orderId'],$content['queryId'],'session_cookie');
                        break;
                    }
                case 'm_usd_unionpay':
                    if($content['RespCode']=='00'){
                        $this->load->model('M_order', 'm_order');
                        $act = $this->m_order->getPayAction($content['orderNum']);
                        //验证交易号是否已经处理过
                        if ($act['table'] === 'trade_orders') {
                            $this->load->model("tb_trade_orders");
                            $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$content['transID']]);
                        }else{
                            $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $content['transID'])->count_all_results();
                        }
                        if ($ducu_txn_id) {
                            break;
                        }
                        echo $content['orderNum'];
                        $this->m_log->ordersRollbackLog($content['orderNum'], $content['transID']);
                        $this->m_debug->dd_log($content['orderNum'],$content['transID'],'session_cookie');
                        break;
                    }
                case 'm_wxpay':
                    break;
                default:
                    echo "No number between 1 and 3";
            }
        }
    }
    
    /**
     * 6.20修复已支付的订单
     */
    public function repair_order2($num) {
        require_once APPPATH."third_party/wxpay/func/wx_notify.php";
        $this->load->model("m_log");
        $this->m_log->createOrdersNotifyLog('11', __CLASS__, $_REQUEST);
        $notify = new PayNotifyCallBack();
        $this->db->select('attach_id as order_id')->from('trade_orders_1706')->where('payment_type', '104');
        if($num==1){
            $this->db->limit(600);
        }  else {
            $this->db->limit(600,600*($num-1));
        }
        $data = $this->db->where('status', '2')->group_by('attach_id')->get()->result_array();
//        fout($data);exit;
        foreach ($data as $k=> $value) {
            if(substr( $value['order_id'], 0, 1 )=='P'){
                continue;
            }
//            echo $value['order_id'];echo '<br />';
            $result=$notify->Queryorder_two($value['order_id']);
            if($result["trade_state"] == "SUCCESS")
		{
                echo '----------------------------------'.$value['order_id'];echo '<br />';
                $this->load->model('M_order', 'm_order');
                $act = $this->m_order->getPayAction($result['out_trade_no']);
                //验证交易号是否已经处理过
                if ($act['table'] === 'trade_orders') {
                    $this->load->model("tb_trade_orders");
                    $ducu_txn_id = $this->tb_trade_orders->get_counts(["txn_id"=>$result['transaction_id']]);
                }else{
                    $ducu_txn_id = $this->db->from($act['table'])->where('txn_id', $result['transaction_id'])->count_all_results();
                }
                if ($ducu_txn_id) {
                    continue;
                }
                $this->m_log->ordersRollbackLog($result['out_trade_no'], $result['transaction_id']);
                $this->m_debug->dd_log($result['out_trade_no'],$result['transaction_id'],'weixin_');
		}  else {
                    echo $value['order_id'];echo '+++++'.$result["trade_state"].'<br />';
                }
        }
        }
}
