<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class test extends CI_Controller {

    function __construct() {
        parent::__construct();

        ignore_user_abort();
        set_time_limit(0);

        $this->load->model('m_debug');
        header("Content-type: text/html; charset=utf-8");
    }
    
    public function wohao_memberSync_add() {

        $this->load->model('m_user');

        $email = 'test80@qq.com';
        $pwdOriginal = 'terry111';
        $pwd_token = $this->m_user->createToken(123456789);
        $pwd = $this->m_user->pwdEncryption($pwdOriginal, $pwd_token);
        $parent_id = 1380100219;
        $name = '录用test80';
        $mobile = '13787877656';
        $country_id = '1';
        $address = '广东省深圳市宝安区民治横岭27栋1505';
        $token = time();
        $sign = api_create_sign($token);
        $postData = array('email' => $email, 'pwd' => $pwd,'pwd_token'=>$pwd_token, 'parent_id' => $parent_id,'languageid'=>1, 'name'=>$name,'mobile'=>$mobile,'country_id'=>$country_id, 'address'=>$address,'token' => $token, 'sign' => $sign);
        $this->__generalPost(base_url('api/wohao/memberSync'), $postData);
    }

    public function mobile_test() {
        $method=trim($this->input->get('m'));
        
        $token = time();
        $sign = api_mobile_create_sign($token);
        $postData = array('token' => $token, 'sign' => $sign);
        $this->__generalPost(base_url('api/mobile/test'), $postData);
    }

	public function mobile_test2() {
		$postData = array (
			'accessType' => '0',
			'bizType' => '000201',
			'certId' => '69597475696',
			'currencyCode' => '156',
			'encoding' => 'utf-8',
			'merId' => '824440357320037',
			'orderId' => 'N201608011428146352',
			'queryId' => '201608011428502726898',
			'respCode' => '00',
			'respMsg' => 'Success!',
			'settleAmt' => '1',
			'settleCurrencyCode' => '156',
			'settleDate' => '0801',
			'signMethod' => '01',
			'traceNo' => '272689',
			'traceTime' => '0801142850',
			'txnAmt' => '1',
			'txnSubType' => '01',
			'txnTime' => '20160801142850',
			'txnType' => '01',
			'version' => '5.0.0',
			'signature' => 'tRPZZClgkuM5Jpoj7KwkJDJ+9ypkn7V6bCrqrBRJdKOblckG7DvtLKZxvXVEg1garwa4TlkkrCbUs3J2I9Jwm2Dh6rUtCrFgfxKzMvsHqwLWwSFxGB5ce+eBf8QxvC+H+N8MNi/jXy/bQ4pl59FgdAM+qKeHTMguE0ZdZ8TVOopwE0lZZEmfuA/e9hXOfKMuRAjz+ILMR2HtQ35KwpxXTxkonSaLGzCIlbO6tGhCroWhMEL8mvMYS0q6CDpU9qcBqwCDoovdi23b3PlUiqpv0YAxrv2fsAd9dcr9CCtFCR/3lQ5SSklJcyhh+VmEpZYldhzDGChB4z3SgbyjDwudJg==',
		);
        $this->__generalPost(base_url('respond/mobile_do_notify?code=czo5OiJvX3BheW1lbnQiOw=='), $postData);
    }


	public function login() {

		$postData = array('token' => '1460101942856', 'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'loginName'=>"1380100948",'pwdOriginal'=>'kipecp','location_id'=>156
		);
		$this->__generalPost(base_url('api/mobile/login'), $postData);
	}

	public function do_batch_trans_notify() {

		$postData = array (
			'sign' => '38abb1eb091285c6943fe9c76e6be1fd',
			'notify_time' => '2016-09-22 15:42:24',
			'pay_user_id' => '2088911308895646',
			'fail_details' => '13972^linl86@126.com^韦芳玲1^0.01^F^ACCOUN_NAME_NOT_MATCH^20160922565523096^20160922154222|13973^linl86@126.com^韦芳玲A^0.01^F^ACCOUN_NAME_NOT_MATCH^20160922565523097^20160922154222|13974^linl86@126.com^韦芳玲123^0.01^F^ACCOUN_NAME_NOT_MATCH^20160922565523098^20160922154222|13975^linl86@126.com^BB^0.01^F^ACCOUN_NAME_NOT_MATCH^20160922565523099^20160922154222|',
			'pay_user_name' => '深圳前海云集品电子商务有限公司',
			'sign_type' => 'MD5',
			'success_details' => '13976^linl86@126.com^韦芳玲^0.01^S^^20160922565523100^20160922154223|13978^linl86@126.com^韦芳玲^0.01^S^^20160922565523101^20160922154223|13979^linl86@126.com^韦芳玲^0.01^S^^20160922565523102^20160922154223|13980^linl86@126.com^韦芳玲^0.01^S^^20160922565523103^20160922154223|13981^linl86@126.com^韦芳玲^0.01^S^^20160922565523104^20160922154224|',
			'notify_type' => 'batch_trans_notify',
			'pay_account_no' => '20889113088956460156',
			'notify_id' => '7b915bc26dbd86000c5a81a9ca43787l66',
			'batch_no' => '2016092297531014',
		);
		$this->__generalPost(base_url('respond/do_batch_trans_notify'), $postData);
	}

	public function get_user_base_info() {

		$postData = array('token' => '1460101942856', 'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>1380217754
		);
		$this->__generalPost(base_url('api/mobile/get_user_base_info'), $postData);
	}

	public function register() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'email'=>'15019287817',
			'pwdOriginal'=>'q123456',
			'reg_type'=>'0',
			'disclaimer'=>'1',
			'captcha'=>'3352',
			'parent_id'=>1380240316,
			'action_id'=>'1',
		);

		$this->__generalPost('http://www.tps138.net/api/mobile/register', $postData);
	}

	public function send_captcha() {
		//echo get_public_domain();
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'email_or_phone'=>'13652388723',
			'language_id'=>'2',
			'reg_type'=>'0',
			'action_id'=>'1',
		);

		$this->__generalPost(base_url('api/mobile/send_captcha'), $postData);
	}
	public function check_captcha() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'email_or_phone'=>'221373524997@qq.com',
			'captcha'=>'3374',
			'action_id'=>'2',
		);

		$this->__generalPost(base_url('api/mobile/check_captcha'), $postData);
	}

	public function search_store() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'keywords'=>'ff',
		);

		$this->__generalPost(base_url('api/mobile/search_store'), $postData);
	}

	public function set_cash_pwd() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'cash_pwd'=>'7c4a8d09ca3762af61e59520943dc26494f8941b',
			'cash_pwd_pre'=>'7c4a8d09ca3762af61e59520943dc26494f8941b',
			'uid'=>'1380100287',
		);

		$this->__generalPost(base_url('api/mobile/set_cash_pwd'), $postData);
	}

	public function set_user_name() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'user_name'=>'john.test',
		);

		$this->__generalPost(base_url('api/mobile/set_user_name'), $postData);
	}

	public function get_store_code() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
		);

		$this->__generalPost(base_url('api/mobile/get_store_code'), $postData);
	}

	public function set_user_country() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'country_id'=>'1',
		);

		$this->__generalPost(base_url('api/mobile/set_user_country'), $postData);
	}

	public function get_area_country() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
		);

		$this->__generalPost(base_url('api/mobile/get_area_country'), $postData);
	}

	public function update_cash_pwd() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'cash_pwd'=>'123123',
			'cash_pwd_pre'=>'123123',
			'old_cash_pwd'=>123456,
		);

		$this->__generalPost(base_url('api/mobile/update_cash_pwd'), $postData);
	}

	public function binding_mobile_or_email() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'email_or_phone'=>'18620106915@163.com',
			'captcha'=>'8745',
		);

		$this->__generalPost(base_url('api/mobile/binding_mobile_or_email'), $postData);
	}
	public function withdrawal() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'take_cash_type'=>'3',
			'take_out_amount'=>'100',
			'take_out_pwd'=>'123456',
			'account_bank'=>'123123',
			'subbranch_bank'=>'123123',
			'account_name'=>'123123',
			'card_number'=>'123123',
			'c_card_number'=>'123123',
			'remark'=>'test',
		);

		$this->__generalPost(base_url('api/mobile/withdrawal'), $postData);
	}

	public function set_user_address() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'country'=>'156',
			'addr_lv2'=>'64',
			'addr_lv3'=>'6401',
			'addr_lv4'=>'640101',
			'address_detail'=>'api test',
			'consignee'=>'何谢军',
			'phone'=>'123123',
		);

		/*$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'country'=>'840',
			'addr_lv2'=>'AK',
			'city' => 'nert york',
			'first_name'=>'john',
			'last_name'=>'john',
			'address_detail'=>'country test',
			'phone'=>'123123',
			'zip_code'=>'12312',
		);*/

		/*$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'country'=>'410',
			'addr_lv2'=>'23',
			'consignee'=>'john',
			'address_detail'=>'',
			'phone'=>'123123',
			'zip_code'=>'12312',
			'customs_clearance'=>'12312',
			'id'=>'40675',
		);*/

		$this->__generalPost(base_url('api/mobile/set_user_address'), $postData);
	}

	public function update_user_address() {
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'country'=>'410',
			'addr_lv2'=>'23',
			'consignee'=>'john',
			'address_detail'=>'道县梅花镇哈哈',
			'phone'=>'123123',
			'zip_code'=>'12312',
			'customs_clearance'=>'12312',
			'id'=>'40675',
		);

		$this->__generalPost(base_url('api/mobile/update_user_address'), $postData);
	}

	public function del_user_address() {
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'id'=>'40675',
		);

		$this->__generalPost(base_url('api/mobile/del_user_address'), $postData);
	}

	public function set_user_default_address() {
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100287',
			'id'=>'40653',
		);

		$this->__generalPost(base_url('api/mobile/set_user_default_address'), $postData);
	}
	public function get_user_address_list() {
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380190857',
			'location_id'=>'840',
		);

		$this->__generalPost(base_url('api/mobile/get_user_address_list'), $postData);
	}

	public function submit_id_card() {

		$postData = array(
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'token' => '1460101942856',
			'uid' => '1380190857',
		);

		$this->__generalPost(base_url('api/mobile/submit_id_card'), $postData);
	}

	public function cal_month_fee() {

		$postData = array(
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'token' => '1460101942856',
			'payment_method' => '105',
			'uid' => '1380100287',
			'month' => '3',
		);

		$this->__generalPost(base_url('api/mobile/cal_month_fee'), $postData);
	}

	public function paid_month_fee() {

		$postData = array(
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'token' => '1460101942856',
			'payment_method' => '105',
			'uid' => '1380100287',
			'month' => '3',
			'amount' => 390.07,
			'usd_money' => 60,
		);

		$this->__generalPost(base_url('api/mobile/paid_month_fee'), $postData);
	}

	public function go_order_pay() {

		$postData = array(
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'token' => '1460101942856',
			'payment_method' => '107',
			'uid' => '1380100287',
			'order_id' => 'N201607291851062826',
		);

		$this->__generalPost(base_url('api/mobile/go_order_pay'), $postData);
	}

	public function forgot_pwd() {

		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'email_or_phone'=>13801002871,
			'newPwd'=>'qwe123123',
			'newPwdRe'=>'qwe123123',
		);

		$this->__generalPost(base_url('api/mobile/forgot_pwd'), $postData);
	}

    /*******************************************我的团队*****************************************/

    /* 店铺分布图 */
    public function genealogy_tree(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220'
        );
        $this->__generalPost(base_url('/api/mobile/genealogy_tree'),$postData);
    }

    /* 2×5分布图 */
    public function forced_matrix_2x5(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220'
        );
        $this->__generalPost(base_url('/api/mobile/forced_matrix_2x5'),$postData);
    }

    /* 138分布图 */
    public function forced_matrix_138(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380126254'
        );
        $this->__generalPost(base_url('/api/mobile/forced_matrix_138'),$postData);
    }


    /*******************************************我的订单*****************************************/
    /* 店铺订单 */
    public function my_store_order(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'time'=>'2016-07'
        );
        $this->__generalPost(base_url('/api/mobile/my_store_order'),$postData);
    }

    /* 自我消费订单 */
    public function my_buy_order(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'time'=>'2016'
        );
        $this->__generalPost(base_url('/api/mobile/my_buy_order'),$postData);
    }

    /* 沃好订单 */
    public function my_wohao_order(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100948',
            'time'=>'2015-09'
        );
        $this->__generalPost(base_url('/api/mobile/my_wohao_order'),$postData);
    }

    /*******************************************佣金报表*****************************************/
    public function commission_report(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100223',
            'time'=>'2016-04',
            'type'=>'11',
            'pager'=>1,
        );
        $this->__generalPost(base_url('/api/mobile/commission_report'),$postData);
    }


    /* 加入购物车 */
    function add_cart(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100223',
            'goods_sn'=>'88210236-1',
            'goods_number'=>1,
            'country_id'=>156
        );
        $this->__generalPost(base_url('/api/mobile/add_cart'),$postData);
    }

    /* 获取购物车 */
    function cart(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'country_id'=>'156'
        );
        $this->__generalPost(base_url('/api/mobile/cart'),$postData);
    }

    /* 删除购物车商品 */
    function delete_cart(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100223',
            'goods_sn'=>'25918623-1',
            'country_id'=>'156'
        );
        $this->__generalPost(base_url('/api/mobile/delete_cart'),$postData);
    }

    /* 编辑购物车商品数量 */
    function edit_cart_number(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100223',
            'goods_sn'=>'25918623-2',
            'country_id'=>'840',
            'goods_number'=>'5'
        );
        $this->__generalPost(base_url('/api/mobile/edit_cart_number'),$postData);
    }

    /* 代品卷 */
    function coupons(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380183286'
        );
        $this->__generalPost(base_url('/api/mobile/coupons'),$postData);
    }


    /* 代品券套装专区 */
    function use_coupons(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'location_id'=>410
        );
        $this->__generalPost(base_url('/api/mobile/use_coupons'),$postData);
    }

    /* 代品券购物车结算 */
    function confirm_choose_coupons(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'goods_list'=>'16306453-1:1$46952052-1:1',
            'location_id'=>156,
            'address_id'=>40684
        );
        $this->__generalPost(base_url('/api/mobile/confirm_choose_coupons'),$postData);
    }

    /*******************************************我的资产*****************************************/

    /* 提现明细 */
    public function cash_take_out_logs(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100287',
            'time'=>'2016-06'
        );
        $this->__generalPost(base_url('/api/mobile/cash_take_out_logs'),$postData);
    }

    /* 分红明细 */
    public function profit_sharing_point_logs(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'time'=>'2016-07'
        );
        $this->__generalPost(base_url('/api/mobile/profit_sharing_point_logs'),$postData);
    }

    /* 月费明细 */
    public function monthly_fee_logs(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100360',
            'time'=>'2016-05',
            'date'=>'',
        );
        $this->__generalPost(base_url('/api/mobile/monthly_fee_logs'),$postData);
    }

    /**
     * 设置or取消某用户账户的“现金池自动转月费池”
     */
    public function set_cashpool_autosupply_monthlyfeepool(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
            'is_auto'=>'0'
        );
        $this->__generalPost(base_url('/api/mobile/set_cashpool_autosupply_monthlyfeepool'),$postData);
    }

    /**
     * 获取某用户账户是否设置了“现金池自动转月费池”
     */
    public function get_cashpool_autosupply_monthlyfeepool(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
        );
        $this->__generalPost(base_url('/api/mobile/get_cashpool_autosupply_monthlyfeepool'),$postData);
    }

    /**
     * 获取用户分红点信息（奖励分红点、普通分红点、佣金自动转分红比例）
     */
    public function get_user_sharingpoint_info(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
        );
        $this->__generalPost(base_url('/api/mobile/get_user_sharingpoint_info'),$postData);
    }

    /**
     * 设置／修改“佣金自动转分红点比例”。
     */
    public function set_sharingpoint_proportion(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
            'proportion'=>'50.45',
        );
        $this->__generalPost(base_url('/api/mobile/set_sharingpoint_proportion'),$postData);
    }

    /**
     * 分红点转现金池。
     */
    public function sharingpoint_to_cash(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
            'point'=>'0.02',
        );
        $this->__generalPost(base_url('/api/mobile/sharingpoint_to_cash'),$postData);
    }

    /**
     * 会员之间转帐
     */
    public function tran_money_between_mem(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'fromId'=>'1380100706',
            'tranToMemId'=>'1380100219',
            'tranToMemAmount'=>'0.03',
            'tranToMemFundsPwd'=>'198910',
        );
        $this->__generalPost(base_url('/api/mobile/tran_money_between_mem'),$postData);
    }
    
    /**
     * 根据用户id获取姓名
     */
    public function get_user_name_by_id(){

    	$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100706',
        );
        $this->__generalPost(base_url('/api/mobile/get_user_name_by_id'),$postData);
    }


    /*******************************************用户升级*****************************************/
    /* 用户升级 */
    public function member_upgrade(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100241',
        );
        $this->__generalPost(base_url('/api/mobile/member_upgrade'),$postData);
    }

    /* 去升级 */
    public function do_member_upgrade(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100241',
            'location_id'=>156
        );
        $this->__generalPost(base_url('/api/mobile/do_member_upgrade'),$postData);
    }

    /* 会员升级购物车结算 */
    public function confirm_choose(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'new_level'=>1,
            'goods_list'=>'23464934-1:1',
            'coupons_list'=>'10005:4',
            'location_id'=>156,
            'address_id'=>40802
        );


        $this->__generalPost(base_url('/api/mobile/confirm_choose'),$postData);
    }

    /* 下单详情页 */
    public function make_order_page(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380119086',
            'goods_list'=>'30957403-1:1$30244390-1:1$23464934-1:1',
            'location_id'=>156,
            'currency'=>'USD',
            'address_id'=>'25796'
        );
        $this->__generalPost(base_url('/api/mobile/make_order_page'),$postData);
    }

    /* 提交订单 */
    public function do_checkout_order(){
		$postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'shopkeeper_id'=>'1380100220',
            'address_id'=>40798,
            'location_id'=>156,
            'remark'=>'测试订单',
            'goods_list'=>'19102941-1:1',
            'currency'=>'CNY'
        );
        $this->__generalPost(base_url('/api/mobile/do_checkout_order'),$postData);
    }

    /* 提交订单 (会员升级,代品券选购)*/
    public function do_checkout_order_group(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'uid'=>'1380100220',
            'address_id'=>40655,
            'location_id'=>156,
            'remark'=>'测试订单',
            'goods_list'=>'92090952-1:1',
            'order_type'=>3,
            'coupons_list'=>'',
            'level'=>''
        );
        $this->__generalPost(base_url('/api/mobile/do_checkout_order_group'),$postData);
    }

	public function cancel_withdrawal(){
		$postData = array(
			'token' => '1460101942856',
			'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
			'uid'=>'1380100948',
			'id'=>'1380100948'
		);
		$this->__generalPost(base_url('/api/mobile/cancel_withdrawal'),$postData);
	}

    /* 下单详情页 */
    public function get_goods_details_pager(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'goods_id'=>54895,
            'goods_sn_main'=>'10094773',
            'location_id'=>156
        );
        $this->__generalPost(base_url('/api/mobile/get_goods_details_pager'),$postData);
    }

    public function add_order_remark(){
        $postData = array (
            'order_id' => 'N201701121407166994',
            'type' => '2',
            'remark' => '123',
            'admin_id' => 0,
            'created_at' => '2017-01-17 16:52:22',
            'operator' => '操作人名称',
            'sign' => 'bfafbf8c929fe17ac0b2f77df0e9920f',
        );
        $this->__generalPost(base_url('/api/erp/add_order_remark'),$postData);
    }

    public function orders_modify(){

        $postData = array (
                'data'=>'a:4:{i:0;a:2:{s:8:"order_id";s:9:"110000033";s:6:"status";i:1;}i:1;a:2:{s:8:"order_id";s:9:"110000026";s:6:"status";i:1;}i:2;a:2:{s:8:"order_id";s:9:"110000018";s:6:"status";i:1;}i:3;a:2:{s:8:"order_id";s:9:"100000008";s:6:"status";i:1;}}',
                'sign' => 'bfafbf8c929fe17ac0b2f77df0e9920f',
            );
        $this->__generalPost(base_url('/api/erp/orders_modify'),$postData);
    }
	public function order_modify(){
		$postData = array (
						'order_id' => '100000001',
						'status' => '1',
            'sign' => 'bfafbf8c929fe17ac0b2f77df0e9920f'

		);
		$this->__generalPost(base_url('/api/erp/order_modify'),$postData);
	}

    /* 取消订单接口 */
    public function cancel_order(){
        $postData = array(
            'token' => '1460101942856',
            'sign' => 'f379eab5603ca17c247f6ed79f316b663570d2cc',
            'order_id'=>"N201607231120184033",
        );
        $this->__generalPost(base_url('/api/mobile/cancel_order'),$postData);
    }

    public function wohao_orderCancel(){

        $order_id = 'DDGL1507310013';
        $token = time();
        $sign = api_create_sign($token);
        $this->__generalPost(base_url('api/wohao/orderCancel'), array('token'=>$token,'sign'=>$sign,'order_id'=>$order_id));
    }

    public function wohao_orderCancel_online(){

        $order_ids = array('TKD16042508','WLTHD16042507');
        $token = time();
        $sign = api_create_sign($token);
        $i=0;
        foreach($order_ids as $order_id){
            $this->__generalPost('https://mall.tps138.com/api/wohao/orderCancel', array('token'=>$token,'sign'=>$sign,'order_id'=>$order_id));
            $i++;
        }
        echo $i;
    }

    public function wohao_memberSync_update() {

        $this->load->model('m_user');

        $user_id = 1380109795;
        $userInfo = current($this->m_user->getInfo($user_id));
        $email = 'test68@qq.com';
        $pwdOriginal = 'terry222';
        $pwd = $this->m_user->pwdEncryption($pwdOriginal, $userInfo['token']);
        $mobile = '13787877656';
        $country_id = '2';
        $address = '广东省深圳市宝安区民治横岭27栋1606';
        $token = time();
        $sign = api_create_sign($token);
        $postData = array('user_id'=>$user_id,'email' => $email,'pwd'=>$pwd,'mobile'=>$mobile,'country_id'=>$country_id, 'address'=>$address,'token' => $token, 'sign' => $sign);
        $this->__generalPost(base_url('api/wohao/memberSync'), $postData);
    }

    public function wohao_orderSync() {

    	echo 23;
        echo "<pre>";print_r(unserialize('a:15:{s:4:"area";s:0:"";s:6:"status";s:0:"";s:7:"cate_id";s:0:"";s:10:"order_type";s:0:"";s:10:"store_code";s:0:"";s:14:"store_code_arr";s:0:"";s:10:"start_date";s:0:"";s:8:"end_date";s:0:"";s:18:"start_deliver_date";s:0:"";s:16:"end_deliver_date";s:0:"";s:5:"brand";s:0:"";s:3:"ext";s:0:"";s:14:"is_export_lock";s:0:"";s:21:"select_is_export_lock";s:0:"";s:11:"language_id";s:1:"2";}'));exit;
        $token = time();
        $sign = api_create_sign($token);
        $date = date("Y-m-d H:i:s",time());

        // $postData = array(
        //     'order_id'=>'testly005',
        //     'order_pay_time'=>'',
        //     'customer_id'=>0,
        //     'shopkeeper_id'=>1380100219,
        //     'order_amount'=>1240,
        //     'order_profit'=>200,
        //     'currency'=>'CNY',
        //     'token'=>$token,
        //     'sign'=>$sign,
        // );
        $postData = array (
            'sign' => '7062add82c958b3acfc0f912be11396f88357d4c',  
            'order_amount' => '20.04',   'token' => '1445820001',  
            'shopkeeper_id' => '1380100706 ', 
            'order_pay_time' => '2015-11-01 10:12:24',  
            'order_profit' => '0.08',   
            'order_id' => 'HD999',   
            'customer_id' => '1380100706',
            'order_year_month' => '201703',
            'currency' => 'CNY');
        // $this->__generalPost(base_url('api/wohao/orderSync'), $postData);
        $this->__generalPost(base_url('api/wohao/orderSync'), $postData);
    }

    public function syncMemberToWohao() {

        $timestamp = time();
        $sign = api_create_sign($timestamp);

        $this->load->model('m_user');

        $user_id = 1380100215;
        $email = '0';
        $pwdOriginal = 'terry111';
        $pwd_token = $this->m_user->createToken(time());
        $pwd = $this->m_user->pwdEncryption($pwdOriginal, $pwd_token);
        $parent_id = 1380100217;
        $name = '卢勇';
        $mobile = '12345678901';
        $country_id = '0';
        $address = '2342r2rr23r3r';
        $store_prefix = '1380100706';
        $store_level = 4;
        $create_time = '2015-06-10 10:15:20';
        $status = 1;

        $postData = array(
            'user_id'=>$user_id,
            'email'=>$email,
            'pwd'=>$pwd,
            'pwd_token'=>$pwd_token,
            'parent_id'=>$parent_id,
            'languageid'=>1,
            'name'=>$name,
            'mobile'=>$mobile,
            'country_id'=>$country_id,
            'address'=>$address,
            'store_prefix'=>$store_prefix,
            'store_level'=>$store_level,
            'create_time'=>$create_time,
            'status'=>$status,
            'token'=>$timestamp,
            'sign'=>$sign,
        );
        // $url = 'http://www.walhao.com/member/register';
        $url = 'http://www.walhao.com/index.php/home/userapi/adduser';
        $res = tps_curl_post2($url, $postData);
        var_dump($res);
    }
    public function orderCancel_wohao(){
        $token = time();
        $sign = api_create_sign($token);
        $postData = array('order_id' => 'DDGL1609080857','sign'=>$sign,'token'=>$token);
        $this->__generalPost(base_url('api/wohao/orderCancel'), $postData);
    }
    private function __generalPost($url, $postData) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        echo '<pre/>';
		echo $result.'<br>';
        var_dump(json_decode($result));
        //print_r(json_decode((Remove_UTF8_BOM($result))));
    }
    
    private function __generalPost2($url, $postData) {
        
        $dataFormat = '';
        foreach($postData as $k=>$v){
            $v = iconv("UTF-8","GBK", $v);
            $dataFormat.='&'.$k.'='.urlencode($v);
        }
        
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => substr($dataFormat,1),
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        echo '<pre/>';
        print_r(json_decode($result));
    }
    
    private function __generalGet($url, $data) {
        
        $urlAndData = $url.'?1=1';
        foreach($data as $k=>$v){
            $urlAndData.='&'.$k.'='.$v;
        }
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $urlAndData);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($cu);
        curl_close($cu);
        echo '<pre/>';
        print_r(json_decode($result));
    }

}
