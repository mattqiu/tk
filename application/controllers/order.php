<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("tb_trade_addr_linkage");
		$this->load->model("m_trade");
		$this->load->model("m_erp");
		$this->load->model("tb_trade_orders");
	}

	public function index()
	{
		echo "403 Forbidden!";
		exit;
	}

	/**
	 * 产品直接下单
	 */
	public function product_checkout()
	{

		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect='.substr($_SERVER['HTTP_REFERER'], strlen(base_url())));
		}
		$uid = $this->_userInfo['id'];

		// 生成提交 token
		set_cookie("submit_token", md5("tpssubmittoken{$uid}"), 86400 * 30, get_public_domain(), '/');

		$attr = $this->input->get();
		$rules = array(
			'goods_sn' => "required|max:32",
			'quantity' => "required|integer|max:99",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			redirect(base_url());
		}

		// 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();

		// 获取商品列表
		$goods_list = $this->m_trade->get_product_goods_list(
			$attr['goods_sn'],
			$attr['quantity'],
			$this->_viewData['curLan_id'],
			$this->_viewData['curLan'],
			$this->_viewData['cur_rate']
		);


        // 获取用户收货地址列表
        $this->load->model("tb_trade_user_address");
		$addr_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);
		if (empty($addr_list))
		{
			$this->_viewData['data'] = $this->m_trade->get_checkout_data_for_new(
				$goods_list,
				$this->_viewData['curCur_flag'],
				$this->_viewData['cur_rate']
			);
			$page = "checkout_for_new";
		}
		else
		{
			$this->_viewData['data'] = $this->m_trade->get_checkout_data(
				$goods_list,
				$addr_list,
				$this->_viewData['curCur'],
				$this->_viewData['cur_rate'],
				$this->_viewData['currency_all']
			);
			$page = "checkout";
		}
		$goods_sn_list = [];
		foreach($goods_list as $v) {
			$goods_sn_list[] = $v['goods_sn'];
		}
		$this->_viewData['goods_sn_list'] = $goods_sn_list;
		$adinfo = [];
		foreach($addr_list as $k=>$v) {
			$adinfo[$k]['id']  = $v['id'];
			$adinfo[$k]['addr_lv2'] = $v['addr_lv2'];
		}
		$this->_viewData['addr_list'] = $adinfo;
        //判断商品下单时是否用填写身份证号或上传身份证图片
        $is_reg_num = 0;
        $is_reg_img = 0;
        $is_reg_all = 0;
        foreach($goods_list as $is_reg){
            if($is_reg['is_require_id'] == 1){
                if($is_reg['require_type'] ==1){
                    //需要身份证号码
                    $is_reg_num = 1;
                    continue;
                }elseif($is_reg['require_type'] ==2){
                    //需要身份证图片
                    $is_reg_img = 2;
                    continue;
                }elseif($is_reg['require_type'] ==3){
					//需要身份证图片和号码
					$is_reg_all = 3;
					continue;
				}
            }
        }
        $this->_viewData['is_reg_num'] = $is_reg_num;
        $this->_viewData['is_reg_img'] = $is_reg_img;
        $this->_viewData['is_reg_all'] = $is_reg_all;


		$this->_viewData['clear_cart'] = 0;
		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');
		parent::index('mall/', $page);
	}

	/**
	 * 购物车结算下单
	 */
	public function cart_checkout()
	{
//        $stime=microtime(true);
		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect = cart');
		}
		$uid = $this->_userInfo['id'];

		// 生成提交 token
		set_cookie("submit_token", md5("tpssubmittoken{$uid}"), 86400 * 30, get_public_domain(), '/');

		//取选中的产品
		$sn = $this->input->get("sn");
		if(!$sn)
        {
            redirect(base_url('cart'));
        }

		// 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();

		// 获取购物车商品信息
        $this->load->model("tb_user_cart");
		$goods_list = $this->tb_user_cart->get_cart_goods_list(
			$this->_viewData['store_id'],
			$this->_viewData['curLan_id'],
			$this->_viewData['curLan'],
			$this->_viewData['cur_rate'],
			$this->_viewData['curLocation_id'],
            $sn
		);

		//若未选对结算产品
        if(!$goods_list)
        {
            redirect(base_url('cart'));
        }

		// 获取用户收货地址列表
        $this->load->model("tb_trade_user_address");
		$addr_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);

//        $etime=microtime(true);
//        $execute_time = $etime-$stime;
//        $this->m_debug->log(__LINE__.".cart_checkout->execute_time:".$execute_time);

		if (empty($addr_list))
		{
			$this->_viewData['data'] = $this->m_trade->get_checkout_data_for_new(
				$goods_list,
				$this->_viewData['curCur_flag'],
				$this->_viewData['cur_rate']
			);
			$page = "checkout_for_new";
		}
		else
		{
			$checkout_data = $this->m_trade->get_checkout_data(
				$goods_list,
				$addr_list,
				$this->_viewData['curCur'],
				$this->_viewData['cur_rate'],
				$this->_viewData['currency_all']
			);
			$this->_viewData['data'] = $checkout_data;
			$page = "checkout";
		}

		$goods_sn_list = [];
		foreach($goods_list as $v) {
			$goods_sn_list[] = $v['goods_sn'];
		}
		$this->_viewData['goods_sn_list'] = $goods_sn_list;
		$adinfo = [];
		foreach($addr_list as $k=>$v) {
			$adinfo[$k]['id']  = $v['id'];
			$adinfo[$k]['addr_lv2'] = $v['addr_lv2'];
		}
		$this->_viewData['addr_list'] = $adinfo;

        //判断商品下单时是否用填写身份证号或上传身份证图片
        $is_reg_num = 0;
        $is_reg_img = 0;
        $is_reg_all = 0;
        foreach($goods_list as $is_reg){
            if($is_reg['is_require_id'] == 1){
                if($is_reg['require_type'] ==1){
                    //需要身份证号码
                    $is_reg_num = 1;
                    continue;
                }elseif($is_reg['require_type'] ==2){
                    //需要身份证图片
                    $is_reg_img = 2;
                    continue;
                }elseif($is_reg['require_type'] ==3){
                    //需要身份证图片和号码
                    $is_reg_all = 3;
                    continue;
                }
            }
        }
		$this->_viewData['is_reg_num'] = $is_reg_num;
		$this->_viewData['is_reg_img'] = $is_reg_img;
        $this->_viewData['is_reg_all'] = $is_reg_all;

