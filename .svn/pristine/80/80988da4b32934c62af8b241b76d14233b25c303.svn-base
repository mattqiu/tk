<?php

class m_admin_user extends MY_Model {

    function __construct() {
        parent::__construct();
    }

	public function getAfterSaleList($filter,$admin_id,$curCon,$perPage = 10) {
            unset($filter['page_num']);//每页数量
		$this->db->from('admin_after_sale_order');
		if($admin_id == 5&&$curCon=='after_sale_order_list'){
			$this->db->where('type !=',2);
		}else if($admin_id == 18&&$curCon=='after_sale_order_list'){
			$this->db->where('type',2);
		}
		$this->filterForAfterSale($filter);
		return $this->db->order_by("apply_time", "asc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	public function filterForAfterSale($filter){
		foreach ($filter as $k => $v) {
			if ($v == '' || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'start':
					$this->db->where('apply_time >=', ($v));
					break;
				case 'end':
					$this->db->where('apply_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
					break;
				case 'status':
					if($v == 'do_refund'){
						$this->db->where_in('status', array('1','3','5','7'));
					}else{
						$this->db->where($k, $v);
					}
					break;
				case 'email':
					if(is_numeric($v)){
						$this->db->where('admin_id', $v);
					}else{
						$this->db->where("(admin_email like '%$v%')");
					}
					break;
				case 'as_id':
					$this->db->where('as_id', $v)->or_where('order_id',$v);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	public function getAfterSaleRows($filter,$admin_id,$curCon) {
            unset($filter['page_num']);//每页数量
		$this->db->from('admin_after_sale_order');
		if($admin_id == 5&&$curCon=='after_sale_order_list'){
			$this->db->where('type !=',2);
		}else if($admin_id == 18&&$curCon=='after_sale_order_list'){
			$this->db->where('type',2);
		}
		$this->filterForAfterSale($filter);
		return $this->db->count_all_results();
	}

    public function getInfo($id){
        return $this->db->from('admin_users')->where('id',$id)->get()->row_array();
    }

    /**
     * 根据id数组获取邮箱信息(佣金管理用到此函数)
     * @return array
     * @author andy
    **/
    public function getAdminEmailByIdArr($adminIdArr){
        if($adminIdArr && is_array($adminIdArr)){
            return $this->db->select('id,email')->from('admin_users')->where_in('id',$adminIdArr)->get()->result_array();
        }else{
            return array();
        }
    }

    public function login($loginData){
        $username = trim($loginData['user_name']); //账号
	$pwd = trim($loginData['pwd']);     //密码
        $captcha = isset($loginData['captcha_code']) ? trim($loginData['captcha_code']) :"";     //用户输入的图片验证码
        $resUserInfo = $this->db->from('admin_users')->where('email',$username)->get()->row_array();
        if(!$resUserInfo){
           // return 1008;
            return array('success' => FALSE, 'code_msg' => '* ' . lang(config_item('error_code')[1008]));
        }

        if($resUserInfo['pwd_encry']!==$this->createPwdEncry($pwd, $resUserInfo['token'])){
           // return 1010;
          // return array('success' => FALSE, 'code_msg' => '* ' . lang(config_item('error_code')[1010]));
        }
        if(!$resUserInfo['status']){
           // return 1021;
            return array('success' => FALSE, 'code_msg' => '* ' . lang(config_item('error_code')[1021]));
        }
        
        if($resUserInfo['status']==3){
           // return 1021;
            return array('success' => FALSE, 'code_msg' => '* ' . lang('admin_user_account_disabled_hint'));
        }
         
        $conf = config_item("login_captcha");
        
        if($conf['switch'] ==1) {  //启用登陆验证码 
        
            //输入的图片验证码为空
            if (empty($captcha)) { 
                return array('success' => FALSE, 'code_msg' => '* ' . lang('email_code_not_nul'));
            }

            //比较判断用户输入的验证码和Redis/或Cookie保存的验证码
            if(isset($loginData['redis_code'])) {
                
                if($loginData['redis_code'] == -1 )  {  //Redis保存的验证码过期 则为-1
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('captcha_code_expire'));
                }
                
                if(strtolower($captcha) !== $loginData['redis_code'] )  {
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('mobile_code_error'));
                }
            } else {  //cookie保存加密验证码

                if(md5(strtolower($captcha)."yun#138") !== $loginData['cookie_code'])  {
                    return array('success' => FALSE, 'code_msg' => '* ' . lang('mobile_code_error'));
                }

            }
        }
        //判断密码是不是相同
        if ($this->createPwdEncry($pwd, $resUserInfo['token']) !== $resUserInfo['pwd_encry']) {
            //以下注释的内容是 账户密码错误3次后冻结账户功能
            //错误的数量 判断是不是大于3次了
            //redis开启才判断
            if(REDIS_STOP == 0) {
                    $error_count = $this->record_user_login_error($resUserInfo['id']);
                    if($error_count >= 3){
                        //锁定账户  设置激活时间
                        //$enable_time = date('Y-m-d H:i:s',time()+60*60*24);
                        //$this->record_user_enable_time($userInfo['id'],3,$enable_time);//设置用户账户锁定 添加 激活时间
                        $this->record_user_login_error($resUserInfo['id'],false);//记录帐号的错误次数
                        $this->db->where("id",$resUserInfo['id'])->update("admin_users",array("status"=>3));//更新用户状态为冻结状态
                        return array('success' => FALSE, 'code_msg' => '* '.lang('admin_user_account_disabled_hint'));//错误次数提示信息
                    }else{
                        $this->record_user_login_error($resUserInfo['id'],false);//记录帐号的错误次数
                        return array('success' => FALSE, 'code_msg' => '* ' . lang('admin_user_login_pwd_error'));
                    }
            }
            return array('success' => FALSE, 'code_msg' => '* ' . lang('admin_user_login_pwd_error'));
        } else {
            if(REDIS_STOP == 0) {
                $error_count = $this->record_user_login_error($resUserInfo['id']);
                if ($error_count >= 3) {
                    return array('success' => FALSE, 'code_msg' => '* '.lang('admin_user_account_disabled_hint'));//错误次数提示信息
                }
            }
        }    
        $sign = $this->createCookieSign($resUserInfo['token']);
        $userInfoSeri = serialize(array('id' => $resUserInfo['id'], 'sign' => $sign));
        set_cookie("adminUserInfo", $userInfoSeri, 0, get_public_domain());
		$this->m_log->adminActionLog($resUserInfo['id'],'admin_login','admin_users',$resUserInfo['id'],
			'',get_real_ip(),'');
        return array('success' => TRUE);
    }
    /**
    * 记录会员登录账户的 错误次数
    * @param unknown $user_id    账户（ID，邮箱，手机号）
    * @param string  $session_bool  是否重置记录的次数（默认是 true 不重置）
    * @param string  $ok            取当前数据还是新增数据（默认true 新增数据）
    * @return number
    */
   public function record_user_login_error($user_id,$session_bool = true,$ok=true){
           $key = "admin_mall:login:submit:error_counts:".$user_id;
           $session_count = 0;//记录的次数
           $time_over = strtotime(date('Ymd')) + 86400;//今天夜里24点的时间戳
           $time_start = $time_over - time();//redis的缓存时间
           if($session_bool){
                   //判断key是不是存在
                   if( $this->redis_get($key) ){
                        $session_count = $this->redis_get($key);//获取记录的值
                   }else{
                        $this->redis_set($key,$session_count);//初始化redis中的值

                   }
           }else{
                   //记录错误次数
                   $record_count = $this->redis_get($key);//获取记录的值
                   $session_count = $record_count + 1;//累计增加次数
                   $this->redis_set($key,$session_count);
           }
           if ($this->redis_ttl($key) == -1) {
                $this->redis_settimeout($key,$time_start);//初始化redis中的值
            }
           return $session_count;
   }
    
    /* 供应上商登录 */
    public function supplier_login($username,$pwd){
        $resUserInfo = $this->db->from('mall_supplier')->where('supplier_username',$username)->get()->row_array();
        if(!$resUserInfo){
            return 1008;
        }

        if($resUserInfo['supplier_password']!==md5($pwd)){
            return 1010;
        }

        $now = time();
        $this->db->set('supplier_login_time','supplier_login_time+1',false)
                 ->set('supplier_last_time',$now,false)
                 ->where('supplier_id',$resUserInfo['supplier_id'])->update('mall_supplier');

        $userInfoSeri = serialize(array('supplier_id' => $resUserInfo['supplier_id'], 'supplier_user' => $resUserInfo['supplier_username'],'supplier_username'=>$resUserInfo['supplier_name']));
        set_cookie("adminSupplierInfo", $userInfoSeri, 0, get_public_domain());

        // 登录成功后，执行erp第三方帐号登录更新接口
        $param = array(
            'sid' => $resUserInfo['supplier_id'],
            'username' => $resUserInfo['supplier_username'],
            'login_count' => $resUserInfo['supplier_login_time'] + 1,
            'last_time' => date("Y-m-d H:i:s", $now)
        );

        erp_api_query('Api/Basic/supplierUserLoginUpdate', $param);

        return 0;
    }

    /* 获取供应商信息 */
    public function getSupplierInfo($id){
        return $this->db->from('mall_supplier')->where('supplier_id',$id)->get()->row_array();
    }

    public function createRootAccount(){
        $email = config_item('admin_root_account')['account'];
        $pwd = config_item('admin_root_account')['pwd'];
        $token = $this->createToken();
        $pwd_encry = $this->createPwdEncry($pwd, $token);
        $this->db->replace('admin_users', array(
            'id'=>1,
            'email'=>  $email,
            'pwd_encry'=>$pwd_encry,
            'token'=>$token,
            'role'=>0,
            'status'=>1
        ));
    }

    public function reset_admin_pwd($id,$pwd){
        $token = $this->createToken();
        $pwd_encry = $this->createPwdEncry($pwd, $token);
        $this->db->where('id', $id)->update('admin_users', array('token'=>$token,'pwd_encry' => $pwd_encry));
    }

    public function createAdmin($data){

        $token = $this->createToken();
        $pwd_encry = $this->createPwdEncry($data['pwdOriginal'], $token);
        $insert_data = array(
            'email'=>  $data['email'],
            'pwd_encry'=>$pwd_encry,
            'token'=>$token,
            'role'=>$data['role'],
            );
        if($data['job_number_select']!='' && in_array($data['role'],array(1,2,5))){
            $number = $this->get_new_job_number_by_area($data['cus_area']);
            $insert_data['area']        = $data['cus_area'];
            $insert_data['job_number']  = $number['job_number']+1;
        }
        $this->db->insert('admin_users',$insert_data);

        $this->load->model('tb_empty');
        $this->tb_empty->redis_del('tickets:all_customers');

		return $this->db->insert_id();
    }

    public function updatePwd($data,$token) {
        $new_pwd = $this->createPwdEncry($data['pwdOriginal'], $token);
        $this->db->where('id', $data['id'])->update('admin_users', array('pwd_encry' => $new_pwd));
    }

    public function createCookieSign($token){
        return sha1('rg4'.$token.'s3r');
    }

    public function createToken(){
        return md5('147' . time() . '96tes!#rg@*');
    }


    public function createPwdEncry($pwd,$token){
        return sha1('rf8r06f'.$pwd.'ef78fe'.$token.'t56d7fb');
    }

    public function checkUserExist($idOrEmail) {
        if(is_numeric($idOrEmail)){
            $res = $this->db->from('admin_users')->where('id', $idOrEmail)->get()->row_array();
        }else{
            $res = $this->db->from('admin_users')->where('email', $idOrEmail)->get()->row_array();
        }
        return $res ? $res : FALSE;
    }

    public function checkUserPwd($registerData) {
        $userInfo = $this->getUserByIdOrEmail($registerData['id']);
        if ($this->createPwdEncry($registerData['pwdOld'], $userInfo['token']) === $userInfo['pwd_encry']) {
            return true;
        }
        return false;
    }

    public function checkJobNumberExist($jobNumber){
        $res = $this->db->from('admin_users')->where('job_number',$jobNumber)->get()->row_array();
        if($res){
            return true;
        }
        return false;
    }

    public function getUserByIdOrEmail($idOrEmail) {
        $res = $this->db->from('admin_users')->where('id', $idOrEmail)->or_where('email', $idOrEmail)->get()->row_array();
        return $res;
    }
    public function checkRegisterItems($registerData) {

        $return = array();
        foreach ($registerData as $itemName => $itemVal) {
            $itemVal = trim($itemVal);
            $isRight = TRUE;
            $error_code = 0;
            $foreachContinue = FALSE;
            switch ($itemName) {
                case 'email':
                    if (empty($itemVal) || !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1001;
                    } elseif ($this->checkUserExist($itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1002;
                    }
                    break;

                case 'pwdOriginal':
                    $pwdLen = strlen($itemVal);
                    if ($pwdLen < 6 || $pwdLen > 18 || preg_match("/ /", $itemVal) || preg_match('/^\d+$/', $itemVal)) {
                        $isRight = FALSE;
                        $error_code = 1003;
                    }
                    break;

                case 'pwdOriginal_re':
                    $pwdVal = isset($registerData['pwdVal'])?trim($registerData['pwdVal']):trim($registerData['pwdOriginal']);
                    if ($itemVal !== $pwdVal) {
                        $isRight = FALSE;
                        $error_code = 1004;
                    }
                    if (!$pwdVal) {
                        $isRight = FALSE;
                        $error_code = 1005;
                    }
                    break;

                 case 'pwdOld':
                    if (!$this->checkUserPwd($registerData)) {
                        $isRight = FALSE;
                        $error_code = 1010;
                    }
                    break;

//                case 'jobNumber':
//                    if($itemVal==''){
//                        break;
//                    }
//                    if(!preg_match("/^\d{3}$/", $itemVal) || $itemVal<100){
//                        $isRight = FALSE;
//                        $error_code = 1051;
//                        break;
//                    }
//                    if($this->checkJobNumberExist($itemVal)){
//                        $isRight = FALSE;
//                        $error_code = 1050;
//                    }
//                    break;
                default:
                    $foreachContinue = TRUE;
                    break;
            }
            if ($foreachContinue) {
                continue;
            }
            $error = config_item('error_code');
            $return[$itemName] = array('isRight' => $isRight, 'msg' => is_numeric($error_code) ? lang($error[$error_code]) : $error_code);
        }
        return $return;
    }

    /*
    ＊验证手动加减佣金提交的数据。
    */
    public function checkCommChangeSubData($admin_id,$data){
        $this->load->model('m_user');
        $uid = isset($data['commChangeUid'])?trim($data['commChangeUid']):'';
        $amount = isset($data['commChangeAmount'])?trim($data['commChangeAmount']):'';
        $commChangeDesc = isset($data['commChangeDesc'])?trim($data['commChangeDesc']):'';
        //备注
        $remark = isset($data['commChangeDesc'])?trim($data['commChangeDesc']):'';

        if(!$uid){
            return lang('user_id_list_requied');
        }
        $memInfo = current($this->m_user->getInfo($uid));
        if(!$memInfo){
            return lang('no_exist');
        }
        if(!$amount){
            return lang('pls_input_amount');
        }
        if (!is_numeric($amount) || $amount <= 0 ||  get_decimal_places($amount)>2) {
            return lang('amount_condition');
        }
        // if($data['commChangeOper']==2){
        //     if($memInfo['amount']<$amount){
        //         return lang('cur_commission_lack');
        //     }
        // }
        if(!$commChangeDesc){
            return lang('pls_input_reson');
        }
//        if(in_array($admin_id,array(62,8,9)) && $amount>1500){
//            return lang('amount_limit').' 1500';
//        }
//        if(in_array($admin_id,array(61,71,103,116,121,107,120,224)) && $amount>500){
//            return lang('amount_limit').' 500';
//        }
//        if(in_array($admin_id,array(129)) && $amount>250){
//            return lang('amount_limit').' 250';
//        }

        if(check_right('commission_admin_limit_1500') && $amount>1500){
            return lang('amount_limit').' 1500';
        }
        if(check_right('commission_admin_limit_500') && $amount>500){
            return lang('amount_limit').' 500';
        }
        if(check_right('commission_admin_limit_250') && $amount>250){
            return lang('amount_limit').' 250';
        }

        $operate = $data['commChangeOper']==1?'+':'-';
        $this->db->where('id', $uid)->set('amount','amount'.$operate.$amount,FALSE)->update('users');
        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($uid,9,$operate.$amount,'0','','',$remark);

        //获取上一条语句插入的id
        $last_id = $this->db->insert_id();
        //获取当前的表名
        $mon = date('Ym');//当月
        $table_name = 'cash_account_log_'.$mon;
        $str = $table_name.'|'.$last_id;

        $this->addCommissionManageLog($admin_id,$uid,$operate.$amount,$commChangeDesc,1,$str);
		$after_amount = $data['commChangeOper']==1? $memInfo['amount']+$amount : $memInfo['amount']-$amount;
		$this->m_log->adminActionLog($admin_id,'admin_commission_manage','users',$uid,
			'amount',$memInfo['amount'],$after_amount,$operate.$amount);
        return '';
    }

    /*
    ＊验证手动加减月费池提交的数据。
    */
    public function checkMonthfeePoolChangeSubData($admin_id,$data){

        $this->load->model('m_user');
        $this->load->model('m_coupons');
        $uid = isset($data['monthFeePoolChangeUid'])?trim($data['monthFeePoolChangeUid']):'';
        $amount = isset($data['monthFeePoolChangeAmount'])?trim($data['monthFeePoolChangeAmount']):'';
        $commChangeDesc = isset($data['monthFeePoolChangeDesc'])?trim($data['monthFeePoolChangeDesc']):'';
        $operate = $data['monthFeePoolChangeOper']==1?'+':'-';

        if(!$uid){
            return lang('user_id_list_requied');
        }
        $memInfo = current($this->m_user->getInfo($uid));
        if(!$memInfo){
            return lang('no_exist');
        }
        if(!$amount){
            return lang('pls_input_amount');
        }
        if (!is_numeric($amount) || $amount <= 0 ||  get_decimal_places($amount)>2) {
            return lang('amount_condition');
        }
        if($operate=='-'){
            if($memInfo['month_fee_pool']<$amount){
                return lang('cur_commission_lack');
            }
        }
        if(!$commChangeDesc){
            return lang('pls_input_reson');
        }

        $this->db->where('id', $uid)->set('month_fee_pool','month_fee_pool'.$operate.$amount,FALSE)->update('users');

        /**  月費變動記錄 */
        $this->load->model('m_commission');
        $date_time = date('Y-m-d H:i:s');
        $newMonthFee = $operate=='+'? ($memInfo['month_fee_pool']+$amount) : ($memInfo['month_fee_pool']-$amount);
        $monthlyFeeCouponNum = $this->m_coupons->getMonthlyFeeCouponNum($uid);
        $this->m_commission->monthFeeChangeLog($uid,$memInfo['month_fee_pool'],$newMonthFee,$operate.$amount,$date_time,7,$monthlyFeeCouponNum,$monthlyFeeCouponNum,0);

        $this->addCommissionManageLog($admin_id,$uid,$operate.$amount,$commChangeDesc,2);

        return '';
    }

    public function addCommissionManageLog($admin_id,$uid,$comm_amount,$desc,$per_type=1,$key=''){
        $this->db->insert('admin_manage_commission_logs',array(
            'admin_id'=>$admin_id,
            'oper_type'=>$per_type,
            'uid'=>$uid,
            'comm_amount'=>$comm_amount,
            'desc'=>$desc,
            'key'=>$key
        ));
    }

    public function checkUpgradeUserManuallyData($data){
        if(!isset($data['user_id_list']) || !$data['user_id_list']){
            return lang('user_id_list_requied');
        }
        if(!isset($data['levelType']) || !$data['levelType']){
            return lang('month_fee_or_user_rank_requied');
        }
        if(!isset($data['levelValue']) || !$data['levelValue']){
            return lang('please_sel_level');
        }
        return '';
    }

    public function upgradeUserManually($data,$adminId) {
        $this->load->model('m_user');
        $this->load->model('m_order');
        $user_upgrade_list = explode(',', str_replace('，', ',', $data['user_id_list']));
        $levelType = $data['levelType'];
        $levelValue = $data['levelValue'];
        $num = 0;
        foreach ($user_upgrade_list as $uid) {
            $uid = trim($uid);
            $userInfo = current($this->m_user->getInfo($uid));
            if(!$userInfo){
                continue;
            }
            if ($userInfo['status'] == 0) {
                $noVeri = TRUE;
                $this->m_user->enableAccount(array('id' => $uid), $noVeri);
            }
            if ($levelType == 'user_rank') {
                //$resMonthFeeLevel = $this->m_order->uptrade_month_level_manually($uid, $levelValue,$adminId);
				$is_grant_generation = isset($data['is_grant_generation']) && $data['is_grant_generation'] == 1 ? true : false;//发放团队销售佣金
				$is_certificate = isset($data['is_certificate']) && $data['is_certificate'] == 1 ? true : false;//是否发放代品券
                $resStoreLevel = $this->m_order->uptradeStoreLevelManually($uid, $levelValue,$adminId ,$is_grant_generation,$is_certificate);
                if ($resStoreLevel) {
                    $num++;
					$this->m_log->adminActionLog($adminId,'admin_upgrade_user_manually','users',$uid,
						'user_rank',$userInfo['user_rank'],$levelValue);
                }
            } else {
                /*$resMonthFeeLevel = $this->m_order->uptrade_month_level_manually($uid, $levelValue,$adminId);
                if ($resMonthFeeLevel) {
                    $num++;
                }*/
            }
        }
        return $num;
    }

    //更改提现记录状态
    public function updateWithdrawalInfo($id,$data){
        $this->db->where('id',$id)->update('cash_take_out_logs',$data);
        return $this->db->affected_rows();
    }

	/** 插入退款的訂單記錄 */
	function insertPayPalRefund($order_id,$email,$txn_id,$amount,$note,$create_time,$name,$type){
		$count = $this->db->from('mall_orders_paypal_refund')->where('order_id',$order_id)->count_all_results();
		if($count == 0){
			$this->db->insert('mall_orders_paypal_refund',array(
				'order_id'=>$order_id,
				'txn_id'=>$txn_id,
				'email'=>$email,
				'name'=>$name,
				'amount'=>$amount,
				'note'=>$note,
				'type'=>$type,
				'create_time'=>$create_time,
			));
		}
	}

	/** 得到国家省份的code */
	function get_country_province_code(){
		$sql = "select name,code from trade_addr_linkage where country_code = '156' and
		code in ('11','12','13','14','15','21','22','23','31','32','33',34,35,36,37,41,42,43,44,45,46,50,51,52,53,54,61,62,63,64,65)";
		$codes = $this->db->query($sql)->result_array();
		return $codes;
	}

	/** 生成售后单号 */
	function create_after_sale_id(){
		do{
			$as_id = get_after_sale_id(); //获取售后单号
			$count =  $this->db->from('admin_after_sale_order')->where('as_id',$as_id)->count_all_results();
		}
		while ($count > 0); //如果是售后单号重复则重新售后单号
		return $as_id;
	}

	/**
	 * 购买产品套装金额（2015年11月4号前的含第一次月费）- 已经收货的升级订单金额
	 * -  提现的金额  -   会员之间转账转出部分  -  商城消费（资金变动报表中查询） - 已发货的代品券订单
	 * -  系统成功扣取的用户月费（不算月费券） + 会员间转账转入部分
	 */
	function getProductSetByUid($uid,$level,$month_fee_pool){

                $this->load->model('m_split_order');
                $this->load->model('m_user');
                $is_true = $this->m_user->is_first_upgrade_time_1_1($uid);
                if($is_true){
                        $product_set = config_item('old_join_fee_and_month_fee');
                }else{
                        $product_set = $this->m_user->getJoinFeeAndMonthFee();
                }

                $str = '';
                $amount = $product_set[$level]['join_fee'];

                /** 2015年11月4号前的含第一次月费 */
                $month_level_time = $this->db->query("select create_time,new_level from users_level_change_log where uid=$uid and level_type=1 order by id limit 1")->row_array();
                if($month_level_time && $month_level_time['create_time'] <= "2015-11-04 23:59:59"){
                        $amount += config_item('join_fee_and_month_fee')[$month_level_time['new_level']]['month_fee'];
                }

                $str .= '购买产品套装金额（2015年11月4号前的含第一次月费）$'.$amount;

                /** 已经收货的订单金额 ，未取消的升级订单金额*/
//                $orders = $this->db->select('order_id,goods_amount_usd,deliver_fee_usd,order_prop,attach_id')
//                    ->where('customer_id',$uid)->where_in('order_prop',array('0','1'))
//                    ->where_in('status',array('1','3','4','5','6'))->get('trade_orders')->result_array();
                $this->load->model("tb_trade_orders");
                $orders = $this->tb_trade_orders->get_list_auto([
                    "select"=>"order_id,goods_amount_usd,deliver_fee_usd,order_prop,attach_id",
                    "where"=>[
                        'customer_id'=>$uid,
                        'order_prop'=>array('0','1'),
                        'status'=>array('1','3','4','5','6')
                    ]
                ]);
                $upgrade_amount = 0;
                if($orders)foreach($orders as $order){
                        $order_id = $order['order_prop'] == 1 ? $order['attach_id'] : $order['order_id'];
                        $type = $this->m_split_order->get_order_type($order_id);
                        if($type == '3'){
                            $tmp_amount = $order['goods_amount_usd']/100 + $order['deliver_fee_usd']/100;
                            $upgrade_amount += $tmp_amount;
                        }
                }
                $str .= ' - 已经收货的升级订单金额$'.$upgrade_amount;
                /** 特殊款项 */
                $this->load->model('o_cash_account');
                $specialAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>9,
                ))/100;
               // $str .= ' + 特殊款项(需人工核查)$'. $specialAmount;
                /** 支出的钱，提现 */
                $this->load->model('o_cash_account');
                $withdralAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type in'=>array('10','12')
                ))/100;
                $str .= ' + 提现的金额$'. $withdralAmount;
                /** 转账部分 **/
                $tansferAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>11,
                ))/100;
                $str .= ' + 会员之间转账部分$'. $tansferAmount;
              
                /** 升级费用 */
                /*客服需求，暂时不用减去升级费用。
                $upgrade = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>13,
                ))/100;
                $transfer = abs($upgrade);
                $str .= ' - 升级费用$'. $transfer;
                 * 
                 */
                
                /** 现金池转月费池*/
                $month_feeAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>14
                ))/100;
                $final_month_fee = abs($month_feeAmount);
                $str .= ' - 现金池转月费池$'. $final_month_fee;
                /** 月费转现金池 */
                $fee_feeAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>15
                ))/100;
                $fee_month_final = abs($fee_feeAmount);
                $str .= ' + 月费转现金池'. $fee_month_final;
                
                /** 商城消费 */
                $mall_ordersAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>20,
                ))/100;
                $mall_amount = abs($mall_ordersAmount);
                $str .= ' - 商城消费$'. $mall_amount;
                
                /** 订单退款 */
                $cance_ordersAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>22,
                ))/100;
                $cance_order_amount = abs($cance_ordersAmount);
                $str .= ' + 订单退款$'. $cance_order_amount;
                
                /* 平台管理费部分*/
                $managementAmount = $this->o_cash_account->getSumAmount(array(
                    'uid'=>$uid,
                    'item_type'=>28,
                ))/100;
                $str .= ' - 会员平台管理费$'. abs($managementAmount);        
                /**
                 * 升级订单总金额-未取消的升级订单金额-特殊款项处理（仅减掉支出的部分，如是收入，则加回来）-  $specialAmount
                 * 提现+取消提现-转出转账+转入转账-升级费用-
                 * 现金池转月费池+月费池转现金池-商城消费+订单退款-平台管理费
                 */
                $new_amount = $amount - $upgrade_amount + 
                        $withdralAmount + $tansferAmount -
                        $final_month_fee+$fee_month_final-$mall_amount+ $cance_order_amount - abs($managementAmount);
                $tmp_new_amount = $new_amount + $specialAmount;
                $str .= ' = $'. $new_amount ." + 特殊款项(需人工核查)$" . $specialAmount ." ≈ $" .$tmp_new_amount;
               // $new_amount =  $new_amount < 0 ? 0 : $new_amount;

                return array('amount'=>$tmp_new_amount,'amount_str'=>$str);
	}

    //批量更新状态，审核驳回
    public function update_status($ids,$status){
        $this->db->where_in('as_id', $ids)->update('admin_after_sale_order',array('status'=>$status));
        return $this->db->affected_rows();
    }

    /**
     * @return mixed
     * @author andy
     * @return mixed
     */
    public function get_customers(){

        $this->db->select('id,role,area,email,status,job_number')->from('admin_users');
        $this->db->where_in('status',array(1,2))->where('job_number >',0)->order_by('job_number','ASC');
        return $this->db->get()->result_array();
    }

    /** 获取中国区域客服
     * @return mixed
     * @author andy
     */
    public function get_area_china_customers(){
        $this->db->select('id,area,job_number')->from('admin_users');
        $this->db->where_in('status',array(1,2))->where('job_number >',800)->where('area',2);
        return $this->db->get()->result_array();
    }

    /** 获取韩国区域客服
     * @return mixed
     * @author andy
     */
    public function get_area_kr_customers(){
        $this->db->select('id,area,job_number')->from('admin_users');
        $this->db->where_in('status',array(1,2))->where('job_number >',800)->where('area',4);
        return $this->db->get()->result_array();
    }

    /** 获取某个客服的id，email，role
     * @param $admin_id
     * @return mixed
     */
    public function get_customer_by_id($admin_id){
        $this->db->select('id,email,role,job_number')->from('admin_users')->where('id',$admin_id);
        return $this->db->get()->row_array();
    }

    public function get_new_job_number_by_area($area){
        if($area==1){
            return $this->db->select_max('job_number')->from('admin_users')->where('area',$area)->get()->row_array();
        }else{
            return $this->db->select_max('job_number')->from('admin_users')->get()->row_array();
        }
    }

    /**
     * @param $ids array
     * @return mixed
     * @author andy
     */
    public function get_customer_by_ids($ids){
        $this->db->select('id,email,role,job_number')->from('admin_users')->where_in('id',$ids);
        return $this->db->get()->result_array();
    }

}
