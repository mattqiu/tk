<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Respond extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /** 充值月費付款流程 */
    public function go_month_pay(){

        $data = $this->input->post();
        $data['time'] = time();

        /*if(in_array($this->_userInfo['email'],config_item('test_pay_email'))){
            $data['amount'] = 0.01;
			$data['usd_money'] = 0.01;
        }*/

        if(isset($data['type'] )&& $data['type'] == 'month_fee'){
            $type = $data['type']; //月費訂單
        }else if(isset($data['type'] )&& $data['type'] == 'upgrade_month_fee'){
            $type = $data['type'];
        }else{
			$type = 'month_fee';
		}

        if($this->_userInfo['status'] == 3){ //如果等级没有激活，用户的等级其实就是免费的
            $this->_userInfo['user_rank'] = 4;
        }

        if(isset($data['month_payment_method'])){
            $data['payment_method'] = $data['month_payment_method'];
        }

        $payment = $this->m_global->getPaymentById($data['payment_method']);
		if(!$payment){
			redirect(base_url('welcome_new'));exit;
		}

        $this->load->model('M_order', 'm_order');

        if($type == 'upgrade_month_fee'){
            $order = $this->m_order->createUpgradeMonthFeeOrder($data,$this->_userInfo['id'],strtolower($payment['pay_name']));
        }else{
            $order = $this->m_order->createMonthFeeOrder($data,$this->_userInfo,strtolower($payment['pay_name']));
        }

		/** 如果是ewallet */
		if($payment['pay_code'] === 'm_ewallet'){
			if(!$this->_userInfo['ewallet_name']){
				redirect(base_url('welcome_new'));exit;
			}
			$order['ewallet_name'] = $this->_userInfo['ewallet_name'];
		}

        $order['money'] = $order['usd_money'] * 100;
		$order['view_currency'] = 'CNY';
		$order['currency'] = 'USD';  //兼容商城的
        $this->load->model($payment['pay_code']);
        $str = $this->$payment['pay_code']->get_code($order);

        $this->_viewData['title'] = lang('alipay');
        $this->_viewData['pay_str'] = $str;

        parent::index('ucenter/','pay');
    }
    /*
     * WAP手机网页版支付提交地址
     */
    public function go_order_paywap(){

         $data = $this->input->post();

        if(!isset($data['order_id']) || !$data['order_id'] ){
			redirect('order/sign_up_pay/'.$data['order_id']) ;exit;
        }
        if(!isset($data['payment_method']) || $data['payment_method'] == '110'){
            redirect('order/sign_up_pay/'.$data['order_id']) ;exit;
        }

        $payment = $this->m_global->getPaymentById($data['payment_method']);
		if(!$payment){
			redirect('order/sign_up_pay/'.$data['order_id']) ;exit;
		}

        $this->load->model('M_trade', 'm_trade');
        $order = $this->m_trade->get_order_info($data['order_id']);

        if(!$order || $order['txn_id'] || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT )){//已经付款
            redirect('ucenter/my_orders_new');exit;
        }
        if(!in_array(substr($data['order_id'], 0, 1 ), array('L'))){
        if($this->_userInfo['id'] != $order['customer_id']){
            redirect('order/sign_up_pay/'.$data['order_id']) ;exit;
        }}

		/** 如果是ewallet */
		if($payment['pay_code'] === 'm_ewallet'){
			if(!$this->_userInfo['ewallet_name']){
				redirect(base_url('welcome_new'));exit;
			}
			$order['ewallet_name'] = $this->_userInfo['ewallet_name'];
		}
		
		$order['view_currency'] = $this->_curCurrency ? $this->_curCurrency : 'USD';
                $is=$this->m_trade->update_order_payment($order,$payment['pay_id']);
                if($is){
                    $this->load->model($payment['pay_code']);
                    $str = $this->$payment['pay_code']->get_codemobile($order);
                }  else {
                    $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>".lang('admin_return_fee_tip1')."！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/order/pay/".$order['order_id']."';}setTimeout('showTime()',1000);}showTime();</script>";
                }
    }
    public function go_order_pay(){

         $data = $this->input->post();

        if(!isset($data['order_id']) || !$data['order_id'] ){
			redirect('order/pay/'.$data['order_id']) ;exit;
        }
        if(!isset($data['payment_method']) || $data['payment_method'] == '110'){
            redirect('order/pay/'.$data['order_id']) ;exit;
        }

        $payment = $this->m_global->getPaymentById($data['payment_method']);
		if(!$payment){
			redirect('order/pay/'.$data['order_id']) ;exit;
		}

        $this->load->model('M_trade', 'm_trade');
        $order = $this->m_trade->get_order_info($data['order_id']);

        if(!$order || $order['txn_id'] || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT )){//已经付款
            redirect('ucenter/my_orders_new');exit;
        }
        if(!in_array(substr($data['order_id'], 0, 1 ), array('L','S'))){
        if($this->_userInfo['id'] != $order['customer_id']){
            redirect('order/pay/'.$data['order_id']) ;exit;
        }}

		/** 如果是ewallet */
		if($payment['pay_code'] === 'm_ewallet'){
			if(!$this->_userInfo['ewallet_name']){
				redirect(base_url('welcome_new'));exit;
			}
			$order['ewallet_name'] = $this->_userInfo['ewallet_name'];
		}

		$order['view_currency'] = $this->_curCurrency ? $this->_curCurrency : 'USD';
        $is=$this->m_trade->update_order_payment($order,$payment['pay_id']);
        if($is){
            $this->load->model($payment['pay_code']);
            $str = $this->$payment['pay_code']->get_code($order);
        }  else {
            $str="<div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'>".lang('admin_return_fee_tip1')."！".  lang('account_active_success_jump')."</div><div id='sec'></div><script type='text/javascript'>var tim = 4;function showTime(){tim -= 1;document.getElementById('sec').innerHTML= tim;if(tim==0){location.href='/order/pay/".$order['order_id']."';}setTimeout('showTime()',1000);}showTime();</script>";
        }

        $this->_viewData['title']=lang('alipay').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['pay_str'] = $str;

        parent::index('mall/', 'pay');
    }

	public function go_wx_order_pay($order_id,$payment_method){

		if(!$order_id){
			redirect('order/pay/'.$order_id) ;exit;
		}

		$payment = $this->m_global->getPaymentById($payment_method);
		if(!$payment){
			redirect('order/pay/'.$order_id) ;exit;
		}

		$this->load->model('M_trade', 'm_trade');
		$order = $this->m_trade->get_order_info($order_id);

		if(!$order || $order['txn_id'] || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT )){//已经付款
			redirect('ucenter/my_orders_new');exit;
		}

		if($this->_userInfo['id'] != $order['customer_id']){
			redirect('order/pay/'.$order_id) ;exit;
		}

		$order['view_currency'] = $this->_curCurrency ? $this->_curCurrency : 'USD';
		$this->load->model($payment['pay_code']);
		$this->m_trade->update_order_payment($order,$payment['pay_id']);
		$this->$payment['pay_code']->get_code($order);
	}

    /**  同步通知　*/
    public function do_return(){

        /* 支付方式代码 */
        $pay_code = !empty($_GET['code']) ? trim($_GET['code']) : '';

		if($pay_code == 'czoxMDoibV91bmlvbnBheSI7'){ /** 如果是银联，直接转码，直接进入 */
			$pay_code = 'm_unionpay';
		}else{
			$pay_code = unserialize(base64_decode($pay_code));
		}

        $result['success'] = FALSE;
        /* 参数是否为空 */
        if (empty($pay_code))
        {
            $result['msg'] = lang('pay_not_exist');
        }
        else
        {
            $is_exist = $this->m_global->isPaymentExist($pay_code);
            if (!$is_exist)
            {
                $result['msg'] = lang('pay_not_exist');
            }
            else
            {
                if ( file_exists(APPPATH . 'models/'.$pay_code.'.php'))
                {
                    $this->load->model($pay_code);
                    $result = $this->$pay_code->do_return();
                }
                else
                {
                    $result['msg'] = lang('pay_not_exist');
                }

            }
        }
        $this->_viewData['title']=lang('alipay').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['result']=$result;


        if(isset($result['table'])){
            if($result['table'] === 'trade_orders'){
                $this->_viewData['amount']= $this->m_currency->price_format($result['amount'],$this->_curCurrency);
                parent::index('mall/','respond','header1','footer1');
            }else if($result['table'] === 'user_month_fee_order' ){
                $this->_viewData['month_fee']= 'month_fee';
                parent::index('ucenter/','respond','header','footer');
            }else{
                parent::index('ucenter/','respond','header','footer');
            }
        }else{
            parent::index('mall/','respond','header1','footer1');
        }
    }

	public function paypal_do_return(){

		if ( file_exists(APPPATH . 'models/m_paypal.php'))
		{
			$this->load->model('m_paypal');
			$this->m_paypal->do_return();
		}

		$this->_viewData['title']=lang('alipay').' - '.lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');

		$this->load->view('mall/mobile_pay_success',$this->_viewData);
	}

	/**
	 * 返回商户
	 */
	public function do_cancel(){

	}

    /**  异步通知　*/
	public function do_notify(){

        /* 支付方式代码 */
        $pay_code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : $_GET['code'];

		if($pay_code == 'czoxMDoibV91bmlvbnBheSI7'){ /** 如果是银联，直接转码，直接进入 */
			$pay_code = 'm_unionpay';
		}else{
			$pay_code = unserialize(base64_decode($pay_code));
		}

		/* 参数是否为空 */
		if (!empty($pay_code))
		{
			$is_exist = $this->m_global->isPaymentExist($pay_code);
			if ($is_exist)
			{
				if ( file_exists(APPPATH . 'models/'.$pay_code.'.php'))
				{
					$this->load->model($pay_code);
					$result = $this->$pay_code->do_notify();
				}
			}
		}else{
			$this->m_log->createOrdersLog('111',$_REQUEST['code'].'解码失败，找不到支付接口！！');
		}
	}

	/**  手机端异步通知　*/
	public function mobile_do_notify(){

		/* 支付方式代码 */
		$pay_code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : $_GET['code'];

		if($pay_code == 'czoxMDoibV91bmlvbnBheSI7'){ /** 如果是银联，直接转码，直接进入 */
			$pay_code = 'm_unionpay';
		}else{
			$pay_code = unserialize(base64_decode($pay_code));
		}

		$this->load->model('m_debug');

		/* 参数是否为空 */
		if (!empty($pay_code))
		{
			$is_exist = $this->m_global->isPaymentExist($pay_code);
			if ($is_exist)
			{
				$this->load->model('m_debug');

				$this->load->model('o_payment');
				$function =  $pay_code.'_do_notify';
				$result = $this->o_payment->$function();
			}
		}else{
			$this->m_log->createOrdersLog('111',$_REQUEST['code'].'解码失败，找不到支付接口！！');
		}
	}

	/** 微信通知返回 */
	public function wx_do_return($order_id){

		/*$this->load->model('M_trade', 'm_trade');
		$order = $this->m_trade->get_order_info($order_id);

		if(!$order){
			redirect('ucenter/my_orders_new');exit;
		}*/

		$result['success'] = FALSE;

		$this->load->model('m_wxpay');
		$result = $this->m_wxpay->do_return($order_id);

		$this->_viewData['title']=lang('alipay').' - '.lang('m_title');
		$this->_viewData['keywords']=lang('m_keywords');
		$this->_viewData['description']=lang('m_description');
		$this->_viewData['canonical']=base_url();
		$this->_viewData['result']=$result;

		if(isset($result['table'])){
			if($result['table'] === 'trade_orders'){
				$this->_viewData['amount']= $this->m_currency->price_format($result['amount'],$this->_curCurrency);
				parent::index('mall/','respond','header1','footer1');
			}else if($result['table'] === 'user_month_fee_order' ){
				$this->_viewData['month_fee']= 'month_fee';
				parent::index('ucenter/','respond','header','footer');
			}else{
				parent::index('ucenter/','respond','header','footer');
			}
		}else{
			parent::index('mall/','respond','header1','footer1');
		}
	}

	/**  微信异步通知　*/
	public function wx_do_notify(){
		$this->load->model('m_wxpay');
		$this->m_wxpay->do_notify();
	}

	/**
	 * 批量付款到支付宝账户
	 */
	public function do_batch_trans_notify(){
		$this->load->model('m_alipay');
		$this->m_alipay->do_batch_trans_notify();
	}

    /**
	 * 批量退出转账到支付宝
	 */
	public function do_batch_trans_notify_after(){
		$this->load->model('m_alipay');
        $this->m_log->createCronLog($_POST);
		$this->m_alipay->do_batch_trans_notify_after();
	}
        /**  paypal大宗付款异步通知　*/
	public function do_get_masspay(){
                $pay_code = 'm_paypal';
		/* 参数是否为空 */
		if (!empty($pay_code))
		{
			$is_exist = $this->m_global->isPaymentExist($pay_code);
			if ($is_exist)
			{
				if ( file_exists(APPPATH . 'models/'.$pay_code.'.php'))
				{
					$this->load->model($pay_code);
					$result = $this->$pay_code->get_masspay();
				}
			}
		}
	}
        /**
     * WAP版付款接口回调，同步
     */
    public function do_return_mobile() {
        /* 支付方式代码 */
        $pay_code = !empty($_GET['code']) ? trim($_GET['code']) : '';

		if($pay_code == 'czoxMDoibV91bmlvbnBheSI7'){ /** 如果是银联，直接转码，直接进入 */
			$pay_code = 'm_unionpay';
		}
                else{
			$pay_code = base64_decode($pay_code);
		}
        $result['success'] = FALSE;
        /* 参数是否为空 */
        if (empty($pay_code))
        {
            $result['msg'] = lang('pay_not_exist');
        }
        else
        {
            $is_exist = $this->m_global->isPaymentExist($pay_code);
            if (!$is_exist)
            {
                $result['msg'] = lang('pay_not_exist');
            }
            else
            {
                if ( file_exists(APPPATH . 'models/'.$pay_code.'.php'))
                {
                    $this->load->model($pay_code);
                    $result = $this->$pay_code->do_return_mobile();
                }
                else
                {
                    $result['msg'] = lang('pay_not_exist');
                }

            }
        }
        $this->_viewData['title']=lang('alipay').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['result']=$result;


        if(isset($result['table'])){
            if($result['table'] === 'trade_orders'){
                $this->_viewData['amount']= $this->m_currency->price_format($result['amount'],$this->_curCurrency);
                parent::index($this->is_mobile.'/', 'respond');
            }else if($result['table'] === 'user_month_fee_order' ){
                $this->_viewData['month_fee']= 'month_fee';
                parent::index('ucenter/','respond','header','footer');
            }else{
                parent::index('ucenter/','respond','header','footer');
            }
        }else{
//            parent::index('mall/','respond','header1','footer1');
            parent::index($this->is_mobile.'/', 'respond');
        }
    }
    /**
     * WAP版付款接口回调，异步
     */
    public function do_notify_mobile() {
        /* 支付方式代码 */
        $pay_code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : $_GET['code'];

		if($pay_code == 'czoxMDoibV91bmlvbnBheSI7'){ /** 如果是银联，直接转码，直接进入 */
			$pay_code = 'm_unionpay';
		}else{
			$pay_code = base64_decode($pay_code);
		}

		/* 参数是否为空 */
		if (!empty($pay_code))
		{
			$is_exist = $this->m_global->isPaymentExist($pay_code);
			if ($is_exist)
			{
				if ( file_exists(APPPATH . 'models/'.$pay_code.'.php'))
				{
					$this->load->model($pay_code);
					$result = $this->$pay_code->do_notify_mobile();
				}
			}
		}else{
			$this->m_log->createOrdersLog('111',$_REQUEST['code'].'解码失败，找不到支付接口！！');
		}
    }

}