//        $doba_goods = $this->m_trade->have_doba_goods($goods_list);
//        if($doba_goods)
//        {
//            $this->_viewData['address_max_length'] = 25;
//        }else{
//            $this->_viewData['address_max_length'] = 255;
//        }
		$this->_viewData['clear_cart'] = 1;

		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');

//        $etime=microtime(true);
//        $execute_time = $etime-$stime;
//        $this->m_debug->log(__LINE__."cart_checkout->execute_time:".$execute_time);

		parent::index('mall/', $page);
	}

	//保存新增地址
	public function do_save_user_addr()
	{
		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = "user logged out";
			echo json_encode($ret_data);
			exit;
		}
		$uid = $this->_userInfo['id'];

		$attr = $this->input->post();

		$rules = array(
			'country' => "required|max:64",
			'addr_lv2' => "max:64",
			'addr_lv3' => "max:64",
			'addr_lv4' => "max:64",
			'addr_lv5' => "max:64",
			'address_detail' => "required|max:255",
			'consignee' => "required|max:255",
			'phone' => "required|between:6,16",
			'reserve_num' => "between:6,20",
			'zip_code' => "max:16",
			'uid' => "required|integer|in:{$uid}",
			'is_default' => "required|boolean",
			'customs_clearance' => "max:32",
		);

        $this->load->model("m_validate");
        $this->m_validate->validate_deliver_address($attr);

		//如果是美国地址,新的验证规则
		if($attr['country'] == 840)
		{
			//拼接姓名
			$attr['consignee'] = $attr['first_name'].' '.$attr['last_name'];
		}
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

        //限制收货地址每个语言的个数上限为5个
