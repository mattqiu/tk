<?php
/**
 *  会员中心的业务处理类
 * @author john
 */
class o_ucenter_info extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/**
	 * 用户特定的信息
	 */
	public function get_user_info($postData){
		$this->load->model('tb_users');
		return $this->tb_users->get_user_info($postData['uid'],$postData['fields']);
	}

	/**
	 * 会员基础的信息
	 */
	public function get_user_base_info($postData){

		$this->load->model('tb_users');
		$userInfo = $this->tb_users->get_user_info($postData['uid'],'id,user_rank,name,country_id,email
		,mobile,pwd_take_out_cash,is_verified_email,is_verified_mobile,amount,month_fee_pool,is_auto,member_url_prefix');
		if (!$userInfo) {
			$error_code = 1001; //您的ID不存在。
			return $error_code;
		}

		/**
		 * 身份證審核信息
		 */
		$this->load->model('tb_user_id_card_info');
		$id_card = $this->tb_user_id_card_info->getUserIdCardField($userInfo['id']);
		if($id_card){
			$userInfo['id_card_num'] = $id_card['id_card_num'];
			$userInfo['id_card_scan'] = $id_card['id_card_scan'];
			$userInfo['id_card_scan_back'] = $id_card['id_card_scan_back'];
			$userInfo['check_status'] = $id_card['check_status'];
			$userInfo['check_info'] = $id_card['check_info'];
		}else{
			$userInfo['id_card_num'] = $userInfo['id_card_scan'] = $userInfo['id_card_scan_back'] =
			$userInfo['check_status'] = $userInfo['check_info'] = "";
		}

		/**
		 * 個人資料是否完善
		 */
		if(!$userInfo['name'] || !$userInfo['is_verified_email'] || !$userInfo['is_verified_mobile']
			|| $userInfo['country_id'] != NULL || !$userInfo['pwd_take_out_cash'] || !$id_card['status']){
			$userInfo['is_complete_info'] = '0';
		}else{
			$userInfo['is_complete_info'] = '1';
		}

		/**
		 * 是否有未支付的订单
		 */
		$this->load->model('tb_trade_orders');
		$userInfo['is_not_payment'] = (string)$this->tb_trade_orders->is_no_payment($userInfo['id']);

		/**
		 * 代品券数量
		 */
		$this->load->model('m_coupons');
		$userInfo['coupons'] = (string)$this->m_coupons->get_coupons_list($userInfo['id'])['total_money'];

		return $userInfo;
	}

	/**
	 * 会员店铺二维码
	 */
	public function get_store_code($postData){
		$this->load->model('tb_users');
		$user = $this->tb_users->get_user_info($postData['uid'],'member_url_prefix');
		if(!$user && empty($user['member_url_prefix'])){
			return 1001;
		}
		$path = "upload/qrcode/".$user['member_url_prefix'].".png";
		if(!file_exists($path)){
			create_qr_code($user['member_url_prefix']);
		}
		return array('error_code'=>0,'path'=>$path);
	}

	/**
	 *	上传身份证件
	 */
	public function upload_id_card_file($postData){

		if(!in_array($postData['type'],array('1','2'))){
			return 1005;//参数值不对
		}

		$type_name = $postData['type'] == '1' ? 'id_card_scan' : 'id_card_scan_back';
		$file_name = 'file';

		if(isset($_FILES[$file_name])) { //上传图片

			$picname = $_FILES[$file_name]['name'];
			$picsize = $_FILES[$file_name]['size'];
			if ($picname != "") {
				if ($picsize > config_item('id_card_scan_size_limit')) {
					return 1001;//限制上传大小
				}
				$type = strstr($picname, '.');
				if ($type != ".gif" && $type != ".jpg" && $type!=".bmp" && $type!=".png") {
					return 1002;//限制上传格式
				}

				//防止上传图片名重名
				do{
					$rand = rand(100, 999);
					$pathImg = 'idCardScan/'.date('Ymd').'/'.md5(date("His") . $rand) . $type;//待保存的图片路径
					$count = $this->tb_user_id_card_info->uniqueIdIdCardName($pathImg,$type_name);
				}
				while ($count > 0); //如果是重复路径名则重新生成名字

				/*上传图片*/
				$this->load->model('m_do_img');
				if($this->m_do_img->upload($pathImg,$_FILES[$file_name]['tmp_name'])){
					$this->tb_user_id_card_info->updateIdCardPath($postData['uid'],$pathImg,$type_name);
					$this->tb_user_id_card_info->updateIdCard($postData['uid'],array('id_card_num'=>$postData['number']));
				}else{
					return 1003;//上传图片服务器失败
				}

				return array('error_code'=>0,'pathImg'=>config_item('img_server_url').'/'.$pathImg);
			}
		}else{
			return 1004;//没有图片文件
		}
	}

	/**
	 * 删除身份证图片
	 */
	public function del_id_card($postData){

		if(!in_array($postData['type'],array('1','2'))){
			return 1005;//参数值不对
		}

		$type_name = $postData['type'] == '1' ? 'id_card_scan' : 'id_card_scan_back';

		$this->load->model('m_do_img');
		$success = $this->m_do_img->delete($postData['pathImg']);
		if($success){
			$this->load->model('tb_user_id_card_info');
			$this->tb_user_id_card_info->del_id_card($postData['uid'],$type_name);
			return 0;
		}else{
			return 1001;//删除失败
		}


	}

	/**
	 * 提交审核身份证
	 */
	public function submit_id_card($postData){

		//$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
		$this->load->model('tb_users');
		$user = $this->tb_users->getUserByIdOrEmail($postData['uid']);
		$this->load->model('tb_user_id_card_info');
		$idCard = $this->tb_user_id_card_info->getUserIdCard($postData['uid']);

		if(!$user['name']){
			return 1001;//请输入真实姓名
		}
		if(!$idCard['id_card_num']){
			return 1002;//请输入身份证号码
		}
		if(!$idCard['id_card_scan'] && !$idCard['id_card_scan_back']){
			return 1003;//请上传身份证照片
		}

		$data = array('check_status'=>1,'create_time'=>time());
		$this->tb_user_id_card_info->updateIdCard($postData['uid'],$data);
		return 0;
	}

	/**
	 * 得到会员的地址列表
	 */
	public function get_user_address_list($attr){
		$this->load->model('tb_trade_user_address');
		return $this->tb_trade_user_address->get_deliver_address_list_by_uid($attr['uid'],$attr['location_id']);
	}

	/**
	 *  新增会员的收货地址
	 */
	public function set_user_address($attr)
	{

		$return = $this->validate_user_address($attr);
		if($return != 0) return $return;

		unset($attr['token']);
		unset($attr['sign']);
		unset($attr['id']);

		if(!isset($attr['reserve_num'])){
			$attr['reserve_num'] = 0;
		}

		if($attr['country'] == 840 ){
			$attr['consignee'] = $attr['first_name'].$attr['last_name'];
		}

		$attr['is_default'] = 1;
		$this->load->model('m_trade');
		$ret = $this->m_trade->add_deliver_address_app($attr);
		if (!$ret)
		{
			return 1111;//插入数据失败
		}

		return array('error_code'=>0,'id'=>$ret);
	}

	/**
	 * 修改收货地址
	 */
	public function update_user_address($attr)
	{
		$return = $this->validate_user_address($attr);
		if($return != 0) return $return;

		unset($attr['token']);
		unset($attr['sign']);

		if($attr['country'] == 840 ){
			$attr['consignee'] = $attr['first_name'].$attr['last_name'];
		}

		$this->load->model('m_trade');
		$ret = $this->m_trade->edit_deliver_address($attr, $attr['uid']);
		if (TRUE !== $ret)
		{
			return 1111;//修改数据失败
		}

		return 0;
	}

	/**
	 *	删除收货地址
	 */
	public function del_user_address($attr){

		$this->load->model('m_trade');
		$ret = $this->m_trade->delete_deliver_address($attr['id'], $attr['uid']);
		if (TRUE !== $ret)
		{
			return 1111;//删除失败
		}

		return 0;
	}

	/**
	 * 默认收货地址
	 */
	public function set_user_default_address($attr){

		$this->load->model('m_trade');
		$ret = $this->m_trade->set_default_deliver_address($attr['id'], $attr['uid']);
		if (TRUE !== $ret)
		{
			return 1111;//设置失败
		}

		return 0;
	}

	/**
	 * 提现处理
	 */
	public function withdrawal_process($postData){

		$this->load->model('tb_users');
		$this->load->model('m_admin_helper');

		$uid = $postData['uid'];
		$userInfo = $this->tb_users->getUserByIdOrEmail($uid);

		$status = $this->m_admin_helper->getCardOne($uid);
		if (!$status || $status['check_status'] != 2) {
			$error_code = 1001;//当您看到这个信息时，表明您的身份验证还没有通过TPS的审核。
		}elseif(!$postData['take_cash_type']){
			$error_code  = 1002;//请选择提现方式
		}elseif($postData['take_out_amount'] < 100){
			$error_code = 1003;//至少提现100美金
		}elseif(!$this->checkTakeOutAmount($postData['take_out_amount'],$userInfo)){
			$error_code = 1004;//请填写正确的提现金额
		}elseif(!$this->checkTakeOutPwd($postData['take_out_pwd'],$userInfo)){
			$error_code = 1005;//请填写正确的资金密码
		}elseif((!$postData['account_bank'] || !$postData['account_name'] || !$postData['card_number'] ||!$postData['c_card_number'] || !$postData['subbranch_bank']) && $postData['take_cash_type'] == 3){
			$error_code = 1006;//收款人的银行卡信息不完整
		}else if($postData['card_number'] !== $postData['c_card_number'] && $postData['take_cash_type'] == 3){
			$error_code = 1007;//卡号不一致
		}else if((!$postData['maxie_card_number'] ||!$postData['c_maxie_card_number']) && $postData['take_cash_type'] == 4){
			$error_code = 1006;
		}else if($postData['maxie_card_number'] !== $postData['c_maxie_card_number'] && $postData['take_cash_type'] == 4){
			$error_code = 1007;
		}
		else{
			$this->db->trans_begin();

			if($postData['take_cash_type'] == 4){
				$postData['card_number'] = $postData['maxie_card_number'];
			}else if($postData['take_cash_type'] == 2){
				$postData['card_number'] = $userInfo['alipay_account'];
				$postData['account_name'] = $userInfo['alipay_name'];
			}

			$this->load->model('m_user');
			$this->load->model('m_commission');

			$this->m_user->takeOutCash($uid,$postData);
			$this->m_commission->commissionLogs($uid,10,-1 * $postData['take_out_amount']);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$error_code = 1111; //插入数据失败
			}
			else
			{

				$this->db->trans_commit();
				$error_code = 0;
			}

		}
		return $error_code;
	}

	/**
	 * 取消提现
	 */
	public function cancel_withdrawal($dataPost){

		$this->load->model('m_user_helper');
		$log = $this->m_user_helper->getOneTakeCashLog($dataPost['id']);

		if($log && ($log['status'] == 0 || $log['status'] == 3) && $log['uid'] == $dataPost['uid']){

			$this->load->model('m_commission');
			$this->db->trans_begin();

			$insert_count = $this->m_commission->commissionLogs($dataPost['uid'],12,$log['amount']);
			$update_count = $this->m_user_helper->addUserCash($log);
			$count = $this->m_user_helper->deleteOneTakeCashLog($dataPost['id']);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return 1111;//更新数据失败
			}

			if($count && $insert_count && $update_count){
				$this->db->trans_commit();
				return 0;
			}else{
				$this->db->trans_rollback();
				return 1111;//更新数据失败
			}

		}else{
			return 1001;//提现记录异常
		}
	}

	/** 核对用户提现的金额：最少提现100美金，只保留2位小数，不能超过用户的余额
	 * @param $takeOutAmount 金额
	 * @param $user_info 用户信息
	 * @return bool
	 */
	public function checkTakeOutAmount($takeOutAmount,$user_info){
		if (!is_numeric($takeOutAmount) || $takeOutAmount < 100 ||  get_decimal_places($takeOutAmount)>2) {
			return FALSE;
		}elseif($takeOutAmount>$user_info['amount']){
			return FALSE;
		}
		return TRUE;
	}

	/**	核对用户的资金密码
	 * @param $pwd 原文密码
	 * @param $user_info 用户信息
	 * @return bool True 密码正确  False 密码错误
	 */
	public function checkTakeOutPwd($pwd,$user_info){
		$this->load->model('m_user');
		if($this->m_user->encyTakeCashPwd($pwd,$user_info['token']) !==$user_info['pwd_take_out_cash']){
			return false;
		}
		return TRUE;
	}

	/**
	 * 核对收货地址
	 */
	public function validate_user_address($attr){

		if($attr['country'] == '156'){
			if(!$attr['country'] || !$attr['addr_lv2']|| !$attr['addr_lv3']|| !$attr['addr_lv4'] || !trim($attr['address_detail']) ){
				return 1008;//收货地址不完整
			}
		}

		if($attr['country'] == '410'){
			if(!$attr['country'] || !$attr['addr_lv2'] || !trim($attr['address_detail'])){
				return 1008;//收货地址不完整
			}
		}

		if($attr['country'] != 840 && !trim($attr['consignee'])){
			return 1009;//收货人必填项
		}

		$is_number =  is_numeric(trim($attr['phone']));
		if($is_number == FALSE || strlen(trim($attr['phone']))<6 || strlen(trim($attr['phone']))>16 ){
			return 1010; //手机号码是6-16位的数字
		}

		if($attr['reserve_num'] != '' && (is_numeric(trim($attr['reserve_num'])) == FALSE || strlen(trim($attr['reserve_num']))<6 || strlen(trim($attr['reserve_num']))>16 )){
			return 1011; //备用号码是6-16位的数字
		}

		//如果是美国地址,新的验证规则
		if($attr['country'] == 840)
		{
			if(!$attr['country'] || !$attr['addr_lv2']|| !$attr['city'] || !trim($attr['address_detail'])){
				return 1008;//收货地址不完整
			}

			$match =  is_numeric(trim($attr['zip_code']));
			if($match == FALSE || strlen(trim($attr['zip_code']))>5){
				return 1006; //邮编必须为5位数字
			}

			//验证长度
			if($attr['city'] == '' || strlen($attr['city'])>25) {
				return 1001;//城市名称不能为空且不能超过25个字母
			}
			if($attr['address_detail'] == '' || strlen($attr['address_detail'])>25) {
				return 1002;//街道名称不能为空且不能超过25个字母
			}
			if($attr['first_name'] == '' || strlen($attr['first_name'])>25) {
				return 1003;//名字不能超过25个字母
			}
			if($attr['last_name'] == '' || strlen($attr['last_name'])>25) {
				return 1004;//姓不能超过25个字母
			}
			if($attr['phone'] =='' || strlen($attr['phone'])>10) {
				return 1005;//电话最多可以输入10位
			}

			//拼接姓名
			$attr['consignee'] = $attr['first_name'].' '.$attr['last_name'];
		}

		if ($attr['country'] == 410 && (empty($attr['customs_clearance']) || empty($attr['zip_code'])))
		{
			return 1007; // 韩国必须填海关报关号，邮编

		}

		return 0;
	}

	/**
	 * 计算充值月费
	 */
	public function cal_month_fee($postData){

		$this->load->model('tb_users');
		$this->load->model('m_global');

		$userInfo = $this->tb_users->getUserByIdOrEmail($postData['uid']);

		$payment = $this->m_global->getPaymentById($postData['payment_method']);

		$this->load->model('m_user');
		$month_fee = $this->m_user->getJoinFeeAndMonthFee();
		$month_fee = $month_fee[$userInfo['user_rank']]['month_fee'] * $postData['month'];

		$this->load->model('M_currency','m_currency');
		$result = $this->m_currency->price_format_array($month_fee,$payment['payment_currency']);

		return $result;
	}

	/**
	 * 充值月费
	 */
	public function paid_month_fee($postData){

		$this->load->model('tb_users');
		$this->load->model('m_user');
		$this->load->model('m_global');
		$this->load->model('m_log');

		$userInfo = $this->tb_users->getUserByIdOrEmail($postData['uid']);

		$res = $this->m_user->checkUserMonthData($postData,$userInfo['user_rank']);
		if($res['error_code'] == 101 ){
			return 1001;//参数值不对
		}

		if(!in_array($postData['payment_method'],array(105,106,107,112))){
			return 1002;//不支持的支付方式
		}


		$postData['time'] = time();

		if($userInfo['status'] == 3){ //如果等级没有激活，用户的等级其实就是免费的
			$userInfo['user_rank'] = 4;
		}

		$payment = $this->m_global->getPaymentById($postData['payment_method']);

		$this->load->model('M_order', 'm_order');
		$order = $this->m_order->createMonthFeeOrder($postData,$userInfo,strtolower($payment['pay_name']));

		$order['money'] = $order['usd_money'] * 100;
		$order['view_currency'] = 'CNY';
		$order['currency'] = 'USD';  //兼容商城的

		$this->load->model('o_payment');
		$function = $payment['pay_code'].'_get_code';
		$result = $this->o_payment->$function($order);

		return $result;
	}

	/**
	 * 订单支付
	 */
	public function go_order_pay($data){

		$this->load->model('m_global');
		$this->load->model('m_trade');
		$this->load->model('M_trade', 'm_trade');
		$this->load->model('m_log');

		$order = $this->m_trade->get_order_info($data['order_id']);

		if(!$order || $order['txn_id'] || ($order['status'] > Order_enum::STATUS_CHECKOUT && $order['status'] != Order_enum::STATUS_COMPONENT )){//已经付款
			return 1001;//订单状态异常
		}

		if($data['uid'] != $order['customer_id']){
			return 1002;//订单的顾客ID异常
		}

		if(!in_array($data['payment_method'],array(105,106,107,110,111))){
			return 1104;//不支持的支付方式
		}


		$this->load->model('m_order');
		$check_goods = $this->m_order->test_order_product($data['order_id']);
		if($check_goods['success'] == 0){
			return 1101;//产品品下架或无库存
		}

		if($data['payment_method'] == 110){  //如果是余额支付

			$this->load->model('tb_users');
			$userInfo = $this->tb_users->getUserByIdOrEmail($data['uid']);

			if($order['money'] > $userInfo['amount']*100){
				return 1102; //现金池余额不足
			}
			if(!$this->checkTakeOutPwd($data['cash_pwd'],$userInfo)){
				return 1103; //资金密码不对
			}
		}

		$payment = $this->m_global->getPaymentById($data['payment_method']);
		$order['view_currency'] = $order['currency'];

		$this->load->model('o_payment');
		$function = $payment['pay_code'].'_get_code';
		$result = $this->o_payment->$function($order);
		$this->m_trade->update_order_payment($order,$payment['pay_id']);

		return $result;
	}

}
