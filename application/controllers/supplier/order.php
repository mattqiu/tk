<?php
/** 
 *　供应商订单管理
 * @date: 2015-12-10 
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order extends MY_Controller {
    private $supplier=array();

    public function __construct() {
        parent::__construct();

        $this->load->model('m_goods');

        $this->supplier=unserialize(filter_input(INPUT_COOKIE, 'adminSupplierInfo'));
    }
    
    /* 页面-订单列表 */
    public function order_list() {
        // 状态映射表
        $status_select = array(
                0 => lang('admin_order_status_all'),
                Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
                Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
                Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
                Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
                Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
                Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
                Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
                Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
        );
        $status_map = array(
                Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
                Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
                Order_enum::STATUS_SHIPPING => array('class' => "text-warning", 'text' => lang('admin_order_status_paied')),
                Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
                Order_enum::STATUS_EVALUATION => array('class' => "text-success", 'text' => lang('admin_order_status_arrival')),
                Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
                Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
                Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
                Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
                Order_enum::STATUS_COMPONENT => array('class' => "text-default", 'text' => lang('admin_order_status_component')),
        );
        
        // 仓库列表
        /* $storehouse_list = $this->m_trade->get_storehouse_list();
        $storehouse_map = array("ALL" => lang('admin_oper_storehouse_ALL'));
        foreach ($storehouse_list as $v)
        {
            $storehouse_map[$v['store_code']] = $v['storehouse_name'];
        } */
        
        // 快递公司 map
        $freight_list = $this->m_trade->get_freight_list();
        $freight_map = array();
        foreach ($freight_list as $v)
        {
            $freight_map[$v['company_code']] = $v['company_name'];
        }
        // 自定义 key 为 0
        $freight_map[0] = lang('label_customer');
        
        $order_list = array();
        
        $page_status = Order_enum::STATUS_SHIPPING;
        $page_storehouse = "ALL";
        $page_order_id = null;
        $page_uid = null;
        $page_store_id = null;
        $page_tracking_num = null;
        $page_start_date = null;
        $page_end_date = null;
        
        // 输入校验
        $attr = $this->input->get();
        if (false === $attr)
        {
            $attr = array();
        }
        array_walk($attr, function(&$val)
        {
            if ($val == "")
            {
                $val = null;
            }
        });
        $rules = array(
                'status' => "in:".implode(",", array_keys($status_select)),
                //'storehouse' => "in:".implode(",", array_keys($storehouse_map)),
                'order_id' => "max:19",
                //'uid' => "integer",
                //'store_id' => "integer",
                'tracking_num' => "max:64",
                'start_date' => "date",
                'end_date' => "date",
                'page' => "integer|min:1",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $this->_viewData['err_msg'] = $this->validator->get_err_msg();
            goto end;
        }
        
        // 搜索条件
        $url_param = array();
        if (isset($attr['status']))
        {
            $page_status = $attr['status'];
            $url_param['status'] = $page_status;
        }
        /* if (isset($attr['storehouse']))
        {
            $page_storehouse = $attr['storehouse'];
            $url_param['storehouse'] = $page_storehouse;
        } */
        if (isset($attr['order_id']))
        {
            $page_order_id = $attr['order_id'];
            $url_param['order_id'] = $page_order_id;
        }
        /*  if (isset($attr['uid']))
        {
            $page_uid = $attr['uid'];
            $url_param['uid'] = $page_uid;
        }
        if (isset($attr['store_id']))
        {
            $page_store_id = $attr['store_id'];
            $url_param['store_id'] = $page_store_id;
        } */
        if (isset($attr['tracking_num']))
        {
            $page_tracking_num = $attr['tracking_num'];
            $url_param['tracking_num'] = $page_tracking_num;
        }
        if (isset($attr['start_date']))
        {
            $page_start_date = $attr['start_date'];
            $url_param['start_date'] = $page_start_date;
        }
        if (isset($attr['end_date']))
        {
            $page_end_date = $attr['end_date'];
            $url_param['end_date'] = $page_end_date;
        }
        
        // 查询数据库的参数
        $query_status = $page_status == 0 ? null : $page_status;
        $query_storehouse = $page_storehouse == "ALL" ? null : $page_storehouse;
        $query_start_date = isset($page_start_date) ? date("Y-m-d H:i:s", strtotime($page_start_date)) : null;
        $query_end_date = isset($page_end_date) ? date("Y-m-d H:i:s", strtotime($page_end_date) + 86400 - 1) : null;
        
        // 获取分页数据
        $page_size = 25;
        $count = $this->m_trade->get_order_count_by_attr(
                $query_status,
                $query_storehouse,
                $page_order_id,
                $page_uid,
                $page_store_id,
                $page_tracking_num,
                $query_start_date,
                $query_end_date,
                $this->supplier['supplier_id']
        );
        if ($count < $page_size) {
            $total_page = 1;
        } else if ($count % $page_size == 0) {
            $total_page = $count / $page_size;
        } else {
            $total_page = (int)($count / $page_size) + 1;
        }
        
        // 当前页不能小于 1 ，且不能大于最大页
        $cur_page = isset($attr['page']) ? min(max($attr['page'], 1), $total_page) : 1;
        
        $order_list = $this->m_trade->get_order_list_by_attr(
                $page_size * ($cur_page - 1),
                $page_size,
                $query_status,
                $query_storehouse,
                $page_order_id,
                $page_uid,
                $page_store_id,
                $page_tracking_num,
                $query_start_date,
                $query_end_date,
                $this->supplier['supplier_id']
        );         
        
        $this->load->library('pagination');
        $url = 'supplier/order/order_list';
        add_params_to_url($url, $url_param);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $count;
        $config['per_page'] = $page_size;
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['paginate'] = $this->pagination->create_links(true);
        
        end:
        $this->_viewData['title'] = lang('admin_trade_order');
        
        $this->_viewData['page_data'] = array(
                'status' => $page_status,
                'storehouse' => $query_storehouse,
                'order_id' => $page_order_id,
                'uid' => $page_uid,
                'store_id' => $page_store_id,
                'tracking_num' => $page_tracking_num,
                'start_date' => $page_start_date,
                'end_date' => $page_end_date,
        );
        
        $this->_viewData['status_select'] = $status_select;
        $this->_viewData['status_map'] = $status_map;
        //$this->_viewData['storehouse_map'] = $storehouse_map;
        $this->_viewData['freight_map'] = $freight_map;
        
        $this->_viewData['order_list'] = $order_list;
        
        parent::index('supplier/','order_list');
    }

    /* 页面-获取订单详情 */
    public function get_order_info($order_id)
    {
        // 订单类型 map
        $this->_viewData['prop_map'] = array(
                0 => lang('admin_order_prop_normal'),
                1 => lang('admin_order_prop_component'),
                2 => lang('admin_order_prop_merge'),
        );
    
        // 收货时间 map
        $this->_viewData['deliver_time_map'] = array(
                1 => lang('checkout_deliver_time_period1'),
                2 => lang('checkout_deliver_time_period2'),
                3 => lang('checkout_deliver_time_period3'),
        );
    
        // 收据 map
        $this->_viewData['receipt_map'] = array(
                0 => lang('admin_order_receipt_0'),
                1 => lang('admin_order_receipt_1'),
        );
    
        // 支付方式 map
        $this->_viewData['payment_map'] = array(
                0 => lang('admin_order_payment_unpay'),
                1 => lang('admin_order_payment_group'),
                2 => lang('admin_order_payment_coupon'),
                105 => lang('admin_order_payment_alipay'),
                106 => lang('admin_order_payment_unionpay'),
                107 => lang('admin_order_payment_paypal'),
                108 => lang('admin_order_payment_ewallet'),
                109 => lang('admin_order_payment_yspay'),
                110 => lang('admin_order_payment_amount'),
                111 => lang('payment_111'),
        );
    
        // 折扣 map
        $this->_viewData['discount_map'] = array(
                0 => lang('admin_order_dicount_no'),
                1 => lang('admin_order_dicount_get'),
                2 => lang('admin_order_dicount_use'),
        );
    
        // status map
        $this->_viewData['status_map'] = array(
                0 => lang('admin_order_status_all'),
                Order_enum::STATUS_INIT => lang('admin_order_status_init'),
                Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
                Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
                Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
                Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
                Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
                Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
                Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
                Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
        );
    
        $order_data = $this->m_trade->get_order_data($order_id);
        if (false == $order_data)
        {
            $this->_viewData['err_msg'] = "get tarde_orders id {$order_id} row failed";
        }
        $this->_viewData['data'] = $order_data;
    
        $this->load->view('supplier/order_info', $this->_viewData);
    }
    
    /* 页面-打印订单  */
    public function order_shipping_print($order_id)
    {
        $code_file_map = array(
                101 => "supplier/shipping_print_szyd",
        );
    
        $order_data = $this->m_trade->get_order_data($order_id);
        if (isset($order_data['order_detail']['area']) && $order_data['order_detail']['area'] == 156)
        {
            $phone = $order_data['order_detail']['phone'];
            $reserve_num = $order_data['order_detail']['reserve_num'];
            $recipient_company = $order_id;
    
            list($country, $province, $city) = explode(" ", $order_data['order_detail']['address']);
            // 直辖市：北京、天津、重庆
            if ($province == "北京市" || $province == "天津市" || $province == "重庆市")
            {
                $city = $province;
            }
            
            //获取供应商的信息
            $supplier_info=$this->m_goods->get_supplier_info($this->supplier['supplier_id']);
            $page_data = array(
                    // 寄件信息
                    'sender_name' => $supplier_info['supplier_user'],
                    'send_company' => $supplier_info['supplier_name'],
                    'send_address' => $supplier_info['supplier_address'],
                    'send_postal' => '000000',
                    'send_phone' => $supplier_info['supplier_phone'],
    
                    // 收件信息
                    'recipient_name' => $order_data['order_detail']['consignee'],
                    'recipient_city' => $city,
                    'recipient_company' => $recipient_company,
                    'recipient_address' => $order_data['order_detail']['address'],
                    'recipient_postal' => "",
                    'recipient_phone' => $phone,
                    'recipient_reserve_num' => $reserve_num,
                    'order_id' => $order_id,
    
                    // 品名
                    'description' => "商品",
            );
    
            $view_file = $code_file_map[101];
        }
        else
        {
            $view_file = "supplier/shipping_print_invalid";
        }
    
        $this->load->view($view_file, $page_data);
    }
    
    /* 获取订单信息 */
    public function get_order_data($order_id)
    {
        $ret_data = array(
                'code' => 0,
                'data' => array(),
        );
    
        if (empty($order_id))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
    
        $order_data = $this->m_trade->get_order_data($order_id);
        if (empty($order_data))
        {
            $ret_data['code'] = 103;
            log_message('error', "[get_order_data] get data failed");
            echo json_encode($ret_data);
            exit;
        }
    
        $ret_data['data']['consignee'] = $order_data['order_detail']['consignee'];
        $ret_data['data']['phone'] = $order_data['order_detail']['phone'];
        $ret_data['data']['reserve_num'] = $order_data['order_detail']['reserve_num'];
        $ret_data['data']['address'] = $order_data['order_detail']['address'];
        $ret_data['data']['deliver_fee'] = $order_data['order_detail']['deliver_fee'];
    
        echo json_encode($ret_data);
        exit;
    }
    
    /* 页面-更新订单 */
    public function order_modify($order_id, $url)
    {
        // 收货时间 map
        $this->_viewData['deliver_time_map'] = array(
                1 => lang('checkout_deliver_time_period1'),
                2 => lang('checkout_deliver_time_period2'),
                3 => lang('checkout_deliver_time_period3'),
        );
    
        $order_data = $this->m_trade->get_order_data($order_id);
        if (empty($order_data))
        {
            $this->_viewData['err_msg'] = "trade_orders id {$order_id} row not found";
        }
        $this->_viewData['data'] = $order_data;
    
        $this->_viewData['url'] = base_url(urldecode($url));
        $this->_viewData['cur_icon'] = $this->m_currency->get_icon_by_currency($order_data['order_detail']['currency'])['icon'];
    
        $this->_viewData['title'] = lang('admin_trade_order');
    
        parent::index('supplier/', 'order_modify');
    }
    
    /* 页面-订单备注管理 */
    public function order_remark_manager($order_id, $url)
    {
        $remark_data = $this->m_trade->get_order_remark_data($order_id);
        if ($remark_data['code'] != 0)
        {
            $this->_viewData['err_msg'] = "tarde_orders id {$order_id} row not found";
        }
    
        $this->_viewData['url'] = base_url(urldecode($url));
        $this->_viewData['title'] = lang('admin_trade_order');
    
        $this->_viewData['order_id'] = $order_id;
        $this->_viewData['customer'] = $remark_data['customer'];
        $this->_viewData['system'] = $remark_data['system'];
    
        parent::index('supplier/', 'order_remark');
    }
    
    /* 修改订单提交 */
    public function do_order_modify()
    {
        $ret_data = array(
                'code' => 0,
                'msg' => "",
        );
    
        $attr = $this->input->post();
        $rules = array(
                'order_id' => "required|max:19",
                'consignee' => "required|max:255",
                'phone' => "required_without:reserve_num|between:6,16",
                'reserve_num' => "required_without:phone|between:6,16",
                'address' => "required|max:512",
                'zip_code' => "max:16",
                'customs_clearance' => "max:32",
                'deliver_time_type' => "required|in:1,2,3",
                'deliver_fee' => "required|numeric",
                'expect_deliver_date' => "date",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            $ret_data['msg'] = $this->validator->get_err_msg();
            echo json_encode($ret_data);
            exit;
        }
    
        // 预计发货日期不能早于当天日期
        if (!empty($attr['expect_deliver_date']) && strtotime($attr['expect_deliver_date']) < strtotime(date("Y-m-d")))
        {
            $ret_data['code'] = 101;
            $ret_data['msg'] = lang('admin_order_expect_deliver_date_invalid');
            echo json_encode($ret_data);
            exit;
        }
    
        if (FALSE === $this->m_trade->order_admin_modify($attr,$this->_adminInfo['id']))
        {
            $ret_data['code'] = 103;
            $ret_data['msg'] = "system error";
            echo json_encode($ret_data);
            exit;
        }
    
        echo json_encode($ret_data);
        exit;
    }
    
    /* 取消订单提交检测  */
    public function do_cancel_order_check(){
        $ret_data = array(
                'code' => 0,
                'refundHtml' => '',
        );
    
        $attr = $this->input->post();
    
        //非普通订单状态
        $group_status = array(1,2,3,4);
    
        $rules = array(
                'order_id' => "required|max:19",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
    
        //获得订单类型
        $this->load->model('m_split_order');
        $order_type = $this->m_split_order->get_order_type($attr['order_id']);
        $this->load->model('m_order');
    
        //如果是套装订单
        if(in_array($order_type,$group_status))
        {
            if($attr['status'] == '99'){
                $status_arr = array('3');
            }else if($attr['status'] == '98'){
                $status_arr = array('4','5','6');
            }
            $orderInfo = $this->m_trade->get_order_info($attr['order_id']);
            if ((in_array($orderInfo['status'],$status_arr))) {
                $ret_data['refundHtml'] = '<p style="background-color: rgb(81, 197, 114);
padding: 0px 10px 0px 10px;
margin: 0px 10px 0px 0px">
				' . lang('refund') . ':&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="refund_type" value="2" checked="checked" />' . lang('refund_coupons') . ' &nbsp;&nbsp;
				<input type="radio" name="refund_type" value="3" />' . lang('only_cancel') . '
				</p>';
            }
        }
        else
        {
            //todo terry.
            $orderInfo = $this->m_trade->get_order_info($attr['order_id']);
            if (in_array($orderInfo['status'], array(3, 4, 5, 6, 98)) && $orderInfo['discount_type'] == '0') {
                $ret_data['refundHtml'] = '<p style="background-color: rgb(81, 197, 114);
padding: 0px 10px 0px 10px;
margin: 0px 10px 0px 0px">
				' . lang('refund') . ':&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="refund_type" value="1" checked="checked" />' . lang('amount_pool') . ' &nbsp;&nbsp;
				<input type="radio" name="refund_type" value="0" />' . lang('no_refund') . '
				</p>';
            }
        }
    
    
        echo json_encode($ret_data);
        exit;
    }
    
    /* 取消订单提交  */
    public function do_cancel_order()
    {
        $ret_data = array(
                'code' => 0,
        );
    
        $attr = $this->input->post();
        $order_id = $attr['order_id'];
        $rules = array(
                'order_id' => "required|max:19",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
        if(TRUE === $this->m_trade->is_locked($attr['order_id'],$this->_adminInfo['id'])){
            $ret_data['code'] = 112;
            echo json_encode($ret_data);
            exit;
        }
        /*事务开始*/
        $this->db->trans_start();
    
        //取消普通订单(退款到现金池)
        if($attr['refund_type']=='1'){
            $this->load->model('m_order');
            if(!$this->m_order->get_order_type($attr['order_id'])){
                $orderInfo = $this->m_trade->get_order_info($attr['order_id']);
                if(in_array($orderInfo['status'],array(3,4,5,6,98)) && $orderInfo['discount_type'] == '0'){
                    $this->m_order->refundOrderToTpsAmount($attr['order_id'],$orderInfo['customer_id'],$orderInfo['order_amount_usd']/100);
                }
            }
        }
    
        //取消其他类型订单(退还代品券)
        if($attr['refund_type'] == '2'){
//            $order = $this->db->query("select * from trade_orders WHERE order_id='$order_id'")->row_array();
            $this->load->model("tb_trade_orders");
            $order = $this->tb_trade_orders->get_one("*",["order_id"=>$order_id]);
            if(empty($order))
            {
                exit();
            }
    
            if($attr['status'] == '99'){
                $status_arr = array('3');
            }else if($attr['status'] == '98'){
                $status_arr = array('4','5','6');
            }
    
            if(!in_array($order['status'],$status_arr))
            {
                exit();
            }
    
            //商品金额
            $customer_id = $order['customer_id'];
            $goods_amount_usd = $order['goods_amount_usd']/100;
            $this->load->model("m_suite_exchange_coupon");
            $this->m_suite_exchange_coupon->add_voucher($customer_id,$goods_amount_usd);
        }
    
        /*更改订单状态*/
        $update_attr = array(
                'status' => $attr['status'],
        );
        if (TRUE != $this->m_trade->order_modify($attr['order_id'], $update_attr))
        {
            $ret_data['code'] = 103;
            log_message('error', "[get_order_data] order cancel failed, id: {$attr['order_id']}");
            echo json_encode($ret_data);
            exit;
        }
    
        /*事务结束*/
        $this->db->trans_complete();
    
        echo json_encode($ret_data);
        exit;
    }
    
    /* 确认发货  */
    public function do_order_deliver()
    {
        $ret_data = array(
                'code' => 0,
        );
    
        // 快递公司 map
        $freight_list = $this->m_trade->get_freight_list();
        $freight_map = array();
        foreach ($freight_list as $v)
        {
            $freight_map[$v['company_code']] = $v['company_name'];
        }
        $freight_map[0] = lang('label_customer');
    
        $attr = $this->input->post();
        $rules = array(
                'order_id' => "required|alpha_num",
                'company_code' => "required|in:".implode(",", array_keys($freight_map)),
                'express_id' => "required",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
    
        // 获取订单信息
        $order_info = $this->m_trade->get_order_data($attr['order_id']);
        if (empty($order_info))
        {
            $ret_data['code'] = 103;
            echo json_encode($ret_data);
            exit;
        }
    
        // 只有等待发货
        if (Order_enum::STATUS_SHIPPING != $order_info['order_detail']['status'] &&
                Order_enum::STATUS_SHIPPED != $order_info['order_detail']['status'])
        {
            $ret_data['code'] = 103;
            echo json_encode($ret_data);
            exit;
        }

        $freight_info = $attr['company_code']."|".$attr['express_id'];
        $freight_info = trim($freight_info,'|');
        $update_attr = array(
                'status' => "4",
                'freight_info' => $freight_info,
                'deliver_time' => date("Y-m-d H:i:s"),
        );

        /*事务开始*/
        $this->db->trans_start();

        if (TRUE != $this->m_trade->order_modify($attr['order_id'], $update_attr))
        {
            $ret_data['code'] = 103;
            log_message('error', "[get_order_data] order deliver failed, id: {$attr['order_id']}, attr: ".var_export($update_attr, true));
            echo json_encode($ret_data);
            exit;
        }
    
        $this->m_trade->paypal_order_deliver($attr['order_id'],$attr);
    
        //$admin_user = unserialize(get_cookie("adminUserInfo"));
        $statement = "{$freight_map[$attr['company_code']]} {$attr['express_id']}";
        $this->m_trade->add_order_log($attr['order_id'], 102, $statement, $this->supplier['supplier_id']);

        $this->db->trans_complete();//事务结束

        echo json_encode($ret_data);
        exit;
    }
    
    /* 检查订单是否已经存在物流单号  */
    public function check_order_shipping_status()
    {
        $ret_data = array(
                'code' => 1,
        );
    
        $order=$this->input->post();
    
        if (!empty($order['order_id']))
        {
            if (!$this->m_trade->check_shipping_status($order['order_id']))
            {
                $ret_data['code'] = 0;
            }
        }
    
        echo json_encode($ret_data);
        exit;
    }
    
    /* 添加备注信息提交  */
    public function do_add_order_remark()
    {
        $ret_data = array(
                'code' => 0,
        );
    
        if (!isset($this->supplier['supplier_id']))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
    
        $attr = $this->input->post();
        $rules = array(
                'order_id' => "required|max:19",
                'type' => "required|in:1,2",
                'remark' => "required|max:255",
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            echo json_encode($ret_data);
            exit;
        }
    
        $insert_attr = array(
                'order_id' => $attr['order_id'],
                'type' => $attr['type'],
                'remark' => 'Supplier Add:'.$attr['remark'],
                'admin_id' => $this->supplier['supplier_id'],
        );
        if (TRUE != $this->m_trade->add_order_remark_record($insert_attr))
        {
            $ret_data['code'] = 103;
            echo json_encode($ret_data);
            exit;
        }
    
        echo json_encode($ret_data);
        exit;
    }
    
    /****************************** 订单修复 ******************************/
    
    /**
     * 订单修复
     */
    public function order_repair()
    {
        // 输入参数
        $attr = $this->input->get();
        if (false === $attr)
        {
            $attr = array();
        }
    
        // 标签栏映射表
        $tabs_map = array(
                1 => array(
                        'desc' => lang('admin_trade_repair_modify'),
                        'url' => "admin/trade/order_repair?tabs_type=1",
                ),
                2 => array(
                        'desc' => lang('admin_trade_repair_component'),
                        'url' => "admin/trade/order_repair?tabs_type=2",
                ),
                3 => array(
                        'desc' => lang('admin_trade_repair_rollback'),
                        'url' => "admin/trade/order_repair?tabs_type=3",
                ),
        );
        $this->_viewData['tabs_map'] = $tabs_map;
    
        // 标签类型
        if (isset($attr['tabs_type']) && isset($tabs_map[$attr['tabs_type']]))
        {
            $tabs_type = $attr['tabs_type'];
        }
        else
        {
            $tabs_type = 1;
        }
        $this->_viewData['tabs_type'] = $tabs_type;
    
        // 状态映射表
        $this->_viewData['status_map'] = array(
                Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
                Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
                Order_enum::STATUS_SHIPPING => array('class' => "text-warning", 'text' => lang('admin_order_status_paied')),
                Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
                Order_enum::STATUS_EVALUATION => array('class' => "text-success", 'text' => lang('admin_order_status_arrival')),
                Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
                Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
                Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
                Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
        );
    
    
        // 根据不同标签类型获取不同数据
        switch ($tabs_type)
        {
            case 1:
                if (isset($attr['order_id']) && $attr['order_id'] != "")
                {
                    // 订单 id
                    $this->_viewData['order_id'] = $attr['order_id'];
    
                    // 订单数据
                    $order_info = $this->m_trade->get_order_data_efficient($attr['order_id']);
                    if (empty($order_info))
                    {
                        $this->_viewData['err_msg'] = "tarde order id {$attr['order_id']} row not found";
                    }
                    $this->_viewData['order_info'] = $order_info;
                }
    
                $page = "trade_repair_modify";
                break;
    
            case 2:
                $page = "trade_repair_component";
                break;
    
            case 3:
                $page = "trade_repair_rollback";
                break;
    
            default:
                redirect(base_url('admin/trade/order_repair'));
        }
    
        // 公共 view data
        $this->_viewData['title'] = lang('admin_trade_repair');
    
        parent::index('admin/', $page);
    }
    
    /****************************** 导入订单 ******************************/
    
    /**
     * 导入订单
     */
    public function order_import()
    {
        $this->_viewData['title'] = lang('admin_trade_order_import');
    
        parent::index('admin/', 'order_import');
    }
    
    /**
     * 批量导入运单号
     */
    public function admin_upload_freight_info()
    {
        if (isset($_FILES['excelfile']['type']) == null)
        {
            $result['msg'] = lang('admin_file_format');
            $result['success'] = 0;
            die(json_encode($result));
        }
        $mime = $_FILES['excelfile']['type'];
        $path = $_FILES['excelfile']['tmp_name'];
    
        /* 读取数据 */
        $order_data = readExcel($path, $mime);
    
        // 去掉第一行的标题
        unset($order_data[1]);
    
        if ($order_data === FALSE)
        {
            $result['msg'] = lang('admin_file_format');
            $result['success'] = 0;
            die(json_encode($result));
        }
        if (empty($order_data))
        {
            $result['msg'] = lang('admin_file_data');
            $result['success'] = 0;
            die(json_encode($result));
        }
    
        /* 检测数据 */
        $order_error = $line_error = $freight_error = array();
    
        foreach ($order_data as $line => $order_item)
        {
            if (isset($order_item[0]) && isset($order_item[1]) && isset($order_item[2]))
            {
                // 订单存在，且是等待发货,物流信息为空
//                $count = $this->db
//                ->from('trade_orders')
//                ->where('order_id', $order_item[0])
//                ->where('status',  Order_enum::STATUS_SHIPPING)
//                ->where('freight_info is null')
//                ->count_all_results();
                $this->load->model("tb_trade_orders");
                $count = $this->tb_trade_orders->get_counts([
                    'order_id'=>$order_item[0],
                    'status'=>Order_enum::STATUS_SHIPPING,
                    'freight_info is null'=>null,
                ]);
                if ($count === 0)
                {
                    $order_error[] = $order_item[0];
                }
                // 物流號對應的公司
                $freight_count = $this->db
                ->from('trade_freight')
                ->where('company_code', $order_item[1])
                ->count_all_results();
                if ($freight_count === 0)
                {
                    $freight_error[] =  $order_item[1];
                }
            }
            else if (!isset($order_item[0]) && !isset($order_item[1]) && !isset($order_item[2]))
            {
                unset($order_data[$line]);
                continue; //整行为空，跳过。
            }
            else
            {
                $line_error[] = $line;
            }
        }
        if (!empty($line_error))
        {
            $result['success'] = 0;
            $result['msg'] = sprintf(lang('admin_file_not_full'), implode('，', $line_error));
            die(json_encode($result));
        }
        if (!empty($freight_error))
        {
            $result['success'] = 0;
            $result['msg'] = sprintf(lang('admin_file_not_freight'), implode('，', $freight_error));
            die(json_encode($result));
        }
        if (!empty($order_error))
        {
            $result['success'] = 0;
            $result['msg'] = sprintf(lang('admin_file_order_status'), implode('，', $order_error));
            die(json_encode($result));
        }
    
        $update_data = array();
    
        /* 检测完成，批量修改订单的物流信息 */
        $deliver_date = date('Y-m-d H:i:s', time());
        foreach ($order_data as $order_item)
        {
            $freight_info = trim($order_item[1].'|'.$order_item[2],'|');
            $update_data[] = array(
                    'order_id' => "$order_item[0]",
                    'freight_info' => $freight_info,
                    'status' => Order_enum::STATUS_SHIPPED,
                    'deliver_time' => $deliver_date,
            );
            $this->m_trade->paypal_order_deliver($order_item[0],array('company_code'=>$order_item[1],'express_id'=>$order_item[2]));
        }
    
//        $this->db->update_batch('trade_orders', $update_data, 'order_id');
        $this->load->model("tb_trade_orders");
        $this->tb_trade_orders->update_batch_auto([
           "data"=>$update_data,
            "index"=>"order_id"
        ]);
        $result['success'] = 1;
        $result['msg'] = lang('update_success');
        die(json_encode($result));
    }
    
    /* 扫描发货 */
    public function scan_shipping() {
        // 快递公司 map
        $freight_list = $this->m_trade->get_freight_list();
        $freight_map = array();
        foreach ($freight_list as $v)
        {
            $freight_map[$v['company_code']] = $v['company_name'];
        }
        // 自定义 key 为 0
        $freight_map[0] = lang('label_customer');
        
        $this->_viewData['freight_map'] = $freight_map;
        
        $this->load->view('supplier/scan_shipping', $this->_viewData);
    }
}