//        $count = $this->db->from('trade_user_address')->where('uid',$attr['uid'])->where('country',$attr['country'])->count_all_results();
        $this->load->model("tb_trade_user_address");
        if ($attr['country'] == 156) {
            if ($attr['addr_lv2'] == 81) {
//                $count = $this->db->from('trade_user_address')->where('uid',$uid)->where(array('country'=>$attr['country'],'addr_lv2 '=>'81'))->count_all_results();
                $where = ["uid"=>$uid,"country"=>$attr['country'],"addr_lv2"=>'81'];
                $count = $this->tb_trade_user_address->get_counts($where);
            } else {
//                $count = $this->db->from('trade_user_address')->where('uid',$uid)->where(array('country'=>$attr['country'],'addr_lv2 !='=>'81'))->count_all_results();
                $where = ["uid"=>$uid,"country"=>$attr['country'],"addr_lv2 !="=>'81'];
                $count = $this->tb_trade_user_address->get_counts($where);
            }
        }  else {
//            $count = $this->db->from('trade_user_address')->where('uid',$uid)->where(array('country'=>$attr['country']))->count_all_results();
            $where = ["uid"=>$uid,"country"=>$attr['country']];
            $count = $this->tb_trade_user_address->get_counts($where);
        }
        if($count >= 5){
            $ret_data['code'] = 105;
            $ret_data['msg'] = lang('user_addr_full');
            echo json_encode($ret_data);
            exit;
        }

        $this->load->model('m_trade');
		$ret = $this->m_trade->add_deliver_address($attr);

		if (TRUE !== $ret)
		{
			$ret_data['code'] = 103;
			echo json_encode($ret_data);
			exit;
		}


		echo json_encode($ret_data);
		exit;
	}

	public function do_edit_user_addr()
	{
		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = "user logged out";
			echo json_encode($ret_data);
			exit;
		}
		$uid = $this->_userInfo['id'];

		$attr = $this->input->post();
		$rules = array(
			'country' => "required|max:64",
			'addr_lv2' => "max:64",
			'addr_lv3' => "max:64",
			'addr_lv4' => "max:64",
			'addr_lv5' => "max:64",
			'address_detail' => "required|max:255",
			'consignee' => "required|max:255",
			'phone' => "required|between:6,16",
			'reserve_num' => "between:6,20",
			'zip_code' => "max:16",
			'id' => "required|integer",
		);

		$this->load->model("m_validate");
        $this->m_validate->validate_deliver_address($attr);

		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		$ret = $this->m_trade->edit_deliver_address($attr, $uid);

		if (TRUE !== $ret)
		{
			$ret_data['code'] = 103;
			echo json_encode($ret_data);
			exit;
		}

		echo json_encode($ret_data);

        exit;
	}

	public function do_set_default_addr()
	{
//	    $this->xhprof_start();
		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 103;
			$ret_data['msg'] = "user logout";
			echo json_encode($ret_data);
			exit;
		}

		$attr = $this->input->post();
		$rules = array(
			'id' => "required|integer",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		$ret = $this->m_trade->set_default_deliver_address($attr['id'], $this->_userInfo['id']);
		if (TRUE !== $ret)
		{
			$ret_data['code'] = 103;
			echo json_encode($ret_data);
			exit;
		}

		echo json_encode($ret_data);
//		$this->xhprof_finish("do_set_default_address");
		exit;
	}

	public function do_delete_addr()
	{
		$ret_data = array(
			'code' => 0,
			'msg' => "",
		);

		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 103;
			$ret_data['msg'] = "user logout";
			echo json_encode($ret_data);
			exit;
		}

		$attr = $this->input->post();
		$rules = array(
			'id' => "required|integer",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		$ret = $this->m_trade->delete_deliver_address($attr['id'], $this->_userInfo['id']);
		if (TRUE !== $ret)
		{
			$ret_data['code'] = 103;
			echo json_encode($ret_data);
			exit;
		}

		echo json_encode($ret_data);
		exit;
	}

	public function do_checkout_order()
	{
		$ret_data = array(
			'code' => 0,
			'msg' => "",
			'id' => null,
		);

        //货币汇率
		$currency_map = array();
		foreach ($this->_viewData['currency_all'] as $v)
		{
			$currency_map[$v['currency']] = $v['rate'];
		}

		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 103;
			$ret_data['msg'] = "user logout";
			echo json_encode($ret_data);
			exit;
		}

		$uid = $this->_userInfo['id'];

		// 进行 submit token 校验
		/* $submit_token = get_cookie('submit_token');
		if (empty($submit_token) || $submit_token != md5("tpssubmittoken{$uid}")) {
			$ret_data['code'] = 103;
			$ret_data['msg'] = lang('checkout_order_token_error');
			echo json_encode($ret_data);
			exit;
		} */

		$attr = $this->input->post();

		//中国需要同意购买协议
		if($this->_curLanguage == 156)
        {
            if($attr['protocol'] !== "checked")
            {
                $ret_data['code'] = 103;
                $ret_data['msg'] = lang('register_agreement');
                echo json_encode($ret_data);
            }
        }

        //m by brady.wang 冬季不发货商品校验
        $this->load->model("M_order",'m_order');
        $this->m_order->goods_undeliver_check_4order1($attr);

		$rules = array(
			'customer_id' => "required|integer",
			'shopkeeper_id' => "required|in:".$this->_viewData['store_id'],
			'addr_id' => "required|integer",
			'deliver_time_type' => "required|in:1,2,3",
			'deliver' => "required|array",
			'remark' => "max:128",
			'need_receipt' => "required|boolean",
			'clear_cart' => "required|boolean",
		);

		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		/**验证下单地址是否正确**/
        $this->load->model("m_validate");
        $this->m_validate->validate_deliver_address(null,$attr['addr_id']);

		//取生成订单的goods_sn
        $goods_sn = "";
		foreach($attr['deliver'] as $k=>$v)
        {
            if(empty($v)or !is_array($v))
            {
                continue;
            }
            foreach($v as $k2=>$v2)
            {
                if(empty($v2) or !is_array($v2)){
                    continue;
                }
                foreach($v2 as $k3=>$v3)
                {
                    $goods_sn .= $v3['goods_sn'].",";
                }
            }
        }
        $goods_sn = trim($goods_sn,",");
        
        //校验表单是否重复提交
        if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'submit_order_token', $attr)) {
        	$ret_data['code'] = 103;
        	$ret_data['msg'] = lang('checkout_order_token_error');
        	echo json_encode($ret_data);
        	exit;
        }
        
		//生成订单
		$ret = $this->m_trade->make_order(
			$attr,
			$this->_viewData['curCur'],
			$this->_viewData['cur_rate'],
			$currency_map
		);

		if (FALSE === $ret or empty($ret) or null === $ret)
		{
			$ret_data['code'] = $this->m_trade->get_err_code();
			if ($ret_data['code'] == 1039)
			{
				$ret_data['msg'] = lang('checkout_order_low_stocks');
			}
            else if($ret_data['code'] == 1040)
            {
                $ret_data['msg'] = lang('understock');
            }
			else
			{
				$ret_data['msg'] = lang('try_again');
			}
			echo json_encode($ret_data);
			exit;
		}
		$ret_data['id'] = $ret;

		if ($attr['clear_cart'] == 1)
		{
            $this->load->model("tb_user_cart");
            //把已生成订单购物车删除
            $this->tb_user_cart->remove_batch($this->_viewData['store_id'],$this->_viewData['curLocation_id'],
                $goods_sn,$this->get_user_id());
            // 清空购物车
//          $customer_id = $attr['customer_id'];
//			$this->tb_user_cart->clear_cart($this->_viewData['store_id'],
//                $this->_viewData['curLocation_id'],$customer_id);
		}

		echo json_encode($ret_data);
		exit;
	}

	public function pay($order_id)
	{
		// 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();

		// 货币
		$this->_viewData['currency_all'] = $this->m_global->getCurrencyList();

		// 语种
		$this->_viewData['language_all'] = $this->m_global->getLangList();

		$this->_viewData['order_id'] = $order_id;

		$order_info = $this->m_trade->get_order_data($order_id);
		if ($order_info === FALSE)
		{
			redirect('http://mall.'.get_public_domain().'/cart');exit;
		}
		if ($order_info['order_detail']['txn_id'] || ($order_info['order_detail']['status'] > Order_enum::STATUS_CHECKOUT
			&& $order_info['order_detail']['status'] != Order_enum::STATUS_COMPONENT))
		{
			//已经付款
			redirect('ucenter/my_orders_new');exit;
		}
		$this->_viewData['order_info'] = $order_info;

		$payments = $this->m_global->getPayments('USD',$this->_userInfo['id']);

		if($this->_curCurrency == $order_info['order_detail']['currency']){ /** 如果汇率相等 */
			$icon = $this->m_currency->get_icon_by_currency($order_info['order_detail']['currency']);
            $pay_amount_show = $icon['icon'].number_format($order_info['order_detail']['order_amount'] / 100,2);
		}else{
			$pay_amount_show = $this->m_currency->price_format($order_info['order_detail']['order_amount_usd'],$this->_curCurrency);
		}

		$my_amount = $this->m_currency->price_format($this->_userInfo['amount']*100,$this->_curCurrency);

		$this->_viewData['payments'] = $payments;
        $this->_viewData['pay_amount_show'] = $pay_amount_show;     //页面上显示的订单金额
		$this->_viewData['my_amount'] = $my_amount;
		$this->_viewData['disabled_amount'] = $order_info['order_detail']['order_amount_usd']/100 > $this->_userInfo['amount'] ? 'disabled' : '';

		parent::index('mall/', 'pay');
	}

        //报名费支付
        public function sign_up_pay() {
            header("Content-Type: text/html; charset=UTF-8");
            $user=$this->_userInfo;
            if (!isset($user['id'])) {
                $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>未登录！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
            }
            if (!in_array($user['sale_rank'], array(4,5))) {
                $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>不符合参与资格！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
            }
            $mvp_list = $this->db->select('order_id')->from('mvp_list')->where('uid', $user['id'])->order_by("id", "desc")->get()->row_array();
            if(isset($mvp_list['order_id'])){
                $order_info = $this->m_trade->get_order_data($mvp_list['order_id']);
            }
            //查询是否有报名费订单
            if(isset($mvp_list['order_id'])&&  !in_array($order_info['order_detail']['status'], array('90'.'97','99'))){
                $order_id=$mvp_list['order_id'];
            }  else {
                $this->load->model('m_order');
                $order_id= $this->m_order->sign_up_order($user['id']);
            }
            if (!isset($order_id)) {
                $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>异常！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
            }
            // 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();

		// 货币
		$this->_viewData['currency_all'] = $this->m_global->getCurrencyList();

		// 语种
		$this->_viewData['language_all'] = $this->m_global->getLangList();

		$this->_viewData['order_id'] = $order_id;

		$order_info = $this->m_trade->get_order_data($order_id);
		if ($order_info === FALSE)
		{
			$str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>异常！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
		}
		if ($order_info['order_detail']['txn_id'] || ($order_info['order_detail']['status'] > Order_enum::STATUS_CHECKOUT&&$order_info['order_detail']['status'] < '10'
			&& $order_info['order_detail']['status'] != Order_enum::STATUS_COMPONENT))
		{
			//已经付款
			$str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>已经报名成功！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
		}
		$this->_viewData['order_info'] = $order_info;

		$payments = $this->m_global->getPayments('USD',$this->_userInfo['id'],$this->is_mobile);

		if($this->_curCurrency == $order_info['order_detail']['currency']){ /** 如果汇率相等 */
			$icon = $this->m_currency->get_icon_by_currency($order_info['order_detail']['currency']);
            $pay_amount_show = $icon['icon'].number_format($order_info['order_detail']['order_amount'] / 100,2);
		}else{
			$pay_amount_show = $this->m_currency->price_format($order_info['order_detail']['order_amount_usd'],$this->_curCurrency);
		}

		$my_amount = $this->m_currency->price_format($this->_userInfo['amount']*100,$this->_curCurrency);

		$this->_viewData['payments'] = $payments;
        $this->_viewData['pay_amount_show'] = $pay_amount_show;     //页面上显示的订单金额
		$this->_viewData['my_amount'] = $my_amount;
		$this->_viewData['disabled_amount'] = $order_info['order_detail']['order_amount_usd']/100 > $this->_userInfo['amount'] ? 'disabled' : '';

		parent::index($this->is_mobile.'/', 'pay');
//                parent::index('mobile/', 'pay');
        }
        //直播费支付
        public function live_up_pay() {
//            if(strtolower($_SERVER['REQUEST_METHOD'])!='post'){
//                exit('error');
//            }
            $mkey='TPs1#)8!6';
            $data = $this->input->get();
            if($data['token']!=md5($data['orderNo'].$data['url'].$mkey)){
                exit('The signature failed');
            }
            header("Content-Type: text/html; charset=UTF-8");
            $this->load->model('m_order');
            $order_id= $this->m_order->live_up_order($data);
            if (!isset($order_id)) {
                $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>异常！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
            }
            set_cookie($order_id.'url',$data['url'],360);//存跳转地址
            // 头部信息
		$this->_viewData['title']=lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();
		// 货币
		$this->_viewData['currency_all'] = $this->m_global->getCurrencyList();

		// 语种
		$this->_viewData['language_all'] = $this->m_global->getLangList();

		$this->_viewData['order_id'] = $order_id;

		$order_info = $this->m_trade->get_order_data($order_id);
		if ($order_info === FALSE)
		{
			$str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>异常！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/';}setTimeout('showTime()',1000);}showTime();</script>";
                        echo $str;exit;
		}
		$this->_viewData['order_info'] = $order_info;

		$payments = $this->m_global->getPayments('USD',99999999,$this->is_mobile);

		if($this->_curCurrency == $order_info['order_detail']['currency']){ /** 如果汇率相等 */
			$icon = $this->m_currency->get_icon_by_currency($order_info['order_detail']['currency']);
            $pay_amount_show = $icon['icon'].number_format($order_info['order_detail']['order_amount'] / 100,2);
		}else{
			$pay_amount_show = $this->m_currency->price_format($order_info['order_detail']['order_amount_usd'],$this->_curCurrency);
		}

		$my_amount = $this->m_currency->price_format($this->_userInfo['amount']*100,$this->_curCurrency);

		$this->_viewData['payments'] = $payments;
        $this->_viewData['pay_amount_show'] = $pay_amount_show;     //页面上显示的订单金额
		$this->_viewData['my_amount'] = $my_amount;
		$this->_viewData['disabled_amount'] = $order_info['order_detail']['order_amount_usd']/100 > $this->_userInfo['amount'] ? 'disabled' : '';

//		parent::index($this->is_mobile.'/', 'live_pay');
                parent::index('mobile/', 'live_pay');
        }
	/** 余额 支付 商城 订单 */
	public function go_amount_pay(){
		$data = $_REQUEST;
		if(!isset($data['order_id']) || !$data['order_id'] ){
			redirect(base_url('/'));
		}
		if(!isset($data['payment_method']) || $data['payment_method'] != '110'){
			redirect('order/pay/'.$data['order_id']) ;exit;
		}
		$order = $this->m_trade->get_order_info($data['order_id']);

		//添加一个会员状态 退会中: 6 ；User:Ckf,改状态下不能余额支付
		if($this->_userInfo['status'] == 6){
			redirect('order/pay/'.$data['order_id']) ;exit;
		}

		if(!$order || $order['txn_id'] || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT) ){//已经付款
			redirect('ucenter/my_orders_new');exit;
		}

		if($this->_userInfo['id'] != $order['customer_id']){ //uid不对等
			redirect('ucenter/my_orders_new');exit;
		}

		if($order['money'] > $this->_userInfo['amount']*100){ //余额不足
			redirect('order/pay/'.$data['order_id']) ;exit;
		}
                if(($this->_userInfo['amount']*100)<0){ //現金池金額不足
			redirect('order/pay/'.$data['order_id']) ;exit;
		}
                if($order['money']<0){ //訂單金額有誤
			redirect('order/pay/'.$data['order_id']) ;exit;
		}

		if(!$this->checkTakeOutPwd($data['pay_pwd'])){  //支付密码错误
			redirect('order/pay/'.$data['order_id']) ;exit;
		}

		$this->load->model('m_order');
		$order['order_id'] = $data['order_id']; //兼容 mall_general_order_paid函數的字段
		$order_type = $this->m_order->get_order_type($order['order_id']);

