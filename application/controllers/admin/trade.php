<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends MY_Controller
{
    protected $DEBUG = TRUE;
	public function __construct()
	{
		parent::__construct();
        $this->load->model('m_order');
        $this->load->model('m_trade');
        $this->load->model('m_log');
        $this->load->model('tb_trade_user_address');
        $this->load->model('m_group');
        $this->load->model('m_global');
	}

    /**
     * 调试信息输出
     * @param $msg
     * @param int $start
     */
    public function debug($msg,$start=0)
    {
        if($this->DEBUG || $start)
        {
            $this->load->model("tb_empty");
            $redis_key = "trade:debug:".date("Ymdh");
            $this->tb_empty->redis_lPush($redis_key,date("Y-m-d H:i:s").":".$msg);
            if($this->tb_empty->redis_ttl($redis_key) == -1)
            {
                $this->tb_empty->redis_setTimeout($redis_key,60*60);
            }
        }
    }

    /****************************** 订单管理 ******************************/

	/*
	 * 订单列表，默认
	 */
	public function index()
	{
		// 状态映射表
		$status_select = array(
			0 => lang('admin_order_status_all'),
			Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
			Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
			Order_enum::STATUS_INIT=>lang('admin_order_status_init'),
			Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
			Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
			Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
			Order_enum::STATUS_HOLDING =>lang("admin_order_status_holding"),
			Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
			Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
			Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
		    Order_enum::STATUS_DOBA_EXCEPTION => lang('admin_order_status_doba_exception')
		);
		$status_map = array(
			Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
			Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
			Order_enum::STATUS_SHIPPING => array('class' => "text-warning", 'text' => lang('admin_order_status_paied')),
			Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
			Order_enum::STATUS_EVALUATION => array('class' => "text-success", 'text' => lang('admin_order_status_arrival')),
			Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
			Order_enum::STATUS_HOLDING => array('class' => "text-holding", 'text' => lang('admin_order_status_holding')),
			Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
			Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
			Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
			Order_enum::STATUS_COMPONENT => array('class' => "text-default", 'text' => lang('admin_order_status_component')),
		    Order_enum::STATUS_DOBA_EXCEPTION => array('class' => "text-default", 'text' => lang('admin_order_status_doba_exception'))
		);
		//类型映射
		$order_type = array(
            '0' => lang('all_group'),
            '1' => lang('choose_group'),
            '2' => lang('admin_as_upgrade_order'),
            '3' => lang('generation_group'),
            '4' => lang('retail_group'),
            '5' => lang('exchange_order')
		);

		// 仓库列表
		$shipper_list = $this->m_trade->get_storehouse_list();
		$storehouse_map = array("ALL" => lang('admin_oper_shipper_ALL'));

		foreach ($shipper_list as $v)
		{
			$storehouse_map[$v['supplier_id']] = $v['supplier_name'];
		}

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

		$page_status = 0;//Order_enum::STATUS_SHIPPING;
		$page_storehouse = "ALL";
		$page_order_id = null;
		$page_uid = null;
		$page_store_id = null;
                $page_express = null;
		$page_tracking_num = null;
		$page_start_date = null;
		$page_end_date = null;
		$txn_id = null;
		$query_storehouse =null;
		$page_order_type = null;

		// 输入校验
		$attr = $this->input->get();

		$to_query = false;
		if(!$attr)
		{
			$to_query = false;

		}else{

			foreach($attr as $key=>$val){

				if($val && $key!='storehouse')
				{
					$to_query = true;
					break;

				}elseif($key=='storehouse' && $val!='ALL')
				{
					$to_query = true;
					break;
				}
			}

		}

		$attr['order_id'] = isset($attr['order_id'])?trim($attr['order_id']):'';
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
			'storehouse' => "in:".implode(",", array_keys($storehouse_map)),
			'order_id' => "max:19",
			'uid' => "integer",
			'txn_id' => "max:64",
			'store_id' => "integer",
			'tracking_num' => "max:64",
			'start_date' => "date",
			'end_date' => "date",
			'page' => "integer|min:1",
		);

		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$this->_viewData['err_msg'] = $this->validator->get_err_lang_msg();
			goto end;
		}

        if($_GET){
            if($attr['express'] || $attr['tracking_num']){
                if(empty($attr['express']) || empty($attr['tracking_num'])){
                    $this->_viewData['err_msg'] = lang("error_express");
                    goto end;
                }
            }
        }
        //echo "<pre>";print_r($attr);exit;
          
		// 搜索条件
		$url_param = array();
		if (isset($attr['status']))
		{
			$page_status = $attr['status'];
			$url_param['status'] = $page_status;
		}
        if (isset($attr['order_type']))
		{
			$page_order_type = $attr['order_type'];
			$url_param['order_type'] = $page_order_type;
		}
		if (isset($attr['storehouse']))
		{
			$page_storehouse = $attr['storehouse'];
			$url_param['storehouse'] = $page_storehouse;
		}
		if (isset($attr['order_id']))
		{
			$page_order_id = $attr['order_id'];
			$url_param['order_id'] = $page_order_id;
		}
		if (isset($attr['txn_id']))
		{
			$txn_id = $attr['txn_id'];
			$url_param['txn_id'] = $txn_id;
		}
		if (isset($attr['uid']))
		{
			$page_uid = $attr['uid'];
			$url_param['uid'] = $page_uid;
		}
		if (isset($attr['store_id']))
		{
			$page_store_id = $attr['store_id'];
			$url_param['store_id'] = $page_store_id;
		}
                if (isset($attr['express']))
		{
			$page_express = $attr['express'];
			$url_param['express'] = $page_express;
		}
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
//		else{
//            $page_start_date = date('Y-m-d',strtotime('-7 days'));
//            $url_param['start_date'] = $page_start_date;
//        }
		if (isset($attr['end_date']))
		{
			$page_end_date = $attr['end_date'];
			$url_param['end_date'] = $page_end_date;
		}

		// 查询数据库的参数
		$query_status = $page_status == 0 ? null : $page_status;
		$query_order_type = $page_order_type == 0 ? null : $page_order_type;
		$query_storehouse = $page_storehouse == "ALL" ? null : $page_storehouse;
		$query_start_date = isset($page_start_date) ? date("Y-m-d H:i:s", strtotime($page_start_date)) : null;
		$query_end_date = isset($page_end_date) ? date("Y-m-d H:i:s", strtotime($page_end_date) + 86400 - 1) : null;

		// 获取分页数据
		$page_size = 15;
		$count = $to_query ? $this->m_trade->get_order_count_by_attrinfo(
			$query_status,
			$query_storehouse,
            $query_order_type,
			$page_order_id,
			$page_uid,
			$page_store_id,
            $page_express,
			$page_tracking_num,
			$query_start_date,
			$query_end_date,
			null,
			$txn_id
		) : 0;

		if ($count < $page_size) {
			$total_page = 1;
		} else if ($count % $page_size == 0) {
			$total_page = $count / $page_size;
		} else {
			$total_page = (int)($count / $page_size) + 1;
		}

		// 当前页不能小于 1 ，且不能大于最大页
		$cur_page = isset($attr['page']) ? min(max($attr['page'], 1), $total_page) : 1;

		$order_list = $to_query ? $this->m_trade->get_order_list_by_attrinfo(
			$page_size * ($cur_page - 1),
			$page_size,
			$query_status,
			$query_storehouse,
            $query_order_type,
			$page_order_id,
			$page_uid,
			$page_store_id,
            $page_express,
			$page_tracking_num,
			$query_start_date,
			$query_end_date,
			null,
			$txn_id,
			$this->_adminInfo['role']
		) : '';

		$this->load->library('pagination');
		$url = 'admin/trade';
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
            'order_type' => $page_order_type,
			'storehouse' => $query_storehouse,
			'order_id' => $page_order_id,
			'uid' => $page_uid,
			'store_id' => $page_store_id,
                        'express' => $page_express,
			'tracking_num' => $page_tracking_num,
			'start_date' => $page_start_date,
			'end_date' => $page_end_date,
			'txn_id' => $txn_id,
		);

		$this->_viewData['status_select'] = $status_select;
		$this->_viewData['status_map'] = $status_map;
		if(in_array($this->_adminInfo['role'],array(6,7))){
			$this->_viewData['storehouse_map'] = array();
		}else{
			$this->_viewData['storehouse_map'] = $storehouse_map;
		}
		$this->_viewData['order_type'] = $order_type;
		$this->_viewData['freight_map'] = $freight_map;
		$this->_viewData['order_list'] = $order_list;
		$this->_viewData['error_code'] = $to_query ? 1001 : 1003;

		parent::index('admin/', 'trade_order');
	}

	public function get_order_info($order_id)
	{
		// 订单类型 map
		$this->_viewData['prop_map'] = array(
			0 => lang('admin_order_prop_normal'),
			1 => lang('admin_order_prop_component'),
			2 => lang('admin_order_prop_merge'),
		);
        //类型
        $this->_viewData['order_type_map'] = array(
            '1' => lang('choose_group'),
            '2' => lang('admin_as_upgrade_order'),
            '3' => lang('generation_group'),
            '4' => lang('retail_group'),
            '5' => lang('exchange_order')
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
			104 => 'WxPay(微信支付)',
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
			Order_enum::STATUS_HOLDING => lang('admin_order_status_holding'),
			Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
			Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
			Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
		    Order_enum::STATUS_DOBA_EXCEPTION => lang('admin_order_status_doba_exception')
		);

		$order_data = $this->m_trade->get_order_data($order_id);
		if (false == $order_data)
		{
			$this->_viewData['err_msg'] = "get trade_orders id {$order_id} row failed";
		}
		$this->_viewData['data'] = $order_data;

		$this->load->view('admin/order_info', $this->_viewData);
	}

	public function order_shipping_print($order_id)
	{
		$code_file_map = array(
			101 => "admin/shipping_print_szyd",
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

			$page_data = array(
				// 寄件信息
				'sender_name' => "云集品",
				'send_company' => "深圳前海云集品电子商务有限公司",
				'send_address' => "陈锦华 440881199004086513",
				'send_postal' => "518000",
				'send_phone' => "0755-33198568",

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
			$view_file = "admin/shipping_print_invalid";
		}

		$this->load->view($view_file, $page_data);
	}

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

		parent::index('admin/', 'order_modify');
	}

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

		parent::index('admin/', 'order_remark');
	}

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

        //备注框
        $placeholder = lang('pls_input_reson');
        $remark = "<br><textarea id='cancel_remark' placeholder='$placeholder'></textarea><br><span id='cancel_order_msg' style='color: #ff0000'></span>";

		$orderInfo_o = $this->m_trade->get_order_data_efficient($attr['order_id']);
