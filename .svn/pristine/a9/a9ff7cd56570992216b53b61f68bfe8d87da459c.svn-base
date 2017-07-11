<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
        //用户ID
        $uid = $this->get_user_id();

		/* 头部信息 */
		$this->_viewData['title'] = lang('m_title');
		$this->_viewData['keywords'] = lang('m_keywords');
		$this->_viewData['description'] = lang('m_description');
		$this->_viewData['canonical'] = base_url();
		$this->_viewData['uid'] = $uid;

		/* 获取购物车商品数据 */
        $this->load->model("tb_user_cart");
		$this->_viewData['cart_data'] = $this->tb_user_cart->get_cart_page_data(
			$this->_viewData['store_id'],
			$this->_viewData['curLan_id'],
			$this->_viewData['curCur_flag'],
			$this->_viewData['cur_rate'],
			$this->_viewData['curLocation_id'],
            $uid
		);
		parent::index('mall/', 'cart');
	}

	/**
	 * 修改购物车内容
	 */
	public function do_cart_edit()
	{
		$ret_data = array(
			'code' => 0,
			'count' => 0,
			'sn' => "",
			'qty' => 0,
			'amount_to' => "$0.00",
			'total_amout' => "$0.00",
			'total_save' => "$0.00",
		);

		$attr = $this->input->post();
		$rules = array(
			'type' => "required|in:1,2,3,4",
			'sn' => "required|max:1088",
			'qty' => "required|integer",
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			echo json_encode($ret_data);
			exit;
		}

		$attr['qty'] = trim($attr['qty']);

        //用户ID
        $uid = null;
        if($this->_userInfo){
            $uid = $this->_userInfo['id'];
        }

        $this->load->model("tb_user_cart");
		$cart = $this->tb_user_cart->edit_cart(
			$this->_viewData['store_id'],
			$this->_viewData['curCur_flag'],
			$this->_viewData['cur_rate'],
			$attr['type'],
			$attr['sn'],
			$attr['qty'],
			$this->_viewData['curLocation_id'],
            $uid
		);
		if ($cart['code'] != 0)
		{
			$ret_data['code'] = $cart['code'];
			$ret_data['qty'] = trim($cart['qty']);
			echo json_encode($ret_data);
			exit;
		}

		$ret_data['count'] = $cart['count'];
		$ret_data['figure'] = strlen(strval($cart['count']));
		$ret_data['sn'] = $cart['sn'];
		$ret_data['qty'] = trim($cart['qty']);
		$ret_data['amount_to'] = $cart['amount_to'];
		$ret_data['total_amout'] = $cart['total_amout'];
		$ret_data['total_save'] = $cart['total_save'];

		echo json_encode($ret_data);
		exit;
	}

	/**
	 * 将商品添加到购物车
	 */
	public function do_add_to_cart()
	{
		$ret_data = array(
			'code' => 0,
		);

		$attr = $this->input->post();
		log_message('ERROR', "[do_add_to_cart] attr: ".var_export($attr, true));

		$rules = array(
			'goods_sn' => "required|max:32",		# 商品 sn
			'quantity' => "required|integer|min:1",	# 数量
		);
		if (TRUE !== $this->validator->validate($attr, $rules))
		{
			$ret_data['code'] = 101;
			log_message('ERROR', "[do_add_to_cart] validate failed, ".$this->validator->get_err_msg());
			echo json_encode($ret_data);
			exit;
		}

        //用户ID
        $uid = $this->get_user_id();

        $this->load->model("tb_user_cart");
		$ret = $this->tb_user_cart->add_to_cart($this->_viewData['store_id'], $attr,
            $this->_viewData['curLocation_id'],$uid);

        $ret_data['quantity'] = $attr['quantity'];

		if (0 != $ret)
		{
            $ret_data['code'] = $ret;
			echo json_encode($ret_data);
			exit;
		}

		echo json_encode($ret_data);
		exit;
	}
	
	/** 
	 * 检查购买商品数量的合法性
	 * @date: 2016-5-25 
	 * @author: sky yuan
	 * @parameter: 
	 * @return: 
	 */ 
	public function check_goods_stock()
	{
	    $ret_data = array(
	            'code' => 0,
	    );
	
	    $attr = $this->input->post();
	    log_message('error', "[check out] attr: ".var_export($attr, true));
	
	    $rules = array(
	            'goods_sn' => "required|max:32",		# 商品 sn
	            'quantity' => "required|integer|min:1",	# 数量
	    );
	    if (TRUE !== $this->validator->validate($attr, $rules))
	    {
	        $ret_data['code'] = 101;
	        log_message('error', "[check out] validate failed, ".$this->validator->get_err_msg());
	        echo json_encode($ret_data);
	        exit;
	    }
	
	    $ret = $this->m_trade->check_stock_valid($attr['goods_sn'],$attr['quantity']);
	    if (0 != $ret)
	    {
	        $ret_data['code'] = $ret;
	        echo json_encode($ret_data);
	        exit;
	    }
	
	    echo json_encode($ret_data);
	    exit;
	}

	public function remove_batch()
    {
        $ret_data = array(
            'code' => 0,
        );
        $attr = $this->input->post();
        $rules = array(
            'sn' => "required|max:1088",		# 商品 sn
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            log_message('error', "[remove_batch] validate failed, ".$this->validator->get_err_msg());
            echo json_encode($ret_data);
            exit;
        }

        $this->load->model("tb_user_cart");
        $res = $this->tb_user_cart->remove_batch($this->_viewData['store_id'],$this->_viewData['curLocation_id'],
            $attr['sn'],$this->get_user_id());

        $ret_data['sn'] = $res;
        echo json_encode($ret_data);
        exit;
    }

    /**
     * 移动到关注
     */
    public function move_wish_batch()
    {
        $ret_data = array(
            'code' => 0,
        );
        $attr = $this->input->post();
        $rules = array(
            'sn' => "required|max:1088",		# 商品 sn
        );
        if (TRUE !== $this->validator->validate($attr, $rules))
        {
            $ret_data['code'] = 101;
            log_message('error', "[move_wish_batch] validate failed, ".$this->validator->get_err_msg());
            echo json_encode($ret_data);
            exit;
        }

        //批量添加到关注
        $this->load->model("tb_mall_wish");
        $this->tb_mall_wish->add_wish_batch($this->get_user_id(),$attr['sn']);
        //批量从购物车删除
        $this->load->model("tb_user_cart");
        $res = $this->tb_user_cart->remove_batch($this->_viewData['store_id'],$this->_viewData['curLocation_id'],
            $attr['sn'],$this->get_user_id());

        $ret_data['sn'] = $res;
        echo json_encode($ret_data);
        exit;
    }

}