//		//防止重复提交
		$redis_key = md5(serialize($data['order_id']));
		$this->load->model('o_trade');
		$res = $this->o_trade->redis_anti_repeat($redis_key);
		//缓存存在该值，阻止重复提交
		if($res){
			return false;
		}

		//防重支付
//		if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'pay_for_order_11', $order['order_id'], 300)) {
//			return false;
//		}

		if($order_type){
			//產品套裝
			$payFunc = 'mall_order_paid';
		}else{
			//普通訂單
			$payFunc = 'mall_general_order_paid';
		}
		if($payFunc === 'mall_order_paid'){ //  兼容以前的功能
			$order['join_fee'] = $order_type['amount'];
			$order['level'] = $order_type['level'];
		}

//		if($order['notify_num'] != 0 ){
//			$this->db->where('order_id',$order['order_id'])->update('trade_orders',array('notify_num'=>0));
//			$this->load->model("tb_trade_orders");
//			$this->tb_trade_orders->update_one(['order_id'=>$order['order_id']],array('notify_num'=>0));
//		}

		$status = $this->m_order->$payFunc($order,'TPS'.$order['order_id'], Order_enum::STATUS_SHIPPING);
		if($status === TRUE){
			$result['order_id'] = $order['order_id'];
			$result['success'] = 1;
		}else{
			$result['success'] = 0;
			$result['msg'] = 'Pay failure';
		}

		if($this->_curCurrency == $order['currency']){ /** 如果汇率相等 */
			$icon = $this->m_currency->get_icon_by_currency($order['currency']);
			$pay_amount = $icon['icon'].number_format($order['order_amount'] / 100,2);

		}else{
			$pay_amount = $this->m_currency->price_format($order['order_amount_usd'],$this->_curCurrency);
		}

		//百度统计订单信息 需要用到的订单数据 --- leon        start
		//输出订单信息到页面做统计
		//获取商品分类
		$order_info = array();
		$order_info['order_id'] = $order['order_id'];
		$order_info['order_total'] = $order['order_amount_usd'] / 100;
		//获取商品分类id
		$c_list = '';
		foreach($order['goods_list'] as $k => $v){
			$c_list .= "'".$v['cate_id']."',";
		}
		$c_list = rtrim($c_list,',');
		$cate_list = $this->db->query("select cate_id,cate_name from mall_goods_category where cate_id in ($c_list)")->result_array();
		foreach($cate_list as $k => $v){
			$cate_arr[$v['cate_id']] = $v['cate_name'];
		}
		foreach($order['goods_list'] as $k => $v){
			$order_info['order_list'][$k]['sku_id'] = $v['goods_sn'];
			$order_info['order_list'][$k]['sku_name'] = $v['goods_name'];
			$order_info['order_list'][$k]['category'] = $cate_arr[$v['cate_id']];
			$order_info['order_list'][$k]['price'] = $v['goods_price'];
			$order_info['order_list'][$k]['quantity'] = $v['goods_number'];
		}
		$this->_viewData['order_info']=$order_info;
		//百度统计订单信息 需要用到的订单数据 --- leon        end

		$this->_viewData['title']=lang('alipay').' - '.lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();
		$this->_viewData['result']=$result;
		$this->_viewData['amount']= $pay_amount;

		parent::index($this->is_mobile.'/','respond');
	}

	/** 验证支付信息 */
	public function check_pay_info(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			$result = array('success'=>1,'msg'=>'');
			if(!isset($data['order_id']) || !$data['order_id'] ){
				$result['success'] = 0;
				$result['msg'] = 'Hacker';
				die(json_encode($result));
			}
//          if(substr($data['order_id'], 0, 1 )!='S'){//订单号首字母为S，报名费订单，不判断商品是否下架
            if(!in_array(substr($data['order_id'], 0, 1 ), array('S','L'))){
                $this->load->model('m_order');
                $check_goods = $this->m_order->test_order_product($data['order_id']);
                if($check_goods['success'] == 0){
                        die(json_encode($check_goods));
                }
            }
			if(!isset($data['payment_method']) || $data['payment_method'] == ''){
				$result['success'] = 0;
				$result['msg'] = lang('pls_sel_payment');
				die(json_encode($result));
			}
			$order = $this->m_trade->get_order_info($data['order_id']);

			if(!$order || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT&& $order['status'] < '10' )){//已经付款
				$result['success'] = 0;
				$result['msg'] = 'Hacker';
				die(json_encode($result));
			}

            //换货订单