//print_r($orderInfo);exit;
		//如果是套装订单
		if(in_array($order_type,$group_status))
		{
			if($attr['status'] == '99'){
				$status_arr = array('1','3');
			}else if($attr['status'] == '98'){
				$status_arr = array('1','4','5','6');
			}
			$orderInfo = $this->m_trade->get_order_data_efficient($attr['order_id']);
			$cancel_tip = $orderInfo['status']==1 ?  lang('no_cancel_order') : '';
            //3月3日凌晨之后支付的订单、换货订单在等待发货、正在发货中、等待收货、等待评价、已完成、冻结（等待换货），这六种状态下开放“取消”按钮，提示框里面仅显示“仅取消”选项，不能退代品劵
//            print_r($orderInfo['pay_time']);exit;
            if(($orderInfo_o['pay_time'] >= config_item('upgrade_exchange') && in_array($orderInfo_o['status'], array('1', '4', '3','5','6')) && in_array($orderInfo_o['order_type'], array('2','5'))) || ($orderInfo_o['status'] == '90' && strstr($orderInfo_o['remark'],'#exchange'))){
                //备注框
                $placeholder = lang('pls_input_reson_1');
                $remark = "<br><textarea id='cancel_remark' placeholder='$placeholder'></textarea><br><span id='cancel_order_msg' style='color: #ff0000'></span>";
                if ((in_array($orderInfo['status'], $status_arr))) {
					$ret_data['refundHtml'] = '
				' . '' . $remark . '<strong style="color:red">' . $cancel_tip . '</strong>';
				}
			}else {
				if ((in_array($orderInfo['status'], $status_arr))) {
					$ret_data['refundHtml'] = '<p style="background-color: rgb(81, 197, 114);
padding: 0px 10px 0px 10px;
margin: 0px 10px 0px 0px">
				' . lang('refund') . ':&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="refund_type" value="2" checked="checked" />' . lang('refund_coupons') . ' &nbsp;&nbsp;
				<input type="radio" name="refund_type" value="3" />' . lang('only_cancel') . '
				</p>' . $remark . '<strong style="color:red">' . $cancel_tip . '</strong>';
				}
			}
		}
		else
		{
			//todo terry.
			$orderInfo = $this->m_trade->get_order_info($attr['order_id']);
			$cancel_tip = $orderInfo['status']==1 ?  lang('no_cancel_order') : '';
            //3月3日凌晨之后支付的订单、换货订单在等待发货、正在发货中、等待收货、等待评价、已完成、冻结（等待换货），这六种状态下开放“取消”按钮，提示框里面仅显示“仅取消”选项，不能退代品劵
//            print_r($orderInfo);exit;
            if(($orderInfo_o['pay_time'] >= config_item('upgrade_exchange') && in_array($orderInfo_o['status'], array('1', '4', '3','5','6')) && in_array($orderInfo_o['order_type'], array('2','5'))) || ($orderInfo_o['status'] == '90' && strstr($orderInfo_o['remark'],'#exchange'))){
                $placeholder = lang('pls_input_reson_1');
                $remark = "<br><textarea id='cancel_remark' placeholder='$placeholder'></textarea><br><span id='cancel_order_msg' style='color: #ff0000'></span>";
                if (in_array($orderInfo['status'], array(1, 3, 4, 5, 6, 98, 111)) && $orderInfo['discount_type'] == '0') {
                    $ret_data['refundHtml'] = '
				'  . '' . $remark . '<strong style="color:red">' . $cancel_tip . '</strong>';
                }
            }else {
                if (in_array($orderInfo['status'], array(1, 3, 4, 5, 6, 98, 111)) && $orderInfo['discount_type'] == '0') {
                    $ret_data['refundHtml'] = '<p style="background-color: rgb(81, 197, 114);
padding: 0px 10px 0px 10px;
margin: 0px 10px 0px 0px">
				' . lang('refund') . ':&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="refund_type" value="1" checked="checked" />' . lang('amount_pool') . ' &nbsp;&nbsp;
				<input type="radio" name="refund_type" value="0" />' . lang('no_refund') . '
				</p>' . $remark . '<strong style="color:red">' . $cancel_tip . '</strong>';
                }
            }
		}

		echo json_encode($ret_data);
		exit;
	}

	public function do_cancel_order()
	{
		$this->load->model('o_order_cancel');
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
            $ret_data['error_msg'] = "data invalidate.";
			echo json_encode($ret_data);
			exit;
		}

		if(TRUE === $this->m_trade->is_locked($attr['order_id'],$this->_adminInfo['id'])){
			$ret_data['code'] = 112;
            $ret_data['error_msg'] = "order is locked.";
			echo json_encode($ret_data);
			exit;
		}

        if(isset($attr['remark']) && trim($attr['remark']) == '') {
            $ret_data['code'] = 118;
            $ret_data['error_msg'] = "remark is empty.";
            echo json_encode($ret_data);
            exit;
        }
		$attr['remark'] = isset($attr['remark']) ?  $attr['remark'] : '';

		$orderInfo = $this->m_trade->get_order_info($attr['order_id']);

//        if(date('Y-m-d H:i:s',time()) >= config_item('upgrade_not_coupon')) {
//            //升级订单不能取消
//            if ($orderInfo['order_type'] == 2) {
//                $ret_data['code'] = 1001;
//                echo json_encode($ret_data);
//                exit;
//            }
//        }
		if(in_array($orderInfo['status'],array('2','98','99'))){
			log_message('error', "[get_order_data] order cancel failed, id: {$attr['order_id']}");
            $ret_data['error_msg'] = "status in [2,98,99], not allow";
			echo json_encode($ret_data);
			exit;
		}

		/*更改订单状态*/
		$update_attr = array(
			'status' => $attr['status'],
		);

		//校验表单是否重复提交
		if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'cancel_order_token', $attr)) {
			$ret_data['code'] = 1003;
			echo json_encode($ret_data);
			exit;
		}

        /*事务开始*/
        $this->db->trans_start();

		if (TRUE != $this->m_trade->order_modify($attr['order_id'], $update_attr))
		{
			$ret_data['code'] = 103;
			log_message('error', "[get_order_data] order cancel failed, id: {$attr['order_id']}");
			echo json_encode($ret_data);
			exit;
		}

		//取消普通订单(退款到现金池)
		if($attr['refund_type']=='1'){
			if(!$this->m_order->get_order_type($attr['order_id'])){
				if(in_array($orderInfo['status'],array('1','3','4','5','6','90','97','111')) && $orderInfo['discount_type'] == '0'){
					$this->m_order->refundOrderToTpsAmount($attr['order_id'],$orderInfo['customer_id'],$orderInfo['order_amount_usd']/100);
				}
			}
		}

		/*取消订单触发佣金抽回相关处理*/
		if(in_array($orderInfo['status'],array('1','3','4','5','6','90','97','111'))){
			$this->o_order_cancel->preWithdrawOfOrder($order_id);
		}

		//取消其他类型订单(退还代品券)
		if($attr['refund_type'] == '2'){

//			$order = $this->db->query("select * from trade_orders WHERE order_id='$order_id'")->row_array();
			$this->load->model("tb_trade_orders");
			$order = $this->tb_trade_orders->get_one_auto([
			    "where"=>["order_id"=>$order_id]
            ]);
			if(empty($order))
			{
				exit();
			}

			if($attr['status'] == '99'){
				$status_arr = array('1','3');
			}else if($attr['status'] == '98'){
				$status_arr = array('1','4','5','6');
			}

			if(!in_array($orderInfo['status'],$status_arr))
			{
				exit();
			}

			//商品金额
			$customer_id = $order['customer_id'];
			$goods_amount_usd = $order['goods_amount_usd']/100;

			$this->load->model("m_suite_exchange_coupon");
			$this->m_suite_exchange_coupon->add_voucher($customer_id, $goods_amount_usd);
		}
        //插入到trade_orders_log
        $ret = $this->m_trade->add_order_log($order_id,105,$attr['remark'],$this->_adminInfo['id']);
        if(!$ret){
            exit;
        }

        $this->load->model('m_erp');
        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $order_id;
        $insert_data['data']['status'] = $attr['status'];

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

        //只有取消订单才加库存，退货不用加库存
        if($attr['status'] == '99'){
            //后台取消订单加库存
//            $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order_id)
//                ->from('trade_orders_goods')->get()->result_array();
            $this->load->model("tb_trade_orders_goods");
            $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                ['order_id'=>$order_id]);
            $this->m_group->add_goods_number($goods, $order_id);
        }

		$this->db->trans_complete();//事务结束

        //事务回滚了
        if ($this->db->trans_status() === FALSE)
        {
            exit;
        }

        //事务提交
        else
        {
            $this->db->trans_commit();
            if(in_array($orderInfo['status'],array('1','3','4','5','6','90','97','111'))){
                $this->m_erp->wd_erp_cancel_push($order_id);
            }
            echo json_encode($ret_data);
            exit;
        }
	}

    /**
     * 等待收货
     */
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
			Order_enum::STATUS_SHIPPED != $order_info['order_detail']['status'] &&
			Order_enum::STATUS_INIT != $order_info['order_detail']['status'] &&
		    Order_enum::STATUS_DOBA_EXCEPTION != $order_info['order_detail']['status'])
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

        //同步到erp(等待收货)
//        $update_item = array(
//            'order_id' => $attr['order_id'],
//            'logistics_code'=>$attr['company_code'],
//            'tracking_no'=>$attr['express_id'],
//            'status'=>$update_attr['status'],
//            'deliver_time' => $update_attr['deliver_time']
//        );
//        $this->m_erp->update_order_to_erp_log($update_item);

        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $attr['order_id'];
        $insert_data['data']['status'] =$update_attr['status'];
        $insert_data['data']['logistics_code'] = $attr['company_code'];
        $insert_data['data']['tracking_no'] = $attr['express_id'];
        $insert_data['data']['deliver_time'] = $update_attr['deliver_time'];

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单


		/** 发货邮箱通知 */
