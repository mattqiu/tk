<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class choose_goods_for_exchange_checkout extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $this->load->model('m_suite_exchange_coupon');
        $this->load->model('m_split_order');
        $this->load->model('tb_trade_orders');
	}

	public function index()
	{
        // 校验用户登陆状态
        if (empty($this->_userInfo))
        {
            redirect(base_url('login').'?redirect = choose_goods_for_exchange_checkout');
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

        //获取选购的商品
        $all_product = $this->m_group->get_all_product();

        $goods_sn_list = [];
        foreach($all_product['group'] as $v) {
            $goods_sn_list[] = $v['goods_sn'];
        }
        $this->_viewData['goods_sn_list'] = $goods_sn_list;
        $adinfo = [];
        foreach($address_list as $k=>$v) {
            $adinfo[$k]['id']  = $v['id'];
            $adinfo[$k]['addr_lv2'] = $v['addr_lv2'];
        }
        $this->_viewData['addr_list'] = $adinfo;


        //获得选购商品的总金额
        $total_money = $this->m_group->get_product_total_money($all_product);

        //要换的订单信息
        $exchange = isset($_COOKIE['exchange'])?unserialize($_COOKIE['exchange']):array();

        $this->_viewData['all_product'] = $all_product;
        $this->_viewData['total_money'] = $total_money;

        //实际支付金额
        $real_money = $total_money - $exchange['order_money'];
        if($real_money < 0){
            echo '数据异常';
            exit;
        }
        $this->_viewData['real_pay_money'] = $real_money;

        // 配送方式与费用（单位分）
        $deliver_info = array(
            'type' => "checkout_order_deliver_type_express",
            'fee' => 0,
        );

        $this->_viewData['order_type'] = 'exchange';
        $this->_viewData['deliver_info'] = $deliver_info;
        $this->_viewData['order_address_info_tip'] = lang('order_address_error_tip');
        parent::index('mall/', $page, 'header', 'footer');
	}

    /**
     * 提交订单,换货订单的提交
     */
    public function submit_order()
    {

        // 校验用户登陆状态
        if (empty($this->_userInfo))
        {
            redirect(base_url('login').'?redirect = choose_goods_for_exchange_checkout');
        }

        //要换的订单信息，判断session是否存在
        $exchange = isset($_COOKIE['exchange'])?unserialize($_COOKIE['exchange']):array();
        if(!isset($exchange) || $exchange == array()){
            echo json_encode(array('success' => false,'msg'=>lang('not_repeat_submit')));
            exit();
        }

        //判断之前的订单是否已被换货成功
        $order_info = $this->tb_trade_orders->getOrderInfo($exchange['order_id'], array('status','customer_id'));
        if(empty($order_info)){
            echo json_encode(array('success' => false,'msg'=>lang('exchange_ok')));
            exit();
        }
        if($order_info['status'] == '98'){
            echo json_encode(array('success' => false,'msg'=>lang('exchange_ok')));
            exit();
        }

        //判断会员的登录id
        if($order_info['customer_id'] !== $this->_userInfo['id']){
            echo json_encode(array('success' => false,'msg'=>'System error'));
            exit();
        }

        //所选购商品的总价
        $all_product = $this->m_group->get_all_product();
        $total_money = $this->m_group->get_product_total_money($all_product);

        //所选商品金额小于原来的金额，不能提交
        if ($total_money < $exchange['order_money']) {
            echo json_encode(array('success' => false, 'msg' => lang('user_upgrade_not_lower')));
            exit();
        }

        //顾客基本资料
        $attr = $this->input->post('data');

        //检测收货地址的正确性
        $this->load->model("m_validate");
        $this->m_validate->validate_deliver_address(null,intval($attr['address_id']));

        //订单类型
        $order_type = 'exchange';

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

        //事务开始
        $this->db->trans_begin();
//sleep(15);
        //提交订单,返回订单id
        $ret_id = $this->m_group->make_order($attr,$split_list,$order_type);
        if($ret_id === false)
        {
            echo json_encode(array('success' => false,'msg'=>lang('info_error')));
            exit();
        }

        if ($this->db->trans_status() === FALSE)
        {
            echo json_encode(array('success' => false,'msg'=>lang('info_error')));
            exit();
        }
        else
        {
            $this->db->trans_commit();

            //删除cookie
            delete_cookie('exchange', get_public_domain());
            //如果需要补差价,跳到支付页面,如果不需要,则直接提交
            if (bccomp($total_money,$exchange['order_money'],2) == 0)
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