//            print_r($order);exit;
            if($order['order_type'] == 5){
                $strlen = strlen($order['remark']);
                $tp = strpos($order['remark'],"#");  //limit之前的字符长度
                $p_order_id = substr($order['remark'],-$strlen,$tp);  //从头开始截取到指字符位置。
                $order_info = $this->tb_trade_orders->getOrderInfo($p_order_id, array('status'));
                if($order_info['status'] != '90'){
                    $result['success'] = 0;
                    $result['msg'] = lang('exchange_ok');
                    die(json_encode($result));
                }
            }
            if(!in_array(substr($data['order_id'], 0, 1 ), array('L'))){
                if($this->_userInfo['id'] != $order['customer_id']){
                    $result['success'] = 0;
                    $result['msg'] = 'Hacker';
                    die(json_encode($result));
                }
            }
            if($order['discount_type']=='2'){
                $this->load->model('m_coupons');
                $coupons_info = $this->m_coupons->get_coupons_list($this->_userInfo['id']);
                $yanz=(int)$order['goods_amount_usd']-(int)$coupons_info['total_money']*100;
                if($yanz!=$order['order_amount']){
                    $result['success'] = 0;
                    $result['msg'] = lang('admin_return_fee_tip1');
                    die(json_encode($result));
                }
            }
			if($data['payment_method'] == 110){  //如果是余额支付
                $nums=(int)$order['money'] - (int)($this->_userInfo['amount']*100);
                // $this->load->model('m_debug');
                // $this->m_debug->log((int)$order['money'].'：订单金额');
                // $this->m_debug->log((int)($this->_userInfo['amount']*100).'：现金池金额');
                // $this->m_debug->log($nums.'：差额');

                //添加一个会员状态 退会中: 6 ；User:Ckf,该状态下不能余额支付
                if($this->_userInfo['status'] == 6){
                    $result['success'] = 0;
                    $result['msg'] = lang('signouting_not_pay');
                    die(json_encode($result));
                }

				if($nums>0){
					$result['success'] = 0;
					$result['msg'] = lang('cur_commission_lack');
					die(json_encode($result));
				}
                $this->load->model('tb_users');
                $error_num=$this->tb_users->checkTakeOutPwd($this->_userInfo['id'],$data['pay_pwd']);
				if(!$error_num['is']){
					$result['success'] = 0;
					$result['msg'] = 5-$error_num['num']?lang('pls_input_correct_take_out_pwd'):lang('pls_pwd_retry');
					die(json_encode($result));
				}
			}
			if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'pay_for_order', $data, 10)) {
				$result['success'] = 0;
				$result['msg'] = '正在支付, 请稍候';
				die(json_encode($result));
			}
			die(json_encode($result));
		}
	}

	public function checkTakeOutPwd($pwd){
		$this->load->model('m_user');
		if($this->m_user->encyTakeCashPwd($pwd,$this->_userInfo['token']) !==$this->_userInfo['pwd_take_out_cash']){
			return false;
		}
		return TRUE;
	}

	/**
	 * 得到異步通知記錄
	 */
	public function get_orders_notify(){
		if($this->input->is_ajax_request()){
			$order_id = $this->input->post('order_id');
			$count = $this->db->from('logs_orders_rollback')->where('order_id',$order_id)->count_all_results();
			die(json_encode(array('success'=>$count)));
		}
	}

	/**
	 * 上传身份证验证-----下单时需要验证的商品
	 */
    public function upScan() {
        $addr_id = $this->input->post('addr_id');//收货人id
        if(isset($_FILES['ID_front'])) { //上传图片
            $picname = $_FILES['ID_front']['name'];
            $picsize = $_FILES['ID_front']['size'];
			$pathImg = '';
            if ($picname != "") {
                if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
                    echo json_encode(lang('id_card_scan_too_large'));
                    exit;
                }
                $type = strstr($picname, '.'); //限制上传格式
                if (strtolower($type) != ".gif" && strtolower($type) != ".jpg" && strtolower($type)!=".bmp" && strtolower($type)!=".png") {
                    echo json_encode(lang('id_card_scan_type_ext_error'));
                    exit;
                }

                //防止上传图片名重名
                do{
                    $rand = rand(100, 999);
                    $pathImg = 'consignee_card/'.md5(date("His") . $rand) . $type;//待保存的图片路径
                    $this->load->model("tb_trade_user_address");
                    $count = $this->tb_trade_user_address->get_counts(["ID_front"=>$pathImg],["ID_reverse"=>$pathImg]);
                    //$count =  $this->db->from('trade_user_address')->where('ID_front',$pathImg)->or_where('ID_reverse',$pathImg)->count_all_results();
//                    echo $this->db->last_query();exit;
                }
                while ($count > 0); //如果是重复路径名则重新生成名字

                /*上传图片*/
                $this->load->model('m_do_img');
                $result = $this->m_do_img->upload($pathImg,$_FILES['ID_front']['tmp_name']);
                if(!$result){
                    echo json_encode(lang('card_upload_error'));//上传失败
                    exit;
                }
                //保存图片到地址表，身份证信息跟收货人对应
                $this->load->model("tb_trade_user_address");
                $addr_info = $this->tb_trade_user_address->get_deliver_address_by_id($addr_id);
                //$this->db->select('*')->where('id', $addr_id)->get('trade_user_address')->row_array();

                //获取身份证信息和收货人
                $adds_uid = isset($addr_info['uid']) ? $addr_info['uid'] : '';
                $consignee = isset($addr_info['consignee']) ? $addr_info['consignee'] : '';

                //修改该收货人的证件信息
                $this->tb_trade_user_address->update_batch(["uid"=>$adds_uid,"consignee"=>$consignee],["ID_front"=>$pathImg]);
                //$this->db->where('uid', $adds_uid)->where('consignee', $consignee)->update('trade_user_address', array('ID_front' => $pathImg,));

            }
            $size = round($picsize / 1024, 2); //转换成kb
            $arr = array(
                'name' => $picname,
                'pic' => $pathImg,
                'size' => $size,
                'picUrl' => config_item('img_server_url').'/'.$pathImg,
                'delete' => lang('checkout_deliver_delete')
            );
            echo json_encode($arr);
        }else{
            echo '';exit;
        }
    }

    public function delImg(){
        $action = $this->input->get('act');
        if ($action == 'delimg') { //删除图片
            $filename = $this->input->post('imagename');
            if (!empty($filename)) {
                $this->load->model('m_do_img');
                $result = $this->m_do_img->delete($filename);
				if(!$result){
                    echo json_encode(array('msg' => 2,'zm' => lang('card_img_zm'),'bm' => lang('card_img_bm')));
                }
                //删除数据库的记录
                $consignee = $this->input->post('consignee');//收货人id
                $back = $this->input->get('back');//0正面，1背面
                if($back == 0){
                    $this->load->model("tb_trade_user_address");
                    $this->tb_trade_user_address->update_batch(["uid"=>$this->_userInfo['id'],"consignee"=>$consignee],
                        ["ID_front"=>""]);
                    //$this->db->where('consignee',$consignee)->where('uid',$this->_userInfo['id'])->update('trade_user_address', array('ID_front' => '',));
                }elseif($back == 1){
                    $this->load->model("tb_trade_user_address");
                    $this->tb_trade_user_address->update_batch(["uid"=>$this->_userInfo['id'],"consignee"=>$consignee],
                        ["ID_reverse"=>""]);
//                    $this->db->where('consignee',$consignee)->where('uid',$this->_userInfo['id'])->update('trade_user_address', array(
//                        'ID_reverse' => '',
//                    ));
                }
                echo json_encode(array('msg' => 1,'zm' => lang('card_img_zm'),'bm' => lang('card_img_bm')));
            } else {
                echo json_encode(array('msg' => 2,'zm' => lang('card_img_zm'),'bm' => lang('card_img_bm')));
            }
        }
    }
    public function upScan2() {
        $addr_id = $this->input->post('addr_id');//收货人id
        if(isset($_FILES['ID_reverse'])) { //上传图片
            $picname = $_FILES['ID_reverse']['name'];
            $picsize = $_FILES['ID_reverse']['size'];
            $pathImg = '';
            if ($picname != "") {
                if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
                    echo json_encode(lang('id_card_scan_too_large'));
                    exit;
                }
                $type = strstr($picname, '.'); //限制上传格式
                if (strtolower($type) != ".gif" && strtolower($type) != ".jpg" && strtolower($type)!=".bmp" && strtolower($type)!=".png") {
                    echo json_encode(lang('id_card_scan_type_ext_error'));
                    exit;
                }

                //防止上传图片名重名
                do{
                    $rand = rand(100, 999);
                    $pathImg = 'consignee_card/'.md5(date("His") . $rand) . $type;//待保存的图片路径
                    $this->load->model("tb_trade_user_address");
                    $count = $this->tb_trade_user_address->get_counts(["ID_front"=>$pathImg],["ID_reverse"=>$pathImg]);
                    //$count =  $this->db->from('trade_user_address')->where('ID_front',$pathImg)->or_where('ID_reverse',$pathImg)->count_all_results();
//                    echo $this->db->last_query();exit;
                }
                while ($count > 0); //如果是重复路径名则重新生成名字

                /*上传图片*/
                $this->load->model('m_do_img');
                $result = $this->m_do_img->upload($pathImg,$_FILES['ID_reverse']['tmp_name']);
                if(!$result){
                    echo json_encode(lang('card_upload_error'));//上传失败
                    exit;
                }
                //保存图片到地址表，身份证信息跟收货人对应
                $this->load->model("tb_trade_user_address");
                $addr_info = $this->tb_trade_user_address->get_deliver_address_by_id($addr_id);
//                $addr_info = $this->db
//                    ->select('*')
//                    ->where('id', $addr_id)
//                    ->get('trade_user_address')
//                    ->row_array();

                //获取身份证信息和收货人
                $adds_uid = isset($addr_info['uid']) ? $addr_info['uid'] : '';
                $consignee = isset($addr_info['consignee']) ? $addr_info['consignee'] : '';

                //修改该收货人的证件信息
                $this->tb_trade_user_address->update_batch(["uid"=>$adds_uid,"consignee"=>$consignee],
                    ['ID_reverse' => $pathImg]);
//                $this->db->where('uid', $adds_uid)->where('consignee', $consignee)->update('trade_user_address', array(
//                    'ID_reverse' => $pathImg,
//                ));
            }
            $size = round($picsize / 1024, 2); //转换成kb
            $arr = array(
                'name' => $picname,
                'pic' => $pathImg,
                'size' => $size,
                'picUrl' => config_item('img_server_url').'/'.$pathImg,
                'delete' => lang('checkout_deliver_delete')
            );
            echo json_encode($arr); //输出json数据
        }else{
            echo '';exit;
        }
    }


	//修改订单的新地址
	public function do_save_user_addr_order()
	{
		$ret_data = array(
				'code' => 0,
				'msg' => "",
		);

		if (empty($this->_userInfo)) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = "user logged out";
			echo json_encode($ret_data);
			exit;
		}
		$order_id = $this->input->post('order_id',true);
		if (empty($order_id)) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = "order_id required";
			echo json_encode($ret_data);
			exit;
		}
		$uid = $this->_userInfo['id'];
		$phone = $this->_userInfo['mobile'];
		$attr = $this->input->post();
		unset($attr['order_id']);
		$rules = array(

				'country' => "required|max:64",
				'addr_lv2' => "max:64",
				'addr_lv3' => "max:64",
				'addr_lv4' => "max:64",
				'addr_lv5' => "max:64",
				'address_detail' => "required|max:255",
				'consignee' => "required|max:255",
				'zip_code' => "max:16",
				'uid' => "required|integer|in:{$uid}",
				'is_default' => "required|boolean",
				//'customs_clearance' => "max:32",
		);

		//如果是美国地址,新的验证规则
		if ($attr['country'] == 840) {
			$this->load->model('m_validate');
			$this->m_validate->verify_addr_for_us($attr);
			//拼接姓名
			$attr['consignee'] = $attr['first_name'] . ' ' . $attr['last_name'];
		}

		if (TRUE !== $this->validator->validate($attr, $rules)) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = $this->validator->get_err_msg();
			echo json_encode($ret_data);
			exit;
		}

		//应测试要求 增加数字验证
		if(empty($attr['phone'])) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = lang('phone_not_null');
			echo json_encode($ret_data);
			exit;
		}

		//短信验证码
		if($attr['country'] == '156' && $attr['addr_lv2'] != 81 && $this->_userInfo['country_id'] == 1) {
			$mobile_code = trim($this->input->post("mobile_code"));

			//验证码非空
			if (empty($mobile_code)) {
				$ret_data['code'] = 101;
				$ret_data['msg'] = lang("phone_code_not_null");
				echo json_encode($ret_data);
				exit;
			}

			//验证码格式
			if(!preg_match('/\d{4,6}/',$mobile_code)) {
				$ret_data['code'] = 101;
				$ret_data['msg'] = lang("phone_code_rule_error");
				echo json_encode($ret_data);
				exit;
			}

			//验证码是否正确
			try {
				$this->load->model("tb_mobile_message_log");

				$this->tb_mobile_message_log->verify_mobile_code($phone, $mobile_code);
			} catch(Exception $e){
				$this->load->model("service_message_model");
				$this->service_message_model->error_response($e->getMessage());
			}

			$this->tb_mobile_message_log->delete_code($phone); //删除验证码

		}

		if ($attr['country'] != 410) {
			if(!preg_match('/^\d{8,13}$/',$attr['phone'])) {

				$ret_data['code'] = 101;
				$ret_data['msg'] = lang('phone_check_length');
				echo json_encode($ret_data);
				exit;
			}
		}


		// 韩国必须填海关报关号
		if ($attr['country'] == 410 && empty($attr['zip_code'])) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = " Zip code field is required.";
			echo json_encode($ret_data);
			exit;
		}
		$old_freight  = $this->input->post('old_freight');

		//获取订单详情

		$order = $this->m_trade->get_order_data($order_id);
		
		//增加判断 订单状态修改地址
		$order_status = $order['order_detail']['status'];
		if(!(in_array($order_status,[2,3]) && in_array($attr['country'],['156','344','410'])))
		{
			$ret_data['code'] = 1001;
			$ret_data['msg'] = "订单状态不允许修改地址";
			echo json_encode($ret_data);
			exit;
		}
		$new_arr = array();
		foreach($order['goods_info'] as $v) {
			$new_arr[] = $v['goods_sn'];
		}

        //m by brady.wang 冬季不发货商品校验
        $this->load->model("M_order",'m_order');
        $this->m_order->goods_undeliver_check($attr,$new_arr);

		if (isset($order['order_detail']['shopkeeper_id']) && (int)($order['order_detail']['shopkeeper_id']) > 0) {
			//地址提交前先验证运费
			if($attr['country'] != 410) {
				$res = $this->verify_freigh($order_id,$attr);
				$res = json_decode($res,true);
				if (!empty($res)) {
					if ($res['error'] == false) {
						if ($res['msg'] >$old_freight) {
							$ret_data['code'] = 103;
							$ret_data['msg'] = lang("order_fee_beyond");
							$ret_data['old'] = $old_freight;
							$ret_data['new'] = $res['msg'];
							echo json_encode($ret_data);
							exit;
						}
					}else {
						$ret_data['code'] = 103;
						$ret_data['msg'] = $res['msg'];
						echo json_encode($ret_data);
						exit;
					}
				}
//				else {
//					$ret_data['code'] = 103;
//					$ret_data['msg'] = 'get freight failed';
//					echo json_encode($ret_data);
//					exit;
//				}
			}
		}

		$this->db->trans_start();
		unset($attr['old_freight']);
