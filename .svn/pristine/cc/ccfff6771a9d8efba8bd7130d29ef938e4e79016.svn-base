<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class choose_goods_for_coupons_checkout extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_coupons');
		$this->load->model('m_group');
        $this->load->model('m_split_order');
	}

	public function index()
	{
		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect = choose_goods_for_coupons_checkout');
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

        // 获取收货地址
        $uid = $this->_userInfo['id'];
        $this->load->model("tb_trade_user_address");
        $address_list = $this->tb_trade_user_address->get_deliver_address_list_by_uid($uid,$this->_viewData['curLocation_id']);

        if (empty($address_list))
        {
            $page = "choose_goods_checkout_new";
        }
        else
        {
            $page = "choose_goods_checkout";
            $this->_viewData['address_list'] = $address_list;
        }

		// 获取商品列表
		$all_product = $this->m_group->get_all_product();
		$this->_viewData['all_product'] = $all_product;

        $goods_sn_list = [];
        foreach($all_product['goods'] as $v) {
            $goods_sn_list[] = $v['goods_sn'];
        }
        $this->_viewData['goods_sn_list'] = $goods_sn_list;
        $adinfo = [];
        foreach($address_list as $k=>$v) {
            $adinfo[$k]['id']  = $v['id'];
            $adinfo[$k]['addr_lv2'] = $v['addr_lv2'];
        }
        $this->_viewData['addr_list'] = $adinfo;


		// 商品总价，固定美金结算
		$total_money = $this->m_coupons->get_product_total_money_coupons($all_product);
		$this->_viewData['total_money'] = $total_money;

		// 用户代品券信息：张数、总额
		$coupons_list = $this->m_coupons->get_coupons_list($uid);
		$this->_viewData['coupons_list'] = $coupons_list;

		// 实付金额 = 商品总价 - 代品券总额
		$pay_amount = $total_money - $coupons_list['total_money'];
		if ($pay_amount > 0)
		{
			$this->_viewData['real_pay_money'] = $pay_amount;
		}
		else
		{
			$this->_viewData['real_pay_money'] = 0;
		}

		// 配送方式与费用（单位分）
		$deliver_info = array(
			'type' => "checkout_order_deliver_type_express",
			'fee' => 0,
		);

        $this->_viewData['order_type'] = 'coupons';
		$this->_viewData['deliver_info'] = $deliver_info;
		$this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');

		parent::index('mall/', $page, 'header', 'footer');
	}


    /**
     * 提交订单，代品券订单的提交
     */
    public function submit_order()
    {
        //顾客基本资料
        $attr = $this->input->post('data');

        //检测收货地址的正确性
        $this->load->model("m_validate");
        $this->m_validate->validate_deliver_address(null,intval($attr['address_id']));

		/** 提交订单前，检测用户的代品券数量 */
		$this->load->model('m_coupons');
		$is_true  = $this->m_coupons->get_use_coupons_sum($attr['customer_id']);

		if($is_true === false){
			echo json_encode(array('success' => false,'msg'=>'data exception(数据异常)，可用代品券数量不正确'));
			exit();
		}

        //算会员的代品券总金额和商品总值的差价
        $coupons_list = $this->m_coupons->get_coupons_list($attr['customer_id']);
        //会员没有代品券
        if($coupons_list['total_money'] <= 0){
//            echo '您的账户中代品劵为0，不允许提交代品劵订单。请您重新确认您需要提交的订单类型。';
            echo json_encode(array('success' => false, 'msg'=>lang('you_not_coupons')));
            exit;
        }

        //订单类型
        $order_type = 'coupons';

        //获取goods list
        $goods_list = $this->m_group->get_goods_list();

        //拆分goods list 的sku 和 数量
        $new_arr = $this->m_split_order->split_goods_list($goods_list);

        //获取拆单列表
        $split_list = $this->m_split_order->get_split_list($new_arr);

        //m by brady.wang 冬季不发货商品校验
        $this->load->model("M_order",'m_order');
        $this->m_order->goods_undeliver_check($attr,$new_arr);

        //验证订单是否包含不配送产品
        $this->load->model("o_order_validate");
        $this->o_order_validate->goods_list_deliver_validate($goods_list,
            $attr,
            $this->_viewData['curLan_id'],
            $this->_viewData['curLan'],
            $this->_viewData['cur_rate'],
            $this->_viewData['curCur'],
            $this->_viewData['currency_all']);

        //校验表单是否重复提交
        if (!check_form_is_repeat_submit($this->session->userdata('session_id'), 'choose_goods_for_coupons_checkout_submit_order', $attr)) {
        	echo json_encode(array('success' => false,'msg'=>lang('checkout_order_token_error')));
        	exit;
        }
        
        //提交订单,返回订单id
        $ret_id = $this->m_group->make_order($attr,$split_list,$order_type,1);

//        $goods_amount_usd = $this->db->query("select goods_amount_usd from trade_orders WHERE order_id = '$ret_id'")->row()->goods_amount_usd;
        $goods_amount_usd = 0;
        $this->load->model("tb_trade_orders");
        $tmp = $this->tb_trade_orders->get_one("goods_amount_usd",["order_id"=>$ret_id]);
        if($tmp)
        {
            $goods_amount_usd = $tmp['goods_amount_usd'];
        }

        $goods_amount_usd = $goods_amount_usd/100;

        if (!$ret_id)
        {
            echo json_encode(array('success' => false,'msg'=>lang('info_error')));
            exit();
        }
        else
        {

            //删除cookie
            delete_cookie('sel_country',get_public_domain());

            //2017-06-30 leon 删除套餐产品选中的 cookie 信息
            $this->load->model('m_group');
            $this->m_group->del_cookie();

            //如果需要补差价,跳到支付页面,如果不需要,则直接提交
            if($coupons_list['total_money'] >= $goods_amount_usd)
            {
                echo json_encode(array('success' =>true, 'code'=>0, 'msg'=>lang('submit_order_ok')));
                exit;
            }
            else
            {
                echo json_encode(array('success' =>true, 'code'=>101, 'id'=>$ret_id));
                exit;
            }
        }
    }
}