//		$row = $this->db->select('customer_id')->where('order_id',$attr['order_id'])->get('trade_orders')->row_array();
		$this->load->model("tb_trade_orders");
		$row = $this->tb_trade_orders->get_one("customer_id",["order_id"=>$attr['order_id']]);
		$this->db->insert('sync_send_receipt_email',array('uid'=>$row['customer_id'],'order_id'=>$attr['order_id'],'type'=>1));

		$admin_user = unserialize(get_cookie("adminUserInfo"));
		$statement = "{$freight_map[$attr['company_code']]} {$attr['express_id']}";
		$this->m_trade->add_order_log($attr['order_id'], 102, $statement, $admin_user['id']);

        $this->db->trans_complete();//事务结束

		echo json_encode($ret_data);
		exit;
	}

	public function get_doba_order_info()
	{
	    $ret_data = array(
	            'code' => 0,
	    );	
	
	    $attr = $this->input->post();
	    $rules = array(
	            'doba_id' => "required|alpha_num",
	            'order_id' => "required|alpha_num",
	    );
	    if (TRUE !== $this->validator->validate($attr, $rules))
	    {
	        $ret_data['code'] = 101;
	        echo json_encode($ret_data);
	        exit;
	    }
	
	    $this->load->model('m_goods');	
	
	    if (TRUE != $this->m_goods->create_doba_info_by_hands($attr['doba_id'],$attr['order_id']))
	    {
	        $ret_data['code'] = 103;
	        log_message('error', "[get_doba_order_data] get doba order info failed, id: {$attr['doba_id']}");
	        echo json_encode($ret_data);
	        exit;
	    }	
	
	    echo json_encode($ret_data);
	    exit;
	}
	/* 检查订单是否已经存在物流单号  */
	/*public function check_order_shipping_status()
	{
	    $ret_data = array(
	            'code' => 1,
	    );

	    $order=$this->input->post();

	    if(!empty($order['order_id'])) {
	        if(!$this->m_trade->check_shipping_status($order['order_id'])) {
	            $ret_data['code'] = 0;
	        }
	    }

	    echo json_encode($ret_data);
	    exit;
	}*/

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

    /**
     * 添加订单备注
     */
	public function do_add_order_remark()
	{
		$ret_data = array(
			'code' => 0,
		);

		if (!isset($this->_viewData['adminInfo']['id']))
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
			'remark' => $attr['remark'],
			'admin_id' => $this->_viewData['adminInfo']['id'],
		);
		if (TRUE != $this->m_trade->add_order_remark_record($insert_attr))
		{
			$ret_data['code'] = 103;
			echo json_encode($ret_data);
			exit;
		}

        //同步到erp
        $this->load->model('m_erp');
        $insert_attr['recorder'] = $insert_attr['admin_id'];
        $insert_attr['created_time'] = date('Y-m-d H:i:s',time());
        unset($insert_attr['admin_id']);