//		$ret = $this->m_trade->add_deliver_address($attr);
//		if (TRUE !== $ret) {
//			$ret_data['code'] = 103;
//			echo json_encode($ret_data);
//			exit;
//		}
		//将地址更新到订单表里面
		$update_data = [];

		$attr['addr_lv3'] = !isset($attr['addr_lv3']) ? '' : $attr['addr_lv3'];
		$attr['addr_lv4'] = !isset($attr['addr_lv4']) ? '' : $attr['addr_lv4'];
		$attr['addr_lv5'] = !isset($attr['addr_lv5']) ? '' : $attr['addr_lv5'];
		if (empty($attr['country']) || empty($attr['addr_lv2']) || empty($attr['address_detail']) ) {
			$ret_data['code'] = 404;
			$ret_data['msg'] = "system error";
			echo json_encode($ret_data);
			exit;
		}
		$address_str = $this->tb_trade_addr_linkage->implode_address_info_by_attr($attr);
		if (!$address_str) {
			$ret_data['code'] = 500;
			$ret_data['msg'] = "system error";
			echo json_encode($ret_data);
			exit;
		}
		$update_data['address'] =  trim($address_str);
		//$update_data['area'] = $attr['country'];
		$update_data['consignee'] = $attr['consignee'];
		$update_data['reserve_num'] = $attr['reserve_num'];
		$update_data['zip_code'] = $attr['zip_code'];
		$update_data['phone'] = $attr['phone'];
		//$update_data['customs_clearance'] = $attr['customs_clearance'];
		$update_data['updated_at'] = date("Y-m-d H:i:s");

		$this->load->model("M_order", 'm_order');
		$update_res = $this->m_order->update_order_address($update_data, $order_id);
		if (!$update_res) {
			$ret_data['code'] = 101;
			$ret_data['msg'] = lang('update_address_failed');
			echo json_encode($ret_data);
			exit;
		}
		//记录3
		$log_contents = "修改订单地址";
        $this->load->model("m_trade");
		$this->m_trade->add_order_log($order_id,109,$log_contents,$uid);

        //修改订单信息同步到erp
        $insert_data = array();
        $insert_data['oper_type'] = 'modify';

        $insert_data['data'] = $update_data;
		$insert_data['data']['order_id'] = $order_id;
        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
		$this->db->trans_complete();
		//地址增加成功 将地址更新到订单里面
		$ret_data['code'] = 200;
		$ret_data['msg'] = lang('update_address_successed');
		echo json_encode($ret_data);
		exit;
	}

	public function getFreight2()
	{
		$order_id = $this->input->post('order_id', true);
		$addr = $this->input->post();
		$res = $this->verify_freigh($order_id, $addr);
		echo $res;
	}

	public  function verify_freigh($order_id, $addr)
	{
		$order_id = $this->input->post('order_id', true);
		$addr = $this->input->post();
		$addr['id'] = 1;
		$addr['is_default'] = 0;
		$addr['consignee'] = $addr['phone'] = $addr['reserve_num'] = $addr['address_detail'] = $addr['zip_code'] = $addr['addr_str'] = $addr['customs_clearance'] = $addr['first_name'] = $addr['last_name'] = $addr['city'] = $addr['city'] = $addr['ID_no'] = '';
		$addr['addr_lv3'] = isset($addr['addr_lv3']) ? $addr['addr_lv3'] : '';
		$addr['addr_lv4'] = isset($addr['addr_lv4']) ? $addr['addr_lv4'] : '';
		$addr['addr_lv5'] = isset($addr['addr_lv5']) ? $addr['addr_lv5'] : '';
		$ret_data = [
				'error' => true,
				'msg' => ''
		];

		try {
			if (empty($order_id) || empty($addr['addr_lv2'])  || empty($addr['country'])) {
				throw new Exception("params miss");
			}
			//根据订单获取产品信息
			//根据订单号获取订单详情
			$this->load->model('M_order', 'm_order');
			$this->load->model("m_trade");
//			$params = [
//					'select' => 'trade_orders.status,trade_orders.shipper_id,trade_orders.is_doba_order,trade_orders.deliver_fee, b.*',
//					'where' => [
//							'b.order_id' => $order_id
//					],
//					'join' => [
//							[
//									'table' => 'trade_orders_goods as b',
//									'where' => 'trade_orders.order_id = b.order_id',
//									'type' => 'inner'
//							]
//
//					]
//			];
//			$order_info = $this->m_order->get_by_where($params, false, false);

			$this->load->model("tb_trade_orders");
			$this->load->model("tb_trade_orders_goods");
			$where = ["order_id"=>$order_id];

			//取order信息
			$order = $this->tb_trade_orders->get_list_auto([
			   "select"=>"status,shipper_id,is_doba_order,deliver_fee",
                "where"=>$where
            ]);

			//未找到数据
			if (empty($order)) {
				throw new Exception("获取订单数据失败");
			}
			//取shipper_id,检测运费，是否支持配送
			$shipper_id = $order[0]['shipper_id'];

			//取order_goods
            $order_goods = $this->tb_trade_orders_goods->get_list_auto([
                "where"=>$where
            ]);

			// 获取购物车商品信息
			$goods_list = array();
			$new_arr = array();
			foreach ($order_goods as $goods_sn => $v) {
				$goods_list[] = array('goods_sn' => $v['goods_sn'], 'quantity' => $v['goods_number']);
				$new_arr[] = $v['goods_sn'];
			}

			$this->load->model("tb_user_cart");
			$goods_data = $this->tb_user_cart->get_goods_data(
					$goods_list,
					$this->_viewData['curLan_id'],
					$this->_viewData['curLan'],
					$this->_viewData['cur_rate']);
			//根据地质和商品获取运费

            //m by brady.wang 冬季不发货商品校验
            $this->load->model("M_order",'m_order');
            $this->m_order->goods_undeliver_check_4order2($addr,$new_arr);

			$addr_list = array($addr);

			$fee_res = $this->_viewData['data'] = $this->m_trade->get_checkout_data(
					$goods_data,
					$addr_list,
					$this->_viewData['curCur'],
					$this->_viewData['cur_rate'],
					$this->_viewData['currency_all']
			);

//			var_dump($fee_res[1]['deliverable_list']);var_dump($shipper_id);exit;
			if (isset($fee_res[1]['deliverable_list'][$shipper_id]['shipping_type'][0]['fee'])) {
				$fee = $fee_res[1]['deliverable_list'][$shipper_id]['shipping_type'][0]['fee'];
			} else {
				throw new Exception(lang("checkout_order_deliver_unable"));
			}

			$ret_data['error'] = false;
			$ret_data['msg'] = $fee;
			return json_encode($ret_data);

		} catch (Exception $ex) {
			$ret_data['error'] = true;
			$ret_data['msg'] = $ex->getMessage();
			return json_encode($ret_data);
		}
	}





}