//        $this->m_erp->add_remark_to_erp_log($insert_attr);

        $insert_data_remark = array();
        $insert_data_remark['oper_type'] = 'remark';
        $insert_data_remark['data'] = $insert_attr;
        $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

		echo json_encode($ret_data);
		exit;
	}

	/*****订单冻结*****/
	public function order_frozen(){
		$order_id = $this->input->post('order_id');
		$remark = $this->input->post('remark');
		if(trim($order_id) == '')
		{
			echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
			exit;
		}
		if(trim($remark) == '')
		{
			echo json_encode(array('success'=>false,'msg'=>lang('remark_not_null')));
			exit;
		}

//		$order = $this->db->query("select order_type,status,is_export_lock,remark from trade_orders WHERE order_id ='$order_id'")->row_array();
        $this->load->model("tb_trade_orders");
        $order = $this->tb_trade_orders->get_one("order_type,status,is_export_lock,remark",["order_id"=>$order_id]);
		//已经锁定的订单不能冻结
		if($order['is_export_lock'] == '1')
		{
			// 升级订单 处于“正在发货中”状态时可以冻结
			if($order['order_type']!=2 || $order['status']!=1){
				echo json_encode(array('success'=>false,'msg'=>lang('lock_order_not_can_freeze')));
				exit;
			}

		}

		//事务开始
		$this->db->trans_begin();

		//更改订单状态为冻结,并且插入备注信息
        //把订单备注的exchange单词去掉，以免和冻结带换货混淆
        $is_ex = strstr($order['remark'],"#exchange");
        $o_remark = $order['remark'];
        if($is_ex){
            $count = strpos($order['remark'],"#exchange");
            $o_remark = substr_replace($order['remark'],"",$count,9);
        }
//        print_r($o_remark)  ;exit;
//		$this->db->where('order_id', $order_id)->set('status',Order_enum::STATUS_HOLDING)->set('remark',$o_remark)->update('trade_orders');
        $this->load->model("tb_trade_orders");
        $this->tb_trade_orders->update_one(["order_id"=>$order_id],
            ["status"=>Order_enum::STATUS_HOLDING,"remark"=>$o_remark]);
		$this->db->insert('trade_order_remark_record',array(
			'order_id'=>$order_id,
			'type'=>'1',
			'remark'=>$remark,
			'admin_id'=>$this->_adminInfo['id']
		));

        //插入到trade_orders_log
        $ret = $this->m_trade->add_order_log($order_id,106,$remark,$this->_adminInfo['id']);
        if(!$ret){
            exit;
        }

        //同步到erp(订单冻结)
        $this->load->model('m_erp');
//        $update_attr = array('order_id' => $order_id,'status'=>Order_enum::STATUS_HOLDING);
//        $this->m_erp->update_order_to_erp_log($update_attr);

        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $order_id;
        $insert_data['data']['status'] = Order_enum::STATUS_HOLDING;

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
			exit;
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array('success'=>true,'msg'=>lang('freeze_success')));
			exit;
		}

	}

	/***解除冻结***/
	public function remove_frozen(){
        $this->m_global->checkPermission('remove_frozen',$this->_adminInfo);

		$order_id = $this->input->post('order_id');
        $remark = $this->input->post('remark');

		if(trim($order_id) == '')
		{
			echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
			exit;
		}

        //备注不能为空
        if(trim($remark) == '')
        {
            echo json_encode(array('success'=>false,'msg'=>lang('pls_input_reson')));
            exit;
        }

		//事务开始
		$this->db->trans_begin();

		//更改订单状态为待发货,并且插入备注信息
        //把订单备注的exchange单词去掉，以免和冻结带换货混淆
//        $order = $this->db->query("select order_type,status,is_export_lock,remark from trade_orders WHERE order_id ='$order_id'")->row_array();
        $this->load->model("tb_trade_orders");
        $order = $this->tb_trade_orders->get_one("order_type,status,is_export_lock,remark",
            ["order_id"=>$order_id]);
        $is_ex = strstr($order['remark'],"#exchange");
        $o_remark = $order['remark'];
        if($is_ex){
            $count = strpos($order['remark'],"#exchange");
            $o_remark = substr_replace($order['remark'],"",$count,9);
        }
//		$this->db->where('order_id', $order_id)->set('status',Order_enum::STATUS_SHIPPING)->set('remark',$o_remark)->update('trade_orders');
        $this->load->model("tb_trade_orders");
        $this->tb_trade_orders->update_one(["order_id"=>$order_id],
            ["status"=>Order_enum::STATUS_SHIPPING,"remark"=>$o_remark]);
        //插入到trade_orders_log
        $ret = $this->m_trade->add_order_log($order_id,107,$remark,$this->_adminInfo['id']);
        if(!$ret){
            exit;
        }

        //同步到erp(解除冻结)
        $this->load->model('m_erp');
//        $update_attr = array('order_id' => $order_id,'status'=>Order_enum::STATUS_SHIPPING);
//        $this->m_erp->update_order_to_erp_log($update_attr);

        $insert_data = array();
        $insert_data['oper_type'] = 'modify';
        $insert_data['data']['order_id'] = $order_id;
        $insert_data['data']['status'] = Order_enum::STATUS_SHIPPING;

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
			exit;
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array('success'=>true,'msg'=>lang('remove_frozen_success')));
			exit;
		}
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
				4 => array(
						'desc' => lang('admin_trade_repair_addnumber'),
						'url' => "admin/trade/order_repair?tabs_type=4",
				),
				5 => array(
						'desc' => lang('admin_trade_repair_adddaba'),
						'url' => "admin/trade/order_repair?tabs_type=5",
				),
				6 => array(
						'desc' => lang('admin_trade_repair_recovery'),
						'url' => "admin/trade/order_repair?tabs_type=6",
				),
    		    7 => array(
    		        'desc' => lang('admin_trade_feright_modify'),
    		        'url' => "admin/trade/order_repair?tabs_type=7",
    		    ),
    		    8 => array(
    		        'desc' => lang('award_prizes'),
    		        'url' => "admin/trade/order_repair?tabs_type=8",
    		    ),
    		    9 => array(
    		        'desc' => lang('order_score_correct'),
    		        'url' => "admin/trade/order_repair?tabs_type=9",
    		    ),
    		    10 => array(
    		        'desc' => lang('user_achievement_edit'),
    		        'url' => "admin/trade/order_repair?tabs_type=10",
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
			Order_enum::STATUS_HOLDING => array('class' => "text-holding", 'text' => lang('admin_order_status_holding')),
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

            case 4:
                $statusArr = array(
                    '0' => '未处理',
                    '1' => '处理成功'
                );
                $this->_viewData['statusArr'] = $statusArr;

                $searchData = $this->input->get()?$this->input->get():array();
                $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
                $searchData['order_sn'] = isset($searchData['order_sn'])?$searchData['order_sn']:'';
                $list = $this->m_log->ordersRollbackLogList($searchData);

                $this->load->library('pagination');
                $url = 'admin/trade/order_repair';
                add_params_to_url($url, $searchData);
                $config['base_url'] = base_url($url);
                $config['total_rows'] = $this->m_log->getExceptionRows($searchData);
                $config['cur_page'] = $searchData['page'];
                $this->pagination->initialize_ucenter($config);
                $this->_viewData['paginate'] = $this->pagination->create_links(true);
                $this->_viewData['searchData'] = $searchData;

                $this->_viewData['list'] = $list;

                $this->_viewData['page_data'] = array(
                    'order_sn' => $searchData['order_sn'],
                    'page' => $searchData['page'],
                );

                $page = "trade_repair_addnumber";
                break;
            case 5:
                $page = "trade_repair_add_doba";
                break;
            case 6:
                $page = "trade_repair_recovery";
                break;
            case 7:
                
                //用户冻结解封权限
                if(in_array($this->_adminInfo['email'],config_item('order_modify_users_power')))
                {
                    $this->_viewData['user_rooter'] = 1;
                }
                else
                {
                    $this->_viewData['user_rooter'] = 0;
                }
                //-->end 用户冻结解封权限
                
                $page = "trade_orders_modify_info";
                break;
            case 8:
            	$page = "trade_repair_award_prizes";
            	
            	break;
            case 9:
                $page = 'trade_repair_score_correct';
                break;
            case 10:
                $page = 'user_achievement_edit';
                break;
			default:
				redirect(base_url('admin/trade/order_repair'));
		}

		// 公共 view datau
		$this->_viewData['title'] = lang('admin_trade_repair');

		parent::index('admin/', $page);
	}

	/**
	 * 手动发奖(个人/团队业绩奖)
	 * @author: derrick
	 * @date: 2017年4月14日
	 * @param: 
	 * @reurn: return_type
	 */
	public function trade_award_prizes() {
		if ($this->input->is_ajax_request()) {
			if (!in_array($this->_adminInfo['role'], array(0, 2))) {
				//校验权限
				echo json_encode(array('success'=>false, 'msg'=>lang('attach_no_permissions')));
				exit;
			}
			$order_id = $this->input->post('oid');
			if (empty($order_id)) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_id_not_null')));
				exit;
			}
			/* $this->load->model('tb_new_order_trigger_queue_admin_log');
			$exists = $this->tb_new_order_trigger_queue_admin_log->find_by_order_id($order_id);
			if ($exists) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_already_award_prizes')));
				exit;
			} */
			
			//校验订单是否存在
			$order_info = [];
			switch (substr($order_id, 0, 2)) {
				case 'W-':
				case 'WL':
					$this->load->model('tb_mall_orders');
					$order_info[0] = $this->tb_mall_orders->getWhOrderInfo($order_id);
					if ($order_info[0]) {
						$order_info[0]['order_amount_usd'] = tps_int_format($order_info[0]['order_amount_usd']*100);
						$order_info[0]['order_profit_usd'] = tps_int_format($order_info[0]['order_profit_usd']*100);
						$order_info[0]['pay_time'] = $order_info[0]['order_pay_time'];
						$order_info[0]['status'] = $order_info[0]['status'] == 1 ? Order_enum::STATUS_COMPLETE : Order_enum::STATUS_CANCEL;
						$order_info[0]['order_id'] = $order_id;
					}
					break;
				case 'O-':
					$this->load->model('tb_one_direct_orders');
					$order_info[0] = $this->tb_one_direct_orders->find_by_order_id($order_id);
					if ($order_info[0]) {
						$order_info[0]['order_amount_usd'] = tps_int_format($order_info[0]['order_amount_usd']*100);
						$order_info[0]['order_profit_usd'] = tps_int_format($order_info[0]['order_profit_usd']*100);
						$order_info[0]['pay_time'] = $order_info[0]['status'] == 1 ? date('Y-m-d H:i:s') : '';
						$order_info[0]['status'] = $order_info[0]['status'] == 1 ? Order_enum::STATUS_COMPLETE : Order_enum::STATUS_CANCEL;
						$order_info[0]['order_id'] = $order_id;
					}
					break;
				case 'A-':
					$this->load->model('tb_walmart_orders');
					$order_info[0] = $this->tb_walmart_orders->find_by_order_id($order_id);
					if ($order_info[0]) {
						$order_info[0]['order_amount_usd'] = tps_int_format($order_info[0]['order_amount_usd_one_third']);
						$order_info[0]['order_profit_usd'] = tps_int_format($order_info[0]['order_profit_usd']*100);
						$order_info[0]['pay_time'] = $order_info[0]['status'] == 1 ? date('Y-m-d H:i:s') : '';
						$order_info[0]['status'] = $order_info[0]['status'] == 1 ? Order_enum::STATUS_COMPLETE : Order_enum::STATUS_CANCEL;
						$order_info[0]['order_id'] = $order_id;
					}
					break;
				default:
					$this->load->model('tb_trade_orders');
					$order = $this->tb_trade_orders->getOrderInfo($order_id);
					if ($order) {
						if ($order['order_type'] != 4) {
							echo json_encode(array('success'=>false, 'msg'=>lang('retail_order_allow_award_prizes')));
							exit;
						}
						if ($order['order_prop'] == 2) {
							//主订单, 拆分
							$orders = $this->tb_trade_orders->find_children_orders_by_order_id($order_id);
							if (empty($orders)) {
								echo json_encode(array('success'=>false, 'msg'=>lang('children_order_not_found')));
								exit;
							}
							foreach ($orders as $key => $o) {
								if (empty($o['pay_time'])) {
									continue;
								}
								if (!in_array($o['status'], array(Order_enum::STATUS_INIT, Order_enum::STATUS_SHIPPING, Order_enum::STATUS_SHIPPED, Order_enum::STATUS_EVALUATION, Order_enum::STATUS_COMPLETE))) {
									continue;
								}
								$order_info[$key] = array(
									'order_id' => $o['order_id'],
									'shopkeeper_id' => $o['shopkeeper_id'],
									'order_amount_usd' => $o['order_amount_usd'],
									'order_profit_usd' => $o['order_profit_usd'],
									'score_year_month' => $o['score_year_month'],
									'status' => $o['status'],
									'pay_time' => $o['pay_time']
								);
							}
						} else {
							$order_info[0]['score_year_month'] = $order['pay_time'] ? date('Ym', strtotime($order['pay_time'])) : $order['score_year_month'];
							$order_info[0]['order_id'] = $order_id;
							$order_info[0]['pay_time'] = $order['pay_time'];
							$order_info[0]['status'] = $order['status'];
							$order_info[0]['shopkeeper_id'] = $order['shopkeeper_id'];
							$order_info[0]['order_profit_usd'] = $order['order_profit_usd'];
							$order_info[0]['order_amount_usd'] = $order['order_amount_usd'];
						}
					}
					break;
			}
			if (empty($order_info)) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_not_exits')));
				exit;
			}
			
			$this->load->model(array('o_queen', 'tb_new_order_trigger_queue', 'o_bonus', 'tb_cash_account_log_x'));
			foreach ($order_info as $of) {
				if (empty($of['pay_time'])) {
					echo json_encode(array('success'=>false, 'msg'=>lang('order_not_pay')));
					exit;
				}
				if (!in_array($of['status'], array(Order_enum::STATUS_INIT, Order_enum::STATUS_SHIPPING, Order_enum::STATUS_SHIPPED, Order_enum::STATUS_EVALUATION, Order_enum::STATUS_COMPLETE))) {
					echo json_encode(array('success'=>false, 'msg'=>lang('order_status_not_meet_the_requirements')));
					exit;
				}
				/* if ($this->o_queen->check_order_exists_in_history_queen($of['order_id'])) {
					echo json_encode(array('success'=>false, 'msg'=>lang('order_already_prizes')));
					exit;
				} */
			}
			
			//校验通过, 写日志,写队列
			foreach ($order_info as $of) {
				//校验是否发放个人奖
				if (!$this->tb_cash_account_log_x->find_by_datetime_uid_order_id_item_type($of['score_year_month'], $of['shopkeeper_id'], $of['order_id'], 5)) {
					$this->o_bonus->personal_prize($of['shopkeeper_id'], $of['order_id'], $of['order_profit_usd'], $of['score_year_month']);
				}
				//校验是否发团队奖
				$this->load->model('tb_users');
				$users_info = $this->tb_users->get_user_info($of['shopkeeper_id'], 'parent_ids');
				if (empty($users_info)) {
					echo json_encode(array('success'=>true, 'msg'=>lang('award_prizes_success')));
					exit;
				}
				$users_info = explode(',', $users_info['parent_ids']);
				foreach ($users_info as $i =>$uf) {
					if ($i > 1) break;
					if (!$this->tb_cash_account_log_x->find_by_datetime_uid_order_id_item_type($of['score_year_month'], $uf, $of['order_id'], 3)) {
						$ids = [];
						if ($i == 0) {
							$ids[0] = $uf;
							$ids[1] = '';
						} else {
							$ids[0] = '';
							$ids[1] = $uf;
						}
						$this->o_bonus->group_prize($ids, $of['order_id'], $of['order_profit_usd'], $of['shopkeeper_id'], 2, $of['score_year_month']);
					}
				}
				$this->load->model('tb_new_order_trigger_queue_admin_log');
				$this->tb_new_order_trigger_queue_admin_log->add_log($this->_adminInfo['id'], $of['order_id']);
				/* if ($this->tb_new_order_trigger_queue->addNewOrderToQueue($of['order_id'],$of['shopkeeper_id'],$of['order_amount_usd'],$of['order_profit_usd'],$of['score_year_month'])) {
					$this->tb_new_order_trigger_queue_admin_log->add_log($this->_adminInfo['id'], $of['order_id']);
				} */
			}
			echo json_encode(array('success'=>true, 'msg'=>lang('award_prizes_success')));
			exit;
		}
	}
	
	/**
	 * 手动纠正订单业绩月份
	 * @author: derrick
	 * @date: 2017年4月14日
	 * @param: 
	 * @reurn: return_type
	 */
	public function trade_score_correct() {
		if ($this->input->is_ajax_request()) {
			$order_id = $this->input->post('oid');
			$month = $this->input->post('score_month');
			if (empty($order_id)) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_not_exits')));
				exit;
			}
			if (!is_numeric($month) || strlen($month) != 6) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_score_month_format_not_correct')));
				exit;
			}
			$this->load->model('tb_trade_orders');
			$order_info = $this->tb_trade_orders->getOrderInfo($order_id);
			if (empty($order_info)) {
				echo json_encode(array('success'=>false, 'msg'=>lang('order_not_exits')));
				exit;
			}
			$this->tb_trade_orders->updateInfoById($order_id, array('score_year_month' => $month));
			exec('php '.$_SERVER['DOCUMENT_ROOT'].'/index.php cron fixSaleAmount '.$order_info['shopkeeper_id']);
			echo json_encode(array('success'=> true, 'msg'=>lang('order_score_fix_success')));
			exit;
		}
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
	public function admin_upload_freight_info() {

		
		if (isset($_FILES['excelfile']['type']) == null) {
			$result['msg'] 		= lang('admin_file_format');
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		$name = $_FILES['excelfile']['name'];
		$type = strstr($name, '.'); //限制上传格式
		if (!in_array($type,array('.xls','.xlsx'))) {
			$result['msg'] 		= lang('admin_file_format');
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		//$mime        = $_FILES['excelfile']['type'];
		$path        = trim($_FILES['excelfile']['tmp_name']);
		$tempsize    = filesize($path);
		$kbfsize     = ceil($tempsize / 1024);
		$allwosize   = 1024; // 单位kb
		$allowNum    = 30010; // 允许上传的最大数据

		if ($kbfsize > $allwosize) {
			$result['msg'] 		= lang('upload_big_excel_error');
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		// 验证上传文件中遇到的错误
		$uploadError = $_FILES['excelfile']['error'];
		if ($uploadError) {
			$result['msg'] 		= $uploadError;
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		// 设置读取excel数据
		require APPPATH.'third_party/PHPExcel/PHPExcel.php';
		PHPExcel_Settings::setCacheStorageMethod(
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip, 
			array('cacheTime' => 1800)
		);

        // 设定程序永不超时
		ignore_user_abort(TRUE);
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
		set_time_limit(0);
		$useStart = memory_get_usage();
		/** 捕捉PHPEXCEL 异常  */
		try {
			$objReader   = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($path));
			$objReader->setReadDataOnly(true);
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load($path);
		} catch(Exception $e) {
			$result['msg'] 		= $e->getMessage() == 'Autofilter must be set on a range of cells.' 
									? lang('first_line_format_error') : $e->getMessage();
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		/* 读取数据 */
		$sheet 	  = $objPHPExcel->getActiveSheet(0);
		$rowCount = (int)$sheet->getHighestRow();
		unset($objPHPExcel);

		if ($rowCount <= 1) {
			$result['msg'] = lang('admin_file_data');
			$result['success'] = 0;
			die(json_encode($result));
		}

		/** 如果数据量大于3W条就分表导入  */
		if ($rowCount >= $allowNum) {
			$result['msg'] 		= lang('upload_big_excel_error');
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		// 分批处理
		$onsplitcount = 5000; // 一批次
		$splitTotal   = ceil($rowCount / $onsplitcount);
		$loopIndex    = 2;

		// 批量插入数据
		$this->db->trans_begin();
		for ($i = 1; $i <= $splitTotal; $i++) {
			if ($loopIndex > $rowCount) break;
			// 初始化数据
			$line_error = $freight_info_error = $freight_length_error = $insertData = $order_repeat_error = $logisticscode = $orderIds = array();
			$orderchecked = '';
			$rpart = (($i * $onsplitcount) >= $rowCount) ? $rowCount : ($i * $onsplitcount);
			for ($loopIndex; $loopIndex <= $rpart; $loopIndex++) {
				$temporderId  = $temcode = $temptracking = null;
				$temporderId  = (string)$sheet->getCellByColumnAndRow(0, $loopIndex)->getValue();
				$temcode      = (string)$sheet->getCellByColumnAndRow(1, $loopIndex)->getValue();
				$temptracking = (string)$sheet->getCellByColumnAndRow(2, $loopIndex)->getValue();

				if (!$temporderId && !$temcode && !$temptracking) continue;

				if (!$temporderId || !is_numeric($temcode) || !$temptracking) {
					$line_error[] = $loopIndex;
				}

				$temporderId  = trim($temporderId);
				$temcode      = trim($temcode);
				$temptracking = trim($temptracking);

				// 获取物流code并保存到数组中
				if ($temcode && !isset($logisticscode[$temcode])) {
					$logisticscode[$temcode] = $temcode;
				}

				// 将重复的订单号保存到数组中
				if (isset($orderIds[$temporderId])) {
					$order_repeat_error[] = $temporderId;
				}

				// 获取订单号并保存到数组中
				if ($temporderId) {
					$orderIds[$temporderId] = $temporderId;
				}

				// 用于检测数据是否重复
				if ($temporderId && $orderchecked == '') {
					$orderchecked = $temporderId;
				} 

				//限制快递公司号不能出现其他字符，两个快递公司的话，‘|’前面是0，后面写公司名称或代号，和快递单号
				if (!is_numeric($temcode)) {
	                $freight_info_error[] = $temcode;
	            }

	            if (mb_strlen($temptracking,'UTF-8') > 512) {
					$freight_length_error[] = $temporderId;
				}

				// 构建定时任务的数据
				$insertData[] = array(
					'order_id'		=> $temporderId,
					'company_code'	=> $temcode,
					'trck_num'		=> $temptracking,
					'uid'			=> $this->_adminInfo['id'],
					'update_time'	=> date('Y-m-d H:i:s')
				);

				/*if (($loopIndex % 600) == 0) {
					flush();
					ob_flush();
				}*/

				unset($temporderId, $temcode, $temptracking);
			}
			unset($rpart);


			// 处理错误 数据不完整
			if (!empty($line_error)) {
				$result['success'] = 0;
				$result['msg']     = sprintf(lang('admin_file_not_full'), implode('，', $line_error));
				die(json_encode($result));
			}
			unset($line_error);

			// 检查订单号是否重复
			if (!empty($order_repeat_error)) {
				$result['success'] 	= 0;
	            $result['msg'] 		= sprintf(lang('admin_order_repeat'), implode('，', $order_repeat_error));
	            die(json_encode($result));
			}
			unset($order_repeat_error);

			// 物流错误提示
			if (!empty($freight_info_error)) {
	            $result['success'] 	= 0;
	            $result['msg'] 		= sprintf(lang('admin_file_order_freight_error'), implode('，', $freight_info_error));
	            die(json_encode($result));
	        } 
	        if (!empty($freight_length_error)) {
				$result['success'] 	= 0;
				$result['msg'] 		= sprintf("%s The character length exceeds the 512 limit.", implode('，', $freight_length_error));
				die(json_encode($result));
			}
			unset($freight_info_error, $freight_length_error);

			// 检测是否重复上传
			$findcount = 0;
			if ($orderchecked != '') {
				$findcount = $this->db
				->from('trade_order_cron_import')
				->where('order_id', $orderchecked)
				->count_all_results();
			}

			if ($findcount) {
				$result['success'] 	= 0;
				$result['msg'] 		= lang('admin_repeat_data');
				die(json_encode($result));
			}
			unset($orderchecked, $findcount);

			// 物流对应的公司
			$freightData = array();
			$logisticscode && $freightData = $this->db
						->select('company_code')
	                    ->from('trade_freight')
	                    ->where_in('company_code', $logisticscode)
	                    ->get()->result_array();

	        if (!empty($freightData)) {
	        	foreach($freightData as $items) {
	        		$items['company_code'] = intval($items['company_code']);
	        		if (in_array($items['company_code'], $logisticscode)) unset($logisticscode[$items['company_code']]);
	        	}
	        }

	        $freight_error = $logisticscode;
	        // 物流不存在时的错误提示
	        if (!empty($freight_error)) {
				$result['success'] = 0;
				$result['msg']     = sprintf(lang('admin_file_not_freight'), implode('，', $freight_error));
				die(json_encode($result));
			}
			unset($freightData, $logisticscode, $freight_error);

			// 订单存在，且是等待发货,物流信息为空的数据
			$orderIds && $orderIds = array_unique($orderIds);
			$reorderData   = array();
//			$orderIds && $reorderData = $this->db
//						->select('order_id')
//						->from('trade_orders')
//						->where_in('order_id', $orderIds)
//						->where_in('status',array(Order_enum::STATUS_SHIPPING,Order_enum::STATUS_INIT))
//						->where('freight_info is null')
//						->get()->result_array();

            $this->load->model('tb_trade_orders');
            $orderIds && $reorderData = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id",
                "where"=>['order_id'=>$orderIds,
                    "status"=>array(Order_enum::STATUS_SHIPPING,Order_enum::STATUS_INIT),
                    "freight_info is null"=>null
                ],
                "page_size"=>100000
            ]);

			if (!empty($reorderData)) {
				foreach($reorderData as $items) {
					$items['order_id'] = trim($items['order_id']);
					if (in_array($items['order_id'], $orderIds)) unset($orderIds[$items['order_id']]);
				}
			}
			
			$order_error = $orderIds;
			// 订单的错误提示
			if (!empty($order_error)) {
				$result['success'] = 0;
				$result['msg']     = sprintf(lang('admin_file_order_status'), implode('，', $order_error));
				die(json_encode($result));
			}
			unset($reorderData, $order_error, $orderIds);

			// 批量插入数据
			$this->db->insert_batch('trade_order_cron_import', $insertData);
			unset($insertData);
		}
		unset($loopIndex);

		//保存上传文件
		$this->load->model('m_do_img');
		$uid       = $this->_adminInfo['id'];
		$randNum   = mt_rand(100, 999);
		$tempOssEx = 'trade_order_import/'.$uid.'/'.date('Ymd').'/'.date('His').$randNum.$type;
		// 下面上传的如果php版本大于5.4 则会有问题
		$uploadres = $this->m_do_img->upload($tempOssEx, $path);
		if (!$uploadres) {
			$result['msg'] 		= lang('upload_excel_fail');
			$result['success'] 	= 0;
			die(json_encode($result));
		}

		// 插入导入文件日志记录
		$insertImportLogData = array(
			'uid'			=> $uid,
			'file_name'		=> $name,
			're_file_name'	=> config_item('img_server_url').'/'.$tempOssEx,
			'create_time'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('trade_order_import_log', $insertImportLogData);
		unset($insertImportLogData);

		if ($this->db->trans_status() === FALSE) {
			$result['success'] 	= 0;
			$result['msg'] 		= 'System Error!';
			$this->db->trans_rollback();
		} else {
			$result['success'] 	= 1;
			$result['msg'] 		= lang('update_success');
			$this->db->trans_complete();
		}
		
		die(json_encode($result));
	}

	
	/**
	 * 导入excel 清空订单表中的订单信息 2017-1-6 新增
	 */
	public function admin_upload_freight_info_by_orderid()
	{
	    
	    //接收值
	    $input_rv_data = $this->input->post();	    
	    
	    if (isset($_FILES['excelfile']['type']) == null)
	    {
	        $result['msg'] = lang('admin_file_format');
	        $result['success'] = 0;
	        die(json_encode($result));
	    }
	
	    $name = $_FILES['excelfile']['name'];
	    $type = strstr($name, '.'); //限制上传格式
	    if (!in_array($type,array('.xls','.xlsx'))) {
	        $result['msg'] = lang('admin_file_format');
	        $result['success'] = 0;
	        die(json_encode($result));
	    }
	
	    $mime = $_FILES['excelfile']['type'];
	    $path = $_FILES['excelfile']['tmp_name'];
	
	    // 设定程序永不超时
	    ignore_user_abort(TRUE);
	    ini_set('memory_limit', -1);
	    ini_set('max_execution_time', 0);
	    set_time_limit(0);
	    $useStart = memory_get_usage();
	    
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
	    
	    $order_data = $this->array_unset($order_data,0);
	    /** 去除空格 */
	    foreach($order_data as $k=>$order_item){
	        foreach($order_item as $k1 => $value){
	            $order_item[$k1] = trim($value);
	        }
	        $order_data[$k] = $order_item;
	    }
	    
	    /* 检测数据 */
	    $freight_info_error = $order_error = $line_error = $freight_error = $freight_length_error = array();
		foreach ($order_data as $line => $order_item)
		{

			if (isset($order_item[0]) && isset($order_item[1]) && isset($order_item[2]) && $order_item[0] && $order_item[2])
			{
				// 订单存在，且是等待发货,物流信息为空
//				$count = $this->db
//					->from('trade_orders')
//					->where('order_id', $order_item[0])
//					->count_all_results();
				$this->load->model("tb_trade_orders");
                $count = $this->tb_trade_orders->get_counts(["order_id"=>$order_item[0]]);
				if ($count === 0)
				{
					$order_error[] = $order_item[0];
				}
				// 物流號對應的公司
                if($order_item[1] != 0){
                    $freight_count = $this->db
                        ->from('trade_freight')
                        ->where('company_code', $order_item[1])
                        ->count_all_results();
                    if ($freight_count === 0)
                    {
                        $freight_error[] =  $order_item[1];
                    }
                }

				//限制快递公司号不能出现其他字符，两个快递公司的话，‘|’前面是0，后面写公司名称或代号，和快递单号
				if(!is_numeric($order_item[1])){
                    $freight_info_error[] = $order_item[1];
                }

				if(mb_strlen($order_item[2],'UTF-8')>512){
					$freight_length_error[] = $order_item[0];
				}
			}
			else if (!$order_item[0] && !$order_item[1] && !$order_item[2])
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
        if (!empty($freight_info_error))
        {
            $result['success'] = 0;
            $result['msg'] = sprintf(lang('admin_file_order_freight_error'), implode('，', $freight_info_error));
            die(json_encode($result));
        }
		if(!empty($freight_length_error)){
			$result['success'] = 0;
			$result['msg'] = sprintf("%s The character length exceeds the 512 limit.", implode('，', $freight_length_error));
			die(json_encode($result));
		}
	    
	    $update_data = array();
		$this->db->trans_start();
		$this->load->model('m_user_helper');

		/* 检测完成，批量修改订单的物流信息 */
		$deliver_date = date('Y-m-d H:i:s', time());
		foreach ($order_data as $order_item)
		{
		    
		    $insert_data = array();
		    
		    if(1==$input_rv_data['par_set'])
		    {		        
		        $update_data[] = array(
		            'order_id' => "$order_item[0]",
		            'freight_info' => NULL,
		            'status'  => $input_rv_data['order_status'],
//		            'deliver_time' => $deliver_date
		        );
		        
		        $insert_data['data']['status'] = $input_rv_data['order_status'];
		    }
		    else 
		    {
		        $freight_info = $order_item[1].'|'.$order_item[2];
		        $freight_info = trim($freight_info,'|');
		        $update_data[] = array(
		            'order_id' => "$order_item[0]",
		            'freight_info' => $freight_info,
//		            'deliver_time' => $deliver_date
		        );
		    }
		    
		    $insert_data['oper_type'] = 'modify';
		    $insert_data['data']['order_id'] = "$order_item[0]";
		    $insert_data['data']['logistics_code'] = $order_item[1];
		    $insert_data['data']['tracking_no'] = $order_item[2];
		    $insert_data['data']['deliver_time'] = $deliver_date;		    
			
			$this->m_trade->paypal_order_deliver($order_item[0],array('company_code'=>$order_item[1],'express_id'=>$order_item[2]));
			
			$company = $this->m_user_helper->getFreightName($order_item[1]);
			//写入操作日志
			$this->m_trade->add_order_log($order_item[0],150,"$company $order_item[2]->批量导入",$this->_adminInfo['id']);
            //erp 同步
            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
		}

//		$this->db->update_batch('trade_orders', $update_data, 'order_id');
		$this->load->model("tb_trade_orders");
		$this->tb_trade_orders->update_batch_auto([
		   "data"=>$update_data,
            "index"=>"order_id"
        ]);
	    $this->db->trans_complete();
	    
		if($this->db->trans_status() === FALSE){
			$result['success'] = 0;
			$result['msg'] = 'System Error!';
		}else{
			$result['success'] = 1;
			$result['msg'] = lang('update_success');
		}
		
	    die(json_encode($result));
	}
	
	/**
	 * 导入excel 批量修复会员业绩
	 */
	public function admin_upload_user_achievement()
	{
        set_time_limit(0);
        ini_set ('memory_limit', '512M');

        //接收值
        $input_rv_data = $this->input->post();
        $s_time = $input_rv_data['t_stime'];
        $e_time = $input_rv_data['t_end'];
        $ym = date('Ym', strtotime($s_time)-30*24*3600);
        if (isset($_FILES['excelfile']['type']) == null)
        {
            $result['msg'] = lang('admin_file_format');
            $result['success'] = 0;
            die(json_encode($result));
        }

        $name = $_FILES['excelfile']['name'];
        $type = strstr($name, '.'); //限制上传格式
        if (!in_array($type,array('.xls','.xlsx'))) {
            $result['msg'] = lang('admin_file_format');
            $result['success'] = 0;
            die(json_encode($result));
        }

        $mime = $_FILES['excelfile']['type'];
        $path = $_FILES['excelfile']['tmp_name'];

        // 设定程序永不超时
        ignore_user_abort(TRUE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $useStart = memory_get_usage();

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

        $order_data = $this->array_unset($order_data,0);

        /** 去除空格 */
        foreach($order_data as $k=>$order_item){
            foreach($order_item as $k1 => $value){
                $order_item[$k1] = trim($value);
            }
            $order_data[$k] = $order_item;
        }

        /* 检测数据 */
        $freight_info_error = $order_error = $line_error = $freight_error = $freight_length_error = array();
        foreach ($order_data as $line => $order_item)
        {
            if (isset($order_item[0]))
            {

            }
            else if (!$order_item[0] )
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


        $update_data = array();
        // $this->db->trans_start();
        $this->load->model('m_user_helper');

        /* 检测完成，批量修改订单的物流信息 */
        $this->load->model('tb_users');
        $this->load->model('tb_users_store_sale_info_monthly');
        $this->load->model('tb_daily_bonus_qualified_list');
        $this->load->model('o_fix_commission');
        $this->load->model('tb_user_qualified_for_138');
        $this->load->model('tb_user_coordinates');
        $this->load->model('tb_grant_bonus_user_logs');
        $this->load->model('o_fix_138_elite_commission');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_month_group_share_list');
        $this->load->model('m_user');
        $this->load->model('tb_month_top_leader_bonus');
        $this->load->model('tb_month_leader_bonus_lv5');
        $this->load->model('tb_month_leader_bonus');
        $this->load->model('o_cron');
        $this->load->model('m_user');
        $this->load->model('tb_month_sharing_members');
        $this->load->model('m_admin_user');
        $this->load->model("tb_system_rebate_conf");


        $obonus_rate = $this->tb_system_rebate_conf->get_by_type(26);
        if (!$obonus_rate || empty($obonus_rate) || $obonus_rate['rate_a'] <= 0) {
            echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_failed_rate")));
            exit;
        }
        if ($obonus_rate['rate_a'] >= 1) {
            echo json_dump(array('success'=>false,'msg'=>lang("new_member_bonus_failed_rate_1")));
            exit;
        }

        $rate = $obonus_rate['rate_a']; //发奖

       
	    foreach ($order_data as $order_item)
	    {
	        if(empty($order_item[0])){
	            continue;
            }
            
	       // 检查用户id是否存在	        
            if($input_rv_data['par_ledaer_list']==3)
            {
                $one = $this->tb_users->getUserInfo($order_item[2]);          
                $uid_post = $order_item[2];
            }
            else 
            {
                $one = $this->tb_users->getUserInfo($order_item[0]);      
                $uid_post = $order_item[0];
            }
            if(empty($one)){
                echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
                exit();
            }
	      
	        $user_data = array
	        (
	            'uid'  => $uid_post,
	            'proportion' => 0,
	            'share_point' => 0,	            
	            'amount' => $one['amount']*100,
	            'bonus' => 0,
	            'type' => 1,
	            'item_type' => 0
	        );
	        //修复会员业绩	       
	        //$this->tb_users_store_sale_info_monthly->statistics_user_monthly($order_item[0],0);
	        $this->o_cron->count_monthly($uid_post,null);

	        $time_type = 0;
	        if($input_rv_data['par_set']!=0)
	        {
	            switch($input_rv_data['par_set'])
	            {
	                case 1:
	                    $user_data['item_type'] = 6;
	                    break;
	                case 2:
	                    $user_data['item_type'] = 2;
	                    break;
	                case 3:
	                    $user_data['item_type'] = 25;
	                    break;
	                case 4:
	                    //每周领导对等奖
	                    $user_data['item_type'] = 7;
	                    break;
	                case 5:
	                    //每月团队销售分红
	                    $user_data['item_type'] = 1;
	                    break;
	                case 6:
	                    //每月领导销售分红
	                    $user_data['item_type'] = 23;
	                    break;
	                case 7:
	                    $user_data['item_type'] = 26;
	                    break;
	                case 8:
	                    //每月杰出店铺
	                    $user_data['item_type'] = 8;
	                    break;
	            }
	            $user_data['type'] = 3;
	            $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);

	            switch($input_rv_data['par_set'])
	            {
	                case 1:
	                    //每天全球利润分红
	                    //检测用户是否已经发过次奖或是否已经存在此队列中
	                    //如果用户满足次奖，并未发放，则发放
	                    $this->tb_daily_bonus_qualified_list->addToDailyQualiListNew($uid_post,$s_time);
	                     
	                    $this->o_fix_commission->daily_bonus_reissue_new($uid_post,$s_time,$e_time,6);
	                    break;
	                case 2:
	                    //138日分红
	                    //检测用户是否已经发过次奖或是否已经存在此队列中
	                    //如果用户满足次奖，并未发放，则发放
	                    $res138 = $this->tb_user_coordinates->is_exist_138($uid_post);
	                    if ($res138) {
	                        $this->tb_user_qualified_for_138->addToList($uid_post, $res138['x'], $res138['y']);
	                    }
	                    //$this->o_fix_commission->fixUserComm($order_item[0],2,$s_time,$e_time);
	                    $this->o_fix_138_elite_commission->common_138_elite_interface_new($uid_post,2,$s_time,$e_time);
	                    break;
	                case 3:
	                    //每周团队销售分红
	                    $this->tb_week_leader_members->addWeekTeamQualified($uid_post);
	                    $this->o_fix_commission->week_ressiue_group_money_new($uid_post,$s_time,$e_time,25);
	                    break;
	                case 4:
	                    //每周领导对等奖
	                    $this->tb_week_leader_members->addCurweekQualifiedUserList($uid_post);
	                    $this->o_fix_commission->weekLeaderBonusAwardFix($uid_post, $s_time, $e_time, 7);
	                    break;
	                case 5:
	                    //每月团队组织分红
	                    $this->tb_month_group_share_list->addInCurMonthGroupShareList($uid_post);
	                    $this->o_fix_commission->every_month_ressiue_group_money_new($uid_post,$s_time,$e_time,1);
	                    break;
	                case 6:

	                    // 每月领导分红奖
	                    $user_info = $this->m_user->getUserByIdOrEmail($uid_post);
	                    $user_point = $this->m_user->getTotalSharingPoint($uid_post);
	                     
	                    switch ($user_info['sale_rank']) {
	                        case 3:
	                            // 领导分红- 高级市场主管
	                            $this->tb_month_leader_bonus->addInCurMonthLeader($uid_post, $user_point);
	                            break;
	                        case 4:
	                            // 领导分红-市场总监
	                            $this->tb_month_top_leader_bonus->addInCurMonthTopLeader($uid_post, $user_point);
	                            break;
	                        case 5:
	                            // 领导分红- 销售副总裁
	                            $this->tb_month_leader_bonus_lv5->addInCurMonthLeaderLv5($uid_post, $user_point);
	                            break;
	                    }
	                    $this->o_fix_commission->resMonthLeaderAmount_new($uid_post,$s_time,$e_time,23);
	                    break;
	                case 7:
                        //新会员
                        //$this->load->model("tb_users_level_change_log");
                        //$userLevelData = $this->tb_users_level_change_log->get_user($order_item[0]);
                        $this->o_fix_commission->addUserToCommQualifyList($uid_post,26,"");
                        $this->o_fix_commission->new_member_bonus_reissue_new($uid_post,$s_time,$e_time,26,$rate);
                        break;
                    case 8:
                        //一个月发一次，没有必要再加到队列里，直接补发
                        /*
                        #$user_point = $this->m_user->getTotalSharingPoint($order_item[0]);
                        #$this->tb_month_sharing_members->addCurMonthQualifiedUserList($order_item[0], $user_point);
                        */
                        $this->o_fix_commission->monthEminentStoreBonusAwardFix($uid_post, $s_time, $e_time, 8);
                        break;
	            }
                    $user_info = $this->tb_users->getUserInfo($uid_post);
                    $user_data['type'] = 4;
                    $user_data['amount'] = $user_info['amount']*100;
                    $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);

	        }
	        
	        //业绩修复
	        if($input_rv_data['par_score']!=0)
	        {
	           
	            $ym_amount = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($uid_post,$ym);
	            switch($input_rv_data['par_score'])
	            {	                
	                case 1:
	                    //每天全球利润分红	                    
	                    $table_name = "update daily_bonus_qualified_list set amount=".$ym_amount." where uid = ".$uid_post;	                    
	                    $this->db->query($table_name);
	                    break;
	                case 3:
	                    //每周团队销售分红
	                    $table_name = "update week_share_qualified_list set amount=".$ym_amount." where uid = ".$uid_post;
	                    $this->db->query($table_name);
	                    break;
	            }
	            $user_data['type'] = 5;
	            $user_data['amount'] = $ym_amount;
	            $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
	        }
	        
	        //清空不满足的发奖用户
	        if($input_rv_data['par_clear_list']!=0)
	        {
	           switch($input_rv_data['par_clear_list'])
	            {	                
	                case 1:
	                    //每天全球利润分红
	                    $del_sql = "delete  from daily_bonus_qualified_list where uid =".$uid_post;
	                    $this->db->query($del_sql);
	                    break;
	                case 2:
	                    //138日分红
    	                $del_sql = "delete  from user_qualified_for_138 where user_id =".$uid_post;
    	                $this->db->query($del_sql);
	                    break;
	                case 3:
	                    //每周团队销售分红
	                    $del_sql = "delete  from week_share_qualified_list where uid =".$uid_post;
	                    $this->db->query($del_sql);	                                        
	                    break;
	                case 4:
	                    //每周领导对等奖
	                    $del_sql = "delete  from week_leader_members where uid =".$uid_post;
	                    $this->db->query($del_sql);
	                    break;
	                case 5:
	                    //每月团队组织分红
	                    $del_sql = "delete  from month_group_share_list where uid =".$uid_post;
	                    $this->db->query($del_sql);
	                    break;
	            }
	        }
	        //抽回多发奖
	        if($input_rv_data['par_ledaer_list']!=0)
	        {
	            
	            $user_data['type'] = 3;
	            $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
	            switch($input_rv_data['par_ledaer_list'])
	            {
	                case 1:
	                    //每月总裁分红	                    
	                    $bon_sql = "select * from cash_account_log_201706 where item_type = 4 and uid= ". $uid_post." order by create_time desc limit ".$input_rv_data['par_ledaer_num'];
	                    $query = $this->db->query($bon_sql);
	                    $query_value = $query->result_array();
	                    if(!empty($query_value))
	                    {
	                        foreach($query_value as $sult)
	                        {
	                            $postData = array
	                            (	                                
	                                'commChangeUid' => $uid_post,
	                                'commChangeAmount' => $sult['amount']/100,
	                                'commChangeDesc' => "每月总裁销售奖：".$sult['create_time'].'多重发$'.($sult['amount']/100).',故抽回',
	                                'commChangeOper' =>2,
	                            );
	                            $this->m_admin_user->checkCommChangeSubData($this->_adminInfo['id'],$postData);
                                //$user_info = $this->tb_users->getUserInfo($order_item[0]);
                                $user_data['type'] = 4;
                                $user_data['amount'] = $sult['amount']/100;
                                $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
	                        }	                            
	                    }	                    
	                    break;
                    case 2:
                        //新会员专项奖
                        $day_all = get_day($s_time,$e_time);
                        if (!empty($day_all)) {
                            for($index = 0; $index < count($day_all) ; $index++){
                                $table = get_cash_account_log_table($uid_post,$day_all[$index]);
                                $sql = "select amount,create_time from $table where uid = $uid_post and item_type=26 and DATE_FORMAT(create_time,'%Y-%m-%d') = $day_all[$index]";
                                $sult = $this->db->query($sql)->row_array();
                                if(!empty($sult)){
                                    $postData = array
                                    (
                                        'commChangeUid' => $uid_post,
                                        'commChangeAmount' => $sult['amount']/100,
                                        'commChangeDesc' => "核实抽回 ".$sult['create_time']." 错误发放的新会员专享奖 ".($sult['amount']/100)."美元。",
                                        'commChangeOper' =>2,
                                    );
                                    //1为全部抽回  2为抽回误发差额
                                    if($input_rv_data['par_ledaer_num'] == 2){

                                    }

                                    $this->m_admin_user->checkCommChangeSubData($this->_adminInfo['id'],$postData);
                                    $user_data['type'] = 4;
                                    $user_data['amount'] = $sult['amount']/100;
                                    $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                                }
                            }
                        }

                        break;
                    case 3:
                        
                        //个人销售店铺和团队业绩提成
                        $bons_sql = "select * from cash_account_log_201706 where  order_id='".$order_item[3]."' and uid = ".$uid_post." and item_type in (3,5) limit ".$input_rv_data['par_ledaer_num'].",10";
                     
                        $query = $this->db->query($bons_sql);
                        $query_value = $query->result_array();
                        if(!empty($query_value))
                        {
                            foreach($query_value as $sult)
                            {
                                if($sult['item_type']==3)
                                {
                                    $str_bonse_resutl = "个人店铺销售销售奖：";
                                }
                                else 
                                {
                                    $str_bonse_resutl = "团队销售业绩提成奖：";
                                }
                                $postData = array
                                (
                                    'commChangeUid' => $uid_post,
                                    'commChangeAmount' => $sult['amount']/100,
                                    'commChangeDesc' => $str_bonse_resutl.$sult['create_time'].'多重发$'.($sult['amount']/100).',故抽回',
                                    'commChangeOper' =>2,
                                );
                            
                                $this->m_admin_user->checkCommChangeSubData($this->_adminInfo['id'],$postData);
                                //$user_info = $this->tb_users->getUserInfo($order_item[0]);
                                $user_data['type'] = 4;
                                $user_data['amount'] = $sult['amount']/100;
                                $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                            }
                        }                        
                        break;
                    case 4:
                        //138分红
                        $day_all = get_day($s_time,$e_time);
                        if (!empty($day_all)) {
                            for($index = 0; $index < count($day_all) ; $index++){
                                $table = get_cash_account_log_table($uid_post,date("Ym",strtotime($day_all[$index])));
                                $sql = "select amount,create_time from $table where uid = $uid_post and item_type=2 and DATE_FORMAT(create_time,'%Y%m%d') = $day_all[$index]" ;
                                $sult = $this->db->query($sql)->row_array();
                                $money = $sult['amount'];
                                if(!empty($sult)){
                                    //1为全部抽回  2为抽回误发差额
                                    if($input_rv_data['par_ledaer_num'] == 2){
                                        $data = $this->o_138_supply_data($uid_post,$day_all[$index],$day_all[$index]);
                                        if($data[0]['money']<$money){
                                            $money = $money - $data[0]['money'];
                                        }

                                        $postData = array
                                        (
                                            'commChangeUid' => $uid_post,
                                            'commChangeAmount' => round($money/100,2),
                                            'commChangeDesc' => "核实抽回 ".$sult['create_time']." 多发放的138分红".($money/100)."美元。",
                                            'commChangeOper' =>2,
                                        );
                                        $this->m_log->createCronLog('[Message] 实发 '.$sult['amount'].' 应发 '.$data[0]['money']."==".($sult['amount']-$data[0]['money']));
                                        $this->m_log->createCronLog('[Message] 138抽回 第一步 '.round($money/100,2));

                                    }else{
                                        $postData = array
                                        (
                                            'commChangeUid' => $uid_post,
                                            'commChangeAmount' => round($money/100,2),
                                            'commChangeDesc' => "核实抽回 ".$sult['create_time']." 错误发放的138分红".($money/100)."美元。",
                                            'commChangeOper' =>2,
                                        );
                                    }
                                    $this->m_admin_user->checkCommChangeSubData($this->_adminInfo['id'],$postData);
                                    $user_data['type'] = 4;
                                    $user_data['amount'] = $money/100;
                                    $this->tb_grant_bonus_user_logs->add_grant_amount_logs($user_data);
                                }
                            }
                        }

                        break;

	            }

	        }
	    }
	
/*	    $this->db->trans_complete();
	     
	    if($this->db->trans_status() === FALSE){
	        $result['success'] = 0;
	        $result['msg'] = 'System Error!';
	    }else{
            $this->db->trans_commit();
	        $result['success'] = 1;
	        $result['msg'] = lang('update_success');
	    }*/
        $result['success'] = 1;
        $result['msg'] = lang('update_success');
	    die(json_encode($result));
	}
	
	/**
	 * 批量导入退运费售后单号
	 */
	public function admin_upload_return_freight()
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

		$order_data = $this->array_unset($order_data,0);

		/** 去除空格 */
		foreach($order_data as $k=>$order_item){
			foreach($order_item as $k1 => $value){
				$order_item[$k1] = trim($value);
			}
			$order_data[$k] = $order_item;
		}

		/* 检测数据 */
		$order_error = $line_error = $freight_error = $user_error = $again_error = $drop_error = array();

		foreach ($order_data as $line => $order_item)
		{
			if (isset($order_item[0]) && isset($order_item[1]) && isset($order_item[2]) && isset($order_item[3]) && $order_item[3] && $order_item[2] &&$order_item[1]&&$order_item[0])
			{
				// 订单需要付款完成，正常状态
//				$order = $this->db->select('deliver_fee_usd,status,customer_id')
//					->from('trade_orders')
//					->where('order_id', $order_item[0])
//					->get()->row_array();
                $this->load->model("tb_trade_orders");
                $order = $this->tb_trade_orders->get_one("deliver_fee_usd,status,customer_id",
                    ['order_id'=>$order_item[0]]);
				if (!$order || in_array($order['status'],array(2,99,90,98,111)))
				{
					$order_error[] = $order_item[0];
					continue;
				}
				// 退款运费不能大于订单运费
				if($order_item[1] > ($order['deliver_fee_usd']/100)){
					$freight_error[] =  $order_item[0];
					continue;
				}


				//收款人账号或订单顾客 状态异常,公司账户
				$u = $this->db->select('status')->where('id',$order['customer_id'])->get('users')->row_array();
				if(!$u || $u['status'] == 4){
					$user_error[] = $order_item[0];
					continue;
				}
				$count = $this->db->select('status')->from('users')->where('id',$order_item[2])->get()->row_array();
				if(!$count || $count['status'] == 4){
					$user_error[] = $order_item[0];
					continue;
				}
				//同一个订单，不能重复申请退运费
				$again = $this->db->select('status')->from('admin_after_sale_order')->where('order_id',$order_item[0])->where('type','2')->get()->row_array();
				if($again && $again['status'] != 6 && date('Y-m-d')!='2016-07-13'){
					$again_error[] = $order_item[0];
					continue;
				}
				//订单的顾客ID，不能申请退会售后
				$again_order = $this->db->from('admin_after_sale_order')->where('uid',$order['customer_id'])->where('type','0')
					->where_in('status',array('0','1','2','3','4','5','7'))->count_all_results();
				if($again_order > 0){
					$drop_error[] = $order_item[0];
					continue;
				}

			}
			else if (!isset($order_item[0]) && !isset($order_item[1]) && !isset($order_item[2])&& !isset($order_item[3]))
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
			$result['msg'] = implode('，', $freight_error).lang('admin_return_fee_tip2');
			die(json_encode($result));
		}
		if (!empty($order_error))
		{
			$result['success'] = 0;
			$result['msg'] = implode('，', $order_error).lang('admin_return_fee_tip1');
			die(json_encode($result));
		}
		if (!empty($user_error))
		{
			$result['success'] = 0;
			$result['msg'] = implode('，', $user_error).lang('admin_as_status_error');
			die(json_encode($result));
		}
		if (!empty($again_error))
		{
			$result['success'] = 0;
			$result['msg'] = implode('，', $again_error).lang('admin_return_fee_tip3');
			die(json_encode($result));
		}
		if (!empty($drop_error))
		{
			$result['success'] = 0;
			$result['msg'] = implode('，', $drop_error).lang('admin_return_fee_tip4');
			die(json_encode($result));
		}

		$this->db->trans_start();
		$this->load->model('m_admin_user');
		$this->load->model('m_commission');

		/* 检测完成，批量insert售后订单 */

		foreach ($order_data as $order_item)
		{
			sleep(1);
			$add_data_arr = array(
				'as_id'=>$this->m_admin_user->create_after_sale_id(),
				'type'=>2,
				'refund_amount'=>round($order_item['1'],2),
				'refund_method'=>1,
				'remark'=>$order_item['3'],
				'transfer_uid' => $order_item['2'],
				'admin_id' => $this->_adminInfo['id'],
				'admin_email' => $this->_adminInfo['email'],
				'uid' => 0,
				'order_id' => trim($order_item['0']),
				'status'=>0,
			);
			$this->db->insert('admin_after_sale_order',$add_data_arr);

			/** 退运费到现金池 */
			//$this->m_commission->commissionLogs($order_item['2'],9,$order_item['1']);
			//$this->db->where('id',$order_item['2'])->set('amount','amount +'.$order_item['1'],FALSE)->update('users');

			/** 订单操作流水 */
			$remark = '申请售后订单 - 系统批量导入';
			$this->m_log->admin_after_sale_remark($add_data_arr['as_id'],$this->_adminInfo['id'],$remark);

			/** 订单备注 */
			/*$this->load->model('m_trade');
			$insert_attr = array(
				'order_id' => $order_item['0'],
				'type' => 1,
				'remark' => sprintf(lang('admin_refund_amount'),$order_item['1']),
				'admin_id' => $this->_viewData['adminInfo']['id'],
			);
			$this->m_trade->add_order_remark_record($insert_attr);
			$insert_attr2 = array(
				'order_id' => $order_item['0'],
				'type' => 2,
				'remark' => sprintf(lang('admin_refund_amount'),$order_item['1']),
				'admin_id' => $this->_viewData['adminInfo']['id'],
			);
			$this->m_trade->add_order_remark_record($insert_attr2);*/

		}

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$result['success'] = 0;
			$result['msg'] = 'System Error!';
		}else{
			$result['success'] = 1;
			$result['msg'] = lang('update_success');
		}

		die(json_encode($result));
	}


	//二维数组去除特定键的重复项 //$arr->传入数组   $key->判断的key值
	public function array_unset($arr,$key){
		//建立一个目标数组
		$res = array();
		foreach ($arr as $value) {
			//查看有没有重复项
			if(isset($res[$value[$key]])){
				//有：销毁
				unset($value[$key]);
			}
			else{
				$res[$value[$key]] = $value;
			}
		}
		return $res;
	}

	/* 扫描发货 */
	public function scan_shipping() {
		$this->load->view('admin/scan_shipping', $this->_viewData);
	}

	/**
	 * 确认允许换货
	 */
	public function do_exchange_order()
	{
		if($this->input->is_ajax_request()){
            $this->load->model('tb_trade_orders');
            $this->load->model('m_erp');
            $attr = $this->input->post();
            $order_id = $attr['order_id'];
            $remark = $attr['remark'];

            //记录到trade_order_remark_record
            $this->db->insert('trade_order_remark_record',array(
                'order_id'=>$order_id,
                'type'=>'1',
                'remark'=>$remark,
                'admin_id'=> $this->_adminInfo['id']
            ));
            //备注同步到erp
            $insert_data_remark = array();
            $insert_data_remark['oper_type'] = 'remark';
            $insert_data_remark['data']['order_id'] = $order_id;
            $insert_data_remark['data']['remark'] = $remark;
            $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
            $insert_data_remark['data']['recorder'] = $this->_adminInfo['id']; //操作人
            $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

            $order_info = $this->tb_trade_orders->getOrderInfo($order_id, array('status','remark'));
            $remark_in = '#exchange'.$order_info['remark'];
            //状态换货中，清空订单发货信息
//            $res = $this->db->where('order_id',$order_id)->update('trade_orders',array(
//					'status'=> '90',
//					'remark'=>$remark_in,
//					'deliver_time'=> null,
//					'freight_info'=> null
//			));
            $res = $this->tb_trade_orders->update_one(["order_id"=>$order_id],
                array('status'=> '90',
                    'remark'=>$remark_in,
                    'deliver_time'=> null,
                    'freight_info'=> null));
//            echo $this->db->last_query();
//            print_r($res);
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = Order_enum::STATUS_HOLDING;
            $insert_data['data']['remark'] = $remark_in;
            $insert_data['data']['deliver_time'] = "0000-00-00 00:00:00";
            $insert_data['data']['tracking_no'] = '';
            $insert_data['data']['logistics_code'] = '-1';
            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            /** 添加订单操作记录日志 soly  */
            $date = date('Y-m-d H:i:s');
            $this->db->insert('trade_orders_log', array(
            	'order_id'		=> $order_id,
            	'oper_code' 	=> 130,
            	'statement' 	=> $remark,
            	'operator_id'	=> $this->_adminInfo['id'],
            	'update_time'	=> $date
            ));


			// 添加换货计时器 soly 调整如果后台允许换货不管会员是否取消换货或者是否换货超时都开放会员换货
			$timerTotal = $this->db->from('my_order_exchange_time')->where('order_id', $order_id)->count_all_results();

			if ($timerTotal) {
				$this->db->update('my_order_exchange_time', array(
					'uid' => $this->_adminInfo['id'], 'create_time' => $date
				), array('order_id' => $order_id));
			} else {
				$this->db->insert('my_order_exchange_time', array(
					'uid' 			=> $this->_adminInfo['id'],
					'order_id'		=> $order_id,
					'create_time'	=> $date
				));
			}

			$this->db->where('order_id', $order_id)->delete('my_order_exchange_log');
			$this->db->insert('my_order_exchange_log', array(
				'uid'         => 0,
				'order_id'    => $order_id,
				'status'      => 1,
				'create_time' => $date
			));
			
            if($res){
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
            }
		}
	}



    public function o_138_supply_data($uid,$start,$end){
        $this->load->model("tb_cash_account_log_x");
        $day_all = get_day($start, $end);
        if(!empty($day_all)) {
            for ($index = 0; $index < count($day_all); $index++) {
                $this->load->model('o_bonus');
                $row = $this->db->query("select x,y from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y%m%d')='$day_all[$index]' GROUP BY DATE_FORMAT(create_time,'%Y%m%d')")->row_array();

                //查询发奖参数
                $sql = "select * from grant_bonus_138_every_param where DATE_FORMAT(create_time,'%Y%m%d') = '$day_all[$index]'";
                $param = $this->db->force_master()->query($sql)->row_array();

                $sql="select a.uid,round((((".$row["y"]."-c.y)-if(c.x>".$row["x"].",1,0))/".$param['total_shar_num']."*".$param['total_amount_other'].")+".$param['amount_avg'].") as money
                               from users_store_sale_info_monthly a
                               left join users b on a.uid=b.id
                               LEFT JOIN user_coordinates c on a.uid = c.user_id
                               where a.`year_month`=DATE_FORMAT(date_add('".$day_all[$index]."',interval -1 month),'%Y%m') and  b.user_rank in(1,2,3,5) and a.uid = $uid";

                $result[]= $this->db->force_master()->query($sql)->row_array();
            }
            return $result;
        }
    }

}
